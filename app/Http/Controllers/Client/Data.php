<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;
use Hash;

class Data extends Controller{

    private $dados = [];

    public function index(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_area_reserved');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_data';


        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if (!empty($utilizadores->email_alteracao)){$this->dados['aviso'] = 'email_alteracao';}

        $string = explode(" ", $utilizadores->nome);

        if (date('Y-m-d',strtotime(date('Y-m-d',$utilizadores->ultimo_acesso). ' + 1  day')) < date('Y-m-d',strtotime(date('Y-m-d')))) {

            \DB::table('utilizadores')
                ->where('id', Cookie::get('cookie_user_id'))
                ->update([
                    'email_alteracao' => ''
                ]);

        }


        $this->dados['nome'] = $string[0];
        $this->dados['utilizadores'] = $utilizadores;

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        return view('client/pages/data',$this->dados);
    }

    public function adress(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_adress_area');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_adress';

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}


        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        if (!empty($utilizadores->email_alteracao)){$this->dados['aviso'] = 'email_alteracao';}

        if (date('Y-m-d',strtotime(date('Y-m-d',$utilizadores->ultimo_acesso). ' + 1  day')) < date('Y-m-d',strtotime(date('Y-m-d')))) {

            \DB::table('utilizadores')
                ->where('id', Cookie::get('cookie_user_id'))
                ->update([
                    'email_alteracao' => ''
                ]);

        }

        $this->dados['morada_faturacao'] = \DB::table('utilizadores_morada')
                                                ->select('utilizadores_morada.*','utilizadores_morada.codigo_postal AS code_fat')
                                                ->where('id_utilizador', Cookie::get('cookie_user_id'))
                                                ->where('utilizadores_morada.tipo','morada_faturacao')
                                                ->first();


        $this->dados['morada_entrega'] = \DB::table('utilizadores_morada')
                                            ->where('id_utilizador', Cookie::get('cookie_user_id'))
                                            ->where('utilizadores_morada.tipo','morada_entrega')
                                            ->first();



        $string = explode(" ", $utilizadores->nome);


