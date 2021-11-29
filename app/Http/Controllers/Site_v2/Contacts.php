<?php 
namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Contacts extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = trans('site_v2.t_contacts');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_contacts';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }
        
        $conteudosContact = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosContact as $val) {
            switch ($val->tag) {
                case 'sect_contact_adress_'.$lang:
                    $adress = $val->value;
                    break;

                case 'sect_contact_tlm':
                    $tlm = $val->value;
                    break;

                case 'sect_contact_form_tit_'.$lang:
                    $contact_tit = $val->value;
                    break;

                case 'sect_contact_form_terms_'.$lang:
                    $contact_terms = $val->value;
                    break;
            }
        }

        $contacts = [
        	'adress_txt' => $adress,
        	'tlm_txt' => $tlm,
        	'contact_tit' => $contact_tit,
        	'contact_terms' => $contact_terms
        ];
        $this->dados['contacts'] = $contacts;

        return view('site_v2/pages/contacts',$this->dados);
    }
    public function sendcontact(Request $request){

        $name = $request->name;
        $email = strtolower($request->email);
        $mensage = $request->mensage;
        $termos_cond = $request->termos_cond;

        if (empty($name)) {return trans('site_v2.Field_name_txt');}
        if (empty($email)) {return trans('site_v2.Field_email_txt');}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail'); }
        if (empty($mensage)) {return trans('site_v2.Field_mesage_txt');}
        if (empty($termos_cond)) {return trans('site_v2.Agree_terms_txt');}


        \DB::table('contactos')->insert([
            'nome' => $name,
            'email' => $email,
            'mensagem' => $mensage,
            'data' => \Carbon\Carbon::now()->timestamp
        ]);

        $dados = [  'nome' => $name,
                    'email' => $email,
                    'mensagem' => $mensage ];

        \Mail::send('site_v2.emails.pages.new-contact',['dados' => $dados], function($message) use ($request){

            $message->to($request->email,'Universal')->subject('Novo Contacto www.universal.com.pt');
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' =>'Obrigado! Entraremos em contacto brevemente.'
        ];
        return json_encode($resposta,true);
    }   
}




