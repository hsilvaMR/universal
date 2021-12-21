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

<<<<<<< HEAD
        //return "build communication area";
=======
>>>>>>> server
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

    public function addItemDB_v2(Request $request)
    {

        $nome = trim($request->nome);
        $descricao = trim($request->descricao);
        $tipo = trim($request->tipo);
        $path = "public_html/img/comunicacao";
        $token = str_random(12);
        $response  =  ['init' =>  '0'];



        if (!empty($nome) || !empty($descricao)) {

            $validarFicheiro = json_decode(self::validarFicheiro_v1($request, $path, $tipo), true);

            if ($validarFicheiro['success'] == "ok") {

                $response['file_name'] = $validarFicheiro['file_name'];
                $query = \DB::table('img_comercial')
                    ->where('nome', $nome)
                    ->orWhere('file',  $response['file_name'])
                    ->first();
                // check item DB 
                if (empty($query->nome)) {

                    \DB::table('img_comercial')
                        ->insert([
                            'nome' => $nome,
                            'descricao' => $descricao,
                            'atualizacao' => strtotime(date('Y-m-d H:i:s')),
                            'tipo' => $tipo,
                            'token' => $token,
                            'path' => $path,
                            'file' => $response['file_name']
                        ]);

                    $response = ['success' =>  'success'];
                } else {
                    self::apagarFicheiro($path,  $response['file_name']);
                    $response = ['error' =>  'ficheiro existe'];
                }
            } else {
                $response['error'] =  $validarFicheiro['error'];
            }
        } else {

            self::apagarFicheiro($path,  $response['file_name']);
            $response = ['error' =>  'campos vazios'];
        }

        return  json_encode($response, true);
    }


    public function addItemDB(Request $request)
    {

        $nome = trim($request->nome);
        $descricao = trim($request->descricao);
        $tipo = trim($request->tipo);
<<<<<<< HEAD

=======
>>>>>>> server
        $path = "public_html/img/comunicacao";
        $token = str_random(12);
<<<<<<< HEAD
        $response  = "";
        $nomeFicheiro = "";
        $validarFicheiro = "";
        // public function validarFicheiro($ficheiro, $path,  $nomeFicheiro, $id, $tipo)
        $query = \DB::table('img_comercial')
            ->where('nome', $nome)
            // ->orWhere('file', $nomeFicheiro)
            ->first();

        if (empty($query->nome)) {

            $validarFicheiro = json_decode(self::validarFicheiro_v1($request, $path, $tipo), true);

            if ($validarFicheiro['success'] == "ok") {

                $nomeFicheiro = $validarFicheiro['file_name'];

                \DB::table('img_comercial')
                    ->insert([
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'atualizacao' => strtotime(date('Y-m-d H:i:s')),
                        'tipo' => $tipo,
                        'token' => $token,
                        'path' => $path,
                        'file' => $nomeFicheiro
                    ]);

                $response  = "success";
            } else {

                self::apagarFicheiro($path, $nomeFicheiro);
                $response = "o ficheiro já existe";
=======
        $response  =  ['error' =>  'init', 'success' =>  'init'];

        if (!empty($nome) || !empty($descricao)) {

            $validarFicheiro = json_decode(self::validarFicheiro($request, $path, $tipo), true);

            if ($validarFicheiro['success'] != "" && $validarFicheiro['success'] == "ok") {

                $response['file_name'] = $validarFicheiro['file_name'];
                $query = \DB::table('img_comercial')
                    ->where('descricao', $descricao)
                    ->orWhere('file',  $response['file_name'])
                    ->first();
                // check item DB 
                if (empty($query->nome)) {

                    \DB::table('img_comercial')
                        ->insert([
                            'nome' => $nome,
                            'descricao' => $descricao,
                            'atualizacao' => date('Y-m-d H:i'),
                            'tipo' => $tipo,
                            'token' => $token,
                            'path' => $path,
                            'file' => $response['file_name']
                        ]);

                    $response['success'] = "success";
                } else {
                    self::apagarFicheiro($path,  $response['file_name']);
                    //$response = ['error' =>  'ficheiro existe'];
                    $response['error'] = "ficheiro existe";
                }
            } else {
                $response['error'] = $validarFicheiro['error'];
            }
        } else {

            self::apagarFicheiro($path,  $response['file_name']);
            //$response = ['error' =>  'campos vazios'];
            $response['error'] = "campos vazios";
        }

        return json_encode($response, true);
    }


    public function apagarFicheiro($path, $filename)
    {

        //   \Storage::delete($path . "/" . $filename);

        $response = "";

        if (file_exists(base_path($path) . '/' .  $filename)) {

            \File::delete(base_path($path) . '/' . $filename);
            $response = "delete";
        } else {
            $response = "error | empty file";
        }
        return $response;
    }

    public function validarFicheiro(Request $request,  $path, $tipo)
    {
        // verificar o ficheiro 
        if ($request->hasFile('ficheiro') && $request->file('ficheiro')->isValid()) {

            $ficheiro = $request->file('ficheiro');
            $extensao = strtolower($ficheiro->getClientOriginalExtension());
            $validExtesion = array("jpg", "jpeg",  "png", "svg", "pdf");
            $pasta = base_path($path);
            $id = str_random(3);
            $response = ['error' => 'init', 'success' => 'init', 'file_name' => 'init'];

            // verifica extens達o aceite
            if (in_array($extensao, $validExtesion)) {

                switch ($tipo) {

                    case "Rotulo":
                        $newName = 'COM-' . $tipo . '-' . $id . '.' . $extensao;
                        break;
                    case "Image":
                        $newName = 'COM-' . $tipo . '-' . $id . '.' . $extensao;
                        break;
                    case "Documento":
                        $newName = 'COM-' . $tipo . '-' . $id . '.' . $extensao;
                        break;
                }

                //verifica tamanho suportado
                $maxSize =   15728640;  //  172000  15728640 byte = 15MB  https://convertlive.com/u/convert/megabytes/to/bytes#15
                if (filesize($ficheiro) <= $maxSize) {

                    // https://stackoverflow.com/questions/34443451/file-upload-laravel-5
                    $ficheiro->move($pasta . '/', $newName);
                    $response['success'] = 'ok';
                    $response['file_name'] = $newName;
                } else {
                    //$response = ['error' =>  'tamanho nao suportado'];
                    $response['error'] = 'tamanho nao suportado';
                }
            } else {
                // $response = ['error' =>  'extensao invalido'];
                $response['error'] = 'extensao nao suportado';
>>>>>>> server
            }
        } else {
            //$response = ['error' =>  'ficheiro invalido'];
            $response['error'] = 'ficheiro invalido';
        }

        return json_encode($response, true);
    }

<<<<<<< HEAD
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



    public function validarFicheiro_V2(Request $request,  $path, $tipo)
    {


        // verificar o ficheiro 
        if ($request->hasFile('ficheiro') && $request->file('ficheiro')->isValid()) {

            $ficheiro = $request->file('ficheiro');
            $extensao = strtolower($ficheiro->getClientOriginalExtension());
            $validExtesion = array("jpg", "jpeg",  "png", "svg", "pdf");
            Storage::putFileAs('photos', new File($path . "/"), 'photo.jpg');
        }
    }

    public function validarFicheiro_v1(Request $request,  $path, $tipo)
    {
        // verificar o ficheiro 
        if ($request->hasFile('ficheiro') && $request->file('ficheiro')->isValid()) {

            $ficheiro = $request->file('ficheiro');
            $extensao = strtolower($ficheiro->getClientOriginalExtension());
            $validExtesion = array("jpg", "jpeg",  "png", "svg", "pdf");
            $pasta = base_path($path);
            $id = str_random(3);
            $response = ['init' => '0'];

            // verifica extensão aceite
            if (in_array($extensao, $validExtesion)) {

                switch ($tipo) {

                    case "Rotulo":
                        $newName = 'COMU - ' . $tipo . $id . '.' . $extensao;
                        break;
                    case "Image":
                        $newName = 'COMU -' . $tipo . '-' . $id . '.' . $extensao;
                        break;
                }

                //verifica tamanho suportado
                $maxSize = 15728640;  //   15728640 byte = 15MB  https://convertlive.com/u/convert/megabytes/to/bytes#15
                if (filesize($ficheiro) <= $maxSize) {

                    // https://stackoverflow.com/questions/34443451/file-upload-laravel-5
                    $ficheiro->move($pasta . '/', $newName);
                    $response = [
                        'success' =>   "ok",
                        'file_name' =>   $newName
                    ];
                } else {
                    $response = ['error' =>  'tamanho nao suportado'];
                }
            } else {
                $response = ['error' =>  'extensao invalido'];
            }
        } else {
            $response = ['error' =>  'ficheiro invalido'];
        }

        return  json_encode($response, true);
    }

    public function validarFicheiro($ficheiro, $path, $nomeFicheiro,  $tipo)
=======

    // editar 
    public function editarItem($id)
>>>>>>> server
    {


<<<<<<< HEAD
        $response = ['init' => '0'];
        $pasta = dirname($path);
        $antigoNome = '';
        $extensao = strtolower($ficheiro->getClientOriginalExtension());
        $validExtesion = array("jpg", "jpeg",  "png", "svg", "pdf");
        $id = str_random(3);
        if (!empty($ficheiro)) {
=======
        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "editComuni";
        $this->dados['obj'] = \DB::table('img_comercial')->where('id', $id)->first();

        return view('backoffice/pages/communication-add', $this->dados);
    }
>>>>>>> server



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

<<<<<<< HEAD
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
=======

    public function publicArea()
>>>>>>> server
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
