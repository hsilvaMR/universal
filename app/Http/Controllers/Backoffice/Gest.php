<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Hash;
use Mail;
use Cookie;

class Gest extends Controller
{
	private $dados=[];
	
  /********************/
  /*  Certifications  */
  /********************/
	public function indexCertifications(){
    $this->dados['headTitulo']=trans('backoffice.certificationsTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;

    $query = \DB::table('gest_certificacoes')->get();
    $dados =[];
    foreach ($query as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      /*switch ($valor->tipo){
        case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
        case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
        case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }*/
      $dados[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'data' => $valor->data ? date('Y-m-d',$valor->data) : '',
        'online' => $valor->online
      ];
    }
    $this->dados['dados'] = $dados;

  
    $array_cert_criar=[];
    foreach(json_decode(\Cookie::get('permissions_cookie')) as $value){
      array_push($array_cert_criar, $value->tipo);  
    }

    if(in_array('cert_criar', $array_cert_criar)) {
      $this->dados['tipo_cert_criar'] = 'sim';
    }
           

    return view('backoffice/pages/gest-certifications-all', $this->dados);
	}

  public function newCertification(){
    $this->dados['headTitulo']=trans('backoffice.certificationTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="new";

    $this->dados['documentos'] = \DB::table('gest_documentos')->get();
    return view('backoffice/pages/gest-certifications-new', $this->dados);
  }

  public function editCertification($id){
    $this->dados['headTitulo']=trans('backoffice.certificationTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="edit";

    $this->dados['documentos'] = \DB::table('gest_documentos')->get();
    $this->dados['dados'] = \DB::table('gest_certificacoes')->where('id',$id)->first();    
    return view('backoffice/pages/gest-certifications-new', $this->dados);
  }

  public function formCertification(Request $request){
    $id=trim($request->id);
    $nome=trim($request->nome);
    $online = (isset($request->online)) ? 1 : 0;

    if($id){
      \DB::table('gest_certificacoes')
        ->where('id',$id)
        ->update(['nome'=>$nome,
                  'online'=>$online ]);
    }else{
      $id=\DB::table('gest_certificacoes')
              ->insertGetId([
                'nome'=>$nome,
                'online'=>$online,
                'data'=>\Carbon\Carbon::now()->timestamp
            ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }

  /***************/
  /*  Processes  */
  /***************/
  public function indexProcesses($id_cert){
    $this->dados['headTitulo']=trans('backoffice.processesTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="all";
    $this->dados['id_cert']=$id_cert;

    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$id_cert)->first();
    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;

    $query = \DB::table('gest_processos')
            ->select('gest_processos.*','gest_documentos.referencia AS diagrama','gest_documentos.ficheiro AS ficheiro')
            ->leftJoin('gest_documentos','gest_documentos.id','=','gest_processos.id_documento')
            ->where('gest_processos.id_certificacao', $id_cert)
            ->get();
    $dados =[];
    foreach ($query as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      /*switch ($valor->tipo){
        case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
        case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
        case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }*/
      $dados[] = [
        'id' => $valor->id,
        'referencia' => $valor->referencia,
        'nome' => $valor->nome,
        'diagrama' => '<a href="'.$valor->ficheiro.'" download>'.$valor->diagrama.'</a>',
        'data' => $valor->data ? date('Y-m-d',$valor->data) : '',
        'online' => $valor->online
      ];
    }
    $this->dados['dados'] = $dados;
    return view('backoffice/pages/gest-processes-all', $this->dados);
  }

  public function newProcess($id_cert){
    $this->dados['headTitulo']=trans('backoffice.processTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="new";
    $this->dados['id_cert']=$id_cert;
    $this->dados['documentos']=\DB::table('gest_documentos')->get();
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$id_cert)->first(); 
    return view('backoffice/pages/gest-processes-new', $this->dados);
  }

  public function editProcess($id_cert,$id){
    $this->dados['headTitulo']=trans('backoffice.processTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="edit";
    $this->dados['id_cert']=$id_cert;
    $this->dados['documentos']=\DB::table('gest_documentos')->where('tipo','diagrama')->get();
    $this->dados['dados'] = \DB::table('gest_processos')->where('id',$id)->first(); 
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$id_cert)->first();   
    return view('backoffice/pages/gest-processes-new', $this->dados);
  }

  public function formProcess(Request $request){
    $id=trim($request->id);
    $id_certificacao=trim($request->id_certificacao);
    $id_documento = $request->id_documento ? trim($request->id_documento) : NULL;
    $referencia=trim($request->referencia);
    $nome=trim($request->nome);
    $online = (isset($request->online)) ? 1 : 0;

    if($id){
      \DB::table('gest_processos')
        ->where('id',$id)
        ->where('id_certificacao',$id_certificacao)
        ->update(['id_documento'=>$id_documento,
                  'referencia'=>$referencia,
                  'nome'=>$nome,
                  'online'=>$online ]);
    }else{
      $id=\DB::table('gest_processos')
              ->insertGetId([
                'id_certificacao'=>$id_certificacao,
                'id_documento'=>$id_documento,
                'referencia'=>$referencia,
                'nome'=>$nome,
                'online'=>$online,
                'data'=>\Carbon\Carbon::now()->timestamp
            ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }

  /****************/
  /*  Activities  */
  /****************/
  public function indexActivities($id_proc){
    $this->dados['headTitulo']=trans('backoffice.activitiesTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="all";
    $this->dados['id_proc']=$id_proc;

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;

    $this->dados['process'] = \DB::table('gest_processos')->where('id', $id_proc)->first();
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$this->dados['process']->id_certificacao)->first();   
    $query = \DB::table('gest_atividades')->where('id_processo', $id_proc)->get();
    $dados =[];
    foreach ($query as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      /*switch ($valor->tipo){
        case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
        case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
        case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }*/
      $dados[] = [
        'id' => $valor->id,
        'referencia' => $valor->referencia,
        'nome' => $valor->nome,
        'data' => $valor->data ? date('Y-m-d',$valor->data) : '',
        'online' => $valor->online
      ];
    }
    $this->dados['dados'] = $dados;
    return view('backoffice/pages/gest-activities-all', $this->dados);
  }

  public function newActivity($id_proc){
    $this->dados['headTitulo']=trans('backoffice.activityTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="new";
    $this->dados['id_proc']=$id_proc;
    $this->dados['process'] = \DB::table('gest_processos')->where('id',$id_proc)->first();
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$this->dados['process']->id_certificacao)->first(); 
    return view('backoffice/pages/gest-activities-new', $this->dados);
  }

  public function editActivity($id_proc,$id){
    $this->dados['headTitulo']=trans('backoffice.activityTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="edit";
    $this->dados['id_proc']=$id_proc;
    $this->dados['dados'] = \DB::table('gest_atividades')->where('id',$id)->first(); 
    $this->dados['process'] = \DB::table('gest_processos')->where('id',$id_proc)->first();
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$this->dados['process']->id_certificacao)->first();   
    return view('backoffice/pages/gest-activities-new', $this->dados);
  }

  public function formActivity(Request $request){
    $id=trim($request->id);
    $id_processo=trim($request->id_processo);
    $referencia=trim($request->referencia);
    $nome=trim($request->nome);
    $online = (isset($request->online)) ? 1 : 0;

    if($id){
      \DB::table('gest_atividades')
        ->where('id',$id)
        ->where('id_processo',$id_processo)
        ->update(['referencia'=>$referencia,
                  'nome'=>$nome,
                  'online'=>$online ]);
    }else{
      $id=\DB::table('gest_atividades')
              ->insertGetId([
                'id_processo'=>$id_processo,
                'referencia'=>$referencia,
                'nome'=>$nome,
                'online'=>$online,
                'data'=>\Carbon\Carbon::now()->timestamp
            ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }

  /***********/
  /*  Tasks  */
  /***********/
  public function indexTasks($id_acti){
    $this->dados['headTitulo']=trans('backoffice.tasksTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="all";
    $this->dados['id_acti']=$id_acti;

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;

    $this->dados['acti'] = \DB::table('gest_atividades')->where('id', $id_acti)->first();
    $this->dados['process'] = \DB::table('gest_processos')->where('id', $this->dados['acti']->id_processo)->first();

    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$this->dados['process']->id_certificacao)->first(); 

    $query = \DB::table('gest_tarefas')->where('id_atividade', $id_acti)->orderBy('ordem','ASC')->get();
    $dados =[];
    foreach ($query as $valor){

      $resp=$env=$entrada=$saida='';
      $queryIdent = \DB::table('gest_tar_ident')
                      ->select('gest_tar_ident.*','gest_identificacoes.sigla','gest_identificacoes.descricao')
                      ->leftJoin('gest_identificacoes','gest_identificacoes.id','=','gest_tar_ident.id_identificacao')
                      ->where('gest_tar_ident.id_tarefa', $valor->id)
                      ->get();
      foreach ($queryIdent as $val){
        if($val->tipo=='resp'){
          if($resp){$resp.='<br>';}
          if($val->sigla){ $resp.=$val->sigla; }else{  $resp.=$val->nome; }
        }else{
          if($env){$env.='<br>';}
          if($val->sigla){ $env.=$val->sigla; }else{  $env.=$val->nome; }
        }
      }
      $queryDoc = \DB::table('gest_tar_doc')
                      ->select('gest_tar_doc.*','gest_documentos.referencia','gest_documentos.ficheiro')
                      ->leftJoin('gest_documentos','gest_documentos.id','=','gest_tar_doc.id_documento')
                      ->where('gest_tar_doc.id_tarefa', $valor->id)
                      ->get();
      foreach ($queryDoc as $val){
        if($val->tipo=='entrada'){
          if($entrada){$entrada.='<br>';}
          if($val->referencia){
            $entrada.=$val->nome.' <a href="'.$val->ficheiro.'" target="_blank" download>'.$val->referencia.'</a>';
          }elseif($val->url){
            $entrada.='<a href="'.$val->url.'" target="_blank">'.$val->nome.'</a>';
          }else{
            $entrada.=$val->nome;
          }
        }else{
          if($saida){$saida.='<br>';}
          if($val->referencia){
            $saida.=$val->nome.' <a href="'.$val->ficheiro.'" target="_blank" download>'.$val->referencia.'</a>';
          }elseif($val->url){
            $saida.='<a href="'.$val->url.'" target="_blank">'.$val->nome.'</a>';
          }else{
            $saida.=$val->nome;
          }
        }
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      /*switch ($valor->tipo){
        case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
        case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
        case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }*/
      $dados[] = [
        'id' => $valor->id,
        'tarefa' => $valor->tarefa,
        'resp' => $resp,
        'env' => $env,
        'entrada' => $entrada,
        'saida' => $saida,
        'data' => $valor->data ? date('Y-m-d',$valor->data) : '',
        'online' => $valor->online
      ];
    }
    $this->dados['dados'] = $dados;
    return view('backoffice/pages/gest-tasks-all', $this->dados);
  }

  public function newTask($id_acti){
    $this->dados['headTitulo']=trans('backoffice.taskTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="new";
    $this->dados['id_acti']=$id_acti;
    $this->dados['identificacoes']=\DB::table('gest_identificacoes')->get();
    $this->dados['documentos']=\DB::table('gest_documentos')->get();
    $this->dados['acti'] = \DB::table('gest_atividades')->where('id',$id_acti)->first(); 
    $this->dados['process'] = \DB::table('gest_processos')->where('id', $this->dados['acti']->id_processo)->first();
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$this->dados['process']->id_certificacao)->first(); 
    return view('backoffice/pages/gest-tasks-new', $this->dados);
  }

  public function editTask($id_acti,$id){
    $this->dados['headTitulo']=trans('backoffice.taskTitulo');
    $this->dados['separador']="setGestCertifications";
    $this->dados['funcao']="edit";
    $this->dados['id_acti']=$id_acti;
    $this->dados['identificacoes']=\DB::table('gest_identificacoes')->get();
    $this->dados['documentos']=\DB::table('gest_documentos')->get();

    $this->dados['responsavel']=\DB::table('gest_tar_ident')
                                ->select('gest_tar_ident.*','gest_identificacoes.sigla','gest_identificacoes.descricao')
                                ->leftJoin('gest_identificacoes','gest_identificacoes.id','=','gest_tar_ident.id_identificacao')
                                ->where('gest_tar_ident.id_tarefa', $id)
                                ->where('gest_tar_ident.tipo','resp')
                                ->get();
    //$this->dados['responsavel']=\DB::table('gest_tar_ident')->where('id_tarefa',$id)->where('tipo','resp')->get();
    $this->dados['enviar']=\DB::table('gest_tar_ident')
                                ->select('gest_tar_ident.*','gest_identificacoes.sigla','gest_identificacoes.descricao')
                                ->leftJoin('gest_identificacoes','gest_identificacoes.id','=','gest_tar_ident.id_identificacao')
                                ->where('gest_tar_ident.id_tarefa', $id)
                                ->where('gest_tar_ident.tipo','env')
                                ->get();
    //$this->dados['enviar']=\DB::table('gest_tar_ident')->where('id_tarefa',$id)->where('tipo','env')->get();
    $this->dados['entrada']=\DB::table('gest_tar_doc')
                      ->select('gest_tar_doc.*','gest_documentos.referencia','gest_documentos.ficheiro')
                      ->leftJoin('gest_documentos','gest_documentos.id','=','gest_tar_doc.id_documento')
                      ->where('gest_tar_doc.id_tarefa', $id)
                      ->where('gest_tar_doc.tipo','entrada')
                      ->get();
    //$this->dados['entrada']=\DB::table('gest_tar_doc')->where('id_tarefa',$id)->where('tipo','entrada')->get();
    $this->dados['saida']=\DB::table('gest_tar_doc')
                      ->select('gest_tar_doc.*','gest_documentos.referencia','gest_documentos.ficheiro')
                      ->leftJoin('gest_documentos','gest_documentos.id','=','gest_tar_doc.id_documento')
                      ->where('gest_tar_doc.id_tarefa', $id)
                      ->where('gest_tar_doc.tipo','saida')
                      ->get();
    //$this->dados['saida']=\DB::table('gest_tar_doc')->where('id_tarefa',$id)->where('tipo','saida')->get();
    $this->dados['dados'] = \DB::table('gest_tarefas')->where('id',$id)->first();  
    $this->dados['acti'] = \DB::table('gest_atividades')->where('id',$id_acti)->first(); 
    $this->dados['process'] = \DB::table('gest_processos')->where('id', $this->dados['acti']->id_processo)->first();
    $this->dados['cert']=\DB::table('gest_certificacoes')->where('id',$this->dados['process']->id_certificacao)->first(); 

    return view('backoffice/pages/gest-tasks-new', $this->dados);
  }

  public function formTask(Request $request){
    $id=trim($request->id);
    $id_atividade=trim($request->id_atividade);
    $tarefa=trim($request->tarefa);
    $online = (isset($request->online)) ? 1 : 0;

    $resp=$request->resp;
    $env=$request->env;

    $entTipo=$request->entTipo;
    $entTexto=$request->entTexto;
    $entDocumento=$request->entDocumento;
    $entUrl=$request->entUrl;

    $saiTipo=$request->saiTipo;
    $saiTexto=$request->saiTexto;
    $saiDocumento=$request->saiDocumento;
    $saiUrl=$request->saiUrl;


    if($id){
      \DB::table('gest_tarefas')
        ->where('id',$id)
        ->where('id_atividade',$id_atividade)
        ->update(['tarefa'=>$tarefa,
                  'online'=>$online ]);
    }else{
      $ordem = \DB::table('gest_tarefas')->where('id_atividade', $id_atividade)->max('ordem');
      $id=\DB::table('gest_tarefas')
              ->insertGetId([
                'id_atividade'=>$id_atividade,
                'tarefa'=>$tarefa,
                'online'=>$online,
                'ordem'=>$ordem+1,
                'data'=>\Carbon\Carbon::now()->timestamp
            ]);
    }

    //Responsavel - Enviar
    \DB::table('gest_tar_ident')->where('id_tarefa',$id)->delete();
    //Responsavel
    if(isset($resp)){
      foreach($resp as $valor){
        $valor=trim($valor);
        if($valor){
          if(ctype_digit($valor)){
            \DB::table('gest_tar_ident')->insert(['id_tarefa'=>$id,
                                                  'id_identificacao'=>$valor,
                                                  'tipo'=>'resp' ]);
          }else{
            \DB::table('gest_tar_ident')->insert(['id_tarefa'=>$id,
                                                  'nome'=>$valor,
                                                  'tipo'=>'resp' ]);
          }          
        }
      }
    }
    //Enviar
    if(isset($env)){
      foreach($env as $valor){
        $valor=trim($valor);
        if($valor){
          if(ctype_digit($valor)){
            \DB::table('gest_tar_ident')->insert(['id_tarefa'=>$id,
                                                  'id_identificacao'=>$valor,
                                                  'tipo'=>'env' ]);
          }else{
            \DB::table('gest_tar_ident')->insert(['id_tarefa'=>$id,
                                                  'nome'=>$valor,
                                                  'tipo'=>'env' ]);
          }          
        }
      }
    }

    //Entrada - Saida
    \DB::table('gest_tar_doc')->where('id_tarefa',$id)->delete();
    //Entrada
    if(isset($entTipo)){
      $i=0;
      foreach($entTipo as $valor){
        $valor=trim($valor);
        switch($valor){
            case 'doc':

              \DB::table('gest_tar_doc')->insert(['id_tarefa'=>$id,
                                                  'id_documento'=>$entDocumento[$i],
                                                  'nome'=>empty($entTexto[$i]) ? '' : $entTexto[$i],
                                                  'tipo'=>'entrada' ]);
              break;
            case 'url':
              //$var = 'https://';
              //$pos = strripos($entUrl[$i], $var);

              $tags = $entUrl[$i];
              $termo = 'https';
              $termo2 = 'http';

              $pattern = '/' . $termo . '/';
              $pattern2 = '/' . $termo2 . '/';

              if(preg_match($pattern, $tags)) { $new_url = $entUrl[$i];}
              elseif(preg_match($pattern2, $tags)){ $new_url = $entUrl[$i];}
              else{ $new_url = 'http://'.$entUrl[$i]; }
              
              \DB::table('gest_tar_doc')->insert(['id_tarefa'=>$id,
                                                  'nome'=>$entTexto[$i],
                                                  'url'=>$new_url,
                                                  'tipo'=>'entrada' ]);
              break;
            case 'txt':
              \DB::table('gest_tar_doc')->insert(['id_tarefa'=>$id,
                                                  'nome'=>$entTexto[$i],
                                                  'tipo'=>'entrada' ]);
              break;
        }
        $i++;
      }
    }
    //Saida
    if(isset($saiTipo)){
      $i=0;
      foreach($saiTipo as $valor){
        $valor=trim($valor);
        switch($valor){
            case 'doc':

              \DB::table('gest_tar_doc')->insert(['id_tarefa'=>$id,
                                                  'id_documento'=>$saiDocumento[$i],
                                                  'nome'=>empty($saiTexto[$i]) ? '' : $saiTexto[$i],
                                                  'tipo'=>'saida' ]);
              break;
            case 'url':
              \DB::table('gest_tar_doc')->insert(['id_tarefa'=>$id,
                                                  'nome'=>$saiTexto[$i],
                                                  'url'=>$saiUrl[$i],
                                                  'tipo'=>'saida' ]);
              break;
            case 'txt':
              \DB::table('gest_tar_doc')->insert(['id_tarefa'=>$id,
                                                  'nome'=>$saiTexto[$i],
                                                  'tipo'=>'saida' ]);
              break;
        }
        $i++;
      }
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }



  /**************/
  /*  Acronyms  */
  /**************/
  public function indexAcronyms(){
    $this->dados['headTitulo']=trans('backoffice.acronymsTitulo');
    $this->dados['separador']="setGestAcronyms";
    $this->dados['funcao']="all";

    $query = \DB::table('gest_identificacoes')->get();
    $dados =[];
    foreach ($query as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      /*switch ($valor->tipo){
        case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
        case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
        case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }*/
      $dados[] = [
        'id' => $valor->id,
        'sigla' => $valor->sigla,
        'descricao' => $valor->descricao,
        'data' => $valor->data ? date('Y-m-d',$valor->data) : ''
      ];
    }
    $this->dados['dados'] = $dados;
    return view('backoffice/pages/gest-acronyms-all', $this->dados);
  }

  public function newAcronym(){
    $this->dados['headTitulo']=trans('backoffice.acronymTitulo');
    $this->dados['separador']="setGestAcronyms";
    $this->dados['funcao']="new";
    return view('backoffice/pages/gest-acronyms-new', $this->dados);
  }

  public function editAcronym($id){
    $this->dados['headTitulo']=trans('backoffice.acronymTitulo');
    $this->dados['separador']="setGestAcronyms";
    $this->dados['funcao']="edit";
    $this->dados['dados'] = \DB::table('gest_identificacoes')->where('id',$id)->first();    
    return view('backoffice/pages/gest-acronyms-new', $this->dados);
  }

  public function formAcronym(Request $request){
    $id=trim($request->id);
    $sigla=trim($request->sigla);
    $descricao=trim($request->descricao);

    if($id){
      \DB::table('gest_identificacoes')
        ->where('id',$id)
        ->update(['sigla'=>$sigla,
                  'descricao'=>$descricao ]);
    }else{
      $id=\DB::table('gest_identificacoes')
              ->insertGetId([
                'sigla'=>$sigla,
                'descricao'=>$descricao,
                'data'=>\Carbon\Carbon::now()->timestamp
            ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }
}