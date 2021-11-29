<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;
use Hash;
use Mail;

class Users extends Controller
{
  private $dados=[];
  /*private $lang;
  public function __construct()
  {
    //$this->lang=Session::get('locale');
    $this->middleware(function ($request, $next) {
          $this->lang = Cookie::get('admin_cookie')['lingua'];
          return $next($request);
      });
  }*/
  
  /**********
  *  USERS  *
  **********/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.usersTitulo');
    $this->dados['separador']="userUsers";
    $this->dados['funcao']="all";

    $query = \DB::table('utilizadores')->orderBy('id','DESC')->get();
    $array =[];
    foreach ($query as $valor){

      $avatar = '<img src="'.asset('/img/clientes/default.svg').'" class="table-img-circle">';
      if($valor->foto && file_exists(base_path('public_html/img/clientes/'.$valor->foto))){
        $avatar = '<img src="'.asset('/img/clientes/'.$valor->foto).'" class="table-img-circle">';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->lingua){
        case "pt": $lingua = '<span class="tag tag-vermelho">'.trans('backoffice.Portuguese').'</span>'; break;
        case "en": $lingua = '<span class="tag tag-azul">'.trans('backoffice.English').'</span>'; break;
        case "es": $lingua = '<span class="tag tag-amarelo">'.trans('backoffice.Spanish').'</span>'; break;
        default: $lingua = '<span class="tag tag-cinza">'.$valor->lingua.'</span>';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "ativo": $estado = '<span class="tag tag-verde">'.trans('backoffice.Active').'</span>'; break;
        case "pendente":
          $estado = '<span class="tag tag-amarelo cursor-pointer" onclick="$(\'#erroEnvio'.$valor->id.'\').toggle();">'.trans('backoffice.Pending').'</span>
          <div id="erroEnvio'.$valor->id.'" class="display-none">
            <span class="tag tag-turquesa cursor-pointer nowrap" onclick="$(\'#id_modalRE\').val('.$valor->id.');" data-toggle="modal" data-target="#myModalResendEmail">'.trans('backoffice.ResendEmail').'</span>
          </div>';
          break;
        case "delete": $estado = '<span class="tag tag-cinza">'.trans('backoffice.Deleted').'</span>'; break;
        default: $estado = '<span class="tag tag-roxo">'.$valor->estado.'</span>';
      }

      //abrir dominio do email
      $emailPiece = explode("@", $valor->email);
      if(!in_array($emailPiece[1], ['gmail.com','hotmail.com','outlook.com','outlook.pt','yahoo.com','msn.com','aol.com','live.com','mail.com'])){
        $email = '<a href="http://'.$emailPiece[1].'" target="_blank">'.$valor->email.'</a>';
      }else{ $email = $valor->email; }

      $array[] = [
        'id' => $valor->id,
        'token' => $valor->token,
        'nome' => $valor->nome.' '.$valor->apelido,
        'email' => $email,
        'avatar' => $avatar,
        'registo' => $valor->data ? date('d/m/Y H:i:s',$valor->data) : '',
        'ultimo' => $valor->ultimo_acesso ? date('d/m/Y H:i:s',$valor->ultimo_acesso) : '',
        'lingua' => $lingua,
        'estado' => $estado
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/users-all', $this->dados);
  }

  public function delete(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('utilizadores')->select('id', 'foto')->where('id', $id)->first();
    if(isset($linha->id) && $linha->id){
      if($linha->foto && file_exists(base_path('public_html/img/clientes/'.$linha->foto))){
        \File::delete('../public_html/img/clientes/'.$linha->foto);
        //\File::deleteDirectory('../public_html/img/clientes/'.$linha->foto);
      }
      \DB::table('utilizadores')->where('id',$id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function resendEmail(Request $request){
    $id=trim($request->id);
    $user = \DB::table('utilizadores')->where('id',$id)->first();
    if(isset($user->id) && $user->id){
      $dados = [ 'token' => $user->token ];
      app()->setLocale($user->lingua);
      Mail::send('site_v2.emails.pages.validateAccount',['dados' => $dados], function($message) use ($user){
          $message->to($user->email,'')->subject(trans('site_v2.Validation_Email_txt'));
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
      return 'sucesso';
    }
  }

  public function login($id){
    $user = \DB::table('utilizadores')->where('id', $id)->first();
    if($user){
      $nome_partes = explode(" ", $user->nome);
      $cart = \DB::table('carrinho_linha')
                  ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                  ->where('carrinho.id_utilizador', $user->id)
                  ->where('carrinho.estado','pendente')
                  ->sum('carrinho_linha.quantidade');
   
      Cookie::queue(Cookie::make('cookie_user_id',$user->id,43200));
      Cookie::queue(Cookie::make('cookie_user_token',$user->token,43200));
      Cookie::queue(Cookie::make('cookie_user_name',$nome_partes[0],43200));
      Cookie::queue(Cookie::make('cookie_user_photo',$user->foto,43200));
      Cookie::queue(Cookie::make('cookie_user_points',$user->pontos,43200));
      Cookie::queue(Cookie::make('cookie_user_cart',$cart,43200));
      Cookie::queue(Cookie::make('cookie_user_status',$user->estado,43200));

      return redirect()->route('areaReservedV2');
    }
  }

  /*********
  *  USER  *
  *********/
  public function new(){
    $this->dados['headTitulo']=trans('backoffice.userTitulo');
    $this->dados['separador']="userUsers";
    $this->dados['funcao']="new";
    
    return redirect()->route('usersPageB');
    //return view('backoffice/pages/users-new', $this->dados);
  }

  public function edit($id){
    $this->dados['headTitulo']=trans('backoffice.userTitulo');
    $this->dados['separador']="userUsers";
    $this->dados['funcao']="edit";

    //ADDRESSES
    $addressesQuery=\DB::table('utilizadores_morada')->where('id_utilizador',$id)->get();
    $enderecos = [];
    foreach ($addressesQuery as $valor){
      if($valor->tipo=='morada_faturacao'){
        $enderecos['id_faturacao'] = $valor->id;
        $enderecos['nome_fact'] = $valor->nome;
        $enderecos['email_fact'] = $valor->email;
        $enderecos['telefone_fact'] = $valor->telefone;
        $enderecos['nif_fact'] = $valor->nif;
        $enderecos['morada_fact'] = $valor->morada;
        $enderecos['morada_opc_fact'] = $valor->morada_opc;
        $enderecos['codigo_postal_fact'] = $valor->codigo_postal;
        $enderecos['cidade_fact'] = $valor->cidade;
        $enderecos['pais_fact'] = $valor->pais;
      }else{
        $enderecos['id_entrega'] = $valor->id;
        $enderecos['nome_entrega'] = $valor->nome;
        $enderecos['email_entrega'] = $valor->email;
        $enderecos['telefone_entrega'] = $valor->telefone;
        $enderecos['morada_entrega'] = $valor->morada;
        $enderecos['morada_opc_entrega'] = $valor->morada_opc;
        $enderecos['codigo_postal_entrega'] = $valor->codigo_postal;
        $enderecos['cidade_entrega'] = $valor->cidade;
        $enderecos['pais_entrega'] = $valor->pais;
      }
    }
    $this->dados['enderecos']=$enderecos;

    //POINTS
    $pointsQuery=\DB::table('rotulos_utilizador')
                    ->select('rotulos_utilizador.*','rotulos.codigo','rotulos.valor')
                    ->leftJoin('rotulos','rotulos.id','=','rotulos_utilizador.id_rotulo')
                    ->where('rotulos_utilizador.id_utilizador',$id)
                    ->orderBy('rotulos_utilizador.id', 'DESC')
                    ->get();
    $rotulos = [];
    foreach ($pointsQuery as $valor){

      $pontos = ($valor->valor-$valor->valor_final).' / '.$valor->valor;

      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "disponivel": $estado='<span class="tag tag-verde">'.trans('backoffice.Available').'</span>'; break;
        case "indisponivel": $estado='<span class="tag tag-vermelho">'.trans('backoffice.Unavailable').'</span>'; break;
        default: $estado='<span class="tag tag-cinza">'.$valor->estado.'</span>';
      }

      $rotulos[]=[
        'id' => $valor->id,
        'codigo' => $valor->codigo,
        'pontos' => $pontos,
        'validade' => $valor->data ? date('d/m/Y H:i',$valor->data) : '',
        'estado' => $estado
      ];
    }
    $this->dados['rotulos']=$rotulos;


    //AWARDS
    $cartQuery=\DB::table('carrinho')->where('id_utilizador',$id)->orderBy('id', 'DESC')->get();
    /*$subscricoesQuery=\DB::table('subscricoes')
                          ->select('subscricoes.*','info_userTipo.nome')
                          ->leftJoin('info_userTipo','info_userTipo.id','=','subscricoes.id_userTipo')
                          ->where('subscricoes.id_user', $id)
                          ->get();*/
    $carrinho = [];
    foreach ($cartQuery as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      /*switch ($valor->origem){
        case "compra": $origem = '<span class="tag tag-verde">'.trans('backoffice.sourcePurchase').'</span>'; break;
        case "compra subscricao": $origem = '<span class="tag tag-azul">'.trans('backoffice.sourceSubscription').'</span>'; break;
        case "compra subscricao empresa": $origem = '<span class="tag tag-turquesa">'.trans('backoffice.sourceBusiness').'</span>'; break;
        case "registo": $origem = '<span class="tag tag-amarelo">'.trans('backoffice.sourceRegister').'</span>'; break;
        case "backoffice": $origem = '<span class="tag tag-laranja">'.trans('backoffice.sourceBackoffice').'</span>'; break;
        case "auto-renovacao": $origem = '<span class="tag tag-roxo">'.trans('backoffice.sourceAuto').'</span>'; break;
        case "anuais": $origem = '<span class="tag tag-ouro">'.trans('backoffice.sourceAnnual').'</span>'; break;
        case "convite": $origem = '<span class="tag tag-vermelho">'.trans('backoffice.sourceInvitation').'</span>'; break;
        case "codigo de oferta": $origem = '<span class="tag tag-rosa">'.trans('backoffice.sourceOffer').'</span>'; break;          
        default: $origem = '<span class="tag tag-cinza">'.$valor->origem.'</span>';
      }*/
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "atual":
          $data=$valor->data;
          $estado='<span class="tag tag-amarelo">'.trans('backoffice.Current').'</span>';
          break;
        case "processamento":
          $data=$valor->data_pedido;
          $estado='<span class="tag tag-laranja">'.trans('backoffice.Processing').'</span>';
          break;
        case "enviado":
          $data=$valor->data_envio;
          $estado='<span class="tag tag-verde">'.trans('backoffice.Sent').'</span>';
          break;
        case "concluido":
          $data=$valor->data_conclusao;
          $estado='<span class="tag tag-azul">'.trans('backoffice.Concluded').'</span>';
          break;
        default:
          $data='';
          $estado='<span class="tag tag-cinza">'.$valor->estado.'</span>';
      }

      //produtos
      $produtos=[];
      $prodQuery=\DB::table('carrinho_linha')->where('id_carrinho',$valor->id)->get();
      foreach ($prodQuery as $val){
        $produtos[]=[
          'id' => $val->id,
          'nome' => $val->nome,
          'variante' => $val->variante,
          'quantidade' => $val->quantidade,
          'pontos' => $val->pontos_utilizados.' / '.$val->pontos_necessarios
        ];
      }

      $carrinho[] = [
        'id' => $valor->id,
        'nome' => $valor->nome_fact,
        'pontos' => $valor->pontos_utilizados.' / '.$valor->pontos_necessarios,
        'valor' => number_format($valor->valor_pago, 2, ',', ' ').' €',
        'data' => $data ? date('d/m/Y H:i',$data) : '',
        'estado' => $estado,
        'produtos' => $produtos
      ];
    }
    $this->dados['carrinho']=$carrinho;



    $lang=json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['paises']=\DB::table('pais')->select('id','nome_'.$lang.' AS nome')->get();    
    $this->dados['dados']=(array) \DB::table('utilizadores')->where('id',$id)->first();
    return view('backoffice/pages/users-new', $this->dados);
  }

  public function uploadPhoto(Request $request){
    $id_user=trim($request->id_user);
    $ficheiro=$request->file('ficheiro');

    $novoNome='';
    if(count($ficheiro)){
      $linha = \DB::table('utilizadores')->where('id',$id_user)->first();
      $antigoNome=$linha->foto;
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      $novoNome = 'foto_'.$linha->id.'_'.$cache.'.'.$extensao;

      $pasta = base_path('public_html/img/clientes/');
      $width = 300; $height = 300;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      \DB::table('utilizadores')->where('id',$id_user)->update([ 'foto'=>$novoNome ]);
    }

    $resposta = [
          'estado' => 'sucesso',
          'foto' => $novoNome,
          'token' => $linha->token,
          'erro' => ''
    ];
    return json_encode($resposta,true);
  }

  public function deletePhoto(Request $request){
    $id_user=trim($request->id);
    $linha = \DB::table('utilizadores')->where('id',$id_user)->first();

    if(isset($linha->id))
    {
      if($linha->foto && file_exists(base_path('public_html/img/clientes/'.$linha->foto))){
        \File::delete('../public_html/img/clientes/'.$linha->foto);
      }
      //if($linha['avatar'] && file_exists(base_path().'/public_html/img/trabalhador/'.$ficha->foto)){ \File::delete('../public_html/img/trabalhador/'.$ficha->foto); }
      \DB::table('utilizadores')->where('id',$id_user)->update([ 'foto'=>'' ]);
    }
    return 'sucesso';
  }

  public function usersAccountForm(Request $request){
    $id_user=trim($request->id_user);
    $estado=trim($request->estado);
    $newsletter = (isset($request->newsletter)) ? 1 : 0;

    \DB::table('utilizadores')->where('id',$id_user)->update([ 'estado'=>$estado, 'newsletter'=>$newsletter ]);
    return;
  }

  public function usersPersonalForm(Request $request){
    $id_user=trim($request->id_user);
    $nome=trim($request->nome);
    $apelido=trim($request->apelido);
    $email=trim($request->email);
    $email_alteracao=trim($request->email_alteracao);    
    $telefone=trim($request->telefone);

    \DB::table('utilizadores')->where('id',$id_user)->update([ 'nome'=>$nome,
                                                               'apelido'=>$apelido,
                                                               'email'=>$email,
                                                               'email_alteracao'=>$email_alteracao,
                                                               'telefone'=>$telefone ]);
    return;
  }

  public function usersAddressesForm(Request $request){
    $id_user=trim($request->id_user);
    $id_faturacao=trim($request->id_faturacao);
    $id_entrega=trim($request->id_entrega);
    $nome_fact=trim($request->nome_fact);
    $email_fact=trim($request->email_fact);
    $telefone_fact=trim($request->telefone_fact);
    $nif_fact=trim($request->nif_fact);
    $morada_fact=trim($request->morada_fact);
    $morada_opc_fact=trim($request->morada_opc_fact);
    $codigo_postal_fact=trim($request->codigo_postal_fact);
    $cidade_fact=trim($request->cidade_fact);
    $pais_fact=trim($request->pais_fact);
    $nome_entrega=trim($request->nome_entrega);
    $email_entrega=trim($request->email_entrega);
    $telefone_entrega=trim($request->telefone_entrega);
    $morada_entrega=trim($request->morada_entrega);
    $morada_opc_entrega=trim($request->morada_opc_entrega);
    $codigo_postal_entrega=trim($request->codigo_postal_entrega);
    $cidade_entrega=trim($request->cidade_entrega);
    $pais_entrega=trim($request->pais_entrega);

    if($id_faturacao){
      \DB::table('utilizadores_morada')
        ->where('id',$id_faturacao)
        ->where('id_utilizador',$id_user)
        ->where('tipo','morada_faturacao')
        ->update(['nome'=>$nome_fact,
                  'email'=>$email_fact,
                  'telefone'=>$telefone_fact,
                  'nif'=>$nif_fact,
                  'morada'=>$morada_fact,
                  'morada_opc'=>$morada_opc_fact,
                  'codigo_postal'=>$codigo_postal_fact,
                  'cidade'=>$cidade_fact,
                  'pais'=>$pais_fact ]);
    }else{
      $id_faturacao=\DB::table('utilizadores_morada')
              ->insertGetId(['id_utilizador'=>$id_user,
                             'tipo'=>'morada_faturacao',
                             'nome'=>$nome_fact,
                             'email'=>$email_fact,
                             'telefone'=>$telefone_fact,
                             'nif'=>$nif_fact,
                             'morada'=>$morada_fact,
                             'morada_opc'=>$morada_opc_fact,
                             'codigo_postal'=>$codigo_postal_fact,
                             'cidade'=>$cidade_fact,
                             'pais'=>$pais_fact ]);
    }
    if($id_entrega){
      \DB::table('utilizadores_morada')
        ->where('id',$id_entrega)
        ->where('id_utilizador',$id_user)
        ->where('tipo','morada_entrega')
        ->update(['nome'=>$nome_entrega,
                  'email'=>$email_entrega,
                  'telefone'=>$telefone_entrega,
                  'morada'=>$morada_entrega,
                  'morada_opc'=>$morada_opc_entrega,
                  'codigo_postal'=>$codigo_postal_entrega,
                  'cidade'=>$cidade_entrega,
                  'pais'=>$pais_entrega ]);
    }else{
      $id_entrega=\DB::table('utilizadores_morada')
              ->insertGetId(['id_utilizador'=>$id_user,
                             'tipo'=>'morada_entrega',
                             'nome'=>$nome_entrega,
                             'email'=>$email_entrega,
                             'telefone'=>$telefone_entrega,
                             'morada'=>$morada_entrega,
                             'morada_opc'=>$morada_opc_entrega,
                             'codigo_postal'=>$codigo_postal_entrega,
                             'cidade'=>$cidade_entrega,
                             'pais'=>$pais_entrega ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'mensagem' => '',
        'id_faturacao' => $id_faturacao,
        'id_entrega' => $id_entrega ];
    return json_encode($resposta,true);
  }

  public function deletePoints(Request $request){
    $id_user=trim($request->id_user);
    $id_points=trim($request->id_points);

    \DB::table('rotulos_utilizador')->where('id',$id_points)->where('id_utilizador',$id_user)->delete();
    $pontos = \DB::table('rotulos_utilizador')->where('id_utilizador',$id_user)->get()->sum('valor_final');
    \DB::table('utilizadores')->where('id',$id_user)->update(['pontos'=>$pontos]);
    //SELECT SUM(valor) AS total_despesas FROM lançamentos WHERE tipo_id = 1;

    $resposta = [
      'estado' => 'sucesso',
      'quantidade' => $pontos ];
    return json_encode($resposta,true);
  }

  public function pointsForm(Request $request){
    $id_user=trim($request->id_user);
    $codigo=trim($request->codigo) ? trim($request->codigo) : 'BACKOFFICE';    
    $quantidade=filter_var(trim($request->quantidade), FILTER_VALIDATE_INT);
    $validade=trim($request->validade) ? Carbon::createFromFormat('d-m-Y', trim($request->validade))->timestamp : '';//->toDateTimeString();
    $agora=\Carbon\Carbon::now()->timestamp;

    if($quantidade){
      if(!$validade || $validade<$agora){$validade=Carbon::createFromTimestamp($agora)->addYears(2)->timestamp;}

      $id_rotulo=\DB::table('rotulos')
              ->insertGetId(['codigo'=>$codigo,
                             'serie'=>'BO',
                             'valor'=>$quantidade,
                             'estado'=>'indisponivel',
                             'data'=>\Carbon\Carbon::now()->timestamp ]);

      //inserir no utilizador
      \DB::table('rotulos_utilizador')->insert(['id_rotulo'=>$id_rotulo,
                                                'id_utilizador'=>$id_user,
                                                'valor_final'=>$quantidade,
                                                'data'=>$validade,
                                                'estado'=>'disponivel' ]);

      \DB::table('utilizadores')->where('id', $id_user)->increment('pontos', $quantidade);
      return 'sucesso';
    }
  }

}