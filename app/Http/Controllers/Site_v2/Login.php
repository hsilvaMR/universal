<?php 
namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Hash;
use Validator;
use Mail;
use Cookie;

class Login extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = trans('site_v2.t_login');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_login';

        Cookie::queue(Cookie::make('job', 'sim', 43200));

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

        $conteudosLogin = \DB::table('conteudos_site')->get();
        
        foreach ($conteudosLogin as $val) {
            switch ($val->tag) {
                case 'sect_login_tit_'.$lang:
                    $tit = $val->value;
                    break;

                case 'sect_login_txt_'.$lang:
                    $txt = $val->value;
                    break;

                case 'sect_login_form_tit_'.$lang:
                    $tit_form = $val->value;
                    break;
            }
        }

        $login = [
            'login_tit' => $tit,
            'login_txt' => $txt,
            'login_register_tit' => $tit_form
        ];
        
        $this->dados['login'] = $login;
        return view('site_v2/pages/login',$this->dados);
    }

    public function formLogin(Request $request){

        $email = trim($request->email);
        $password = trim($request->password);

        if(empty($email)) {return trans('site_v2.Field_email_txt');}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail');}
        if(empty($password)) { return trans('site_v2.Field_password_txt');}
        if(strlen($password) < 6) { return trans('site_v2.Field_pass_txt');}

        $user = \DB::table('utilizadores')
                    ->where('email', $email)
                    ->first();

        $comerciante = \DB::table('comerciantes')
                            ->where('email', $email)
                            ->first();


        if($user){
           
            if(!Hash::check($request->password, $user->password)){ return trans('site_v2.incorrect_password'); }

            $nome_partes = explode(" ", $user->nome);

            $cart = \DB::table('carrinho_linha')
                        ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                        ->where('carrinho.id_utilizador', $user->id)
                        ->where('carrinho.estado','pendente')
                        ->sum('carrinho_linha.quantidade');
         
            Cookie::queue(Cookie::make('cookie_user_id',$user->id,43200));
            Cookie::queue(Cookie::make('cookie_user_token',$user->token,43200));
            Cookie::queue(Cookie::make('cookie_user_name',$nome_partes[0],43200));
            Cookie::queue(Cookie::make('cookie_user_photo',$user->foto,43200));
            Cookie::queue(Cookie::make('cookie_user_points',$user->pontos,43200));
            Cookie::queue(Cookie::make('cookie_user_cart',$cart,43200));
            Cookie::queue(Cookie::make('cookie_user_status',$user->estado,43200));


            $resposta = [
               'estado' => 'sucesso',
               'mensagem' =>trans('site_v2.Login_successfully')
            ];
            return json_encode($resposta,true);
        }
        elseif($comerciante){

            $company = \DB::table('empresas')->where('id', $comerciante->id_empresa)->first();
            $count_comerc = \DB::table('comerciantes')->where('id_empresa',$company->id)->count();
            
            if($comerciante->estado == 'pendente'){
                if ($count_comerc > 1) {
                    \DB::table('comerciantes')->where('id',$comerciante->id)
                        ->update([
                            'estado' => 'ativo',
                            'email_alteracao' => '',
                            'token_alteracao' => ''
                        ]);
                }
            }

            if($email == $comerciante->email_alteracao){
                \DB::table('comerciantes')->where('id',$comerciante->id)
                    ->update([
                        'email_alteracao' => '',
                        'token_alteracao' => ''
                    ]);
            }

            $notificacaoes_ativas = \DB::table('notificacoes')
                                        ->where('id_notificado',$comerciante->id)
                                        ->where('vista',0)
                                        ->count();
            
            if(!Hash::check($request->password, $comerciante->password)){ return trans('site_v2.incorrect_password'); }
           
            $nome_partes = explode(" ", $comerciante->nome);

            Cookie::queue(Cookie::make('cookie_comerc_id',$comerciante->id,43200));
            Cookie::queue(Cookie::make('cookie_comerc_id_empresa',$comerciante->id_empresa,43200));
            Cookie::queue(Cookie::make('cookie_comerc_token',$comerciante->token,43200));
            Cookie::queue(Cookie::make('cookie_comerc_name',$nome_partes[0],43200));
            Cookie::queue(Cookie::make('cookie_comerc_photo',$comerciante->foto,43200));
            Cookie::queue(Cookie::make('cookie_company_photo',$company->logotipo,43200));
            Cookie::queue(Cookie::make('cookie_company_name',$company->nome,43200));
            Cookie::queue(Cookie::make('cookie_company_status',$company->estado,43200));
            Cookie::queue(Cookie::make('cookie_comerc_type',$comerciante->tipo,43200));
            Cookie::queue(Cookie::make('cookie_company_points',$company->pontos,43200));
            Cookie::queue(Cookie::make('cookie_not_ative',$notificacaoes_ativas,43200));


            $resposta = [
               'estado' => 'sucesso_comerciante',
               'estado_comerc' => $comerciante->estado,
               'mensagem' =>trans('site_v2.Login_successfully')
            ];
            return json_encode($resposta,true);
        }
        else{ return trans('site_v2.accountNotRegister');}
    }

    public function sendEmailPassword(Request $request){

        $email=trim($request->email);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail'); }
        
        $user = \DB::table('utilizadores')->where('email',$email)->first();
        $comerciante = \DB::table('comerciantes')->where('email',$email)->first();

        if(isset($user->id) || isset($comerciante->id)){

            $token = str_random(12);
            \DB::table('utilizadores_pass')
               ->insert(['email'=>$email,
                         'token' => $token,
                         'data'=>strtotime(date('Y-m-d H:i:s'))
            ]);

            $dados = [ 'token'=>$token ];

            \Mail::send('site_v2.emails.pages.retrievePassword',['dados' => $dados], function($message) use ($request){

                $message->to($request->email,'')->subject(trans('site_v2.Retrieve_Password'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });

            $resposta = [ 'estado' => 'sucesso'];
            return json_encode($resposta,true);
        }
        return trans('site_v2.No_existent_email');
    }

    public function restorePassword($token){

        $linhaToken = \DB::table('utilizadores_pass')->where('token',$token)->first();

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $conteudosLogin = \DB::table('conteudos_site')->get();
        
        foreach ($conteudosLogin as $val) {
            switch ($val->tag) {
                case 'sect_login_tit_'.$lang:
                    $tit = $val->value;
                    break;

                case 'sect_login_txt_'.$lang:
                    $txt = $val->value;
                    break;

                case 'sect_login_form_tit_'.$lang:
                    $tit_form = $val->value;
                    break;
            }
        }

        $login = [
            'login_tit' => $tit,
            'login_txt' => $txt,
            'login_register_tit' => $tit_form
        ];
        
        $this->dados['login'] = $login;

        if(isset($linhaToken->id)){
            $this->dados['headTitulo'] = trans('site_v2.t_restore_pass');
            $this->dados['headDescricao'] = trans('site_v2.d_cheese');
            $this->dados['separador'] = 'page_restore_pass';


            $this->dados['token']=$token;
            return view('site_v2/pages/restorePassword', $this->dados);
        }else{
            return redirect()->route('loginPageV2');
        }
    }

    public function restorePasswordForm(Request $request){


        $token=trim($request->token);
        $password = trim($request->password);
        //$password = bcrypt($request->password);

        if(strlen($password) < 6 || empty($password)){ return trans('site_v2.Field_pass_txt'); }

        $linhaToken = \DB::table('utilizadores_pass')->where('token',$token)->first();
        $comerciante = \DB::table('comerciantes')->where('email',$linhaToken->email)->first();

        if(isset($linhaToken->id)){
       
            \DB::table('utilizadores')
                ->where('email',$linhaToken->email)
                ->update([
                    'password' => bcrypt($password),
                    'estado' => 'ativo'
            ]);


            \DB::table('comerciantes')
                ->where('email',$linhaToken->email)
                ->update([
                    'password' => bcrypt($password),
                    'estado' => 'ativo'
            ]);
              
            \DB::table('utilizadores_pass')->where('email',$linhaToken->email)->delete();

            $resposta = [ 'estado' => 'sucesso' ];
            return json_encode($resposta,true);
        }
        else{ return trans('site_v2.invalid_request');}
    }

    public function pagePending(){

        $this->dados['headTitulo'] = trans('site_v2.Universal');
        $this->dados['headDescricao'] = trans('site_v2.Validate_Account');
        $this->dados['separador'] = 'Page_Pendente';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        $comerciantes = \DB::table('comerciantes')->where('id', Cookie::get('cookie_comerc_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);
            Cookie::queue(Cookie::make('cookie_user_name',$string[0],43200));
        }
        if ($comerciantes) {
            $string = explode(" ", $comerciantes->nome);
            $empresa = \DB::table('empresas')->where('id', $comerciantes->id_empresa)->first();
        }
        

        $this->dados['nome'] = $string[0];

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','pendente')
                                                    ->sum('carrinho_linha.quantidade');

        $this->dados['utilizadores'] = $utilizadores;
        $this->dados['comerciantes'] = $comerciantes;

        if ($utilizadores) {
            if ($utilizadores->estado == 'pendente') { return view('site_v2/pages/pagePending', $this->dados);}
            else{ return redirect()->route('areaReservedV2');}
        }

        if ($comerciantes) {
            if ((($comerciantes->aprovacao == 'aprovado' && $comerciantes->estado == 'ativo') || 
                ($comerciantes->aprovacao == '' && $comerciantes->estado == 'ativo') || ($comerciantes->aprovacao == 'reprovado' && $comerciantes->estado == 'ativo'))) {
                return redirect()->route('personalDataV2');
            }else{
                return view('site_v2/pages/pagePending', $this->dados);
            }
            
            $this->dados['empresa'] = $empresa;
        }
        return redirect()->route('logoutPost');
    }

    public function formRegisterClient(){


        $this->dados['headTitulo'] = trans('site_v2.t_register_client');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_formClient';

        $conteudosRegister = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        foreach ($conteudosRegister as $val) {
            switch ($val->tag) {
                case 'sect_register_client_tit_'.$lang:
                    $tit = $val->value;
                    break;

                case 'sect_register_client_txt_'.$lang:
                    $txt = $val->value;
                    break;
            }
        }

        $register = [
            'register_tit' => $tit,
            'register_txt' => $txt
        ];

        $this->dados['register'] = $register;
        return view('site_v2/pages/formRegisterClient',$this->dados);
    }

    public function sendRegisterClient(Request $request){

        $nome = $request->nome;
        $email = $request->email;
        $password = $request->password;
        $termos_cond = $request->termos_cond;
        $token = str_random(12);
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}



        if(empty($nome)) {return trans('site_v2.Field_name_txt');}
        if (empty($email)) {return trans('site_v2.Field_email_txt');}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail');}
        if(strlen($password) < 6 || empty($password)) { return trans('site_v2.Field_pass_txt');}
        if(empty($termos_cond)){return trans('site_v2.Agree_terms_txt');}

        $utilizadores = \DB::table('utilizadores')
                            ->where('email', $email)
                            ->orWhere('email_alteracao', $email)
                            ->first();

        $comerciantes = \DB::table('comerciantes')
                            ->where('email', $email)
                            ->where('estado','<>','pendente')
                            ->first();

        $empresas = \DB::table('empresas')
                        ->where('email', $email)
                        ->first();

        $partes = explode(' ', $nome);
        $primeiroNome = array_shift($partes);
        $ultimoNome = array_pop($partes);

        if (empty($utilizadores) && empty($comerciantes)) {

            \DB::table('utilizadores')->insert([
                'nome' => $primeiroNome,
                'apelido' => $ultimoNome,
                'email' => $email,
                'password' => bcrypt($password),
                'data' => \Carbon\Carbon::now()->timestamp,
                'token' => $token,
                'estado' => 'pendente',
                'lingua' => $lang
            ]);

            //Enviar Email ao cliente para validação da conta!
            $dados = [ 'token' => $token ];
            \Mail::send('site_v2.emails.pages.validateAccount',['dados' => $dados], function($message) use ($request){

                $message->to($request->email,$request->nome)->subject(trans('site_v2.Validate_Account'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });
        }elseif(isset($comerciantes->id) || isset($empresas->id)){
            return trans('site_v2.ExistentEmailUser');
        }
        else{return trans('site_v2.ExistentEmail');}

        $resposta = [
         'estado' => 'sucesso',
         'mensagem' => trans('site_v2.RegisterSucess_txt')
        ];

        return json_encode($resposta,true);
    }

    public function formRegisterSeller(){
        $this->dados['headTitulo'] = trans('site_v2.t_register_seller');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_formSeller';

        $conteudosRegister = \DB::table('conteudos_site')->get();
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        

        if ($utilizadores) {
            $this->dados['utilizadores'] = $utilizadores;
            $string = explode(" ", $utilizadores->nome);
            $this->dados['nome'] = $string[0];
        }else{
            $this->dados['nome'] = '';
        }


        foreach ($conteudosRegister as $val) {
            switch ($val->tag) {
                case 'sect_register_seller_tit_'.$lang:
                    $tit = $val->value;
                    break;

                case 'sect_register_seller_txt_'.$lang:
                    $txt = $val->value;
                    break;

                case 'sect_register_seller_subtxt_'.$lang:
                    $subTxt = $val->value;
                    break;
            }
        }

        $register = [
            'register_tit' => $tit,
            'register_txt' => $txt,
            'register_subtxt' => $subTxt
        ];

        $this->dados['register'] = $register;
        return view('site_v2/pages/formRegisterSeller',$this->dados);
    }

    public function sendRegisterSeller(Request $request){

        $nome_resp = $request->nome_resp;
        $nome_empresa = $request->nome_empresa;
        $cae = $request->cae;
        $email = $request->email;
        $password = $request->password;
        $termos_cond = $request->termos_cond;
        $token = str_random(12);
        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        if(empty($nome_resp)) {return trans('site_v2.Field_name_resp_txt');}
        if(empty($nome_empresa)) {return trans('site_v2.Field_name_company_txt');}
        if(empty($cae)) {return trans('site_v2.Field_cae_txt');}
        if (empty($email)) {return trans('site_v2.Field_email_txt');}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail');}
        if(strlen($password) < 6 || empty($password)) { return trans('site_v2.Field_pass_txt');}
        if(empty($termos_cond)){return trans('site_v2.Agree_terms_txt');}


        $utilizadores = \DB::table('utilizadores')
                            ->where('email', $email)
                            ->where('estado','<>','pendente')
                            ->orWhere('email_alteracao', $email)
                            ->first();

        $comerciantes = \DB::table('comerciantes')
                            ->where('email', $email)
                            ->first();



        
        if (empty($utilizadores) && empty($comerciantes)) {
            
            $id_empresa = \DB::table('empresas')
                                ->insertGetId([
                                    'nome' => $nome_empresa,
                                    'email' => $email,
                                    'cae' => $cae,
                                    'data' => \Carbon\Carbon::now()->timestamp,
                                    'estado' => 'pendente'
                                ]);

            \DB::table('comerciantes')->insert([
                'token' => $token,
                'id_empresa' => $id_empresa,
                'nome' => $nome_resp,
                'email' => $email,
                'password' => bcrypt($password),
                'data_registo' => \Carbon\Carbon::now()->timestamp,
                'estado' => 'pendente',
                'lingua' => $lang
            ]);

            //Adicionar os produtos a empresa com uma margem de 40%
            $produtos = \DB::table('produtos')->where('online',1)->get();
            $margem_lucro = \DB::table('configuracoes')->where('tag','margem_lucro')->first();
         
            foreach ($produtos as $value) {
                if($value->preco_unitario){
                    
                    $lucro = ($margem_lucro->valor*$value->preco_unitario)/100;
                    $valor_produto = $value->preco_unitario + $lucro;

                    \DB::table('produtos_empresa')
                        ->insert([
                            'id_produto' => $value->id,
                            'id_empresa' => $id_empresa,
                            'valor' => $valor_produto
                        ]); 
                }
            }
            
            //Enviar Email ao cliente para validação da conta!
            $dados = [ 'token'=>$token ];
            \Mail::send('site_v2.emails.pages.validateAccount',['dados' => $dados], function($message) use ($request){

                $message->to($request->email,$request->nome_empresa)->subject(trans('site_v2.Validate_Account'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });
        }
        else{
           
            if(isset($utilizadores->id)){
                return trans('site_v2.ExistentEmailSeller');
            }
            else{
                return trans('site_v2.ExistentEmail');
            }
        }
        
        $resposta = [
         'estado' => 'sucesso',
         'mensagem' => trans('site_v2.RegisterSucess_txt')
        ];

        return json_encode($resposta,true);
    }

    public function resendValidation(Request $request){
    
        $id_uti = Cookie::get('cookie_user_id');
        $id_comerciante = Cookie::get('cookie_comerc_id');

        $utilizadores = \DB::table('utilizadores')
                        ->where('id', $id_uti)
                        ->first();

        $comerciante = \DB::table('comerciantes')
                        ->where('id', $id_comerciante)
                        ->first();

        if ($utilizadores) {

            $dados = [ 'token'=>$utilizadores->token ];
            \Mail::send('site_v2.emails.pages.validateAccount',['dados' => $dados], function($message) use ($utilizadores){
              
                $message->to($utilizadores->email,$utilizadores->nome)->subject(trans('site_v2.Validate_Account'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });
        }
        elseif($comerciante){

            $dados = [ 'token'=>$comerciante->token ];
            \Mail::send('site_v2.emails.pages.validateAccount',['dados' => $dados], function($message) use ($comerciante){
              
                $message->to($comerciante->email,$comerciante->nome)->subject(trans('site_v2.Validate_Account'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });
        }
        else{return trans('site_v2.ExistentEmail');}

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' => trans('site_v2.SuccessfullySubmitted')
        ];
        return json_encode($resposta,true);
    }

    public function validateAccount($token){

        $linhaToken_user = \DB::table('utilizadores')->where('token',$token)->first();
        $linhaToken_comerc = \DB::table('comerciantes')->where('token_alteracao',$token)->first();
        $linhaToken_comerc_new = \DB::table('comerciantes')->where('token',$token)->first();

        if (isset($linhaToken_comerc->id)) {
            $this->dados['headTitulo'] = trans('site_v2.Universal');
            $this->dados['headDescricao'] = trans('site_v2.Validate_Account');
            $this->dados['separador'] = trans('site_v2.Validate_Account');
            $this->dados['comerciante'] = $linhaToken_comerc->id;

            $this->dados['empresa'] = \DB::table('empresas')
                                      ->where('id', $linhaToken_comerc->id_empresa)
                                      ->first();

            //Eliminar utilizador pendente
            \DB::table('utilizadores')->where('email',$linhaToken_comerc->email)->delete();

            //Ativar Comerciante
            
            if(!empty($linhaToken_comerc->email_alteracao)){
             
                \DB::table('comerciantes')
                    ->where('token_alteracao',$token)
                    ->update([
                        'token_alteracao' => '',
                        'email_alteracao' => '',
                        'email' => $linhaToken_comerc->email_alteracao,
                        'estado' => 'ativo'
                    ]);
            }else{
                \DB::table('comerciantes')
                    ->where('token_alteracao',$token)
                    ->update([
                        'token_alteracao' => '',
                        'estado' => 'ativo'
                    ]);
            }
           
            return view('site_v2/pages/validateAccount', $this->dados);
        }

        if (isset($linhaToken_comerc_new->id)) {
            $this->dados['headTitulo'] = trans('site_v2.Universal');
            $this->dados['headDescricao'] = trans('site_v2.Validate_Account');
            $this->dados['separador'] = trans('site_v2.Validate_Account');
            $this->dados['comerciante'] = $linhaToken_comerc_new->id;

            $this->dados['empresa'] = \DB::table('empresas')
                                      ->where('id', $linhaToken_comerc_new->id_empresa)
                                      ->first();

            //Ativar Comerciante
            \DB::table('comerciantes')
                ->where('token',$token)
                ->update([
                    'estado' => 'ativo'
                ]);
           
            return view('site_v2/pages/validateAccount', $this->dados);
        }
        
        if(isset($linhaToken_user->id)){
            $this->dados['headTitulo'] = trans('site_v2.Universal');
            $this->dados['headDescricao'] = trans('site_v2.Validate_Account');
            $this->dados['separador'] = trans('site_v2.Validate_Account');

            //Eliminar Comerciante pendente
            //Contar os comerciantes que existem na empresa
            $comerc=\DB::table('comerciantes')->where('email',$linhaToken_user->email)->first();

            if(isset($comerc->id)){
                $count_comerc=\DB::table('comerciantes')->where('id_empresa',$comerc->id_empresa)->count();

                //Se for igual a 1 (Eliminar a empresa e o comerciante)
                if($count_comerc == 1){
                    \DB::table('empresas')->where('id',$comerc->id_empresa)->delete();
                    \DB::table('comerciantes')->where('email',$linhaToken_user->email)->delete();
                }
                else{
                    //Else eliminar só o comerciante
                    \DB::table('comerciantes')->where('email',$linhaToken_user->email)->delete();
                }
            }
            
            
            \DB::table('utilizadores')
                ->where('token',$token)
                ->update(['estado'=>'ativo']);

            if ($linhaToken_user->estado == 'ativo') {return redirect()->route('areaReservedV2');}
            else{return view('site_v2/pages/validateAccount', $this->dados); }
        }

        return redirect()->route('homePageV2'); 
    }

    public function newAccountGestor($token){

        $seller = \DB::table('comerciantes')->where('token_alteracao',$token)->first();


        \DB::table('comerciantes')
            ->where('token_alteracao',$token)
            ->update([
                'token_alteracao' => '',
                'estado' => 'ativo'
            ]);

        if(isset($seller->email)){
            \DB::table('comerciantes')->where('email',$seller->email)->where('estado','pendente')->delete();
        }
        return redirect()->route('loginPageV2'); 
    }


    public function logout(){

        Cookie::queue(Cookie::forget('cookie_user_id'));
        Cookie::queue(Cookie::forget('cookie_user_token'));
        Cookie::queue(Cookie::forget('cookie_user_name'));
        Cookie::queue(Cookie::forget('cookie_user_photo'));
        Cookie::queue(Cookie::forget('cookie_user_points'));
        Cookie::queue(Cookie::forget('cookie_user_cart'));
        Cookie::queue(Cookie::forget('cookie_user_status'));
        
        Cookie::queue(Cookie::forget('cookie_comerc_id'));
        Cookie::queue(Cookie::forget('cookie_comerc_token'));
        Cookie::queue(Cookie::forget('cookie_comerc_name'));
        Cookie::queue(Cookie::forget('cookie_comerc_photo'));
        Cookie::queue(Cookie::forget('cookie_comerc_id_empresa'));
        Cookie::queue(Cookie::forget('cookie_company_photo'));
        Cookie::queue(Cookie::forget('cookie_company_name'));
        Cookie::queue(Cookie::forget('cookie_company_status'));
        Cookie::queue(Cookie::forget('cookie_company_points'));
        Cookie::queue(Cookie::forget('cookie_comerc_type'));
        Cookie::queue(Cookie::forget('cookie_not_ative'));

        return redirect()->route('homePageV2');
    }
}