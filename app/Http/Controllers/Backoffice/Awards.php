<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;

class Awards extends Controller
{
  private $dados=[];
  /*private $lang;
  public function __construct()
  {
    //$this->lang=Session::get('locale');
    $this->middleware(function ($request, $next) {
          $this->lang = json_decode(Cookie::get('admin_cookie'))->lingua;
          return $next($request);
      });
  }*/
  
  /************/
  /*  AWARDS  */
  /************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.awardsTitulo');
    $this->dados['separador']="setAwards";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('premios')
                  ->select('*','nome_'.$lang.' AS nome')
                  ->orderBy('indice','ASC')
                  ->get();
    $array =[];
    foreach ($query as $val){
      $imagem = '<img src="'.asset('site_v2/img/premios/default.svg').'" class="table-img">';
      if($val->img && file_exists(base_path('public_html'.$val->img))){
        $imagem = '<a href="'.asset($val->img).'" target="_blank"><img src="'.asset($val->img).'" class="table-img"></a>';
      }

      switch ($val->tipo){
        case "cliente": $tipo = '<span class="tag tag-amarelo">'.trans('backoffice.user').'</span>'; break;
        case "empresa": $tipo = '<span class="tag tag-verde">'.trans('backoffice.company').'</span>'; break;
        default: $tipo = '<span class="tag tag-roxo">'.trans('backoffice.both').'</span>';
      }

      $array[] = [
        'id' => $val->id,
        'imagem' => $imagem,
        'nome' => $val->nome,
        'valor_cliente' => $val->valor_cliente,
        'valor_empresa' => $val->valor_empresa,
        'tipo' => $tipo,
        'validade' => $val->data_validade ? date('Y-m-d',$val->data_validade) : '',
        'online' => $val->online
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/awards-all', $this->dados);
  }

  public function apagar(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('premios')->where('id',$id)->first();
    if(isset($linha->id)){
      if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }

      \DB::table('premios')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function newPage(){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="setAwards";
    $this->dados['funcao']="new";

    $this->dados['variante'] = \DB::table('variante')->get();
    
    return view('backoffice/pages/awards-new', $this->dados);
  }

  public function editPage($id){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="setAwards";
    $this->dados['funcao']="edit";
    $this->dados['obj'] = \DB::table('premios')->where('id',$id)->first();    

    $this->dados['variante'] = \DB::table('variante')->get();
    $this->dados['variante_premio'] = \DB::table('variante_premio')->where('id_premio',$id)->get();

    return view('backoffice/pages/awards-new', $this->dados);
  }

  public function newPageVariants(){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="setVariants";
    $this->dados['funcao']="all";

    $query=\DB::table('variante')->get();

    $array=[];

    foreach ($query as $value) {
      $array[]=[
        'id'=>$value->id,
        'variante_pt'=>$value->variante_pt
      ];  
    }

    $this->dados['array']=$array;
    return view('backoffice/pages/awards-variants-all', $this->dados);
  }

  public function newVariantsPage(){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="setVariants";
    $this->dados['funcao']="new";

    return view('backoffice/pages/awards-variants-new', $this->dados);
  }

  public function editVariantsPage($id){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="setVariants";
    $this->dados['funcao']="edit";

    $this->dados['obj']=\DB::table('variante')->where('id',$id)->first();
    return view('backoffice/pages/awards-variants-new', $this->dados);
  }

  public function awardsVariantForm(Request $request){
    $id=trim($request->id);
    $variante_pt=trim($request->variante_pt);
    $variante_en=trim($request->variante_en);
    $variante_es=trim($request->variante_es);
    $variante_fr=trim($request->variante_fr);

    if (empty($variante_pt) || empty($variante_en) || empty($variante_es) || empty($variante_fr)) {
      return trans('backoffice.EnterVariantsLanguages_txt');
    }

    if($id){
      \DB::table('variante')
          ->where('id',$id)
          ->update([
            'variante_pt'=>$variante_pt,
            'variante_en'=>$variante_en,
            'variante_es'=>$variante_es,
            'variante_fr'=>$variante_fr

          ]);
    }
    else{
      $id=\DB::table('variante')
              ->insertGetId([
                'variante_pt'=>$variante_pt,
                'variante_en'=>$variante_en,
                'variante_es'=>$variante_es,
                'variante_fr'=>$variante_fr
              ]);
    }

    $resposta = [ 
      'estado' => 'sucesso',
      'id'=>$id
    ];
    return json_encode($resposta,true); 
  }

  public function awardsVariantDelete(Request $request){
    $id=trim($request->id);

    \DB::table('variante')->where('id',$id)->delete();

    return 'sucesso';
  }

  public function form(Request $request)
  {    
    $id=trim($request->id);
    $nome_pt=trim($request->nome_pt);
    $nome_en=trim($request->nome_en);
    $nome_es=trim($request->nome_es);
    $nome_fr=trim($request->nome_fr);

    $descricao_pt=trim($request->descricao_pt);
    $descricao_en=trim($request->descricao_en);
    $descricao_es=trim($request->descricao_es);
    $descricao_fr=trim($request->descricao_fr);

    $valor_cliente=trim(intval($request->valor_cliente));
    $valor_empresa=trim(intval($request->valor_empresa));
    $stock=trim(intval($request->stock));
    $tipo=trim($request->tipo);
    //$data_validade = trim($request->data_validade) ? strtotime(trim($request->data_validade)) : \Carbon\Carbon::now()->timestamp;
    $data_validade = trim($request->data_validade) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_validade).' 23:59:59')->timestamp : 0;//->toDateTimeString();
    $online = (isset($request->online)) ? 1 : 0;

    $img_antiga=trim($request->img_antiga);
    $ficheiro=$request->file('ficheiro');
        
    if($id){
      \DB::table('premios')
        ->where('id',$id)
        ->update(['nome_pt'=>$nome_pt,
                  'nome_en'=>$nome_en,
                  'nome_es'=>$nome_es,
                  'nome_fr'=>$nome_fr,
                  'descricao_pt'=>$descricao_pt,
                  'descricao_en'=>$descricao_en,
                  'descricao_es'=>$descricao_es,
                  'descricao_fr'=>$descricao_fr,
                  'valor_cliente'=>$valor_cliente,
                  'valor_empresa'=>$valor_empresa,
                  'stock'=>$stock,
                  'tipo'=>$tipo,
                  'data_validade'=>$data_validade,
                  'online'=>$online ]);
      
      if(empty($img_antiga) || count($ficheiro)){
        $linha = \DB::table('premios')->where('id',$id)->first();
        if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }
        \DB::table('premios')->where('id',$linha->id)->update(['img'=>'']);
      }
    }else{

      $id=\DB::table('premios')
              ->insertGetId(['nome_pt'=>$nome_pt,
                             'nome_en'=>$nome_en,
                             'nome_es'=>$nome_es,
                             'nome_fr'=>$nome_fr,
                             'descricao_pt'=>$descricao_pt,
                             'descricao_en'=>$descricao_en,
                             'descricao_es'=>$descricao_es,
                             'descricao_fr'=>$descricao_fr,
                             'valor_cliente'=>$valor_cliente,
                             'valor_empresa'=>$valor_empresa,
                             'stock'=>$stock,
                             'tipo'=>$tipo,
                             'data_validade'=>$data_validade,
                             'online'=>$online,
                             'data'=>\Carbon\Carbon::now()->timestamp ]);

      //Inserir variantes
      $variantes = \DB::table('variante')->get();
      
      foreach ($variantes as $value) {
        
        $var_pt = 'variante_pt'.$value->id;
        $var_es = 'variante_es'.$value->id;
        $var_en = 'variante_en'.$value->id;
        $var_fr = 'variante_fr'.$value->id;

        $variante_pt = $request->$var_pt;
        $variante_es = $request->$var_es;
        $variante_en = $request->$var_en;
        $variante_fr = $request->$var_fr;



          

          foreach ($variante_pt as $value_pt) {
            \DB::table('variante_premio')
              ->insert([
                'id_premio' => $id,
                'id_variante' => $value->id,
                'valor_pt' => $value_pt
              ]);
          }

          foreach ($variante_es as $value_es) {
            \DB::table('variante_premio')
                ->where('id_premio',$id)
                ->where('id_variante',$value->id)
                ->update(['valor_es' => $value_es]);
          }

          foreach ($variante_en as $value_en) {
            \DB::table('variante_premio')
                ->where('id_premio',$id)
                ->where('id_variante',$value->id)
                ->update(['valor_en' => $value_en]);
          }

          foreach ($variante_fr as $value_fr) {
            \DB::table('variante_premio')
                ->where('id_premio',$id)
                ->where('id_variante',$value->id)
                ->update(['valor_fr' => $value_fr]);
          }
        
      }
    }


    $novoNome=$img_antiga;
    if(count($ficheiro)){
      $pasta = base_path('public_html/site_v2/img/premios/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      
      $novoNome = 'award'.$id.'-'.$cache.'.'.$extensao;
      $width = 600; $height = 600;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      $novoNome='/site_v2/img/premios/'.$novoNome;
      \DB::table('premios')->where('id',$id)->update([ 'img'=>$novoNome ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id,
        'imagem' => $novoNome ];
    return json_encode($resposta,true);
  }

  public function awardsAdd(Request $request){
    $id=trim($request->id);
    $id_premio=trim($request->id_premio);
    $variante_pt=trim($request->variante_pt);
    $variante_en=trim($request->variante_en);
    $variante_es=trim($request->variante_es);
    $variante_fr=trim($request->variante_fr);

    
    $id=\DB::table('variante_premio')
        ->insertGetId([
          'id_premio'=>$id_premio,
          'id_variante'=>$id,
          'valor_pt'=>$variante_pt,
          'valor_en'=>$variante_en,
          'valor_es'=>$variante_es,
          'valor_fr'=>$variante_fr
        ]);

    return $id;
  }

  public function awardsDelete(Request $request){
    $id=trim($request->id);

    \DB::table('variante_premio')->where('id',$id)->delete();
    return 'sucesso';

  }

  /********************
  *  PRÉMIOS - USERS  *
  *********************/
  public function allUserAwards(){
    $this->dados['headTitulo']=trans('backoffice.userTitulo');
    $this->dados['separador']="userAwards";
    $this->dados['funcao']="all";
    
    $cartQuery = \DB::table('carrinho')->where('estado','<>','atual')->orderBy('id', 'DESC')->get();

    $carrinho = [];
    foreach ($cartQuery as $valor){
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


      $carrinho[] = [
        'id' => $valor->id,
        'nome' => $valor->nome_fact,
        'pontos' => $valor->pontos_utilizados.' / '.$valor->pontos_necessarios,
        'valor' => number_format($valor->valor_pago, 2, ',', ' ').' €',
        'data' => $data ? date('d/m/Y H:i',$data) : '',
        'estado' => $estado
      ];
    }
    $this->dados['carrinho']=$carrinho;

    return view('backoffice/pages/awards-users-all', $this->dados);
  }

