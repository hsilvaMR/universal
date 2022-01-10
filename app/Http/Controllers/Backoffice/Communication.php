<?php




/*

	    =======================================================================================
		                  Code by Honório Silva  dasilvah77@gmail.com
		=======================================================================================

*/

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;

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

                $getExtension = explode(".", $tblCom->file);
                if ($getExtension[1] == "pdf") {

                    // <i class="fas fa-file-pdf   table-img-circle"></i>
                    //$avatar = '<img src="' . asset($asset_img_path . "/" . $tblCom->file) . '" class="table-img-circle">';
                    $avatar = '<div class"text-center" style="color:#3097D1;"><i class="fas ps-5 fa-file-pdf fa-3x"></i></div>';
                } else {

                    $avatar = '<img src="' . asset($asset_img_path . "/" . $tblCom->file) . '" class="table-img-circle">';
                }
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
        $response  =  ['error' =>  'init', 'success' =>  'init'];

        if (!empty($nome) || !empty($descricao)) {

            $validarFicheiro = json_decode(self::validarFicheiro($request, $path, $tipo), true);
            //$validarFicheiro = json_decode(self::validarFicheiro_v1($request, $path, $tipo), true);

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
                    $response['error'] = "A descrião do Ficheiro Deve ser Única";
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
            $newName = "init";

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
                $maxSize = 83886080;  //  172000  15728640 byte = 15MB  https://convertlive.com/u/convert/megabytes/to/bytes#15  83886080
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
            }
        } else {
            //$response = ['error' =>  'ficheiro invalido'];
            $response['error'] = 'ficheiro invalido';
        }

        return json_encode($response, true);
    }

    //validar ficheiro v2
    public function validarFicheiro_v1(Request $request,  $path, $tipo)
    {
        // verificar o ficheiro 
        $ficheiro = $request->file('ficheiro');
        $extensao = strtolower($ficheiro->getClientOriginalExtension());
        $validExtesion = array("jpg", "jpeg", "png", "svg", "pdf");
        $pasta = base_path($path);
        $id = str_random(3);
        $response = ['error' => 'init', 'success' => 'init', 'file_name' => 'init'];
        $nomeFicheiro = "init";

        if ($ficheiro->isValid()) {

            // verificar a extensão do ficheiro
            if (self::validarExtensao($extensao, $validExtesion) == "success") {

                // verificar tamanho do ficheiro
                if (self::validarTamanho($ficheiro) == "success") {

                    // gerar nome do ficheiro
                    $gerarNome = self::nomearFicheiro($extensao, $id, $tipo);
                    if ($gerarNome != "init" && $gerarNome != "error" &&  $gerarNome != "") {

                        $nomeFicheiro = $gerarNome;
                        if ($nomeFicheiro != "init") {

                            $responseMovFile = self::moverFicheiro($ficheiro, $pasta, $nomeFicheiro);
                            if ($responseMovFile != "init" && $responseMovFile != "") {

                                $response['success'] = 'ok';
                                $response['file_name'] = $nomeFicheiro;
                            }
                        }
                    } else {
                        $response['error'] = 'Falha na Atribuição Nome ao Ficheiro';
                    }
                } else {
                    $response['error'] = 'Tamanho do Ficheiro Não Suportado';
                }
            } else {
                $response['error'] = 'Extensao Não Suportado';
            }
        } else {
            $response['error'] = 'Ficheiro Não Válido';
        }

        return json_encode($response, true);
    }


    // funcçoes validar ficheiro v2

    // validar a extensão
    public function validarExtensao($extensao, $validExtesion)
    {

        $response = "init";

        if (in_array($extensao, $validExtesion)) {

            $response = "success";
        } else {
            $response = "error";
        }

        return $response;
    }

    // validar tamanho do ficheiro
    public function validarTamanho($ficheiro)
    {

        $response = "init";
        $maxSize = 83886080;  // 104857600 Bytes = 100MB    15728640 byte = 15MB  | 1MB =  1048576 Bytes  https://convertlive.com/u/convert/megabytes/to/bytes#15

        if (filesize($ficheiro) <= $maxSize) {
            $response = "success";
        } else {
            $response = "error";
        }
        return   $response;
    }

    // atribuição do nome ao ficheiro
    public function nomearFicheiro($extensao, $tipo, $id)
    {

        $newName = "init";
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

            default:
                $newName = "error";
                break;
        }

        return  $newName;
    }

    //mover ficheiro para pasta destinado
    public function moverFicheiro($ficheiro, $pasta, $novoNome)
    {
        $response = "init";
        $ficheiro->move($pasta . '/', $novoNome);
        $response = "success";
        return $response;
    }




    public function moverFicheiro_v2($request, $pasta, $novoNome)
    {

        //REF:. https://appdividend.com/2018/08/15/laravel-file-upload-example/

        $uploadedFile = $request->file('file');
        $filename = time() . $uploadedFile->getClientOriginalName();

        Storage::disk('local')->putFileAs(
            'files/' . $filename,
            $uploadedFile,
            $filename
        );

        // armazenar dados upload na base de dados 

    }



    // editar 
    public function editarItem($id)
    {


        $this->dados['headTitulo'] = trans('backoffice.comunicTitulo');
        $this->dados['separador'] = "sellerComuni";
        $this->dados['funcao'] = "editComuni";
        $this->dados['obj'] = \DB::table('img_comercial')->where('id', $id)->first();

        return view('backoffice/pages/communication-add', $this->dados);
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




    public function publicArea()
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