        $this->dados['nome'] = $string[0];
        $this->dados['utilizadores'] = $utilizadores;

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        return view('client/pages/adress',$this->dados);
    }

    public function editAdressBilling(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_adress_invoice');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_adressBilling';

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}


        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        if (!empty($utilizadores->email_alteracao)){$this->dados['aviso'] = 'email_alteracao';}

        if (date('Y-m-d',strtotime(date('Y-m-d',$utilizadores->ultimo_acesso). ' + 1  day')) < date('Y-m-d',strtotime(date('Y-m-d')))) {

            \DB::table('utilizadores')
                ->where('id', Cookie::get('cookie_user_id'))
                ->update([
                    'email_alteracao' => ''
                ]);

        }

         $this->dados['morada_faturacao'] = \DB::table('utilizadores_morada')
                                                ->where('id_utilizador', Cookie::get('cookie_user_id'))
                                                ->where('utilizadores_morada.tipo','morada_faturacao')
                                                ->first();

        $string = explode(" ", $utilizadores->nome);


        $this->dados['nome'] = $string[0];
        $this->dados['utilizadores'] = $utilizadores;

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');
        return view('client/pages/adressBilling',$this->dados);
    }

    public function updateAdressB(Request $request){

        $name = trim($request->name);
        $email = filter_var(strtolower(trim($request->email)), FILTER_VALIDATE_EMAIL);
        $contacto = trim($request->contacto);
        $nif = trim($request->nif) ? trim($request->nif) : 0;
        $morada = trim($request->morada);
        $morada_opc = trim($request->morada_opc);
        $codigoPostal = trim($request->codigoPostal);
        $cidade = trim($request->cidade);


        if(!empty($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))){ return trans('site_v2.FormatInvalidEmail'); }

        $morada_faturacao = \DB::table('utilizadores_morada')
                                ->where('id_utilizador',Cookie::get('cookie_user_id'))
                                ->where('tipo','morada_faturacao')
                                ->first();

        if ($morada_faturacao) {

            \DB::table('utilizadores_morada')->where('id_utilizador',Cookie::get('cookie_user_id'))->where('tipo','morada_faturacao')
                ->update([
                    'nome' => $name,
                    'email' => $email,
                    'telefone' => $contacto,
                    'nif' => $nif,
                    'morada' => $morada,
                    'morada_opc' => $morada_opc,
                    'codigo_postal' => $codigoPostal,
                    'cidade' => $cidade,
                    'pais' => 'Portugal - Continental',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]);
        }else{
     
           \DB::table('utilizadores_morada')
                ->insert([
                    'id_utilizador' => Cookie::get('cookie_user_id'),
                    'nome' => $name,
                    'email' => $email,
                    'telefone' => $contacto,
                    'nif' => $nif,
                    'morada' => $morada,
                    'morada_opc' => $morada_opc,
                    'codigo_postal' => $codigoPostal,
                    'cidade' => $cidade,
                    'pais' => 'Portugal - Continental',
                    'tipo' => 'morada_faturacao',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]); 
        }

        $resposta = [ 'estado' => 'sucesso' ];

        return json_encode($resposta,true);

    }

    public function editAdressDelivery(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_adress_delivery');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_adressDelivery';

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}


        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        if (!empty($utilizadores->email_alteracao)){$this->dados['aviso'] = 'email_alteracao';}

        if (date('Y-m-d',strtotime(date('Y-m-d',$utilizadores->ultimo_acesso). ' + 1  day')) < date('Y-m-d',strtotime(date('Y-m-d')))) {

            \DB::table('utilizadores')
                ->where('id', Cookie::get('cookie_user_id'))
                ->update([
                    'email_alteracao' => ''
                ]);

        }

        $morada_entrega = \DB::table('utilizadores_morada')->where('id_utilizador', Cookie::get('cookie_user_id'))->where('tipo','morada_entrega')->first();

        $string = explode(" ", $utilizadores->nome);


        $this->dados['nome'] = $string[0];
        $this->dados['utilizadores'] = $utilizadores;
        $this->dados['morada_entrega'] = $morada_entrega;
        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        return view('client/pages/adressDelivery',$this->dados);
    }

    public function updateAdressD(Request $request){

        $name = trim($request->name);
        $email = filter_var(strtolower(trim($request->email)), FILTER_VALIDATE_EMAIL);
        $contacto = trim($request->contacto);
        $nif = trim($request->nif) ? trim($request->nif) : 0;
        $morada = trim($request->morada);
        $morada_opc = trim($request->morada_opc);
        $codigoPostal = trim($request->codigoPostal);
        $cidade = trim($request->cidade);

        if(!empty($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))){ return trans('site_v2.FormatInvalidEmail'); }

        $morada_entrega = \DB::table('utilizadores_morada')
                                ->where('id_utilizador',Cookie::get('cookie_user_id'))
                                ->where('tipo','morada_entrega')
                                ->first();

        if ($morada_entrega) {

            \DB::table('utilizadores_morada')->where('id_utilizador',Cookie::get('cookie_user_id'))->where('tipo','morada_entrega')
                ->update([
                    'nome' => $name,
                    'email' => $email,
                    'telefone' => $contacto,
                    'nif' => $nif,
                    'morada' => $morada,
                    'morada_opc' => $morada_opc,
                    'codigo_postal' => $codigoPostal,
                    'cidade' => $cidade,
                    'pais' => 'Portugal - Continental',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]);
        }else{
     
           \DB::table('utilizadores_morada')
                ->insert([
                    'id_utilizador' => Cookie::get('cookie_user_id'),
                    'nome' => $name,
                    'email' => $email,
                    'telefone' => $contacto,
                    'nif' => $nif,
                    'morada' => $morada,
                    'morada_opc' => $morada_opc,
                    'codigo_postal' => $codigoPostal,
                    'cidade' => $cidade,
                    'pais' => 'Portugal - Continental',
                    'tipo' => 'morada_entrega',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]); 
        }

        $resposta = [ 'estado' => 'sucesso' ];

        return json_encode($resposta,true);

    }

    public function askInfoPremium(Request $request){

        $id_pedido=trim($request->id_pedido);
        $id_premio=trim($request->id_premio);
        $data_pedido=trim($request->data);

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $premio = \DB::table('premios')->where('id',$id_premio)->first();
        $cliente = \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->first();


        $dados = [
            'nome_premio' => $premio->nome_pt,
            'nome_cliente' => Cookie::get('cookie_user_id'),
            'id_pedido' => $id_pedido,
            'data_pedido' => $data_pedido
        ];

        $mensagem = 'O utilizador com o #'.Cookie::get('cookie_user_id').' pediu informações sobre a entrega do prémio com #'.$id_pedido.' realizado no dia '.date('Y-m-d',$data_pedido).'.';

        $admins=\DB::table('admin')->get();

        foreach ($admins as $value) {
            \DB::table('admin_not')
                ->insert([
                    'id_admin'=>$value->id,
                    'tipo'=>'premios',
                    'mensagem'=>$mensagem,
                    'data'=>\Carbon\Carbon::now()->timestamp
                ]);
        }
    
        // (Substituir o email tmp@universal.com.pt para site@universal.com.pt)
        \Mail::send('client.emails.pages.askInfoPremium',['dados' => $dados], function($message) use ($cliente){
            $message->to('site@universal.com.pt','')->subject(trans('site_v2.InformationPremium'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });

        return 'sucesso';
        
    }

    public function deleteAccount(Request $request){

        $password_delete=trim($request->password_delete);
        
        $cliente = \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->first();
        
        if(!Hash::check($password_delete, $cliente->password)){ return 'A password que inseriu está incorreta.'; }
        else{
            if($cliente->foto && file_exists(base_path().'/public_html/img/clientes/'.$cliente->foto)){
                \File::delete(base_path().'/public_html/img/clientes/'.$cliente->foto);
            }

            \DB::table('utilizadores_morada')->where('id_utilizador',Cookie::get('cookie_user_id'))->delete();
            \DB::table('carrinho')->where('id_utilizador',Cookie::get('cookie_user_id'))->delete();
            \DB::table('rotulos_utilizador')->where('id_utilizador',Cookie::get('cookie_user_id'))->delete();
            \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->delete();

            Cookie::queue(Cookie::forget('cookie_user_id'));
            Cookie::queue(Cookie::forget('cookie_user_token'));
            Cookie::queue(Cookie::forget('cookie_user_name'));
            Cookie::queue(Cookie::forget('cookie_user_photo'));
            Cookie::queue(Cookie::forget('cookie_user_points'));
            Cookie::queue(Cookie::forget('cookie_user_cart'));
            Cookie::queue(Cookie::forget('cookie_user_status'));
            
            $dados = [ ];
            \Mail::send('site_v2.emails.pages.deleteAccount',$dados, function($message) use ($cliente){
                $message->to($cliente->email,$cliente->nome)->subject('Apagar conta');
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });

            return 'sucesso';
        }
    }

    public function showRegulation(){
        $this->dados['headTitulo'] = trans('site_v2.t_regulation');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_regulation';

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}


        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        if (!empty($utilizadores->email_alteracao)){$this->dados['aviso'] = 'email_alteracao';}

        if (date('Y-m-d',strtotime(date('Y-m-d',$utilizadores->ultimo_acesso). ' + 1  day')) < date('Y-m-d',strtotime(date('Y-m-d')))) {

            \DB::table('utilizadores')
                ->where('id', Cookie::get('cookie_user_id'))
                ->update([
                    'email_alteracao' => ''
                ]);

        }

        $string = explode(" ", $utilizadores->nome);

        $this->dados['nome'] = $string[0];
        $this->dados['utilizadores'] = $utilizadores;

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        $this->dados['passatempo'] = \DB::table('passatempos')
                                    ->select('*')
                                    ->orderBy('estado','ASC')
                                    ->orderBy('data_fim','DESC')
                                    ->first();
        
        return view('client/pages/regulation',$this->dados);
    }

    //Upload photo in area reserved client

    public function uploadPhoto(Request $request){

        $foto_perfil = $request->file('ficheiro');

        $user = \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->first();
        if($foto_perfil) {

            $antigoNome = $user->foto;

            $cache = str_random(3);
            $extension = strtolower($foto_perfil->getClientOriginalExtension());

            if (($extension != 'jpg') && ($extension != 'png')) { return trans('site_v2.Unsupported_extension'); }

            $novoNome = 'foto_perfil-'.Cookie::get('cookie_user_id').'-'.$cache.'.'.$extension;

            $pasta = base_path('public_html/img/clientes/');
            $width = 300; $height = 300;

            $uploadImage = New uploadImage;
            $uploadImage->upload($foto_perfil,$antigoNome,$novoNome,$pasta,$width,$height);

            $getName = $foto_perfil->getPathName();

            move_uploaded_file($getName, $pasta.$novoNome);
        }
        else{
           $novoNome =  $user->foto;
        }

         \DB::table('utilizadores')
            ->where('id',Cookie::get('cookie_user_id'))
            ->update([
                'foto' => $novoNome 
            ]);

        Cookie::queue('cookie_user_photo', $novoNome);
        //return resposta

        $resposta = [
           'estado' => 'sucesso',
           'foto' => $novoNome
        ];

        return json_encode($resposta,true);

    }

    public function updateData(Request $request){

        $name = trim($request->name);
        $email = filter_var(strtolower(trim($request->email)), FILTER_VALIDATE_EMAIL);
        $password = trim($request->password);
        $apelido = trim($request->apelido);
        $contacto = trim($request->contacto);
        $repite_password = trim($request->repite_password);
        $newsletter = trim($request->termos_cond) ? trim($request->termos_cond) : 0;
        //$foto_perfil = $request->file('ficheiro');
        $token_alteracao = str_random(12);

        $list_id = 'a81f0f2aac';
        $api_key = '68a75c0a07d2181efb5e8f2215c9806c-us20';
        $status = 'subscribed';
        $merge_fields = array('FNAME' => '','LNAME' => '');

        if (empty($name)) {return trans('site_v2.Field_name_txt');}
        if (empty($apelido)) {return trans('site_v2.Field_nickname_txt');}
        if (empty($email)) {return trans('site_v2.Field_email_txt');}
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail'); }
        if (!empty($contacto) && ((strlen($contacto) > 9) || (strlen($contacto) < 9))){ return trans('site_v2.Field_invalid_phone_txt');}
        

        if(($password) && (strlen($password) < 6)){ return trans('site_v2.Field_pass_txt'); }
        if(($repite_password) && (strlen($repite_password) < 6)){ return trans('site_v2.Field_pass_txt'); }

        if($password != $repite_password){ return trans('site_v2.Different_passwords');}

        
        $user = \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->first();
        
        if ($password == '') { $nova_password = $user->password; }
        else{ $nova_password = bcrypt($password); }

        $nome_partes = explode(" ", $name);
        
        if ($email != $user->email) { 
           
            $utilizadores = \DB::table('utilizadores')
                                ->where('email', $email)
                                ->orWhere('email_alteracao',$email)
                                ->where('id','<>',Cookie::get('cookie_user_id'))
                                ->where('estado','<>','pendente')
                                ->first();                 
            
            $comerciantes = \DB::table('comerciantes')
                                ->where('email', $email)
                                ->where('estado','<>','pendente')
                                ->first();

            $empresas_responsaveis = \DB::table('empresas_responsaveis')
                                        ->where('email', $email)
                                        ->first();

            
            if (empty($utilizadores) && empty($comerciantes) && empty($empresas_responsaveis)) {
              
                \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))
                    ->update([
                        'nome' => $name,
                        'apelido' => $apelido,
                        'password' => $nova_password,
                        //'foto' => $novoNome,
                        'email_alteracao' => $email,
                        'token_alteracao' => $token_alteracao,
                        'telefone' => $contacto,
                        'newsletter' => $newsletter,
                        'ultimo_acesso' => \Carbon\Carbon::now()->timestamp
                    ]);

                $dados = ['token' => $token_alteracao];
                \Mail::send('client.emails.pages.resendEmail',['dados' => $dados], function($message) use ($request){

                    $message->to($request->email,'')->subject(trans('site_v2.Validation_Email_txt'));
                    $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
                });

                $resposta = [
                   'estado' => 'sucesso',
                   'nome_user' => $nome_partes[0],
                   'email' => 'alterado',
                   'mensagem' => trans('seller.Successfully_sent_email_txt') .$email.'.'
                ];
            }
            else{
                return trans('site_v2.EmailCorrespondsAccount_tx');
            }
        }
        else{ 
            
            \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))
                ->update([
                    'nome' => $name,
                    'apelido' => $apelido,
                    'password' => $nova_password,
                    'email' => $email,
                    'telefone' => $contacto,
                    'newsletter' => $newsletter,
                    'ultimo_acesso' => \Carbon\Carbon::now()->timestamp
                ]);

            $resposta = [
               'estado' => 'sucesso',
               'nome_user' => $nome_partes[0],
               'email' => 'sem_alteracao'
            ];
        }

        Cookie::queue('cookie_user_name', $nome_partes[0],43200);

        if ($newsletter == 1) { $status = 'subscribed'; }
        else{ $status = 'unsubscribed'; }

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

        return json_encode($resposta,true);
    }
    public function resendEmail(){

        $user = \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->first();

        $dados = ['token' => $user->token_alteracao];
        \Mail::send('client.emails.pages.resendEmail',['dados' => $dados], function($message) use ($user){

            $message->to($user->email_alteracao,'')->subject(trans('site_v2.Validation_Email_txt'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' => trans('site_v2.Send_successfully')
        ];

        return json_encode($resposta,true);
    }

    public function valiteResendEmail($token){
        $this->dados['headTitulo'] = trans('site_v2.Validate_Account');
        $this->dados['headDescricao'] = trans('site_v2.Validate_Account');
        $this->dados['separador'] = '';

        $user = \DB::table('utilizadores')->where('token_alteracao',$token)->first();

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');


        if (isset($user->email_alteracao) && isset($user->token_alteracao)) {
        
            \DB::table('utilizadores')
            ->where('token_alteracao',$token)
                ->update([ 
                    'email' => $user->email_alteracao,
                    'email_alteracao' => '',
                    'token_alteracao' => ''
                ]);
        }
        else{
            $this->dados['variavel'] = 'alterado';
            
        } 
        return view('site_v2/pages/validateAccount', $this->dados); 
    }

    public function cancelEmailValition(){

        \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->update([ 'email_alteracao' => '' ]);

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' => trans('site_v2.CancelSucess')
        ];

        return json_encode($resposta,true);
    }

    public function deletePhoto(Request $request){

        $user = \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->first();

        if($user->foto && file_exists(base_path().'/public_html/img/clientes/'.$user->foto)){

            \File::delete(base_path().'/public_html/img/clientes/'.$user->foto);
        }
        \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->update([ 'foto'=>'' ]);

        Cookie::queue('cookie_user_photo', 'default.svg');

        return 'sucesso';
    }
}