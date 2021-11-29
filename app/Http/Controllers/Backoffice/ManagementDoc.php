<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;

class ManagementDoc extends Controller
{
  private $dados=[];
  
  /*************************/
  /*  DOCUMENT MANAGEMENT  */
  /*************************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.docManagementTitulo');
    $this->dados['separador']="setGestDocuments";
    $this->dados['funcao']="all";

    $docs = \DB::table('gest_documentos')->get();

    $doc = [];
    foreach ($docs as $value) {
      $versao = \DB::table('gest_doc_versoes')
                      ->where('id_documento',$value->id)
                      ->orderBy('data','DESC')
                      ->first();
    
      $doc[]=[
        'id' => $value->id,
        'referencia' => $value->referencia,
        'nome' => $value->nome,
        'ficheiro' => $value->ficheiro,
        'versao' => $value->versao,
        'data' => $value->data,
        'estado' => $versao->estado
      ];
    }

    $array_doc_criar=[];
    foreach(json_decode(\Cookie::get('permissions_cookie')) as $value){  
      array_push($array_doc_criar, $value->tipo);  
    }

    if(in_array('doc_criar', $array_doc_criar)) {
      $this->dados['tipo_doc_criar'] = 'sim';
    }

    if(in_array('doc_rever', $array_doc_criar)) {
      $this->dados['tipo_doc_rever'] = 'sim';
    }
            

    $this->dados['documentos'] = $doc;
    return view('backoffice/pages/management-doc', $this->dados);
  }

  //DELETE DOCUMENT
  public function deleteLineDoc(Request $request){
    $id = trim($request->id);
  
    $linha = \DB::table('gest_documentos')->where('id',$id)->first();
    $linha_aux = \DB::table('gest_doc_aux')->where('id_documento',$id)->get();
    $linha_versoes = \DB::table('gest_doc_versoes')->where('id_documento',$id)->get();
    
    foreach ($linha_aux as $value) {
      if($value->ficheiro && file_exists(base_path('public_html/backoffice/gestao_documental/doc_aux/'.$value->ficheiro))){ \File::delete('../public_html/backoffice/gestao_documental/doc_aux/'.$value->ficheiro);}
    }

    foreach ($linha_versoes as $value) {
      $linha_versoes_aux = \DB::table('gest_doc_versoes_aux')->where('id_versao',$value->id)->get();

      foreach ($linha_versoes_aux as $val) {
        if($val->ficheiro && file_exists(base_path('public_html/backoffice/gestao_documental/doc_aux/'.$val->ficheiro))){ 
          \File::delete('../public_html/backoffice/gestao_documental/doc_aux/'.$val->ficheiro);
        }
      }

      if($value->ficheiro && file_exists(base_path('public_html'.$value->ficheiro))){ \File::delete('../public_html'.$value->ficheiro);}
    }

    if($linha->ficheiro && file_exists(base_path('public_html'.$linha->ficheiro))){ \File::delete('../public_html'.$linha->ficheiro);}

    \DB::table('gest_documentos')->where('id',$id)->delete();
    return 'sucesso';
  }

  //CREAT DOCUMENT
  public function creatDocument(){
    $this->dados['headTitulo']=trans('backoffice.docManagementTitulo');
    $this->dados['separador']="setGestDocuments";
    $this->dados['funcao']="new";

    $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    $this->dados['admin'] = \DB::table('admin')->where('id','<>',$id_admin)->get();
   
    return view('backoffice/pages/management-creat-doc', $this->dados);
  }

  public function editDocument($id){
    $this->dados['headTitulo']=trans('backoffice.docManagementTitulo');
    $this->dados['separador']="setGestDocuments";
    $this->dados['funcao']="edit";
    $this->dados['id']=$id;

    $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    $this->dados['admin'] = \DB::table('admin')->where('id','<>',$id_admin)->get();

    $this->dados['obj'] = \DB::table('gest_documentos')->where('id',$id)->first();

    $this->dados['doc_aux'] = \DB::table('gest_doc_aux')
                                  ->where('id_documento',$id)
                                  ->get();

    $doc_versao = \DB::table('gest_doc_versoes')
                      ->where('id_documento',$id)
                      ->orderBy('data','DESC')
                      ->first();

    $this->dados['version_aux'] = \DB::table('gest_doc_versoes_aux')->where('id_versao',$doc_versao->id)->get();

    $nome = str_replace('/backoffice/gestao_documental/doc/', '', $doc_versao->ficheiro);

    $ficheiro = [
      'nome' => $nome,
      'file' => $doc_versao->ficheiro,
      'tipo' => $doc_versao->tipo,
      'estado' => $doc_versao->estado,
      'id_versao' => $doc_versao->id,
      'revisao' => $doc_versao->id_quem_revio,
      'email_revisor' => $doc_versao->email_quem_revio,
      'token' => $doc_versao->token,
      'nota' => $doc_versao->nota
    ];


    $this->dados['ficheiro'] = $ficheiro;
    return view('backoffice/pages/management-creat-doc', $this->dados);
  }

  public function creatDocForm(Request $request){
    $referencia = trim($request->referencia);
    $nome = trim($request->nome);
    $doc = $request->file('doc');
    $diagram_img = trim($request->diagram_img);
    $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    $id_doc = trim($request->id_doc);
    $documento_antigo = trim($request->documento_antigo);
    $id_versoes = trim($request->id_versoes);
    $doc_aux = $request->file('doc_aux');
    $id_revisao = trim($request->id_revisor);
    $tipo_page = trim($request->tipo_page);
    $versao = 1;
    $estado = 'aprovado';
    $img = 'nao';
    $email_revisao = trim($request->email_revisao);
    $ficheiros_aux_diagrama = $request->ficheiros_aux_diagrama;
    $token = str_random(6);

    /*if (empty($referencia)) { return trans('backoffice.FieldRef_txt');}
    if (empty($nome)) { return trans('backoffice.Field_Name_txt');}
    if ((empty($doc) && empty($doc_antiga))&& empty($diagram_img)) { return trans('backoffice.FieldDoc_txt');}
    if ((!empty($doc) && !empty($diagram_img) ) || ((!empty($doc_antiga) && !empty($diagram_img))) || (!empty($doc) && !empty($diagram_old))) {return trans('backoffice.AddFileDiagram_txt');}*/

