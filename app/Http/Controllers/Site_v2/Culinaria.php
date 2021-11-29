<?php 
namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Culinaria extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = trans('site_v2.t_culinaria');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_encomenda';

        return view('site_v2/pages/culinaria',$this->dados);
    }

    public function formCodigo(Request $request){
        $codigo = $request->codigo;

        $codigo_bd = \DB::table('culinaria_codigo')->where('codigo',$codigo)->where('online',1)->first();

        if (isset($codigo_bd->id)) {
            $resposta = [
               'estado' => 'sucesso',
               'mensagem' => $codigo_bd->descricao
            ];
        }else{
            $resposta = [
               'estado' => 'erro'
            ];
        }

        return json_encode($resposta,true);
    }

    public function form(Request $request){

    	$nome = trim($request->nome);
        $email = strtolower($request->email);
        $morada = trim($request->morada);
        $numero_porta = trim($request->numero_porta);
        $localidade = trim($request->localidade);
        $contacto = trim($request->contacto);
        $codigo_postal = trim($request->codigo_postal);
        $mensagem = trim($request->mensagem);





        if (empty($nome)) {return trans('site_v2.Field_name_txt');}
        if (empty($email)) {return trans('site_v2.Field_email_txt');}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail'); }
        if (empty($morada)) {return 'Campo morada por preencher.';}
        if (empty($numero_porta)) {return 'Campo número da porta por preencher.';}
        if (empty($codigo_postal)) {return 'Campo código postal por preencher.';}
        if (empty($localidade)) {return 'Campo localidade por preencher.';}
        if (empty($contacto)) {return 'Campo contacto por preencher.';}
        if (empty($mensagem)) {return trans('site_v2.Field_mesage_txt');}

        $promocao = $request->promocao;
        $promocao_bd = \DB::table('culinaria_codigo')->where('codigo',$promocao)->where('online',1)->first();

        $id_codigo = NULL;
        $codigo_bd = 'O cliente não utilizou código promocional.';
        $codigo_descricao = '';

        if (isset($promocao_bd->id)) {
            $id_codigo = $promocao_bd->id;
            $codigo_bd = $promocao_bd->codigo;
            $codigo_descricao = $promocao_bd->descricao;
        }

        $content_primeiro = preg_replace('~\s*<br ?/?>\s*~',"<br>",$mensagem);
        $content_primeiro = nl2br($content_primeiro);
        
        \DB::table('culinaria_encomenda')
            ->insert([
                'id_codigo' => $id_codigo,
                'nome' => $nome,
                'email' => $email,
                'contacto' => $contacto,
                'morada' => $morada,
                'numero_porta' => $numero_porta,
                'codigo_postal' => $codigo_postal,
                'localidade' => $localidade,
                'mensagem' => $content_primeiro,
                'estado' => 'realizada',
                'data' => strtotime(date('Y-m-d H:i:s'))
            ]);


        //ENVIAR EMAIL PARA A UNIVERSAL
        $dados = [ 
            'nome' => $nome,
            'email' => $email,
            'contacto' => $contacto,
            'morada' => $morada,
            'numero_porta' => $numero_porta,
            'codigo_postal' => $codigo_postal,
            'localidade' => $localidade,
            'mensagem' => $mensagem,
            'codigo' => $codigo_bd,
            'codigo_descricao' => $codigo_descricao
        ];

        \Mail::send('site_v2.emails.pages.culinaria_encomenda',['dados' => $dados], function($message) use ($request){
            $message->to('encomendas@universal.com.pt','UNIVERSAL')->subject(trans('site_v2.t_culinaria'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });

        \Mail::send('site_v2.emails.pages.culinaria_encomenda',['dados' => $dados], function($message) use ($request){
            $message->to($request->email,$request->nome)->subject(trans('site_v2.t_culinaria'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });


        $resposta = [
           'estado' => 'sucesso'
        ];
        return json_encode($resposta,true);

    }
   
}




