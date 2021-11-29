<?php 
namespace App\Http\Controllers\Site_v2;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Mail;
use Cookie;

class Home extends Controller{

    private $dados = [];

    public function index(){

        //$this->dados['headTitulo'] = trans('site_v2.t_home');
        //$this->dados['headDescricao'] = trans('site_v2.d_home');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['faceTipo'] = 'website';
        $this->dados['separador'] = 'home';
        if (Cookie::get('cookie_user_id')) {
            $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        }
        

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        if (isset($utilizadores) && Cookie::get('cookie_user_id')) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosQuery = \DB::table('conteudos_site')->get();

        $sect1_home_txt=$sect1_home_bt=$sect2_home_cheese_tit=$sect2_home_cheese_txt=$sect2_home_cheese_bt=$sect2_home_butter_tit=$sect2_home_butter_txt=$sect2_home_butter_bt=$sect3_home_news_txt='';

        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {

                case 'sect1_home_txt_'.$lang:
                    $sect1_home_txt = $val->value;
                    break;
                    
                case 'sect1_home_bt_'.$lang:
                    $sect1_home_bt = $val->value;
                    break;

                case 'sect2_home_cheese_tit_'.$lang:
                    $sect2_home_cheese_tit = $val->value;
                    break;

                case 'sect2_home_cheese_txt_'.$lang:
                    $sect2_home_cheese_txt = $val->value;
                    break;

                case 'sect2_home_cheese_bt_'.$lang:
                    $sect2_home_cheese_bt = $val->value;
                    break;

                case 'sect2_home_butter_tit_'.$lang:
                    $sect2_home_butter_tit = $val->value;
                    break;

                case 'sect2_home_butter_txt_'.$lang:
                    $sect2_home_butter_txt = $val->value;
                    break;

                case 'sect2_home_butter_bt_'.$lang:
                    $sect2_home_butter_bt = $val->value;
                    break;

                case 'sect3_home_news_txt_'.$lang:
                    $sect3_home_news_txt = $val->value;
                    break;
            }       
        }

        $conteudos = [
            'sect1_home_txt' => $sect1_home_txt,
            'sect1_home_bt' => $sect1_home_bt,
            'sect2_home_cheese_tit' => $sect2_home_cheese_tit,
            'sect2_home_cheese_txt' => $sect2_home_cheese_txt,
            'sect2_home_cheese_bt' => $sect2_home_cheese_bt,
            'sect2_home_butter_tit' => $sect2_home_butter_tit,
            'sect2_home_butter_txt' => $sect2_home_butter_txt,
            'sect2_home_butter_bt' => $sect2_home_butter_bt,
            'sect3_home_news_txt' => $sect3_home_news_txt
        ];

        $this->dados['conteudos'] = $conteudos;

        $slideQuery = \DB::table('conteudos_slide')
                      ->where('online','1')
                      ->orderBy('ordem','ASC')->get();

        $slide=[];
        $tit = 'titulo_'.$lang;
        $txt = 'texto_'.$lang;
        $bt = 'bt_texto_'.$lang;
        foreach ($slideQuery as $val) {

            $slide[] = [
                'id' => $val->id,
                'titulo' => $val->$tit,
                'texto' => $val->$txt,
                'bt_texto' => $val->$bt,
                'img' => $val->img,
                'img_xs' => $val->img_xs,
                'fundo_cor' => $val->fd_cor,
                'url' => $val->url,
                'tipo' => $val->tipo,
                'data_insercao' => ($val->data) ? date('Y-m-d',$val->data) : '' 
            ];
        }

        //return $slide;

        $this->dados['conteudo_slide'] = $slide;