  public function editUserAwards($id){
    $this->dados['headTitulo']=trans('backoffice.userTitulo');
    $this->dados['separador']="userAwards";
    $this->dados['funcao']="edit";

    $cartQuery = \DB::table('carrinho')->where('id',$id)->orderBy('id', 'DESC')->get();

    $carrinho = [];
    foreach ($cartQuery as $valor){
    
      //produtos
      $produtos=[];
      $prodQuery=\DB::table('carrinho_linha')->where('id_carrinho',$valor->id)->get();
      foreach ($prodQuery as $val){
        $premio = \DB::table('premios')->where('id',$val->id_premio)->first();
        $produtos[]=[
          'id_linha' => $val->id,
          'nome' => $val->nome,
          'variante' => $val->variante,
          'quantidade' => $val->quantidade,
          'img' => $premio->img,
          'pontos' => $val->pontos_utilizados.' / '.$val->pontos_necessarios
        ];
      }

      $carrinho[] = [
        'id' => $valor->id,
        'id_utilizador' => $valor->id_utilizador,
        'nome' => $valor->nome_fact,
        'pontos' => $valor->pontos_utilizados.' / '.$valor->pontos_necessarios,
        'pontos_utilizados' => $valor->pontos_utilizados,
        'pontos_necessarios' => $valor->pontos_necessarios,
        'valor' => number_format($valor->valor_pago, 2, ',', ' ').' €',
        'data_pedido' => $valor->data_pedido ? date('d/m/Y H:i',$valor->data_pedido) : '',
        'data_envio' => $valor->data_envio,
        'data_conclusao' => $valor->data_conclusao,
        'nome_fact' => $valor->nome_fact,
        'email_fact' => $valor->email_fact,
        'contacto_fact' => $valor->contacto_fact,
        'nif_fact' => $valor->nif_fact,
        'morada_fact' => $valor->morada_fact,
        'morada_opc_fact' => $valor->morada_opc_fact,
        'code_post_fact' => $valor->code_post_fact,
        'cidade_fact' => $valor->cidade_fact,
        'pais_fact' => $valor->pais_fact,
        'nome_entrega' => $valor->nome_entrega,
        'email_entrega' => $valor->email_entrega,
        'contacto_entrega' => $valor->contacto_entrega,
        'morada_entrega' => $valor->morada_entrega,
        'morada_opc_entrega' => $valor->morada_opc_entrega,
        'code_post_entrega' => $valor->code_post_entrega,
        'cidade_entrega' => $valor->cidade_entrega,
        'pais_entrega' => $valor->pais_entrega,
        'nota' => $valor->nota,
        'estado' => $valor->estado,
        'produtos' => $produtos
      ];
    }
    $this->dados['carrinho']=$carrinho;
    $this->dados['id']=$id;

    return view('backoffice/pages/awards-users-edit', $this->dados);
  }

