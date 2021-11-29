<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;
use Mail;
use Cookie;

class Admin extends Controller
{
  private $dados=[];

  /**********
  *  ADMIN  *
  **********/
  public function index(){
	  $this->dados['headTitulo']=trans('backoffice.managersTitulo');
	  $this->dados['separador']="userAdmin";
    $this->dados['funcao']="all";
	  $queryUsers = \DB::table('admin')
                   ->orderBy('id','DESC')
                   ->get();
    $users =[];
    foreach ($queryUsers as $valor) {

      $avatar = '<img src="'.asset('backoffice/img/admin/default.svg').'" class="table-img-circle">';
      if($valor->avatar && file_exists(base_path('public_html/backoffice/img/admin/'.$valor->avatar))){
        $avatar = '<img src="'.asset('backoffice/img/admin/'.$valor->avatar).'" class="table-img-circle">';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tipo){
        case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
        case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
        case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "ativo": $estado = '<span class="tag tag-azul">'.trans('backoffice.Active').'</span>'; break;
        case "pendente": $estado = '<span class="tag tag-amarelo">'.trans('backoffice.Pending').'</span>'; break;
        case "bloqueado": $estado = '<span class="tag tag-vermelho">'.trans('backoffice.Blocked').'</span>'; break;
        case "eliminado": $estado = '<span class="tag tag-cinza">'.trans('backoffice.Blocked').'</span>'; break;
        default: $estado = '<span class="tag tag-roxo">'.$valor->estado.'</span>';
      }

      $users[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'email' => $valor->email,
        'avatar' => $avatar,
        'ultimo' => $valor->ultimo_acesso ? date('Y-m-d H:i:s',$valor->ultimo_acesso) : '',
        'tipo' => $tipo,
        'estado' => $estado
      ];
    }
    $this->dados['users'] = $users;
	  return view('backoffice/pages/admin-all', $this->dados);
  }

