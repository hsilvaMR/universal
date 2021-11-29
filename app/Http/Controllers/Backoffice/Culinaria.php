<?php 
namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Culinaria extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = 'Culinária';
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'culinaria';

        $this->dados['codigos'] = \DB::table('culinaria_codigo')->get();

        return view('backoffice/pages/culinaria_codigo_all',$this->dados);
    }

    public function newCode(){
        $this->dados['headTitulo']='Culinária';
        $this->dados['separador']="culinaria";
        $this->dados['funcao']="new";

        return view('backoffice/pages/culinaria-codigo-add', $this->dados);

    }

    public function editCode($id){
        $this->dados['headTitulo']='Culinária';
        $this->dados['separador']="culinaria";
        $this->dados['funcao']="edit";

        $this->dados['codigo'] = \DB::table('culinaria_codigo')->where('id',$id)->first();  
        return view('backoffice/pages/culinaria-codigo-add', $this->dados);

    }

    public function ordersAll(){
        $this->dados['headTitulo']='Culinária';
        $this->dados['separador']="culinaria_enc";
        $this->dados['funcao']="new";

        $this->dados['encomenda'] = \DB::table('culinaria_encomenda')->get();

        return view('backoffice/pages/culinaria-orders-all', $this->dados);

    }

    public function newOrder(){
        $this->dados['headTitulo']='Culinária';
        $this->dados['separador']="culinaria_enc";
        $this->dados['funcao']="new";

        return view('backoffice/pages/culinaria-orders-add', $this->dados);

    }

    public function editOrder($id){
        $this->dados['headTitulo']='Culinária';
        $this->dados['separador']="culinaria_enc";
        $this->dados['funcao']="edit";

        $this->dados['encomenda'] = \DB::table('culinaria_encomenda')->where('id',$id)->first();  
        $this->dados['codigo'] = \DB::table('culinaria_codigo')->where('id',$this->dados['encomenda']->id_codigo)->first();  
        return view('backoffice/pages/culinaria-orders-add', $this->dados);

    }

    
    public function formOrder(Request $request){
        $id = trim($request->id);
        $estado = trim($request->estado);

        $nome = trim($request->nome);
        $email = trim($request->email);
        $contacto = trim($request->contacto);
        $morada = trim($request->morada);
        $mensagem = trim($request->mensagem);
        $codigo = trim($request->codigo);
        $numero_porta = trim($request->numero_porta);
        $codigo_postal = trim($request->codigo_postal);
        $localidade = trim($request->localidade);

        $content_primeiro = preg_replace('~\s*<br ?/?>\s*~',"<br>",$mensagem);
        $content_primeiro = nl2br($content_primeiro);
        
        $promocao_bd = \DB::table('culinaria_codigo')->where('codigo',$codigo)->first();
        $id_codigo = NULL;

        if (isset($promocao_bd->id)) {
            $id_codigo = $promocao_bd->id;
        }

        if($id){
            \DB::table('culinaria_encomenda')->where('id',$id)->update([
                'estado' => $estado
            ]); 
        }else{
           
            $id = \DB::table('culinaria_encomenda')->insertGetId([
                'id_codigo' => $id_codigo,
                'nome' => $nome,
                'email' => $email,
                'contacto' => $contacto,
                'morada' => $morada,
                'numero_porta' => $numero_porta,
                'codigo_postal' => $codigo_postal,
                'localidade' => $localidade,
                'mensagem' => $content_primeiro,
                'estado' => $estado,
                'data' => strtotime(date('Y-m-d H:i:s'))
            ]); 
        }
        

        $resposta = [
            'estado' => 'sucesso',
            'id' => $id
        ];
        return json_encode($resposta,true);
        
    }
    
    

    public function formCode(Request $request){
        $id = trim($request->id);
        $codigo = trim($request->codigo);
        $descricao = trim($request->descricao);
        $data_inicio = trim($request->data_inicio) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_inicio).' 00:00:00')->timestamp : \Carbon\Carbon::now()->timestamp;
        $data_fim = trim($request->data_fim) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_fim).' 23:59:59')->timestamp : \Carbon\Carbon::now()->timestamp;
        $online = (isset($request->online)) ? 1 : 0;

        if ($id) {
            \DB::table('culinaria_codigo')->where('id',$id)->update([
                'codigo' => $codigo,
                'descricao' => $descricao,
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'online' => $online
            ]); 
        }else{
            $id = \DB::table('culinaria_codigo')->insertGetId([
                'codigo' => $codigo,
                'descricao' => $descricao,
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'online' => $online
            ]);
        }

        $resposta = [
            'estado' => 'sucesso',
            'id' => $id
        ];
        return json_encode($resposta,true);


    }

  
   
   
}




