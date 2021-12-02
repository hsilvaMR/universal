<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use Cookie;

class Communication extends Controller
{

    private $dados = [];

    public function index()
    {

        $this->dados['headTitulo'] = trans('backoffice.ordersTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "all";


        $path = "public_html/img/comunicacao";
        $asset_img_path = "/img/comunicacao";
        $default_file =  "comunicacao.svg";

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
        return view('backoffice/pages/communication', $this->dados);
    }

    public function addItemPage()
    {

        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "addComuni";

        return view('backoffice/pages/communication-add', $this->dados);


        // return "build add  area";
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

        $path = "public_html/img/comunicacao";
        // $asset_img_path = "/img/comunicacao";
        // $default_file =  "comunicacao.svg";
        $token = str_random(12);
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
            }
        } else {
            $response = $validarFicheiro['error'];
        }

        return  $response;
    }

    public function apagarFicheiro($path, $filename)
    {

        \Storage::delete($path . "/" . $filename);

        //if (file_exists(base_path($path . '/' . $filename))) {
        //File::delete($path . '/' . $filename);
        //https://laravel.com/docs/5.8/filesystem
        //Storage::delete($path . "/" . $filename);
        // }
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
    {
        // CRIAR FICHEIRO LARAVEL
        // https://codeanddeploy.com/blog/laravel/laravel-8-file-upload-example 

        // strtoupper()  Retorna string com todos os caracteres do alfabeto convertidos para maiúsculas. 
        //  strtolower  Retorna string com todos os caracteres do alfabeto convertidos para minúsculas. 
        // strcasecmp()  Comparação de strings sem diferenciar maiúsculas e minúsculas segura para binário 
        //$pasta = base_path('public_html/site_v2/img/slide/');

        $response = ['init' => '0'];
        $pasta = dirname($path);
        $antigoNome = '';
        $extensao = strtolower($ficheiro->getClientOriginalExtension());
        $validExtesion = array("jpg", "jpeg",  "png", "svg", "pdf");
        $id = str_random(3);
        if (!empty($ficheiro)) {

            // verifica extensão aceite
            if (in_array($extensao, $validExtesion)) {

                //verifica tamanho suportado
                $maxSize = 15728640;  //   15728640 byte = 15MB  https://convertlive.com/u/convert/megabytes/to/bytes#15
                if (filesize($ficheiro) <= $maxSize) {

                    $holdName = $nomeFicheiro;
                    $newName = "";

                    switch ($tipo) {

                        case "Rotulo":
                            $newName = 'COMUNIC-' . $tipo . $id . '.' . $extensao;
                            break;
                        case "Image":
                            $newName = 'COMUNIC-' . $tipo . $id . '.' . $extensao;
                            break;
                    }

                    if (move_uploaded_file($holdName, $pasta . "/" . $newName)) {

                        $response = ['success' => 'ok'];
                        $response = ['nameFile' =>  $newName];
                    } else {
                        $response = ['error' =>  'Upload error'];
                    }
                } else {

                    $response = ['error' =>  'tamanho nao suportado'];
                }
            } else {

                $response = ['error' =>  'extensao invalido'];
            }
        } else {

            $response = ['error' =>  'empty file'];
        }

        return  json_encode($response, true);
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
        $currentFile = trim($request->uploads_xs);

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
                        'atualizacao' => strtotime(date('Y-m-d H:i:s')),
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
                'atualizacao' => strtotime(date('Y-m-d H:i:s')),
                'tipo' => $tipo,
                'token' => str_random(12),
                'path' => $path,
                'file' => $currentFile
            ]);
            $response = [
                'estado' => 'sucesso',
                'update' => $updateStatus
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
            if (file_exists(base_path($path . "/" .  $file))) {

                \File::delete($path . "/" . $file);
                \DB::table('img_comercial')->where('id', $id)->delete();
                $response = ['success' =>  'success'];
            } else {
                $response = ['error' =>  'error to delete file'];
            }
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


    public function generateUrl($id, $token, $file)
    {

        $join = $id + $token;

        $resposta = [
            'url' =>  $join,
            'file' => $file
        ];
        return  json_encode($resposta, true);
    }
}
