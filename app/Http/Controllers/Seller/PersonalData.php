<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class PersonalData extends Controller{

    private $dados = [];
     
    public function index(){

        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Dados_Pessoais';

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
        
        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();
        
        $this->dados['seller'] = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

        $this->dados['n_admin'] = \DB::table('comerciantes')
                                    ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                    ->where('tipo','<>','comerciante')
                                    ->where('estado','ativo')
                                    ->where(function($query){
                                                $query->where('aprovacao','')
                                                      ->orWhere('aprovacao','aprovado');
                                            })
                                    ->count();

        return view('seller/pages/personalData',$this->dados);  
    }

    public function saveData(Request $request){
        $id = $request->id;
    	$foto = $request->file('foto');
    	$nome = $request->nome;
    	$email = $request->email;
    	$contacto = $request->contacto;	
    	$password = $request->password;
    	$repite_password = $request->repite_password;
    	$ficheiro_v = $request->file('ficheiro_validacao');
        $permissoes = $request->permissoes;
        $file_old = $request->file_old;
        $alterado = 'nao';

    	$seller = \DB::table('comerciantes')->where('id', $id)->first();
    	$get_email = \DB::table('comerciantes')->select('email')->where('email',$email)->count();
        $get_email_user = \DB::table('utilizadores')->where('email',$email)->orWhere('email_alteracao',$email)->count();
        $company = \DB::table('empresas')->where('id',$seller->id_empresa)->first();

        $cache = str_random(3);

        if (empty($permissoes)) { $permissoes = $seller->tipo; }

        if (($permissoes == 'comerciante') || ($permissoes == 'admin')) {
            if(empty($ficheiro_v) && empty($file_old)){return trans('seller.DocumentValidation');}
            
            if( (!empty($file_old) && ($seller->ficheiro != $file_old)) || ($seller->email != $email)){
                $aprovacao = 'em_aprovacao';
            }else{
                $aprovacao = $seller->aprovacao;
            }  
        }else{
            $aprovacao = '';
        }

        $estado = $seller->estado;
        if ($email != $seller->email) {
            if (($seller->email != $email) && ($get_email == 1) || ($get_email_user == 1)) { return trans('site_v2.EmailCorrespondAccount');}
        }
      
        
        if ($password != $repite_password) {return 'Passwords diferentes! Introduza passwords iguais.';}
        if(!empty($password) && strlen($password) < 6) {return trans('site_v2.Field_pass_txt');}
        elseif(strlen($password) >= 6){
            \DB::table('comerciantes')
                ->where('id',$id)
                ->update([
                    'password' => bcrypt($password)
                ]);
        }

        if (count($ficheiro_v)) {

            \File::delete(base_path().'/public_html/doc/companies/'.$seller->ficheiro);

            $destinationPath = base_path('public_html/doc/companies/');
            $extension = strtolower($ficheiro_v->getClientOriginalExtension());
            $getName =$ficheiro_v->getPathName();

            $ficheiro_validacao = 'company_'.Cookie::get('cookie_comerc_id_empresa').'_'.'user_'.$id.'_'.$cache.'.'.$extension;
            
            move_uploaded_file($getName, $destinationPath.$ficheiro_validacao);

            $aprovacao = 'em_aprovacao';
            
        }
        elseif(isset($seller->ficheiro)){

            if ($permissoes == 'gestor') {
                \File::delete(base_path().'/public_html/doc/companies/'.$seller->ficheiro);
                \DB::table('comerciantes')->where('id',$id)->update(['ficheiro' => '']);

                $ficheiro_validacao =  '';
            }
            else{
                $ficheiro_validacao =  $seller->ficheiro;
            }
        }
        else{ $ficheiro_validacao = $seller->ficheiro; }

        $novoNomeFoto='';
        if (count($foto)) {

            $antigoNome = $seller->foto;

            $extension = strtolower($foto->getClientOriginalExtension());

            $ficheiro = '';

            if (($extension != 'jpg') && ($extension != 'png')) {
                return trans('site_v2.Unsupported_extension');
            }

            $novoNomeFoto = 'comerc'.$id.'-'.$cache.'.'.$extension;

            $pasta = base_path('public_html/img/comerciantes/');
            $width = 300; $height = 300;

            $uploadImage = New uploadImage;
            $uploadImage->upload($ficheiro,$antigoNome,$novoNomeFoto,$pasta,$width,$height);

            $getName = $foto->getPathName();

            move_uploaded_file($getName, $pasta.$novoNomeFoto);

            \File::delete(base_path().'/public_html/img/comerciantes/'.$seller->foto);


            \DB::table('comerciantes')
                ->where('id',$id)
                ->update([
                    'foto' => $novoNomeFoto,
                    'data_registo' => \Carbon\Carbon::now()->timestamp,
                    'ultimo_acesso' => \Carbon\Carbon::now()->timestamp
                ]);
            
            Cookie::queue('cookie_comerc_photo', $novoNomeFoto);
            
            $resposta = [ 
                'estado' => 'sucesso',
                'foto' => $novoNomeFoto,
                'doc' => $ficheiro_validacao
            ];
            return json_encode($resposta,true);

        }
        else{ $novoNomeFoto = $seller->foto;}


        if ($seller->email == $email) { if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('seller.EmailCorrespondsAccount_tx');} }
        elseif($get_email == 1){ return 'o email ja existe';}


        if (($id == $seller->id) && ($seller->email != $email)) {
            $aprovacao = $seller->aprovacao;
        }elseif (($permissoes != 'gestor') && ($seller->ficheiro != $ficheiro_validacao)) {
            $aprovacao = 'em_aprovacao';
        }

        
        //if(($seller->nome != $name_comp) || ($seller->email != $email) || ($seller->telefone != $contacto) || ($seller->foto != $novoNomeFoto) || ($seller->ficheiro != $ficheiro_validacao) || ($seller->tipo != $permissoes)){
        \DB::table('comerciantes')
            ->where('id',$id)
            ->update([
                'nome' => $nome,
                'telefone' => $contacto,
                'foto' => $novoNomeFoto,
                'ficheiro' => $ficheiro_validacao,
                'data_registo' => \Carbon\Carbon::now()->timestamp,
                'ultimo_acesso' => \Carbon\Carbon::now()->timestamp,
                'tipo' => $permissoes,
                'aprovacao' => $aprovacao,
                'estado' => $estado
            ]);
        //}
  
        //Enviar email de validação 
        if ($email != $seller->email) {
            
            $token_alteracao = str_random(12);
            \DB::table('comerciantes')
                ->where('id',$id)
                ->update([
                    'token_alteracao' => $token_alteracao,
                    'email_alteracao' => $email
                ]);
     
            $dados = ['token'=>$token_alteracao];
          
            \Mail::send('seller.emails.pages.resendEmail',['dados' => $dados], function($message) use ($request){
                $message->to($request->email,$request->nome)->subject(trans('site_v2.Validation_Email_txt'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });

            $alterado = 'sim';
        }

        //Enviar email para a UNIVERSAL
        if (($aprovacao != $seller->aprovacao) && ($permissoes != 'gestor')) {

            $dados = ['id_comerciante'=>$id,'nome_empresa'=>$company->nome,'id_empresa'=>$company->id];
           
            \Mail::send('seller.emails.pages.sendInfoAccount',['dados' => $dados], function($message){
                $message->to('site@universal.com.pt','')->subject(trans('seller.Data_Approval'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });
        }

        Cookie::queue('cookie_comerc_type', $permissoes);
        
        $resposta = [ 
            'estado' => 'sucesso',
            'foto' => $novoNomeFoto,
            'doc' => $ficheiro_validacao,
            'alterado' => $alterado,
            'aprovacao' => $aprovacao,
            'mensagem' => trans('seller.Successfully_sent_email_txt') .$email.'.'
        ];

    
        return json_encode($resposta,true);    
    }

    public function cancelEmailSeller(){
        
        \DB::table('comerciantes')
            ->where('id',Cookie::get('cookie_comerc_id'))
            ->update([ 'email_alteracao' => '',
                        'token_alteracao' => ''
                    ]);

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' => trans('site_v2.CancelSucess')
        ];

        return json_encode($resposta,true);
    }

    public function resendEmailSeller(){
        $seller = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

        $dados = ['token' => $seller->token_alteracao];
        \Mail::send('seller.emails.pages.resendEmail',['dados' => $dados], function($message) use ($seller){
            $message->to($seller->email_alteracao,$seller->nome)->subject(trans('site_v2.Validation_Email_txt'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });

        $resposta = [
           'estado' => 'sucesso',
           'mensagem' => trans('site_v2.Send_successfully')
        ];

        return json_encode($resposta,true);
    }
}