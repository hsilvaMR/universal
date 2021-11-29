<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


class Version extends Controller
{   
    private $dados=[];
	
    public function index($token){
        $this->dados['token']=$token;

        $version = \DB::table('gest_doc_versoes')->where('token',$token)->first();

        $this->dados['version_aux'] = \DB::table('gest_doc_versoes_aux')->where('id_versao',$version->id)->get();

        $this->dados['nome_ficheiro'] = str_replace('/backoffice/gestao_documental/doc/', '', $version->ficheiro);

        $this->dados['documento'] =  \DB::table('gest_documentos')->where('id',$version->id_documento)->first();

        $this->dados['version'] = $version;
        return view('backoffice/pages/version-status', $this->dados);
    }

    public function versionForm(Request $request){
        $tipo_status = trim($request->tipo_status);
        $doc_aux_versao = $request->file('doc');
        $nota = trim($request->nota);
        $id_versao = trim($request->id_versao);


        $doc_versao = \DB::table('gest_doc_versoes')->where('id',$id_versao)->first();

        \DB::table('gest_doc_versoes')
            ->where('id',$id_versao)
            ->update([
              'nota' => $nota,
              'estado' => $tipo_status,
              'data_estado' => \Carbon\Carbon::now()->timestamp
            ]);

        if ($tipo_status == 'aprovado') {
            
            $cont_versoes = \DB::table('gest_doc_versoes')->where('id_documento',$doc_versao->id_documento)->count();
            $v_total = $cont_versoes + 1;

            \DB::table('gest_documentos')
                  ->where('id',$doc_versao->id_documento)
                  ->update([
                    'ficheiro' => $doc_versao->ficheiro,
                    'versao' => $v_total,
                    'data' => \Carbon\Carbon::now()->timestamp
                  ]);
        }

        if (count($doc_aux_versao[0])) {
            foreach ($doc_aux_versao as $value) {
                $cache = str_random(3);
                $destinationPath = base_path('public_html/backoffice/gestao_documental/doc_aux/');
                $extension = strtolower($value->getClientOriginalExtension());
                $getName = $value->getPathName();
              
                $novo_doc = 'doc_'.$doc_versao->id_documento.'_aux_'.$id_versao.'_'.$cache.'.'.$extension;
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
}