<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use Cookie;
use Response;
use File;

class Communication extends Controller
{
    private $dados = [];

    public function index()
    {

        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "all";

        $path = "public_html/img/comunicacao";
        $asset_img_path = "/img/comunicacao";
        $default_file =  "comunicacao.svg";
        $array = [];

        $query = \DB::table('img_comercial')->orderBy('id', 'DESC')->get();

        foreach ($query as $tblCom) {

            $avatar = '<img src="' . asset($asset_img_path . "/" . $default_file) . '" class="table-img-circle">';
            if ($tblCom->path && file_exists(base_path($path . "/" . $tblCom->file))) {
                $avatar = '<img src="' . asset($asset_img_path . "/" . $tblCom->file) . '" class="table-img-circle">';
            }

            $array[] = [
                'id' => $tblCom->id,
                'nome' => $tblCom->nome,
                'descr' => $tblCom->descricao,
                'update' => $tblCom->atualizacao,
                'tipo' => $tblCom->tipo,
                'link' => $tblCom->token,
                'ficheiro' => $avatar
            ];
        }

        //return "build communication area";
        $this->dados['array'] = $array;
        return view('backoffice/pages/communication', $this->dados);
    }

    public function addItemPage()
    {


        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "addComuni";

        return view('backoffice/pages/communication-add', $this->dados);
    }

  


    public function addItemDB(Request $request)
    {

        $nome = trim($request->nome);
        $descricao = trim($request->descricao);
        $tipo = trim($request->tipo);
        $path = "public_html/img/comunicacao";
        $token = str_random(12);
        $response  =  ['error' =>  'init', 'success' =>  'init', 'file_name' => 'init'];

        if (!empty($nome) || !empty($descricao)) {


            $validarFicheiro = json_decode(self::validarFicheiro_v3($request), true);

            if ($validarFicheiro['success'] != "" && $validarFicheiro['success'] == "ok") {

                $query = \DB::table('img_comercial')->where('nome', $nome)->first();

                // check item DB 
                if (empty($query)) {

                    \DB::table('img_comercial')
                    ->insert([
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'atualizacao' => date('Y-m-d H:i'),
                        'tipo' => $tipo,
                        'token' => $token,
                        'path' => $path,
                        'file' => $validarFicheiro['file_name']
                    ]);

                    $response['success'] = "success";
                } else {

                    if ($query->nome == $nome && $query->descricao == $descricao && $query->file == $validarFicheiro['file_name']) {

                        self::apagarFicheiro($path, $response['file_name']);
                        $response['error'] = "ja existe um ficheiro com mesmo atributos que este ";
                    } else {

                        \DB::table('img_comercial')
                        ->insert([
                            'nome' => $nome,
                            'descricao' => $descricao,
                            'atualizacao' => date('Y-m-d H:i'),
                            'tipo' => $tipo,
                            'token' => $token,
                            'path' => $path,
                            'file' => $validarFicheiro['file_name']
                        ]);

                        $response['success'] = "success";
                    }
                }
            } else {
                $response['error'] = $validarFicheiro['error'];
            }
        } else {

            self::apagarFicheiro($path,  $response['file_name']);
            $response['error'] = "campos vazios";
        }

        return json_encode($response, true);

        return json_encode($response, true);
    }

    public function apagarFicheiro($path, $filename)
    {

        $response = "";

        if (file_exists(base_path($path) . '/' .  $filename)) {

            \File::delete(base_path($path) . '/' . $filename);

            $response = "delete";
        } else {
            $response = "error | empty file";
        }
        return $response;
    }



    // apagar 
    public function apagarItem(Request $request)
    {

        $id = trim($request->id);
        $query = \DB::table('img_comercial')->where('id', $id)->first();
        $response  =  ['init' =>  '0'];

        if (!empty($query->id)) {

            $file = $query->file;
            $path = $query->path;

            //apagarFicheiro($path,  $file)
            self::apagarFicheiro($path,  $file);
            \DB::table('img_comercial')->where('id', $id)->delete();
            $response = ['success' =>  'success'];
        } else {
            $response = ['error' =>  'id nao existe'];
        }
        return  json_encode($response, true);
    }