    $admin = \DB::table('admin')->where('id',$id_admin)->first();

    if ($id_doc) {
      \DB::table('gest_documentos')
          ->where('id',$id_doc)
          ->update([
            'referencia' => $referencia,
            'nome' => $nome,
            'data' => \Carbon\Carbon::now()->timestamp
          ]);

    }else{
      $id_doc = \DB::table('gest_documentos')
                  ->insertGetId([
                    'referencia' => $referencia,
                    'nome' => $nome,
                    'versao' => $versao,
                    'data' => \Carbon\Carbon::now()->timestamp
                  ]);

      $id_versoes = \DB::table('gest_doc_versoes')
                      ->insertGetId([
                        'id_documento' => $id_doc,
                        'quem_fez' => $admin->nome,
                        'id_quem_fez' => $id_admin,
                        'email_quem_revio' => $email_revisao,
                        'token' => $token,
                        'estado' => $estado
                      ]);
    }

    //Ficheiros Auxiliares Diagramas
    if (count($ficheiros_aux_diagrama[0])) {
      foreach ($ficheiros_aux_diagrama as $value) {
        \DB::table('gest_doc_aux')->insert([
          'id_documento' => $id_doc,
          'ficheiro' => $value,
          'tipo' => 'diagrama'
        ]);
      }
    }

    //Ficheiros Auxiliares Documentos
    if (count($doc_aux[0])) {
      foreach ($doc_aux as $value) {
        $cache = str_random(3);
        $destinationPath = base_path('public_html/backoffice/gestao_documental/doc_aux/');
        $extension = strtolower($value->getClientOriginalExtension());
        $getName = $value->getPathName();
      
        $novo_doc = 'doc_'.$id_doc.'_aux_'.$id_versoes.'_'.$cache.'.'.$extension;
        $url = '/backoffice/gestao_documental/doc_aux/'.$novo_doc;

        move_uploaded_file($getName, $destinationPath.$novo_doc);

        \DB::table('gest_doc_aux')->insert([
          'id_documento' => $id_doc,
          'ficheiro' => $novo_doc
        ]);
      }
    }
   
    if (!empty($diagram_img)) {
      $cache = str_random(3);
      $image = $diagram_img;
      $image = str_replace('data:image/png;base64,', '', $image);
      $image = str_replace(' ', '+', $image);
      $imageName = 'doc_'.$id_doc.'_versao_'.$id_versoes.'_'.$cache.'.png';
      \File::put('../public_html/backoffice/gestao_documental/doc'. '/' . $imageName, base64_decode($image));

      $url_img = '/backoffice/gestao_documental/doc/'.$imageName;
      $tipo = 'diagrama';

      if ($tipo_page == 'new'){
        \DB::table('gest_doc_versoes')
            ->where('id',$id_versoes)
            ->update([ 
              'ficheiro' => $url_img,
              'tipo' => $tipo 
            ]);
      }
      $novo_doc = $imageName;
    }else{
      $url_img = $documento_antigo;
      $tipo = 'diagrama';
    }

