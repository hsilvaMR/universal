<?php namespace App\Http\Controllers\Site_old;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\Mail;

class Home extends Controller{

    public function postContacto(Request $request){

        $nome = trim($request->cf_name);
        $email = trim($request->cf_email);
        $mensagem = trim($request->cf_message);

         $dados = [
            'nome' => $nome,
            'email'=> $email,
            'mensagem' => $mensagem
        ];

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' =>'Obrigado! Entraremos em contacto consigo brevemente.'
        ];

        \Mail::send('site_old.emails.pages.mail',['dados' => $dados], function($message){
            
            $message->to('site@universal.com.pt','Universal')->subject('Novo contacto univesal.pt');//nuno santos
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);

        });



        return json_encode($resposta,true);

    }


}

