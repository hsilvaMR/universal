<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class CompanyData extends Controller{

    private $dados = [];
     

    public function index(){

        $this->dados['headTitulo'] = trans('seller.t_dataCompany');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Dados_Empresa';   

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

        $obj = json_decode($this->dados['company']->ies);

        $arrayIES = [];
        $arrayAno = [];
        if ($obj) {
            foreach ($obj as $value) {       
                $arrayIES[]=[
                    'ano' => $value->ano,
                    'ies' => $value->ies
                ];
            }
        }

        $arrayNew = [];
        for($ano = date('Y')-1; $ano >= date('Y')-3; $ano--){
           $valor = (in_array($ano, array_column($arrayIES, 'ano'))) ? 1 : 0;   

            if ($valor == 1) {
                foreach ($obj as $value) {  
                    if ($ano == $value->ano) {
                        $arrayNew[] = [
                            'ano' => $ano,
                            'valor' => $valor,
                            'ies' => $value->ies
                        ];
                    }  
                }
            }
            else{
                $arrayNew[] = [
                    'ano' => $ano,
                    'valor' => $valor,
                    'ies' => ''
                ];
            } 
        }

       
        $this->dados['arrayIES'] = $arrayIES;
        $this->dados['arrayNew'] = $arrayNew;

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['seller'] = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();
        
        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/companyData',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }

    public function legalR(){
 
        $this->dados['headTitulo'] = trans('seller.t_legalRepresentative');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Dados_Representante';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

        $this->dados['responsavel'] = \DB::table('empresas_responsaveis')->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))->where('tipo','resp_legal')->get();

        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/legalRepresentative',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }

    public function contactPerson(){
        
        $this->dados['headTitulo'] = trans('seller.t_contactPerson');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Dados_Pessoa_Contacto';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();
        
        $this->dados['person_contact'] = \DB::table('empresas_responsaveis')->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))->where('tipo','person_contact')->get();

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
         
        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/contactPerson',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }

    public function technicalInformation(){
        $this->dados['headTitulo'] = trans('seller.Technical_Information');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Information';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
        
        $informacoes_tecnicas = \DB::table('informacoes_tecnicas')
                                    ->where('online',1)
                                    ->orderBy('data','ASC')
                                    ->get();

        foreach ($informacoes_tecnicas as $value) {

            if (date('Y-m-d',strtotime(date('Y-m-d',$value->data). ' + 7days')) <= date('Y-m-d',strtotime(date('Y-m-d')))) {
                
                \DB::table('informacoes_tecnicas')
                    ->where('id',$value->id)
                    ->update(['estado' => 'ativo']);
            }
        }
        
        $this->dados['informacoes_tecnicas'] = $informacoes_tecnicas;
        return view('seller/pages/technicalInformation',$this->dados);  
    }

    public function saveCompanyData(Request $request){

        $logo = $request->file('logo');
        $name = trim($request->name);
        $email = filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) ? filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) : '';
        $cae = trim($request->cae) ? trim($request->cae) : 0;
        $nif = trim($request->nif) ? trim($request->nif) : 0;
        $telefone = trim($request->telefone);
        $n_vendas = trim($request->n_vendas) ? trim($request->n_vendas) : '';
        $v_vendas = trim($request->v_vendas) ? trim($request->v_vendas) : '';
        $ebitda = trim($request->ebitda) ? trim($request->ebitda) : '';
        $certidao = $request->file('certidao');
        $certidao_old = $request->certidao_old;
        $tipo_fatura = trim($request->tipo_fatura);
        $obs = trim($request->obs);
        $prazo_pagamento = trim($request->prazo_pagamento);
        $tipo = trim($request->tipo);
        
        if($tipo == 'submeter'){
           if (empty($name)){ return trans('seller.Field_name_txt'); }
           if (empty($email)){ return trans('seller.Field_email_txt'); } 
           if (empty($cae)){ return trans('seller.Field_CAE_txt'); }
           if (empty($nif)){ return trans('seller.Field_NIF_txt'); }
           if (!is_numeric($nif) || (strlen($nif) < 9) || (strlen($nif) > 9)) { return trans('seller.Invalid_field_NIF');}
           if (empty($telefone)){ return trans('seller.Field_contact_txt'); }
           if (empty($n_vendas)){ return trans('seller.Field_n_pontos_txt'); }
           if (empty($v_vendas)){ return trans('seller.Field_v_vendas_txt'); }
           if (empty($ebitda)){ return trans('seller.Field_ebitda_txt'); }
           if (empty($certidao) && empty($certidao_old)){ return trans('seller.Field_company_certificate'); }  
        }

        if (!empty($nif) && (!is_numeric($nif) || (strlen($nif) < 9) || (strlen($nif) > 9))) { return trans('seller.Invalid_field_NIF');}

        $company = \DB::table('empresas')->where('id', Cookie::get('cookie_comerc_id_empresa'))->first();

        if ($company->estado != 'aprovado') {
            $get_email = \DB::table('empresas')->select('email')->where('email',$email)->where('id',Cookie::get('cookie_comerc_id_empresa'))->count();
            $get_user = \DB::table('utilizadores')->select('email')->where('email',$email)->orWhere('email_alteracao',$email)->count();
            $get_comerc = \DB::table('comerciantes')->select('email')->where('email',$email)->count();

            if ($company->email == $email) { if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site.FormatInvalidEmail');} }
            elseif(($get_email == 1) || ($get_user == 1) || ($get_comerc == 1)){ return trans('seller.Email_repite');}

            $valor = str_replace("." , "" , $ebitda ); // Primeiro tira-se os pontos
            $valor = str_replace("," , "" , $ebitda); // Depois tira-se a vírgula

            $valor_volume = str_replace("." , "" , $v_vendas ); // Primeiro tira-se os pontos
            $valor_volume = str_replace("," , "" , $v_vendas); // Depois tira-se a vírgula

            $novoNome = '';
            
            $cache = str_random(3);
            if (count($logo)) {

                if(file_exists(base_path('public_html/img/empresas/'.$company->logotipo))) {
                    \File::delete(base_path().'/public_html/img/empresas/'.$company->logotipo);
                }

                \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->update(['logotipo'=>'']);

                $destinationPath = base_path('public_html/img/empresas/');
                $extension = strtolower($logo->getClientOriginalExtension());
                $getName = $logo->getPathName();
                

                $novoNome = 'logo-'.Cookie::get('cookie_comerc_id_empresa').'-'.$cache.'.'.$extension;
                
                move_uploaded_file($getName, $destinationPath.$novoNome);

                \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->update(['logotipo'=>$novoNome]);
                Cookie::queue('cookie_company_photo', $novoNome);
            }
            else{$novoNome = $company->logotipo;}

            $arrayRequest = [];

            $obj = json_decode($company->ies);

            for ($ano = date('Y')-1; $ano >= date('Y')-3; $ano--) { 
               
                $nome = 'ies_'.$ano;
                $ies = $request->file($nome);

                if (count($ies)) {

                    if ($obj) {
                        foreach ($obj as $value) {  
                            if($value->ano == $ano && $value->ies){
                                if(file_exists(base_path('public_html/doc/companies/'.$value->ies))){ \File::delete('../public_html/doc/companies/'.$value->ies); }
                            }
                        }
                    }
                    
                    $destinationPath = base_path('public_html/doc/companies/');
                    $extension = strtolower($ies->getClientOriginalExtension());
                    $getName = $ies->getPathName();
                    
                    $ies_novo = 'company_'.Cookie::get('cookie_comerc_id_empresa').'_'.'ies_'.$ano.'_'.$cache.'.'.$extension;
                    
                    move_uploaded_file($getName, $destinationPath.$ies_novo);
                    
                    $arrayRequest[] = [
                        'ano' => $ano,
                        'ies' => $ies_novo
                    ];
                }
                else{ 
                    if ($obj) {
                        foreach ($obj as $value) {
                            if ($value->ano == $ano) { 
                                $ies_novo = $value->ies;
                                $ano = $value->ano;

                                $arrayRequest[] = [
                                    'ano' => $ano,
                                    'ies' => $ies_novo
                                ];
                            }
                        }
                    }
                }
            }
            
            if (count($certidao)) {
                if($company->certidao){
                    if(file_exists(base_path('public_html/doc/companies/'.$company->certidao))){ \File::delete('../public_html/doc/companies/'.$company->certidao); }
                }

                $destinationPath = base_path('public_html/doc/companies/');
                $extension = strtolower($certidao->getClientOriginalExtension());
                $getName = $certidao->getPathName();

                $new_certidao = 'company_'.Cookie::get('cookie_comerc_id_empresa').'_'.'certidao_'.$cache.'.'.$extension;
                
                move_uploaded_file($getName, $destinationPath.$new_certidao);
            }
            else{ $new_certidao = $company->certidao; }

                
            
            \DB::table('empresas')->where('id', Cookie::get('cookie_comerc_id_empresa'))
                ->update([
                    'nome' => $name,
                    'nif' => $nif,
                    'cae' => $cae,
                    'telefone' => $telefone,
                    'ebitda' => $ebitda,
                    'certidao' => $new_certidao,
                    'ies' => json_encode($arrayRequest),
                    'pontos_venda' => $n_vendas,
                    'volume_venda' => $v_vendas,
                    'obs' => $obs,
                    'tipo_fatura' => $tipo_fatura,
                    'prazo_pag' => $prazo_pagamento,
                    'data' => \Carbon\Carbon::now()->timestamp
                ]);

            
      
            if (($tipo == 'submeter')) {
                \DB::table('empresas')->where('id', Cookie::get('cookie_comerc_id_empresa'))
                    ->update([
                        'estado' => 'em_aprovacao',
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);

                /*ENVIAR EMAIL PARA A UNIVERSAL VALIDAR A CONTA EMPRESA (IGUAL AO DO COMERCIANTE)*/
                $dados = ['id_comerciante'=>Cookie::get('cookie_comerc_id'),'nome_empresa'=>$company->nome,'id_empresa'=>$company->id];
                // (Substituir o email tmp@universal.com.pt para site@universal.com.pt)
                \Mail::send('seller.emails.pages.validateDataAccount',['dados' => $dados], function($message){
                    $message->to('site@universal.com.pt','')->subject(trans('seller.Data_Approval'));
                    $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
                });
            }
            

            Cookie::queue('cookie_company_status', 'em_aprovacao');
            Cookie::queue('cookie_company_name', $name);

            /*if ($company->email != $email){

                \DB::table('empresas')->where('id', Cookie::get('cookie_comerc_id_empresa'))
                    ->update([
                        'email_alteracao' => $email,
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);

                $dados = [ ];
                \Mail::send('seller.emails.pages.resendEmail',$dados, function($message) use ($request){

                    $message->to($request->email,'')->subject(trans('site_v2.Validation_Email_txt'));
                    $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
                });
            }*/

            //Criar Notificação
            $mensagem = 'A empresa com o #'.Cookie::get('cookie_comerc_id_empresa').' atualizou os seus dados. É necessário a validação dos mesmos.';
            $admin = \DB::table('admin')->get();

            foreach($admin as $val){
                \DB::table('admin_not')
                    ->insert([
                        'id_admin' => $val->id,
                        'tipo'=> 'empresas',
                        'mensagem' => $mensagem,
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);
            }
            
            $resposta = [ 
                'estado' => 'sucesso',
                'logo' => $novoNome,
                'certidao' => $new_certidao,
                'nome_empresa' => $name,
                'ies' => $arrayRequest,
                'tipo' => $tipo
            ];
        }
        else{
            $resposta = [ 
                'estado' => 'insucesso'
            ];  
        }
        return json_encode($resposta,true);
    }


    public function saveLegalData(Request $request){

        $id=trim($request->id);
        $nome=trim($request->nome);
        $cargo=trim($request->cargo);
        $email = filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) ? filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) : '';
        $contacto=trim($request->contacto); 
        $ficheiro_v=$request->file('ficheiro');
        $logo_old=trim($request->img_value);
        $ficheiro_old=trim($request->ficheiro_v);

        $responsavel = \DB::table('empresas_responsaveis')->where('id', $id)->first();

        if(empty($nome) && empty($ficheiro_old)){return trans('seller.Field_name_txt');}
        if(empty($cargo) && empty($ficheiro_old)){return trans('seller.Field_cargo_txt');}
        if(empty($email)) {return trans('seller.Field_email_txt');}
        if(empty($ficheiro_v) && empty($ficheiro_old)){return trans('seller.DocumentValidation');}
        
        $email_resp = \DB::table('empresas_responsaveis')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('email',$email)->where('tipo','resp_legal')->count();

        if ($id) {

            if (($responsavel->email != $email) && ($email_resp == 1)) {
                return 'O email que introduziu já encontra registado.';
            }

            if (($responsavel->nome != $nome) || ($responsavel->email != $email) || (($responsavel->contacto != $contacto) && ($contacto != 0)) || ($responsavel->cargo != $cargo)) {
                
                \DB::table('empresas_responsaveis')
                    ->where('id',$id)
                    ->update([
                        'nome' => $nome,
                        'email' => $email,
                        'cargo' => $cargo,
                        'contacto' => $contacto,
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);
            }
        }
        else{

            if ($email_resp == 1) {
                return 'O email que introduziu já encontra registado.';
            }

            $id = \DB::table('empresas_responsaveis')
                        ->insertGetId([
                            'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                            'nome' => $nome,
                            'email' => $email,
                            'cargo' => $cargo,
                            'contacto' => $contacto,
                            'tipo' => 'resp_legal',
                            'data' => \Carbon\Carbon::now()->timestamp
                        ]);


            //Criar notificação para backoofice e area reservada
            $admin = \DB::table('admin')->get(); 

            foreach ($admin as $value) {
                \DB::table('admin_not')
                    ->insert([
                        'id_admin' => $value->id,
                        'tipo' => 'utilizadores',
                        'mensagem' => 'O utilizador da empresa # '.Cookie::get('cookie_comerc_id_empresa').' foi alterado.',
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);
            }

            /*\DB::table('notificacoes')
                ->insert([
                    'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                    'id_comerciante' => $id,
                    'img' => '/seller/img/dashboard/users.png',
                    'titulo' => $nome,
                    'texto' => '<i class="fas fa-info-circle tx-orange"></i> Utilizador em aprovação.',
                    'tipo' => 'resp_legal',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]);*/
            
        }

        $declaracao_poderes = $ficheiro_old;

        if (count($ficheiro_v)) {

            \File::delete(base_path().'/public_html/doc/companies/'.$ficheiro_old);

            $destinationPath = base_path('public_html/doc/companies/');
            $extension = strtolower($ficheiro_v->getClientOriginalExtension());
            $getName = $ficheiro_v->getPathName();
            $cache = str_random(3);

            $declaracao_poderes = 'company_'.Cookie::get('cookie_comerc_id_empresa').'_user_'.$id.'_'.$cache.'.'.$extension;
                           
            move_uploaded_file($getName, $destinationPath.$declaracao_poderes);
            
            
            \DB::table('empresas_responsaveis')->where('id',$id)->update(['doc_validacao'=>$declaracao_poderes]);
        }

        
        //Update cookie
        //$count_notificacoes = \DB::table('notificacoes')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('online',1)->count();
        //\Cookie::queue('cookie_notification_ative',$count_notificacoes);


        $resposta = [ 
            'estado' => 'sucesso',
            'declaracao' => $declaracao_poderes,
            //'count_notificacoes' => $count_notificacoes,
            'id'=>$id,
            'conteudo' => '
                <input id="'.$id.'_id" class="'.$id.'_update" type="hidden" name="" value="'.$id.'">
                <input id="'.$id.'_nome" class="'.$id.'_update" type="hidden" name="" value="'.$nome.'">
                <input id="'.$id.'_cargo" class="'.$id.'_update" type="hidden" name="" value="'.$cargo.'">
                <input id="'.$id.'_email" class="'.$id.'_update" type="hidden" name="" value="'.$email.'">
                <input id="'.$id.'_contacto" class="'.$id.'_update" type="hidden" name="" value="'.$contacto.'">
                <input id="'.$id.'_ficheiro_v" class="'.$id.'_update" type="hidden" name="" value="'.$declaracao_poderes.'">
            ',
            'conteudo_tr' => '
                <td class="line-height50"> 
                    <i class="fas fa-times user-delete margin-right20" onclick="$(\'#id_modal\').val('.$id.');" data-toggle="modal" data-target="#myModalDelete"></i> '.$nome.'
                </td>
                <td>'.$cargo.'</td>
                <td>'.$email.'</td>
                <td>'.$contacto.'</td>
                <td><a class="tx-navy" href="seller/ficheiro_validacao/'.$declaracao_poderes.'" download>'.$declaracao_poderes.'</a></td>
                <td class="dashboard-table-details" onclick="editSeller('.$id.',\''.$declaracao_poderes.'\');"><i class="fas fa-pencil-alt"></i>'.trans('seller.Edit').'</td>'
        ];
        return json_encode($resposta,true);
    }

    public function saveContactData(Request $request){

        $nome = trim($request->nome);
        $cargo = trim($request->cargo);
        $email = filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) ? filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) : '';
        $contacto = trim($request->contacto);
        $id = trim($request->id);
        $info_adicional = trim($request->info_adicional);


        if(empty($nome)){return trans('seller.Field_name_txt');}
        if(empty($cargo)){return trans('seller.Field_cargo_txt');}
        if(empty($email)){return trans('seller.Field_email_txt');}

        $person_contact = \DB::table('empresas_responsaveis')->where('id',$id)->first();
        $email_contact = \DB::table('empresas_responsaveis')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('email',$email)->where('tipo','person_contact')->count();
        
        if ($id) {

            if (($email_contact == 1) && ($person_contact->email != $email)) {
                return 'O email que introduziu já encontra registado.';
            }
           
            if (($person_contact->nome != $nome) || ($person_contact->email != $email) || ($person_contact->contacto != $contacto) || ($person_contact->cargo != $cargo) || ($person_contact->obs != $info_adicional)) { 

                \DB::table('empresas_responsaveis')
                    ->where('id',$person_contact->id)
                    ->update([
                        'nome' => $nome,
                        'email' => $email,
                        'contacto' => $contacto,
                        'obs' => $info_adicional,
                        'cargo' => $cargo,
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);
            }
        }
        else{

            if ($email_contact == 1) {
                return 'O email que introduziu já encontra registado.';
            }

            $id=\DB::table('empresas_responsaveis')
                ->insertGetId([
                    'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                    'nome' => $nome,
                    'email' => $email,
                    'cargo' => $cargo,
                    'contacto' => $contacto,
                    'obs' => $info_adicional,
                    'tipo' => 'person_contact',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]);

            //Criar notificação para backoofice e area reservada
            $admin = \DB::table('admin')->get(); 

            foreach ($admin as $value) {
                \DB::table('admin_not')
                    ->insert([
                        'id_admin' => $value->id,
                        'tipo' => 'utilizadores',
                        'mensagem' => 'O utilizador da empresa # '.Cookie::get('cookie_comerc_id_empresa').' foi alterado.',
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);
            }

            /*\DB::table('notificacoes')
                ->insert([
                    'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                    'id_comerciante' => $id,
                    'img' => '/seller/img/dashboard/users.png',
                    'titulo' => $nome,
                    'texto' => '<i class="fas fa-info-circle tx-orange"></i> Utilizador em aprovação.',
                    'tipo' => 'contacto',
                    'data' => \Carbon\Carbon::now()->timestamp
                ]);*/
        }
        
        //Update cookie
        //$count_notificacoes = \DB::table('notificacoes')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('online',1)->count();
        //\Cookie::queue('cookie_notification_ative',$count_notificacoes);

        $resposta = [ 
            'estado' => 'sucesso',
            'id' => $id,
            //'count_notificacoes' => $count_notificacoes,
            'conteudo' => '
                <input id="'.$id.'_id" class="'.$id.'_update" type="hidden" name="" value="'.$id.'">
                <input id="'.$id.'_nome" class="'.$id.'_update" type="hidden" name="" value="'.$nome.'">
                <input id="'.$id.'_cargo" class="'.$id.'_update" type="hidden" name="" value="'.$cargo.'">
                <input id="'.$id.'_email" class="'.$id.'_update" type="hidden" name="" value="'.$email.'">
                <input id="'.$id.'_contacto" class="'.$id.'_update" type="hidden" name="" value="'.$contacto.'">
                <input id="'.$id.'_obs" class="'.$id.'_update" type="hidden" name="" value="'.$info_adicional.'">
            ',
            'conteudo_tr' =>'
                <td class="display-none">'.$id.'</td>
                <td class="line-height50"> 
                    <i class="fas fa-times user-delete margin-right20" onclick="$(\'#id_modal\').val('.$id.');" data-toggle="modal" data-target="#myModalDelete"></i>
                    '.$nome.'
                </td>
                <td>'.$cargo.'</td>
                <td>'.$email.'</td>
                <td>'.$contacto.'</td>
                <td>'.$info_adicional.'</td>
                <td class="dashboard-table-details" onclick="editSeller('.$id.');"><i class="fas fa-pencil-alt"></i>'.trans('seller.Edit').'</td>
            '
        ];
        return json_encode($resposta,true);
    }

    public function deleteCompanyAvatar(Request $request){

        $tipo = trim($request->tipo);

        if ($tipo == 'seller') {
            $seller = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

            if($seller->foto && file_exists(base_path().'/public_html/img/comerciantes/'.$seller->foto)){
                \File::delete(base_path().'/public_html/img/comerciantes/'.$seller->foto);
            }
            \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->update([ 'foto'=>'' ]);

            Cookie::queue('cookie_comerc_photo', '');

        }elseif($tipo == 'company'){
            $company = \DB::table('empresas')->where('id', Cookie::get('cookie_comerc_id_empresa'))->first();

            \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))
               ->update([
                    'logotipo' => ''
                ]);

            \File::delete(base_path().'/public_html/img/empresas/'.$company->logotipo);

            Cookie::queue('cookie_company_photo', '');
        }
        
        return 'sucesso';
    }

    public function valiteEmail($token){

        $this->dados['headTitulo'] = trans('site_v2.Validate_Account');
        $this->dados['headDescricao'] = trans('site_v2.Validate_Account');
        $this->dados['separador'] = '';

        $seller = \DB::table('comerciantes')->where('token_alteracao',$token)->first();
        $company = \DB::table('empresas')->where('token_alteracao',$token)->first();
        

        if (isset($seller->email_alteracao) && isset($seller->token_alteracao)) {
            \DB::table('comerciantes')
                ->where('token_alteracao',$token)
                ->update([ 
                    'email' => $seller->email_alteracao,
                    'email_alteracao' => '',
                    'token_alteracao' => ''
                ]);
        }
        elseif (isset($company->email_alteracao) && isset($company->token_alteracao)) {
            \DB::table('empresas')
                ->where('token_alteracao',$token)
                ->update([ 
                    'email' => $seller->email_alteracao,
                    'email_alteracao' => '',
                    'token_alteracao' => ''
                ]);
        }
        else{
            $this->dados['variavel'] = 'alterado'; 
        } 
        return view('site_v2/pages/validateAccount', $this->dados); 
    }   
}