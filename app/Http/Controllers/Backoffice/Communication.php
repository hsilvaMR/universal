<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

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

    public function addItemDB(Request $request)
    {

        $nome = trim($request->nome);
        $ficheiro = $request->file('ficheiro');
        $nomeFicheiro = trim($request->ficheiro);
        $descricao = trim($request->descricao);
        $tipo = trim($request->tipo) ? trim($request->tipo) : 'image';

        $path = "public_html/img/comunicacao";
        // $asset_img_path = "/img/comunicacao";
        // $default_file =  "comunicacao.svg";
        $token = str_random(12);
        $response  = "";

        $validarFicheiro = json_decode(self::validarFicheiro($ficheiro, $path, $nomeFicheiro, $tipo), true);
        // public function validarFicheiro($ficheiro, $path,  $nomeFicheiro, $id, $tipo)

        if ($validarFicheiro['success'] == "ok") {

            $newName = $validarFicheiro['nameFile'];

            $query = \DB::table('img_comercial')
                ->where('nome', $nome)
                ->orWhere('file', $nomeFicheiro)
                ->first();

            if (empty($query->nome)) {

                \DB::table('img_comercial')
                    ->insert([
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'atualizacao' => strtotime(date('Y-m-d H:i:s')),
                        'tipo' => $tipo,
                        'token' => $token,
                        'path' => $path,
                        'file' => $$newName
                    ]);
            } else {

                $response = "o ficheiro já existe";
            }
        } else {
            $response = $validarFicheiro['error'];
        }

        return  $response;
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
        //$validExtesion = array("jpg", "jpeg",  "png", "svg", "pdf", "JPG", "JPEG", "PNG", "PDF", "SVG");

        if (count($ficheiro)) {

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