        return view('site_v2/pages/home',$this->dados);
    }

    public function subscrever(Request $request){
    	$email_subscrever = trim($request->news);

    	if(!filter_var($email_subscrever, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail'); }


	    $dados = [ 'email_subscrever' => $email_subscrever ];


        Mail::send('site_v2.emails.pages.newsletter',['dados' => $dados], function($message) use ($request){

            $message->to($request->email_subscrever,'')->subject('site_v2.New_Subscriber_txt');
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);

        });

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' =>trans('site_v2.Send_successfully')
        ];
        return json_encode($resposta,true);
    }

    public function pageProduct($tipo){
        if ($tipo=='butter') {
            $this->dados['headTitulo'] = trans('site_v2.t_butter');
        }
        else{
           $this->dados['headTitulo'] = trans('site_v2.t_chesse');
        }
        
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_product';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}
        
        $produto = \DB::table('conteudos_produto')->where('tipo',$tipo)->get(); 
        $section1_img = '';
        $section1_tit = '';
        $section1_txt = '';
        $section1_bt = '';
        $section2_img = '';
        $section2_tit = '';
        $section2_txt = '';
        $section2_li = '';
        $section3_img = '';
        $section3_fd = '';
        $section3_tit = '';
        $section3_txt = '';
        $section3_li = '';
        $energ_min = '';
        $energ_inter = '';
        $energ_max = '';
        $lipidos_min = '';
        $lipidos_inter = '';
        $lipidos_max = '';
        $lipidos_sat_min = '';
        $lipidos_sat_inter = '';
        $lipidos_sat_max = '';
        $hidratos_min = '';
        $hidratos_inter = '';
        $hidratos_max = '';
        $hidratos_acu_min = '';
        $hidratos_acu_inter = '';
        $hidratos_acu_max = '';
        $proteinas_min = '';
        $proteinas_inter = '';
        $proteinas_max = '';
        $sal_min = '';
        $sal_inter = '';
        $sal_max = '';
        $fibras_min = '';
        $fibras_max = '';

        foreach ($produto as $val) {

            switch ($val->tag) {
                case 'section1_img':
                    $section1_img = $val->value;
                    break;

                case 'section1_tit_'.$lang:
                    $section1_tit = $val->value;
                    break;

                case 'section1_txt_'.$lang:
                    $section1_txt = $val->value;
                    break;

                case 'section1_bt_'.$lang:
                    $section1_bt = $val->value;
                    break;

                case 'section2_img':
                    $section2_img = $val->value;
                    break;

                case 'section2_tit_'.$lang:
                    $section2_tit = $val->value;
                    break;

                case 'section2_txt_'.$lang:
                    $section2_txt = $val->value;
                    break;

                case 'section2_li_'.$lang:
                    $section2_li = $val->value;
                    break;

                case 'section3_img':
                    $section3_img = $val->value;
                    break;
                
                case 'section3_fd':
                    $section3_fd = $val->value;
                    break;

                case 'section3_tit_'.$lang:
                    $section3_tit = $val->value;
                    break;

                case 'section3_txt_'.$lang:
                    $section3_txt = $val->value;
                    break;

                case 'section3_li_'.$lang:
                    $section3_li = $val->value;
                    break;

                case 'energ_min':
                    $energ_min = $val->value;
                    break;

                case 'energ_inter':
                    $energ_inter = $val->value;
                    break;

                case 'energ_max':
                    $energ_max = $val->value;
                    break;

                case 'lipidos_min':
                    $lipidos_min = $val->value;
                    break;

                case 'lipidos_inter':
                    $lipidos_inter = $val->value;
                    break;

                case 'lipidos_max':
                    $lipidos_max = $val->value;
                    break;

                case 'lipidos_sat_min':
                    $lipidos_sat_min = $val->value;
                    break;

                case 'lipidos_sat_inter':
                    $lipidos_sat_inter = $val->value;
                    break;

                case 'lipidos_sat_max':
                    $lipidos_sat_max = $val->value;
                    break;

                case 'hidratos_min':
                    $hidratos_min = $val->value;
                    break;

                case 'hidratos_inter':
                    $hidratos_inter = $val->value;
                    break;

                case 'hidratos_max':
                    $hidratos_max = $val->value;
                    break;

                case 'hidratos_acu_min':
                    $hidratos_acu_min = $val->value;
                    break;

                case 'hidratos_acu_inter':
                    $hidratos_acu_inter = $val->value;
                    break;

                case 'hidratos_acu_max':
                    $hidratos_acu_max = $val->value;
                    break;

                case 'proteinas_min':
                    $proteinas_min = $val->value;
                    break;

                case 'proteinas_inter':
                    $proteinas_inter = $val->value;
                    break;

                case 'proteinas_max':
                    $proteinas_max = $val->value;
                    break;

                case 'sal_min':
                    $sal_min = $val->value;
                    break;

                case 'sal_inter':
                    $sal_inter = $val->value;
                    break;

                case 'sal_max':
                    $sal_max = $val->value;
                    break;

                case 'fibras_min':
                    $fibras_min = $val->value;
                    break;
                case 'fibras_max':
                    $fibras_max = $val->value;
                    break;
            }
        }

        $array_produto = [
            'tipo' => $tipo,
            'section1_img' => $section1_img,
            'section1_tit' => $section1_tit,
            'section1_txt' => $section1_txt,
            'section1_bt' => $section1_bt,
            'section2_img' => $section2_img,
            'section2_tit' => $section2_tit,
            'section2_txt' => $section2_txt,
            'section2_li' => $section2_li,
            'section3_img' => $section3_img,
            'section3_fd' => $section3_fd,
            'section3_tit' => $section3_tit,
            'section3_txt' => $section3_txt,
            'section3_li' => $section3_li,
            'energ_min' => $energ_min,
            'energ_inter' => $energ_inter,
            'energ_max' => $energ_max,
            'lipidos_min' => $lipidos_min,
            'lipidos_inter' => $lipidos_inter,
            'lipidos_max' => $lipidos_max,
            'lipidos_sat_min' => $lipidos_sat_min,
            'lipidos_sat_inter' => $lipidos_sat_inter,
            'lipidos_sat_max' => $lipidos_sat_max,
            'hidratos_min' => $hidratos_min,
            'hidratos_inter' => $hidratos_inter,
            'hidratos_max' => $hidratos_max,
            'hidratos_acu_min' => $hidratos_acu_min,
            'hidratos_acu_inter' => $hidratos_acu_inter,
            'hidratos_acu_max' => $hidratos_acu_max,
            'proteinas_min' => $proteinas_min,
            'proteinas_inter' => $proteinas_inter,
            'proteinas_max' => $proteinas_max,
            'sal_min' => $sal_min,
            'sal_inter' => $sal_inter,
            'sal_max' => $sal_max,
            'fibras_min' => $fibras_min,
            'fibras_max' => $fibras_max
        ];

        $this->dados['array_produto'] = $array_produto;
        return view('site_v2/pages/product',$this->dados);
    }

    public function pageUniversal(){
        $this->dados['headTitulo'] = trans('site_v2.t_a_universal');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_universal';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosQuery = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {
                case 'sect_universal_tit_'.$lang:
                    $universal_tit = $val->value;
                    break;

                case 'sect_universal_txt_'.$lang:
                    $universal_txt = $val->value;
                    break;

                case 'sect_universal_bg':
                    $universal_bg = $val->value;
                    break;

                case 'sect_universal_logo':
                    $universal_logo = $val->value;
                    break;

                case 'sect_universal_info_'.$lang:
                    $universal_info = $val->value;
                    break;

                case 'sect_universal_bt_'.$lang:
                    $universal_bt = $val->value;
                    break;
            }
        }

        $universal = [
            'universal_tit' => $universal_tit,
            'universal_txt' => $universal_txt,
            'universal_bg' => $universal_bg,
            'universal_logo' => $universal_logo,
            'universal_info' => $universal_info,
            'universal_bt' => $universal_bt
        ];
        $this->dados['universal'] = $universal;
        return view('site_v2/pages/historyUniversal',$this->dados);
    }


    
    public function pageInnovation(){
        $this->dados['headTitulo'] = trans('site_v2.t_invovation');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_innovation';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosQuery = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {
                case 'sect_innovation_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $innovation = ['innovation_txt' => $txt];
        $this->dados['innovation'] = $innovation;

        return view('site_v2/pages/innovation',$this->dados);
    }

    public function pageQualification(){
        $this->dados['headTitulo'] = trans('site_v2.t_qualification');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_qualification';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }
        
        $conteudosQuery = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {
                case 'sect_qualification_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $qualification = ['qualification_txt' => $txt];
        $this->dados['qualification'] = $qualification;

        return view('site_v2/pages/qualification',$this->dados);
    }

    public function pageInternationalization(){
        $this->dados['headTitulo'] = trans('site_v2.t_internationalization');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_internationalization';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosQuery = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {
                case 'sect_internationalization_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $internationalization = ['internationalization_txt' => $txt];
        $this->dados['internationalization'] = $internationalization;

        return view('site_v2/pages/internationalization',$this->dados);
    }

    public function pageTerms(){
        $this->dados['headTitulo'] = trans('site_v2.t_terms');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_terms';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }
        else{
           $this->dados['nome'] = ''; 
        }

        $conteudosTerms = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosTerms as $val) {
            switch ($val->tag) {
                case 'sect_terms_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $terms = ['terms_txt' => $txt];
        $this->dados['terms'] = $terms;
   
        return view('site_v2/pages/terms',$this->dados);
    }

    public function pageSecurity(){
        $this->dados['headTitulo'] = trans('site_v2.t_security');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_security';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosSecurity = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosSecurity as $val) {
            switch ($val->tag) {
                case 'sect_security_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $security = ['security_txt' => $txt];
        $this->dados['security'] = $security;

        return view('site_v2/pages/termSecurity',$this->dados);
    }

    public function pagePrivacy(){
        $this->dados['headTitulo'] = trans('site_v2.t_privacy');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_privacy';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosPrivacy = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosPrivacy as $val) {
            switch ($val->tag) {
                case 'sect_privacy_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $privacy = ['privacy_txt' => $txt];
        $this->dados['privacy'] = $privacy;

        return view('site_v2/pages/termPrivacy',$this->dados);
    }

    public function pageCookies(){
        $this->dados['headTitulo'] = trans('site_v2.t_cookies');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_cookies';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();


        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $conteudosCookies = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosCookies as $val) {
            switch ($val->tag) {
                case 'sect_cookies_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $cookies = ['cookies_txt' => $txt];
        $this->dados['cookies'] = $cookies;

        return view('site_v2/pages/termCookies',$this->dados);
    }

    public function subscreveSite(Request $request){

        $email = filter_var(strtolower(trim($request->newsletter)), FILTER_VALIDATE_EMAIL);
        $list_id = 'a81f0f2aac';
        $api_key = '68a75c0a07d2181efb5e8f2215c9806c-us20';
        $status = 'subscribed';
        $merge_fields = array('FNAME' => '','LNAME' => '');

        $data = array(
            'apikey'        => $api_key,
            'email_address' => $email,
            'status'        => $status,
            'merge_fields'  => $merge_fields
        );
        $mch_api = curl_init(); // initialize cURL connection
     
        curl_setopt($mch_api, CURLOPT_URL, 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address'])));
        curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.base64_encode( 'user:'.$api_key )));
        curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
        curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
        curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch_api, CURLOPT_POST, true);
        curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
     
        $result = curl_exec($mch_api);

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' => trans('site_v2.Successfully_subscribed'),
        ];
        
        return json_encode($resposta,true);
    } 


    public function avisoLegal(){
        $this->dados['headTitulo'] = trans('site_v2.t_legal');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_aviso';


        return view('site_v2/pages/aviso-legal',$this->dados);
    }

    public function assinaturaOverseas(){
        $this->dados['headTitulo'] = 'Overseas - Universal';
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_af';


        return view('assinaturas/afmachado',$this->dados);
    }

    

}