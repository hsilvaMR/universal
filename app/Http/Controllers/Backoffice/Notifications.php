<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Notifications extends Controller
{
  private $dados=[];

  /******************
  *  NOTIFICATIONS  *
  ******************/
  public function indexPage(){
    $this->dados['headTitulo']=trans('backoffice.notificationsTitulo');
    $this->dados['separador']="notifications";

    if(json_decode(Cookie::get('admin_cookie'))->tipo=='admin'){
      $query = \DB::table('admin_not')
              ->select('admin_not.*','admin.nome AS nomeAgente','admin.email AS emailAgente')
              ->leftJoin('admin','admin.id','=','admin_not.id_admin')
              ->orderBy('id', 'DESC')
              ->get();
    }else{
      $query =\DB::table('admin_not')
              ->select('admin_not.*','admin.nome AS nomeAgente','admin.email AS emailAgente')
              ->leftJoin('admin','admin.id','=','admin_not.id_admin')
              ->where('id_admin', json_decode(Cookie::get('admin_cookie'))->id)
              ->orderBy('id', 'DESC')
              ->get();
    }

    $arrayNew=$array=[];
    foreach($query as $value){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($value->tipo){
        case "website": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Website').'</span>'; break;
        case "premios": $tipo = '<span class="tag tag-turquesa">'.trans('backoffice.Awards').'</span>'; break;
        case "passatempo": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Giveaways').'</span>'; break;
        case "queijinho": $tipo = '<span class="tag tag-azul">'.trans('backoffice.littleCheese').'</span>'; break;
        case "contacto": $tipo = '<span class="tag tag-amarelo">'.trans('backoffice.Contacts').'</span>'; break;
        case "utilizadores": $tipo = '<span class="tag tag-roxo">'.trans('backoffice.Users').'</span>'; break;
        case "empresas": $tipo = '<span class="tag tag-laranja">'.trans('backoffice.Companies').'</span>'; break;
        case "encomendas": $tipo = '<span class="tag tag-cinza">'.trans('backoffice.Orders').'</span>'; break;
        case "produtos": $tipo = '<span class="tag tag-rosa">'.trans('backoffice.Products').'</span>'; break;
        case "informacao": $tipo = '<span class="tag tag-cinza">'.trans('backoffice.TechnicalInformation').'</span>'; break;
        default: $tipo = '<span class="tag tag-vermelho">'.$value->tipo.'</span>';
      }

      $url = ($value->url) ? ' <a href="'.$value->url.'" target="_blank"><span class="tag tag-amarelo">'.trans('backoffice.Go').'</span></a>' : '';

      if(!$value->visto && $value->id_admin==json_decode(Cookie::get('admin_cookie'))->id){
        $arrayNew[] = [
            'id' => $value->id,
            'tipo' => $tipo,
            'mensagem' => $value->mensagem.$url,
            'url' => $value->url,
            'data' => $value->data ? date('Y-m-d H:i',$value->data) : '',
            'visto' => $value->visto,
            'agente' => $value->nomeAgente.' ('.$value->emailAgente.')'
        ]; 
      }else{
        $array[] = [
            'id' => $value->id,
            'tipo' => $tipo,
            'mensagem' => $value->mensagem.$url,
            'url' => $value->url,
            'data' => $value->data ? date('Y-m-d H:i',$value->data) : '',
            'visto' => $value->visto,
            'agente' => $value->nomeAgente.' ('.$value->emailAgente.')'
        ];
      }
    }
    \DB::table('admin_not')->where('id_admin', json_decode(Cookie::get('admin_cookie'))->id)->update([ 'visto'=>1 ]);
    Cookie::queue(Cookie::make('notificacoes', '', 43200));

    $this->dados['array'] = $array;
    $this->dados['arrayNew'] = $arrayNew;
    return view('backoffice/pages/notifications-all', $this->dados);
  }

  public function newPage(){
    $this->dados['headTitulo']=trans('backoffice.notificationTitulo');
    $this->dados['separador']="notifications";
    $this->dados['funcao']="new";

    $this->dados['agentes']=\DB::table('admin')->orderBy('nome','ASC')->get();
    return view('backoffice/pages/notifications-new', $this->dados);
  }

  public function editPage($id){
    $this->dados['headTitulo']=trans('backoffice.notificationTitulo');
    $this->dados['separador']="notifications";
    $this->dados['funcao']="edit";

    $this->dados['agentes']=\DB::table('admin')->orderBy('nome','ASC')->get();

    if(json_decode(Cookie::get('admin_cookie'))->tipo=='admin'){
      $query=\DB::table('admin_not')
                        ->select('admin_not.*','admin.id AS idAgente')
                        ->leftJoin('admin','admin.id','=','admin_not.id_admin')
                        ->where('admin_not.id', $id)
                        ->first();
    }else{ return redirect()->back(); }

    $array = [
        'id' => $query->id,
        'tipo' => $query->tipo,
        'mensagem' => $query->mensagem,
        'url' => $query->url,
        'data' => $query->data ? date('Y-m-d H:i:s',$query->data) : '',
        'visto' => $query->visto,
        'id_agente' => $query->idAgente
    ];
    $this->dados['array'] = $array;

    return view('backoffice/pages/notifications-new', $this->dados);
  }

  public function form(Request $request)
  {
    $id=trim($request->id);
    $tipo=trim($request->tipo) ? trim($request->tipo) : 'contacto';
    $mensagem=trim($request->mensagem);
    $url=trim($request->url);
    $visto = (isset($request->visto)) ? '1' : '0';
    $id_agente = trim($request->id_agente) ? trim($request->id_agente) : json_decode(Cookie::get('admin_cookie'))->id;

   //$dominio = str_replace("https://", "", $dominio_email);
   
    //\DB::table('ag_dominios')->where('id_pai',$id_pai)->delete();
    //\DB::table('ag_dominios')->insert(['id_pai'=>$id, 'descricao'=>$valor_en, 'lang'=>'en']);

    if(!$mensagem){ return trans('backoffice.erroMsgEmpty'); }

    if($id && (json_decode(Cookie::get('admin_cookie'))->tipo=='admin')){
      \DB::table('admin_not')
          ->where('id',$id)
          ->update(['id_admin'=>$id_agente,
                    'tipo'=>$tipo,
                    'mensagem'=>$mensagem,
                    'url'=>$url,
                    'visto'=>$visto
          ]);
    }else{
      $id=\DB::table('admin_not')
          ->insertGetId(['id_admin'=>$id_agente,
                         'tipo'=>$tipo,
                         'mensagem'=>$mensagem,
                         'url'=>$url,
                         'visto'=>0,
                         'data'=>\Carbon\Carbon::now()->timestamp
          ]);
    }
    
    $resposta = [
        'estado' => 'sucesso',
        'id' => $id
    ];
    return json_encode($resposta,true);
  }
}