    public function updateItem(Request $request)
    {

        $id = trim($request->id);
        $nome = trim($request->nome);
        $descricao = trim($request->descricao);
        $tipo = trim($request->tipo);
        $response  =  ['init' =>  '0'];
        $path = "public_html/img/comunicacao";
        $currentFile = trim($request->fileName);

        $ficheiro = $request->file('ficheiro');
        if (filesize($ficheiro) > 0  && !empty($ficheiro)) {

            $query = \DB::table('img_comercial')->where('id', $id)->first();
            $currentFile = $query->file;
            self::apagarFicheiro($path, $currentFile);

            $validarFicheiro = json_decode(self::validarFicheiro_v1($request, $path, $tipo), true);

            if ($validarFicheiro['success'] == "ok") {

                $response['file_name'] = $validarFicheiro['file_name'];

                if (!empty($id) ||  !empty($nome)) {
                    $updateStatus =  \DB::table('img_comercial')->where('id', $id)->update([
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'atualizacao' => date('Y-m-d H:i'),
                        'tipo' => $tipo,
                        'token' => str_random(12),
                        'path' => $path,
                        'file' => $response['file_name']
                    ]);

                    $response = [
                        'estado' => 'sucesso',
                        'update' => $updateStatus
                    ];
                } else {

                    self::apagarFicheiro($path, $response['file_name']);
                    $response = ['error' =>  'Deve Preencher os campos'];
                }
            } else {
                $response['error'] =  $validarFicheiro['error'];
            }
        } else {

            $updateStatus =  \DB::table('img_comercial')->where('id', $id)->update([
                'nome' => $nome,
                'descricao' => $descricao,
                'atualizacao' => date('Y-m-d H:i'),
                // 'atualizacao' => date('Y-m-d H:i:s'),
                'tipo' => $tipo,
                'token' => str_random(12),
                'path' => $path,
                'file' => $currentFile
            ]);

            $response['estado'] = "sucesso";
            $response['update'] = $updateStatus;
            /*$response = [
                'estado' => 'sucesso',
                'update' => $updateStatus,
                 'file' => $currentFile
            ];*/
        }
        return json_encode($response, true);
    }

    public function downloadFile($id)
    {

        $query = \DB::table('img_comercial')->where('id', $id)->first();
        $ficheiro = $query->file;
        $path =  $query->path;


        $file =  "../public_html/img/comunicacao/" . $ficheiro;
        $name = basename($file);
        return response()->download($file, $name);
    }

    public function editarItem_v1($id)
    {
    }

    public function editarPage($id)
    {

        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "editComuni";
        $this->dados['obj'] = \DB::table('img_comercial')->where('id', $id)->first();

        return view('backoffice/pages/communication-add', $this->dados);
    }

    public function updateItem(Request $request)
    {
        $id = trim($request->id);
        $nome = trim($request->nome);
        $descricao = trim($request->descricao);
        $tipo = trim($request->tipo);
        $response  =  ['init' =>  '0'];
        $path = "public_html/img/comunicacao";
        $currentFile = trim($request->fileName);

        $ficheiro = $request->file('ficheiro');
        if (filesize($ficheiro) > 0  && !empty($ficheiro)) {

            $query = \DB::table('img_comercial')->where('id', $id)->first();
            $currentFile = $query->file;
            $response["deleteMessage"]  = self::apagarFicheiro($path, $currentFile);

            $validarFicheiro = json_decode(self::validarFicheiro_v1($request, $path, $tipo), true);

            if ($validarFicheiro['success'] == "ok") {

                $response['file_name'] = $validarFicheiro['file_name'];

                if (!empty($id) ||  !empty($nome)) {
                    $updateStatus =  \DB::table('img_comercial')->where('id', $id)->update([
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'atualizacao' => date('Y-m-d H:i:s'),
                        'tipo' => $tipo,
                        'token' => str_random(12),
                        'path' => $path,
                        'file' => $response['file_name']
                    ]);

                    $response = [
                        'estado' => 'sucesso',
                        'update' => $updateStatus
                    ];
                } else {

                    $response["deleteMessage"]  = self::apagarFicheiro($path, $response['file_name']);
                    $response = ['error' =>  'Deve Preencher os campos'];
                }
            } else {
                $response['error'] =  $validarFicheiro['error'];
            }
        } else {

            $updateStatus =  \DB::table('img_comercial')->where('id', $id)->update([
                'nome' => $nome,
                'descricao' => $descricao,
                'atualizacao' => date('Y-m-d H:i:s'),
                'tipo' => $tipo,
                'token' => str_random(12),
                'path' => $path,
                'file' => $currentFile
            ]);
            $response = [
                'estado' => 'sucesso',
                'update' => $updateStatus,
                'file' => $currentFile
            ];
        }
        return json_encode($response, true);
    }