    if(count($doc)){
      $cache = str_random(3);
      $destinationPath = base_path('public_html/backoffice/gestao_documental/doc/');
      $extension = strtolower($doc->getClientOriginalExtension());
      $getName = $doc->getPathName();

      $novo_doc = 'doc_'.$id_doc.'_versao_'.$id_versoes.'_'.$cache.'.'.$extension;

      $url_doc = '/backoffice/gestao_documental/doc/'.$novo_doc;

      move_uploaded_file($getName, $destinationPath.$novo_doc);
      
      $tipo = 'doc';
      if ($tipo_page == 'new'){
        \DB::table('gest_doc_versoes')->where('id',$id_versoes)->update([ 'ficheiro' => $url_doc ]);
      }
    }
    else{ 
      $url_doc = $documento_antigo; 
      $tipo = 'doc';
    }

    if ($tipo_page == 'edit') {

      $cont_versoes = \DB::table('gest_doc_versoes')->where('id_documento',$id_doc)->count();
      $v_total = $cont_versoes + 1;
      
      if (($documento_antigo != $url_doc) || ($documento_antigo != $url_img)) {
        
        if ($documento_antigo != $url_img) {
          $url = $url_img;
          $tipo = 'diagrama';
        }elseif (($documento_antigo != $url_doc)) {
          $url = $url_doc;
          $tipo = 'doc';
        }

        $id_versoes = \DB::table('gest_doc_versoes')
                          ->insertGetId([
                            'id_documento' => $id_doc,
                            'quem_fez' => $admin->nome,
                            'id_quem_fez' => $id_admin,
                            'email_quem_revio' => $email_revisao,
                            'ficheiro' => $url,
                            'tipo' => $tipo,
                            'data' => \Carbon\Carbon::now()->timestamp,
                            'estado' => $estado
                          ]);

        \DB::table('gest_documentos')
            ->where('id',$id_doc)
            ->update([
              'versao' => $v_total,
              'data' => \Carbon\Carbon::now()->timestamp
            ]);
      }
    }