  public function editUserAwardsForm(Request $request){
    $id_car = trim($request->id_car);
    $nome_fact = trim($request->nome_fact);
    $email_fact = trim($request->email_fact);
    $contact_fact = trim($request->contact_fact);
    $nif = trim($request->nif);
    $morada_fact = trim($request->morada_fact);
    $morada_opc_fact = trim($request->morada_opc_fact);
    $code_postal_fact = trim($request->code_postal_fact);
    $cidade_fact = trim($request->cidade_fact);
    $pais_fact = trim($request->pais_fact);
    $nome_entrega = trim($request->nome_entrega);
    $email_entrega = trim($request->email_entrega);
    $contacto_entrega = trim($request->contacto_entrega);
    $morada_entrega = trim($request->morada_entrega);
    $morada_opc_entrega = trim($request->morada_opc_entrega);
    $code_postal_entrega = trim($request->code_postal_entrega);
    $cidade_entrega = trim($request->cidade_entrega);
    $pais_entrega = trim($request->pais_entrega);
    $nota = trim($request->nota);
    $data_entrega = trim($request->data_entrega) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_entrega).' 23:59:59')->timestamp : 0;
    $data_conclusao = trim($request->data_conclusao) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_conclusao).' 23:59:59')->timestamp : 0;
    $estado = trim($request->estado);

    $car = \DB::table('carrinho')->where('id',$id_car)->first();
    
    \DB::table('carrinho')
        ->where('id',$id_car)
        ->update([
          'nome_fact' => $nome_fact,
          'email_fact' => $email_fact,
          'contacto_fact' => $contact_fact,
          'nif_fact' => $nif,
          'morada_fact' => $morada_fact,
          'morada_opc_fact' => $morada_opc_fact,
          'code_post_fact' => $code_postal_fact,
          'cidade_fact' => $cidade_fact,
          'pais_fact' => $pais_fact,
          'nome_entrega' => $nome_entrega,
          'email_entrega' => $email_entrega,
          'contacto_entrega' => $contacto_entrega,
          'morada_entrega' => $morada_entrega,
          'morada_opc_entrega' => $morada_opc_entrega,
          'code_post_entrega' => $code_postal_entrega,
          'cidade_entrega' => $cidade_entrega,
          'pais_entrega' => $pais_entrega,
          'nota' => $nota,
          'data_envio' => $data_entrega,
          'data_conclusao' => $data_conclusao,
          'estado' => $estado
        ]);

    //se o estado for diferente na BD e a data de envio/conclusão

    if($car->estado != $estado){

      switch ($estado){
        case "processamento":
          $estado=trans('backoffice.in_processing');
          break;
        case "enviado":
          $estado=trans('backoffice.sent');
          break;
        case "concluido":
          $estado=trans('backoffice.concluded');
          break;
        default:
          $estado = $estado;
      }

      $dados = [ 
        'id' => $id_car,
        'estado' => $estado 
      ];
     
      \Mail::send('backoffice.emails.pages.send-status-awards',['dados' => $dados], function($message) use ($request){
        $message->to($request->email_entrega,$request->nome_fact)->subject(trans('Estado da solicitação do prémio'));
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
    }
    
    if($car->data_envio != $data_entrega){
      $dados = [ 
        'id' => $id_car ,
        'data_envio' => date('Y-m-d',$data_entrega) 
      ];

      \Mail::send('backoffice.emails.pages.send-date-awards',['dados' => $dados], function($message) use ($request){
        $message->to($request->email_entrega,$request->nome_fact)->subject(trans('Data de envio da solicitação do prémio'));
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
    }


    $resposta = [
      'estado' => 'sucesso',
      'reload' => 'sim'
    ];
    return json_encode($resposta,true);
  }

  public function deleteUserAwardsForm(Request $request){
    $id = trim($request->id);

    //ir buscar os pontos que o utilizador gastou na solicitacao
    $car = \DB::table('carrinho')->where('id',$id)->first();

    if ($car->estado == 'processamento') {
      //ADD rotulos_utilizador
      $simbolos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $codigo = '';
      $caracteres = '';
      $caracteres .= $simbolos;
      $len = strlen($caracteres);
      for ($n = 1; $n <= 6; $n++)
      {
        $rand = mt_rand(1, $len);
        $codigo .= $caracteres[$rand - 1];
      }

      $count = \DB::table('rotulos')->where('codigo', 'LIKE BINARY', $codigo)->count();
  
      if ($count == 0) {
        $codigo_inv = strrev($codigo);
        $id_rotulo=\DB::table('rotulos')
                      ->insertGetId([
                        'codigo'=> $codigo,
                        'codigo_inv'=> $codigo_inv,
                        'serie'=> 'A',
                        'valor'=> $car->pontos_necessarios,
                        'estado'=> 'disponivel',
                        'data'=>\Carbon\Carbon::now()->timestamp
                      ]);

        switch(strlen($id_rotulo)){
          case '1': $id_inv=strrev($id_rotulo.'00000000000'); break;
          case '2': $id_inv=strrev($id_rotulo.'0000000000'); break;
          case '3': $id_inv=strrev($id_rotulo.'000000000'); break;
          case '4': $id_inv=strrev($id_rotulo.'00000000'); break;
          case '5': $id_inv=strrev($id_rotulo.'0000000'); break;
          case '6': $id_inv=strrev($id_rotulo.'000000'); break;
          case '7': $id_inv=strrev($id_rotulo.'00000'); break;
          case '8': $id_inv=strrev($id_rotulo.'0000'); break;
          case '9': $id_inv=strrev($id_rotulo.'000'); break;
          case '10':$id_inv=strrev($id_rotulo.'00'); break;
          case '11':$id_inv=strrev($id_rotulo.'0'); break;      
          default:  $id_inv=strrev($id_rotulo); break;
        }
  
        \DB::table('rotulos')->where('id',$id_rotulo)->update([ 'id_inv' => $id_inv ]);

        $data_expiracao = strtotime(date('Y-m-d H:i:s'). ' + 24  months');
      
        \DB::table('rotulos_utilizador')
            ->insert([
              'id_rotulo' => $id_rotulo,
              'id_utilizador' => $car->id_utilizador,
              'valor_final' => $car->pontos_necessarios,
              'data' => $data_expiracao,
              'estado' => 'disponivel'
            ]);

        $user = \DB::table('utilizadores')->where('id',$car->id_utilizador)->first();
        $pontos_user = $user->pontos + $car->pontos_necessarios;
        \DB::table('utilizadores')
            ->where('id',$car->id_utilizador)
            ->update([
              'pontos' => $pontos_user
            ]);

        //Atualizar cookies pontos
        \Cookie::queue('cookie_user_points', $pontos_user);
      }
    }

    \DB::table('carrinho')->where('id',$id)->delete();
    return 'sucesso';
  }


  /*********************
  *  PRÉMIOS - SELLER  *
  **********************/

  public function allSellerAwards(){
    $this->dados['headTitulo']=trans('backoffice.userTitulo');
    $this->dados['separador']="sellerAwards";
    $this->dados['funcao']="all";
    
    $cartQuery = \DB::table('premios_empresa')->orderBy('id', 'DESC')->get();

    $carrinho = [];
    foreach ($cartQuery as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "atual":
          $data=$valor->data_pedido;
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

      $empresa = \DB::table('empresas')->where('id',$valor->id_empresa)->first();

      $carrinho[] = [
        'id' => $valor->id,
        'id_empresa' => $valor->id_empresa,
        'nome_empresa' => $empresa->nome,
        'pontos' => $valor->pontos,
        'data' => $data ? date('d/m/Y H:i',$data) : '',
        'estado' => $estado
      ];
    }
    $this->dados['carrinho']=$carrinho;

    return view('backoffice/pages/awards-sellers-all', $this->dados);
  }


  public function editSellerAwards($id){
    $this->dados['headTitulo']=trans('backoffice.userTitulo');
    $this->dados['separador']="sellerAwards";
    $this->dados['funcao']="edit";

    $cartQuery = \DB::table('premios_empresa')->where('id',$id)->orderBy('id', 'DESC')->get();

    $carrinho = [];
    foreach ($cartQuery as $valor){
      //produtos
      $produtos=[];
      $prodQuery=\DB::table('premios')->where('id',$valor->id_premio)->get();
      foreach ($prodQuery as $val){
        $premio = \DB::table('premios')->where('id',$val->id)->first();
        $produtos[]=[
          'id_linha' => $valor->id,
          'nome' => $premio->nome_pt,
          'variante' => $valor->variante,
          'quantidade' => $valor->quantidade,
          'img' => $premio->img,
          'pontos' => $premio->valor_empresa
        ];
      }

      $empresa = \DB::table('empresas')->where('id',$valor->id_empresa)->first();

      $carrinho[] = [
        'id' => $valor->id,
        'id_empresa' => $valor->id_empresa,
        'nome_empresa' => $empresa->nome,
        'pontos' => $valor->pontos,
        'data_pedido' => $valor->data_pedido ? date('d/m/Y H:i',$valor->data_pedido) : '',
        'data_envio' => $valor->data_envio,
        'data_conclusao' => $valor->data_conclusao,
        'estado' => $valor->estado,
        'produtos' => $produtos
      ];
    }
    $this->dados['carrinho']=$carrinho;
    $this->dados['id']=$id;

    return view('backoffice/pages/awards-seller-edit', $this->dados);
  }

  public function editSellerAwardsForm(Request $request){
    $id = trim($request->id);
    $data_entrega = trim($request->data_entrega) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_entrega).' 23:59:59')->timestamp : 0;
    $data_conclusao = trim($request->data_conclusao) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_conclusao).' 23:59:59')->timestamp : 0;
    $estado = trim($request->estado);

    $car = \DB::table('premios_empresa')->where('id',$id)->first();
    $seller = \DB::table('comerciantes')->where('id',$car->id_comerciante)->first();

    \DB::table('premios_empresa')
        ->where('id',$id)
        ->update([
          'data_envio' => $data_entrega,
          'data_conclusao' => $data_conclusao,
          'estado' => $estado
        ]);

    //se o estado for diferente na BD e a data de envio/conclusão

    if($car->estado != $estado){

      switch ($estado){
        case "processamento":
          $estado=trans('backoffice.in_processing');
          break;
        case "enviado":
          $estado=trans('backoffice.sent');
          break;
        case "concluido":
          $estado=trans('backoffice.concluded');
          break;
        default:
          $estado = $estado;
      }

      $dados = [ 
        'id' => $id,
        'estado' => $estado 
      ];
     
      \Mail::send('backoffice.emails.pages.send-status-awards',['dados' => $dados], function($message) use ($seller){
        $message->to($seller->email,$seller->nome)->subject(trans('Estado da solicitação do prémio'));
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });

      //Criar notificação
      if ($estado == 'processamento') {
        $tipo = 'pre_processamento';
      }
      if ($estado == 'enviado') {
        $tipo = 'pre_enviado';
      }else{
        $tipo = 'pre_concluido';
      }

      \DB::table('notificacoes')
        ->insert([
            'id_notificado' => $car->id_comerciante,
            'id_empresa' => $car->id_empresa,
            'id_comerciante' => $car->id_comerciante,
            'id_premio_empresa' => $id,
            'tipo' => $tipo,
            'url' => '\premium-history',
            'data' => \Carbon\Carbon::now()->timestamp
        ]); 
    }
    
    if($car->data_envio != $data_entrega){
      $dados = [ 
        'id' => $id ,
        'data_envio' => date('Y-m-d',$data_entrega) 
      ];

      \Mail::send('backoffice.emails.pages.send-date-awards',['dados' => $dados], function($message) use ($seller){
        $message->to($seller->email,$seller->nome)->subject(trans('Data de envio da solicitação do prémio'));
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });

      //Criar notificação
      \DB::table('notificacoes')
        ->insert([
            'id_notificado' => $car->id_comerciante,
            'id_empresa' => $car->id_empresa,
            'id_comerciante' => $car->id_comerciante,
            'id_premio_empresa' => $id,
            'tipo' => 'pre_data_envio',
            'url' => '\premium-history',
            'data' => \Carbon\Carbon::now()->timestamp
        ]); 
    }

    //atualizar cookie notificacao
    $not_count=\DB::table('notificacoes')->where('id_notificado', $car->id_comerciante)->where('vista',0)->count();

    Cookie::queue(Cookie::make('cookie_not_ative', $not_count, 43200));

    $resposta = [
      'estado' => 'sucesso',
      'reload' => 'sim'
    ];
    return json_encode($resposta,true);
  }

  public function deleteSellerAwardsForm(Request $request){

    $id = trim($request->id);
    $cartQuery = \DB::table('premios_empresa')->where('id', $id)->first();

    if ($cartQuery->estado == 'processamento') {

      $empresa = \DB::table('empresas')->where('id',$cartQuery->id_empresa)->first();

      $total_pontos = $empresa->pontos + $cartQuery->pontos;
      \DB::table('empresas')
          ->where('id',$cartQuery->id_empresa)
          ->update([
            'pontos' => $total_pontos
          ]);
    }

    \DB::table('premios_empresa')->where('id', $id)->delete();
    return 'sucesso';
  }
}