  public function adminApagar(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('admin')
                ->where('id',$id)
                ->first();
    if(isset($linha->id) && $linha->id!=1)
    {
      if($linha->avatar && file_exists(base_path('public_html/backoffice/img/admin/'.$linha->avatar))){
        \File::delete('../public_html/backoffice/img/admin/'.$linha->avatar);
      }
      \DB::table('admin')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }
  
  /*********
  *  USER  *
  *********/
  public function adminNew(){
    $this->dados['headTitulo']=trans('backoffice.managerTitulo');
    $this->dados['separador']="userNew";
    $this->dados['funcao']="new";
    $this->dados['idioma']=json_decode(Cookie::get('admin_cookie'))->lingua;

    /*$array = ['doc_rever','doc_criar','cert_criar','gest_documental'];

    $admin_perm = [];
    foreach ($array as $value) {
      switch ($value) {
        case 'doc_rever':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value);
          $tipo = $value;
          break;
        
        case 'doc_criar':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value);
          $tipo = $value;
          break;

        case 'cert_criar':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value);
          $tipo = $value;
          break;

        case 'gest_documental':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value);
          $tipo = $value;
          break;

        default:
          $txt = $value;
          $tipo = $value;
          break;
      }

      $check_html = '<div class="clearfix height-10"></div>
                    <input type="checkbox" name="check_'.$tipo.'" id="check_'.$tipo.'" value="1">
                    <label for="check_'.$tipo.'"><span></span>'.$txt.'</label>';

      
      $admin_perm [] = [
        'check_html' => $check_html
      ];
    }

    $this->dados['admin_perm'] = $admin_perm;
    */
    return view('backoffice/pages/admin-new', $this->dados);
  }

  public function adminEdit($id){
    $this->dados['headTitulo']=trans('backoffice.managerTitulo');
    $this->dados['separador']="userEdit";
    $this->dados['funcao']="edit";
    $this->dados['idioma']=json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['user'] = \DB::table('admin')->where('id',$id)->first();
    
    
    /*$admin_perm = [];
    
    $admin_permissao=\DB::table('admin_permissao')
                          ->where('id_admin',$id)
                          ->get();

    foreach ($admin_permissao as $value) {
      switch ($value->tipo) {
        case 'doc_rever':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value->tipo);
          break;
        
        case 'doc_criar':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value->tipo);
          break;

        case 'cert_criar':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value->tipo);
          break;

        case 'gest_documental':
          $txt = trans('backoffice.PERMISSION_TXT_'.$value->tipo);
          break;

        default:
          $txt = $value->tipo;
          break;
      }

      $check = '';
      if ($value->estado == 1) {
        $check = 'checked';
      }

      $check_html = '<div class="clearfix height-10"></div>
                    <input type="checkbox" name="check_'.$value->id.'" id="check_'.$value->id.'" value="1" '.$check.'>
                    <label for="check_'.$value->id.'"><span></span>'.$txt.'</label>';

      
      $admin_perm [] = [
        'check_html' => $check_html
      ];
    }
    
    $this->dados['admin_perm'] = $admin_perm;*/
    return view('backoffice/pages/admin-new', $this->dados);
  }

  public function adminForm(Request $request)
  {
    $id=trim($request->id);
    $nome=trim($request->nome);
    $email=filter_var(strtolower(trim($request->email)), FILTER_VALIDATE_EMAIL);
    $tipo = trim($request->tipo) ? trim($request->tipo) : 'admin';
    $lingua = trim($request->lingua) ? trim($request->lingua) : 'pt';
    $estado = (isset($request->estado)) ? 'bloqueado' : 'ativo';

    $img_antiga=trim($request->img_antiga);
    $ficheiro=$request->file('ficheiro');

    if(empty($email)){
      $resposta = [
        'estado' => 'erro',
        'mensagem' => trans('backoffice.invalidEmail'),
        'id' => '',
        'imagem' => '' ];
      return json_encode($resposta,true);
    }

    $linhaEmail = \DB::table('admin')->where('email',$email)->first();
    if(isset($linhaEmail) && $linhaEmail->id!=$id){
      $resposta = [
        'estado' => 'erro',
        'mensagem' => trans('backoffice.emailAlready'),
        'id' => '',
        'imagem' => '' ];
      return json_encode($resposta,true);
    }

    if($id){
      \DB::table('admin')
        ->where('id',$id)
        ->update(['nome'=>$nome,
                  'email'=>$email,
                  'tipo'=>$tipo,
                  'lingua'=>$lingua,
                  'estado'=>$estado ]);
      
      if(empty($img_antiga) || count($ficheiro)){
        $linha = \DB::table('admin')->where('id',$id)->first();
        if($linha->avatar){
          if(file_exists(base_path('public_html/backoffice/img/admin/'.$linha->avatar))){ \File::delete('../public_html/backoffice/img/admin/'.$linha->avatar); }
          \DB::table('admin')->where('id',$linha->id)->update(['avatar'=>'']);
        }
      }
    }else{
      $id=\DB::table('admin')
              ->insertGetId(['token'=>str_random(5).strtotime(\Carbon\Carbon::now()),
                             'nome'=>$nome,
                             'email'=>$email,
                             'tipo'=>$tipo,
                             'estado'=>'pendente',
                             'data_registo'=>\Carbon\Carbon::now()->timestamp,
                             'lingua'=>$lingua ]);
      $token = str_random(12);
      \DB::table('admin_pas')->insert(['email'=>$email,
                                       'token' => $token,
                                       'data'=>strtotime(date('Y-m-d H:i:s'))
      ]);
      
      app()->setLocale($lingua);
      $dados = [ 'nome'=>$nome, 'token'=>$token ];
      Mail::send('backoffice.emails.new-admin', $dados, function($message) use ($request){
          $message->from(config('backoffice.noreply')['mail'], config('backoffice.noreply')['nome']);
          $message->subject(trans('backoffice.subjectWelcome'));
          $message->to($request->email, $request->nome);
          $message->replyTo($request->email, $request->nome);
      });
    }

    $novoNome=$img_antiga;
    if(count($ficheiro)){
      $pasta = base_path('public_html/backoffice/img/admin/');
      $antigoNome='';
      
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      
      $novoNome = 'admin'.$id.'-'.$cache.'.'.$extensao;
      $width = 300; $height = 300;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      \DB::table('admin')->where('id',$id)->update([ 'avatar'=>$novoNome ]);
    }

    $id_user=json_decode(Cookie::get('admin_cookie'))->id;
    if($id_user==$id){
      $userDados=['id'=> $id_user,
                  'nome'=> $nome,
                  'token'=> json_decode(Cookie::get('admin_cookie'))->token,
                  'avatar'=> $novoNome,
                  'tipo'=> $tipo,
                  'lingua'=> json_decode(Cookie::get('admin_cookie'))->lingua
      ];
      $userDados=json_encode($userDados);
      Cookie::queue(Cookie::make('admin_cookie', $userDados, 43200));
    }

    /*$admin_permissao = \DB::table('admin_permissao')->where('id_admin',$id)->get();
    
    if(count($admin_permissao) > 0){
      
      foreach ($admin_permissao as $value) {
        $valor = 'check_'.$value->id;
        $permissao = (isset($request->$valor)) ? '1' : '0';

        \DB::table('admin_permissao')
            ->where('id',$value->id)
            ->update([
              'estado' => $permissao,
              'data' => \Carbon\Carbon::now()->timestamp
            ]);
      }
    }else{
      
      $array = ['doc_rever','doc_criar','cert_criar','gest_documental'];

      foreach ($array as $value) {
        $valor = 'check_'.$value;
        $permissao = (isset($request->$valor)) ? '1' : '0';

        \DB::table('admin_permissao')
            ->insert([
              'id_admin' => $id,
              'tipo' => $value,
              'estado' => $permissao,
              'data' => \Carbon\Carbon::now()->timestamp
            ]);
      }
    }*/

    $admin_permissao = \DB::table('admin_permissao')->where('id_admin',$id)->get();

    if ($tipo == 'user') {
      if(count($admin_permissao) > 0){
        foreach ($admin_permissao as $value) {
          \DB::table('admin_permissao')
              ->where('id',$value->id)
              ->update([
                'estado' => 1,
                'data' => \Carbon\Carbon::now()->timestamp
              ]);
        }
      }
      else{
        $array = ['doc_rever','doc_criar','cert_criar','gest_documental'];
        foreach ($array as $value) {
          \DB::table('admin_permissao')
              ->insert([
                'id_admin' => $id,
                'tipo' => $value,
                'estado' => 1,
                'data' => \Carbon\Carbon::now()->timestamp
              ]);
        }
      }
    }
    elseif($tipo == 'visualizador'){
      if(count($admin_permissao) > 0){
        foreach ($admin_permissao as $value) {
          \DB::table('admin_permissao')
              ->where('id',$value->id)
              ->update([
                'estado' => 0,
                'data' => \Carbon\Carbon::now()->timestamp
              ]);
        }
      }
    }
      
    $permissions_user = \DB::table('admin_permissao')->where('id_admin',$id)->get();

    $permissionsDados = [];
    foreach ($permissions_user as $value) {
      if ($value->estado == 1) {
        $permissionsDados[] = [
          'tipo'=> $value->tipo
        ];
      }
    }

    $permissionsDados=json_encode($permissionsDados);
    Cookie::queue(Cookie::make('permissions_cookie', $permissionsDados, 43200));

    $resposta = [
      'estado' => 'sucesso',
      'mensagem' => '',
      'id' => $id,
      'imagem' => $novoNome ];
    return json_encode($resposta,true);
  }

}