    public function apagarItem(Request $request)
    {

        $id = trim($request->id);
        $query = \DB::table('img_comercial')->where('id', $id)->first();
        $response  =  ['init' =>  '0'];

        if (!empty($query->id)) {

            $file = $query->file;
            $path = $query->path;


            $id = trim($request->id);
            $query = \DB::table('img_comercial')->where('id', $id)->first();
            $response  =  ['init' =>  '0'];

            if (!empty($query->id)) {

                $file = $query->file;
                $path = $query->path;

                //apagarFicheiro($path,  $file)
                self::apagarFicheiro($path,  $file);
                \DB::table('img_comercial')->where('id', $id)->delete();
                $response = ['success' =>  'success'];

                /*if (file_exists(base_path($path . "/" .  $file))) {
    
                    \File::delete($path . "/" . $file);
                    \DB::table('img_comercial')->where('id', $id)->delete();
                    $response = ['success' =>  'success'];
                } else {
                    $response = ['error' =>  'error to delete file'];
                }*/
            } else {
                $response = ['error' =>  'id nao existe'];
            }
            return  json_encode($response, true);



            /*if (file_exists(base_path($path . "/" .  $file))) {

                \File::delete($path . "/" . $file);
                \DB::table('img_comercial')->where('id', $id)->delete();
                $response = ['success' =>  'success'];
            } else {
                $response = ['error' =>  'error to delete file'];
            }*/
        } else {
            $response = ['error' =>  'id nao existe'];
        }
        return  json_encode($response, true);
    }


    //apagar v2
    public function apagarItem_v2(Request $request)
    {
        $id = trim($request->id);
        $linha = \DB::table('empresas')->where('id', $id)->first();
        if (isset($linha->id) && $linha->id) {
            //Apagar Logotipo
            if ($linha->logotipo && file_exists(base_path('public_html/img/empresas/' . $linha->logotipo))) {
                \File::delete('../public_html/img/empresas/' . $linha->logotipo);
                //\File::deleteDirectory('../public_html/img/empresas/'.$linha->logotipo);
            }
            //Apagar documentos (certidao, ies, validação comerciantes)
            if ($linha->certidao && file_exists(base_path('public_html/doc/companies/' . $linha->certidao))) {
                \File::delete('../public_html/doc/companies/' . $linha->certidao);
            }
            if ($linha->ies) {
                $obj = json_decode($linha->ies);
                foreach ($obj as $value) {
                    if ($value->ies && file_exists(base_path('public_html/doc/companies/' . $value->ies))) {
                        \File::delete('../public_html/doc/companies/' . $value->ies);
                    }
                }
            }
            //Apagar tickets
            $tikQuery = \DB::table('tickets')->where('id_empresa', $id)->get();
            foreach ($tikQuery as $valor) {
                $msgQuery = \DB::table('tickets_msg')->where('id_ticket', $valor->id)->get();
                foreach ($msgQuery as $val) {
                    if ($val->ficheiros) {
                        $obj = json_decode($val->ficheiros);
                        foreach ($obj as $file) {
                            if ($file->ficheiro && file_exists(base_path('public_html/doc/support/' . $file->ficheiro))) {
                                \File::delete('../public_html/doc/support/' . $file->ficheiro);
                            }
                        }
                    }
                }
            }
            //Manter as encomendas
            \DB::table('empresas')->where('id', $id)->delete();
            return 'sucesso';
        }
        return 'erro';
    }


    public function downloadFile_v2($id)
    {

        $query = \DB::table('img_comercial')->where('id', $id)->first();
        $ficheiro = $query->file;
        $path =  $query->path;

        //$file =  $path ."/".$ficheiro ;
        $file =  "../public_html/img/comunicacao/" . $ficheiro;
        $name = basename($file);
        return response()->download($file, $name);
    }

    public function downloadFile_v1($ficheiro)
    {

        return response()->download(base_path($path . "/" . $ficheiro));
    }

    public function generateUrl($id, $token, $file)
    {

        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";

        $path = "public_html/img/comunicacao";
        $asset_img_path = "/img/comunicacao";
        $default_file =  "comunicacao.svg";
        $array = [];

        $query = \DB::table('img_comercial')->orderBy('id', 'DESC')->get();
        foreach ($query as $tblCom) {

            $avatar = '<img src="' . asset($asset_img_path . "/" . $default_file) . '" class="table-img-circle">';
            if ($tblCom->path && file_exists(base_path($path . "/" . $tblCom->file))) {
                $avatar = '<img src="' . asset($asset_img_path . "/" . $tblCom->file) . '" class="table-img-circle">';
            }

            $array[] = [
                'id' => $tblCom->id,
                'nome' => $tblCom->nome,
                'descr' => $tblCom->descricao,
                'update' => $tblCom->atualizacao,
                'tipo' => $tblCom->tipo,
                'link' => $tblCom->token,
                'ficheiro' => $avatar
            ];
        }

        //return "build communication area";
        $this->dados['array'] = $array;
        // return view('backoffice/pages/communication', $this->dados);

        return view(' /site_v2/pages/communication-public', $this->dados);
    }
}
