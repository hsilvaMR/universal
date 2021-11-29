<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Hash;
use Validator;
use Mail;
use Cookie;

class GestViewer extends Controller
{
	private $dados=[];
	
	public function index($id){
		$certifications = \DB::table('gest_certificacoes')->where('id',$id)->first();
                $this->dados['headTitulo']='';
		if($certifications){
                    $this->dados['headTitulo']= $certifications->nome;    
                }
		$this->dados['separador']="gestViewer".$id;
                $this->dados['funcao']="";
                $this->dados['id'] = $id;
                $this->dados['process'] = \DB::table('gest_processos')
			             ->where('id_certificacao',$id)
			             ->where('online','1')
			             ->get();
	return view('backoffice/pages/gest-viewer-certification', $this->dados);
	}

	public function getProcess($id){
                $this->dados['funcao']="";
                $process = \DB::table('gest_processos')
                                ->select('gest_processos.*','gest_processos.nome AS nome_processo','gest_processos.referencia AS ref_processo','gest_processos.id AS id_processo','gest_documentos.*')
                                ->leftJoin('gest_documentos','gest_documentos.id','=','gest_processos.id_documento')
                                ->where('gest_processos.id',$id)
                                ->first();
              

                $certifications = \DB::table('gest_certificacoes')->where('id',$process->id_certificacao)->first();

                $first_activit = \DB::table('gest_atividades')
                                        ->where('id_processo',$id)
                                        ->where('online','1')
                                        ->first();
                if ($first_activit) {
                        $first_tarefa = \DB::table('gest_tarefas')
                                ->where('id_atividade',$first_activit->id)
                                ->where('online','1')
                                ->orderBy('ordem','ASC')
                                ->get();
                
                
                        $responsavel = [];
                        $env = [];
                        $entrada = [];
                        $saida = [];
                        foreach ($first_tarefa as $value) {
                                $resp =\DB::table('gest_tar_ident')->where('id_tarefa',$value->id)->get();
                                $openExit =\DB::table('gest_tar_doc')->where('id_tarefa',$value->id)->get();
                                
                                foreach ($resp as $val) {
                                        $ident = \DB::table('gest_identificacoes')->where('id',$val->id_identificacao)->first();

                                        $sigla='';
                                        if ($ident) { $sigla =  $ident->sigla;}
                                        if ($val->tipo == 'resp') {
                                                
                                                $responsavel [] = [
                                                        'id_tarefa'=> $value->id,
                                                        'nome' => $val->nome,
                                                        'sigla' => $sigla
                                                ];
                                               
                                        }elseif($val->tipo == 'env'){
                                                $env [] = [
                                                        'id_tarefa'=> $value->id,
                                                        'nome' => $val->nome,
                                                        'sigla' => $sigla
                                                ];
                                        }
                                }

                                foreach ($openExit as $val) {
                                        $doc = \DB::table('gest_documentos')->where('id',$val->id_documento)->first();

                                        $documento='';
                                        if ($doc) { $documento =  $doc->ficheiro;}
                                        if ($val->tipo == 'entrada') {
                                                
                                                $entrada [] = [
                                                        'id_tarefa'=> $value->id,
                                                        'nome' => $val->nome,
                                                        'url' => $val->url,
                                                        'doc' => $documento
                                                ];
                                               
                                        }elseif($val->tipo == 'saida'){
                                                $saida [] = [
                                                        'id_tarefa'=> $value->id,
                                                        'nome' => $val->nome,
                                                        'url' => $val->url,
                                                        'doc' => $documento
                                                ];
                                        }
                                }
                        }

                        $this->dados['responsavel'] = $responsavel;
                        $this->dados['env'] = $env;
                        $this->dados['entrada'] = $entrada;
                        $this->dados['saida'] = $saida;

                        $activits = \DB::table('gest_atividades')
                                                        ->where('id_processo',$id)
                                                        ->where('id','<>',$first_activit->id)
                                                        ->where('online','1')
                                                        ->get();

                        $tarefas_all = [];
                        $responsavel_all = [];
                        $env_all = [];
                        $entrada_all = [];
                        $saida_all = [];
                        foreach ($activits as $value) {
                                $tarefas_query = \DB::table('gest_tarefas')
                                                        ->where('id_atividade',$value->id)
                                                        ->where('online','1')
                                                        ->orderBy('ordem','ASC')
                                                        ->get();

                                foreach ($tarefas_query as $val) {
                                        $tarefas_all [] = [
                                                'id' => $val->id,
                                                'id_atividade' => $value->id,
                                                'tarefa' => $val->tarefa
                                        ];

                                        $resp =\DB::table('gest_tar_ident')->where('id_tarefa',$val->id)->get();
                                        $openExit =\DB::table('gest_tar_doc')->where('id_tarefa',$val->id)->get();
                                
                                        foreach ($resp as $responsavel) {
                                                $ident = \DB::table('gest_identificacoes')->where('id',$responsavel->id_identificacao)->first();

                                                $sigla='';
                                                if ($ident) { $sigla =  $ident->sigla;}
                                                if ($responsavel->tipo == 'resp') {
                                                
                                                        $responsavel_all [] = [
                                                                'id_tarefa'=> $val->id,
                                                                'nome' => $responsavel->nome,
                                                                'sigla' => $sigla
                                                        ];
                                               
                                                }elseif($responsavel->tipo == 'env'){
                                                        $env_all [] = [
                                                                'id_tarefa'=> $val->id,
                                                                'nome' => $responsavel->nome,
                                                                'sigla' => $sigla
                                                        ];
                                                }
                                        }

                                        foreach ($openExit as $open) {
                                                $doc = \DB::table('gest_documentos')->where('id',$open->id_documento)->first();

                                                $documento='';
                                                if ($doc) { $documento =  $doc->ficheiro;}
                                                if ($open->tipo == 'entrada') {
                                                
                                                        $entrada_all [] = [
                                                                'id_tarefa'=> $val->id,
                                                                'nome' => $open->nome,
                                                                'url' => $open->url,
                                                                'doc' => $documento
                                                        ];
                                                }elseif($open->tipo == 'saida'){
                                                        $saida_all [] = [
                                                                'id_tarefa'=> $val->id,
                                                                'nome' => $open->nome,
                                                                'url' => $open->url,
                                                                'doc' => $documento
                                                        ];
                                                }
                                        }
                                }

                        }

                        $this->dados['responsavel_all'] = $responsavel_all;
                        $this->dados['env_all'] = $env_all;
                        $this->dados['entrada_all'] = $entrada_all;
                        $this->dados['saida_all'] = $saida_all;
                        $this->dados['first_tarefa'] = $first_tarefa;
                        $this->dados['activits'] = $activits;
                        $this->dados['tarefas_all'] = $tarefas_all;
                }
             
                $this->dados['headTitulo']= $certifications->nome;
                $this->dados['separador'] = "gestViewer".$certifications->id;
                $this->dados['process'] = $process;
                $this->dados['first_activit'] = $first_activit;
                                
                
        	return view('backoffice/pages/gest-viewer-process', $this->dados);
	}
}