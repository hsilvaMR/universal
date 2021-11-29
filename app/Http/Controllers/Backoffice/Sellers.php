<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;
use Hash;
use Mail;

class Sellers extends Controller
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
  
  /************
  *  SELLERS  *
  ************/
  public function sellers(){
    $this->dados['headTitulo']=trans('backoffice.sellersTitulo');
    $this->dados['separador']="bizSellers";
    $this->dados['funcao']="all";

    //$query = \DB::table('comerciantes')->orderBy('id','DESC')->get();
    $query = \DB::table('comerciantes')
                ->select('comerciantes.*','empresas.nome AS nome_empresa')
                ->leftJoin('empresas','comerciantes.id_empresa','empresas.id')
                ->orderBy('comerciantes.id','DESC')
                ->get();
    $array =[];
    foreach ($query as $valor){

      $avatar = '<img src="'.asset('/img/comerciantes/default.svg').'" class="table-img-circle">';
      if($valor->foto && file_exists(base_path('public_html/img/comerciantes/'.$valor->foto))){
        $avatar = '<img src="'.asset('/img/comerciantes/'.$valor->foto).'" class="table-img-circle">';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->lingua){
        case "pt": $lingua = '<span class="tag tag-verde">'.trans('backoffice.Portuguese').'</span>'; break;
        case "en": $lingua = '<span class="tag tag-azul">'.trans('backoffice.English').'</span>'; break;
        case "es": $lingua = '<span class="tag tag-amarelo">'.trans('backoffice.Spanish').'</span>'; break;
        default: $lingua = '<span class="tag tag-cinza">'.$valor->lingua.'</span>';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tipo){
        case "gestor": $tipo = '<span class="tag tag-roxo">'.trans('backoffice.Manager').'</span>'; break;
        case "comerciante": $tipo = '<span class="tag tag-turquesa">'.trans('backoffice.Seller').'</span>'; break;
        case "admin": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Administrator').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }
      if($valor->aprovacao=='em_aprovacao'){ $tipo .= ' <span class="tag tag-amarelo">'.trans('backoffice.OnApproval').'</span>'; }
      if($valor->aprovacao=='reprovado'){ $tipo .= ' <span class="tag tag-vermelho">'.trans('backoffice.Disapproved').'</span>'; }
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
        'nome' => $valor->nome,
        'email' => $email,
        'empresa' => '#'.$valor->id_empresa.' '.$valor->nome_empresa,
        'avatar' => $avatar,
        'data' => $valor->ultimo_acesso ? date('d/m/Y H:i:s',$valor->ultimo_acesso) : date('d/m/Y H:i:s',$valor->data_registo),
        'lingua' => $lingua,
        'tipo' => $tipo,
        'estado' => $estado
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/sellers-all', $this->dados);
  }

  public function sellersDelete(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('comerciantes')->select('id', 'foto')->where('id', $id)->first();
    if(isset($linha->id) && $linha->id){
      if($linha->foto && file_exists(base_path('public_html/img/comerciantes/'.$linha->foto))){
        \File::delete('../public_html/img/comerciantes/'.$linha->foto);
        //\File::deleteDirectory('../public_html/img/comerciantes/'.$linha->foto);
      }
      \DB::table('comerciantes')->where('id',$id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function resendEmail(Request $request){
    $id=trim($request->id);
    $user = \DB::table('comerciantes')->where('id',$id)->first();
    if(isset($user->id) && $user->id){
      $dados = [ ];
      app()->setLocale($user->lingua);
      Mail::send('client.emails.pages.resendEmail',$dados, function($message) use ($user){
          $message->to($user->email,'')->subject(trans('site_v2.Validation_Email_txt'));
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
      return 'sucesso';
    }
  }

  public function login($id){
    $user = \DB::table('comerciantes')->where('id', $id)->first();
    if($user){
      $nome_partes = explode(" ", $user->nome);
      $company = \DB::table('empresas')->where('id', $user->id_empresa)->first();
      $notificacaoes_ativas = \DB::table('notificacoes')->where('id_empresa',$user->id_empresa)->where('vista',0)->count();
   
      Cookie::queue(Cookie::make('cookie_comerc_id',$user->id,43200));
      Cookie::queue(Cookie::make('cookie_comerc_id_empresa',$user->id_empresa,43200));
      Cookie::queue(Cookie::make('cookie_comerc_token',$user->token,43200));
      Cookie::queue(Cookie::make('cookie_comerc_name',$nome_partes[0],43200));
      Cookie::queue(Cookie::make('cookie_comerc_photo',$user->foto,43200));
      Cookie::queue(Cookie::make('cookie_company_photo',$company->logotipo,43200));
      Cookie::queue(Cookie::make('cookie_company_name',$company->nome,43200));
      Cookie::queue(Cookie::make('cookie_company_status',$company->estado,43200));
      Cookie::queue(Cookie::make('cookie_comerc_type',$user->tipo,43200));
      Cookie::queue(Cookie::make('cookie_company_points',$company->pontos,43200));
      Cookie::queue(Cookie::make('cookie_notification_ative',$notificacaoes_ativas,43200));

      return redirect()->route('dashboardV2');
    }
  }

  /***********
  *  SELLER  *
  ***********/
  public function sellersNew(){
    $this->dados['headTitulo']=trans('backoffice.sellerTitulo');
    $this->dados['separador']="bizSellers";
    $this->dados['funcao']="new";
    
    return redirect()->route('sellersPageB');
    //return view('backoffice/pages/sellers-new', $this->dados);
  }

  public function sellersEdit($id){
    $this->dados['headTitulo']=trans('backoffice.sellerTitulo');
    $this->dados['separador']="bizSellers";
    $this->dados['funcao']="edit";
    //$lang=json_decode(Cookie::get('admin_cookie'))->lingua;

    $this->dados['dados']=(array) \DB::table('comerciantes')
                                      ->select('comerciantes.*','empresas.nome AS nome_empresa')
                                      ->leftJoin('empresas','comerciantes.id_empresa','empresas.id')
                                      ->where('comerciantes.id',$id)
                                      ->first();

    $comprasQuery=\DB::table('moradas')
                      ->select('id','nome_personalizado AS nome')
                      ->where('id_empresa',$this->dados['dados']['id_empresa'])
                      ->get();
    $compras =[];
    foreach ($comprasQuery as $valor){
      $check = \DB::table('comerc_morada')->select('id')->where('id_morada', $valor->id)->where('id_comerciante', $this->dados['dados']['id'])->count();

      $compras[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'check' => $check
      ];
    }
    $this->dados['compras'] = $compras;

    return view('backoffice/pages/sellers-new', $this->dados);
  }

  public function uploadPhoto(Request $request){
    $id_user=trim($request->id_user);
    $ficheiro=$request->file('ficheiro');

    $novoNome='';
    if(count($ficheiro)){
      $linha = \DB::table('comerciantes')->where('id',$id_user)->first();
      $antigoNome=$linha->foto;
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      $novoNome = 'foto_'.$linha->id.'_'.$cache.'.'.$extensao;

      $pasta = base_path('public_html/img/comerciantes/');
      $width = 300; $height = 300;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      \DB::table('comerciantes')->where('id',$id_user)->update([ 'foto'=>$novoNome ]);
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
    $linha = \DB::table('comerciantes')->where('id',$id_user)->first();

    if(isset($linha->id))
    {
      if($linha->foto && file_exists(base_path('public_html/img/comerciantes/'.$linha->foto))){
        \File::delete('../public_html/img/comerciantes/'.$linha->foto);
      }
      //if($linha['avatar'] && file_exists(base_path().'/public_html/img/trabalhador/'.$ficha->foto)){ \File::delete('../public_html/img/trabalhador/'.$ficha->foto); }
      \DB::table('comerciantes')->where('id',$id_user)->update([ 'foto'=>'' ]);
    }
    return 'sucesso';
  }

  public function sellersAccountForm(Request $request){
    $id_user=trim($request->id_user);
    $estado=trim($request->estado);
    $obs=trim($request->obs);
    $newsletter = (isset($request->newsletter)) ? 1 : 0;

    \DB::table('comerciantes')->where('id',$id_user)->update([ 'obs'=>$obs, 'estado'=>$estado, 'newsletter'=>$newsletter ]);
    return;
  }

  public function sellersPersonalForm(Request $request){
    $id_user=trim($request->id_user);
    $id_empresa=trim($request->id_empresa);
    $nome=trim($request->nome);
    $telefone=trim($request->telefone);
    $email=trim($request->email);
    $email_alteracao=trim($request->email_alteracao);
    $tipo = trim($request->tipo) ? trim($request->tipo) : 'gestor';
    $aprovacao_antiga = trim($request->aprovacao_antiga);
    $aprovacao = trim($request->aprovacao) && $tipo!='gestor' ? trim($request->aprovacao) : '';
    $nota=trim($request->nota);

    $ficheiro_antigo=trim($request->ficheiro_antigo);
    $ficheiro=$request->file('ficheiro');

    \DB::table('comerciantes')
        ->where('id',$id_user)
        ->update([ 'nome'=>$nome,
                   'telefone'=>$telefone,
                   'email'=>$email,
                   'email_alteracao'=>$email_alteracao,
                   'tipo'=>$tipo,
                   'nota'=>$nota,
                   'aprovacao'=>$aprovacao ]);
      
    if(empty($ficheiro_antigo) || count($ficheiro)){
      $linha = \DB::table('comerciantes')->where('id',$id_user)->first();
      if($linha->ficheiro){
        if(file_exists(base_path('public_html/doc/companies/'.$linha->ficheiro))){ \File::delete('../public_html/doc/companies/'.$linha->ficheiro); }
        \DB::table('comerciantes')->where('id',$linha->id)->update(['ficheiro'=>'']);
      }
    }

    $novoNome=$ficheiro_antigo;
    if(count($ficheiro)){
      $pasta = base_path('public_html/doc/companies/');
      $antigoNome='';
      
      $arquivo_tmp = $ficheiro->getPathName(); // caminho
      //$arquivo_name = $ficheiro->getClientOriginalName(); // nome do ficheiro
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      $novoNome = 'company_'.$id_empresa.'_user_'.$id_user.'_'.str_random(3).'.'.$extensao;

      if(@move_uploaded_file($arquivo_tmp, $pasta.$novoNome)){ \DB::table('comerciantes')->where('id',$id_user)->update([ 'ficheiro'=>$novoNome ]); }
    }

    if(($aprovacao_antiga!='aprovado' && $aprovacao=='aprovado') || ($aprovacao_antiga!='reprovado' && $aprovacao=='reprovado')){
      //app()->setLocale($lingua);
      if($aprovacao=='aprovado'){
        Mail::send('backoffice.emails.comerciante-aprovado', [], function($message) use ($nome,$email){
            $message->from(config('backoffice.noreply')['mail'], config('backoffice.noreply')['nome']);
            $message->subject(trans('backoffice.subjectApprovedAccount'));
            $message->to($email, $nome);
            $message->replyTo($email, $nome);
        });
      }else{
        Mail::send('backoffice.emails.comerciante-reprovado', [], function($message) use ($nome,$email){
            $message->from(config('backoffice.noreply')['mail'], config('backoffice.noreply')['nome']);
            $message->subject(trans('backoffice.subjectDisapprovedAccount'));
            $message->to($email, $nome);
            $message->replyTo($email, $nome);
        });
      }
    }    
    
    $resposta = [
        'estado' => 'sucesso',
        'mensagem' => '',
        'id' => $id_user,
        'ficheiro' => $novoNome ];
    return json_encode($resposta,true);
  }

  public function sellersCompanyForm(Request $request){
    $id_user=trim($request->id_user);
    $addresses=$request->addresses;

    \DB::table('comerc_morada')->where('id_comerciante',$id_user)->delete();
    if(is_array($addresses)){
      foreach ($addresses as $value){
        \DB::table('comerc_morada')->insert(['id_comerciante'=>$id_user, 'id_morada'=>$value ]);
      }
    }

    return 'sucesso';
  }

  public function deletePoints(Request $request){
    $id_user=trim($request->id_user);
    $id_points=trim($request->id_points);

    \DB::table('rotulos_utilizador')->where('id',$id_points)->where('id_utilizador',$id_user)->delete();
    $pontos = \DB::table('rotulos_utilizador')->where('id_utilizador',$id_user)->get()->sum('valor_final');
    \DB::table('comerciantes')->where('id',$id_user)->update(['pontos'=>$pontos]);
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

      \DB::table('comerciantes')->where('id', $id_user)->increment('pontos', $quantidade);
      return 'sucesso';
    }
  }

}