    if ($id_revisao || $email_revisao) {
 
      if ($id_revisao) {
        $admin = \DB::table('admin')->where('id',$id_revisao)->first();
        
        \DB::table('gest_doc_versoes')
            ->where('id',$id_versoes)
            ->update([
              'id_quem_revio' => $id_revisao,
              'quem_revio' => $admin->nome
            ]);
      }
      else{
        \DB::table('gest_doc_versoes')
            ->where('id',$id_versoes)
            ->update([
              'id_quem_revio' => NULL,
              'quem_revio' => ''
            ]);
      }
      

      \DB::table('gest_doc_versoes')
          ->where('id',$id_versoes)
          ->update([
            'email_quem_revio' => $email_revisao,
            'data_estado' => \Carbon\Carbon::now()->timestamp,
            'data' => \Carbon\Carbon::now()->timestamp,
            'estado' => 'em_aprovacao'
          ]);

   
      if (empty($documento_antigo)) {
    
        \DB::table('gest_documentos')
            ->where('id',$id_doc)
            ->update([
              'ficheiro' => '',
              'versao' => '',
              'data' => \Carbon\Carbon::now()->timestamp
            ]);
      }
    
      $estado = 'em_aprovacao';

      //send email to the review person
      $dados = ['id_versoes' => $id_versoes];
      if($url_doc){ $file='http://www.universal.com.pt'.$url_doc; }
      else{ $file='http://www.universal.com.pt'.$url_img; }
      
      if ($email_revisao) {
        $email = $email_revisao;      
      }else{
        $email = $admin->email;
      }

      \Mail::send('backoffice.emails.pages.aproved-doc',['dados' => $dados], function($message) use ($email,$file){
        $message->to($email,'')->subject(trans('Aprovação do Documento'));
        $message->attach($file);
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
    }
    else{
      if ($documento_antigo != $url_img) {
        $url = $url_img;
        $tipo = 'diagrama';
      }elseif (($documento_antigo != $url_doc)) {
        $url = $url_doc;
        $tipo = 'doc';
      }else{
        $url = $documento_antigo;
      }

      \DB::table('gest_doc_versoes')
          ->where('id',$id_versoes)
          ->update([
            'id_quem_revio' => NULL,
            'quem_revio' => '',
            'email_quem_revio' => '',
            'data_estado' => \Carbon\Carbon::now()->timestamp
          ]);

      \DB::table('gest_documentos')
          ->where('id',$id_doc)
          ->update([ 
            'ficheiro' => $url,
            'tipo' => $tipo
          ]);
    }
     
    /*if (($tipo_page == 'edit') && ($estado_revisao)) {
      if($estado_revisao == 'aprovado'){
         
          \DB::table('gest_documentos')
              ->where('id',$id_doc)
              ->update([
                'ficheiro' => $documento_antigo,
                'data' => \Carbon\Carbon::now()->timestamp
              ]);
        }

      $admin = \DB::table('admin')->where('id',$id_admin)->first();
      \DB::table('gest_doc_versoes')
          ->where('id',$id_versoes)
          ->update([
            'id_quem_revio' => $id_admin,
            'quem_revio' => $admin->nome,
            'estado' => $estado_revisao
          ]);
    }*/

    $resposta = [
      'estado' => 'sucesso',
      'reload' => 'sim',
      'id_doc' => $id_doc,
      'id_versoes' => $id_versoes
    ];
    return json_encode($resposta,true);
  }

  public function deleteDocAux(Request $request){
    $id = trim($request->id);
    $tabela = trim($request->tabela);

    $linha_aux = \DB::table($tabela)->where('id',$id)->first();
    
    if($linha_aux->ficheiro && file_exists(base_path('public_html/backoffice/gestao_documental/doc_aux/'.$linha_aux->ficheiro))){ 
      \File::delete('../public_html/backoffice/gestao_documental/doc_aux/'.$linha_aux->ficheiro);
    }

    \DB::table($tabela)->where('id',$id)->delete();
    return 'sucesso';
  }

  //GESTAO DOCUMENTAL - VERSOES
  public function versionsAll($id){
    $this->dados['headTitulo']=trans('backoffice.docManagementTitulo');
    $this->dados['separador']="setGestDocuments";
    $this->dados['funcao']="all";
    $this->dados['id']=$id;

    $versoes = \DB::table('gest_doc_versoes')->where('id_documento',$id)->get();

    $v_docs = [];
    foreach ($versoes as $value) {

      switch ($value->estado){
        case "aprovado": $estado = '<span class="tag tag-verde nowrap">'.trans('backoffice.Approved').'</span>'; break;
        case "reprovado": $estado = '<span class="tag tag-vermelho nowrap">'.trans('backoffice.Disapproved').'</span>'; break;
        case "em_aprovacao": $estado = '<span class="tag tag-amarelo nowrap">'.trans('backoffice.OnApproval').'</span>'; break;
        default: $estado = '<span class="tag tag-cinza nowrap">'.$value->estado.'</span>';
      }

      $file = str_replace('/backoffice/gestao_documental/doc/', '', $value->ficheiro);

      $cont = 0;
      if ($value->estado != 'aprovado') {
        $cont = $cont + 1;
      }

      $gest_doc_versoes_aux = \DB::table('gest_doc_versoes_aux')->where('id_versao',$value->id)->get();

      $doc_aux = [];
      foreach ($gest_doc_versoes_aux as $val) {
        $doc_aux[] = [
          'id_versao' => $val->id_versao,
          'ficheiro' => $val->ficheiro
        ];
      }

      $v_docs[] = [
        'id' => $value->id,
        'quem_fez' => $value->quem_fez,
        'quem_revio' => $value->quem_revio,
        'file_href' => $value->ficheiro,
        'ficheiro' => $file,
        'nota' => $value->nota,
        'data' => date('Y-m-d',$value->data),
        'estado' => $value->estado,
        'estado_html' => $estado,
        'doc_aux' => $doc_aux
      ];

      
    }
    
    $this->dados['v_docs'] = $v_docs;
    $this->dados['cont'] = $cont;
  
    return view('backoffice/pages/management-versions-all', $this->dados);
  }

  public function versionsAprovation(Request $request){
    $id = trim($request->id);
    $id_admin = json_decode(Cookie::get('admin_cookie'))->id;

    $admin = \DB::table('admin')->where('id',$id_admin)->first();
    $versao = \DB::table('gest_doc_versoes')->where('id',$id)->first();

    $cont_versoes = \DB::table('gest_doc_versoes')->where('id_documento',$versao->id_documento)->count();
    $v_total = $cont_versoes + 1;

    \DB::table('gest_documentos')
        ->where('id',$versao->id_documento)
        ->update([
          'ficheiro' => $versao->ficheiro,
          'versao' => $v_total,
          'tipo' => $versao->tipo,
          'data' => \Carbon\Carbon::now()->timestamp
        ]);

    \DB::table('gest_doc_versoes')
        ->where('id',$id)
        ->update([
          'estado' => 'aprovado',
          'id_quem_revio' => $id_admin,
          'quem_revio' => $admin->nome
        ]);

    $resposta = [
      'estado' => 'sucesso',
      'id' => $id,
      'aprovado' => '<span class="tag tag-verde nowrap">'.trans('backoffice.Approved').'</span>',
      'quem_revio' => $admin->nome
    ];
    return json_encode($resposta,true);
  }

  public function versionsReprobation(Request $request){
    $id = trim($request->id);
    $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    
    $admin = \DB::table('admin')->where('id',$id_admin)->first();

    \DB::table('gest_doc_versoes')
        ->where('id',$id)
        ->update([
          'estado' => 'reprovado',
          'id_quem_revio' => $id_admin,
          'quem_revio' => $admin->nome
        ]);

    $resposta = [
      'estado' => 'sucesso',
      'id' => $id,
      'aprovado' => '<span class="tag tag-vermelho nowrap">'.trans('backoffice.Disapproved').'</span>',
      'quem_revio' => $admin->nome
    ];
    return json_encode($resposta,true);
  } 

  public function versionStatus(Request $request){
    $tipo_status = trim($request->tipo_status);
    $doc_aux_versao = $request->file('doc_aux_versao');
    $nota_versao = trim($request->nota_versao);
    $id_versao = trim($request->id_versao);
    $id_documento = trim($request->id_documento);
    $id_admin = json_decode(Cookie::get('admin_cookie'))->id;
    
    $admin = \DB::table('admin')->where('id',$id_admin)->first();
    \DB::table('gest_doc_versoes')
        ->where('id',$id_versao)
        ->update([
          'id_quem_revio' => $id_admin,
          'quem_revio' => $admin->nome,
          'email_quem_revio' => '', 
          'nota' => $nota_versao,
          'estado' => $tipo_status,
          'data_estado' => \Carbon\Carbon::now()->timestamp
        ]);

    if ($tipo_status == 'aprovado') {
      $cont_versoes = \DB::table('gest_doc_versoes')->where('id_documento',$id_documento)->count();
      $v_total = $cont_versoes + 1;

      $doc_versao = \DB::table('gest_doc_versoes')->where('id',$id_versao)->first();
      \DB::table('gest_documentos')
          ->where('id',$id_documento)
          ->update([
            'ficheiro' => $doc_versao->ficheiro,
            'versao' => $v_total,
            'tipo' => $doc_versao->tipo,
            'data' => \Carbon\Carbon::now()->timestamp
          ]);
    }
    
    if (count($doc_aux_versao[0])) {
      foreach ($doc_aux_versao as $value) {
        $cache = str_random(3);
        $destinationPath = base_path('public_html/backoffice/gestao_documental/doc_aux/');
        $extension = strtolower($value->getClientOriginalExtension());
        $getName = $value->getPathName();
      
        $novo_doc = 'doc_'.$id_documento.'_aux_'.$id_versao.'_'.$cache.'.'.$extension;
        $url = '/backoffice/gestao_documental/doc_aux/'.$novo_doc;

        move_uploaded_file($getName, $destinationPath.$novo_doc);
        
        \DB::table('gest_doc_versoes_aux')
            ->insert([
              'id_versao' => $id_versao,
              'ficheiro' => $novo_doc
            ]);
      }
    }
    

    $resposta = [
      'estado' => 'sucesso'
    ];
    return json_encode($resposta,true);
  }

  function versionDelete(Request $request){
    $id = trim($request->id);

    $versao = \DB::table('gest_doc_versoes')->where('id',$id)->first();
    if($versao->ficheiro && file_exists(base_path('public_html'.$versao->ficheiro))){ 
      \File::delete('../public_html'.$versao->ficheiro);
    }

    $doc_aux = \DB::table('gest_doc_versoes_aux')->where('id_versao',$id)->get();
    foreach ($doc_aux as $value) {
      if($value->ficheiro && file_exists(base_path('public_html/backoffice/gestao_documental/doc_aux/'.$value->ficheiro))){ 
        \File::delete('../public_html/backoffice/gestao_documental/doc_aux/'.$value->ficheiro);
      }
    }

    \DB::table('gest_doc_versoes')->where('id',$id)->delete();
    return 'sucesso';
  }
}