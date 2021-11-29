<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Users extends Controller{

    private $dados = [];
     

    public function index(){
        
        $this->dados['headTitulo'] = trans('seller.t_users');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Users';

        $this->dados['moradas_armazem'] = \DB::table('moradas')
                                            ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                            ->where('tipo', 'morada_armazem')
                                            ->get();

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

		$empresa_comerc = \DB::table('comerciantes')
                               ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                               ->get();

        $this->dados['empresa_admin'] = \DB::table('comerciantes')
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->where('tipo','<>','comerciante')
                                           ->where('estado','ativo')
                                           ->where(function($query){
                                                $query->where('aprovacao', '')
                                                      ->orWhere('aprovacao','aprovado');
                                            })
                                           ->count();
    
        $array_comerc = [];
        foreach($empresa_comerc as $val){
            $morada = \DB::table('comerc_morada')->where('comerc_morada.id_comerciante',$val->id)->get();

            $morada_id = [];
            foreach($morada as $valor){
                $morada_id[] = ['id' => $valor->id_morada];
            }

            $array_comerc[] = [
                'id' => $val->id,
                'nome' => $val->nome,
                'email' => $val->email,
                'telefone' => $val->telefone,
                'ficheiro' => $val->ficheiro,
                'foto' => $val->foto,
                'tipo' => $val->tipo,
                'estado' => $val->estado,
                'id_morada' => $morada_id
            ];
        }
                
        $this->dados['array_comerc'] = $array_comerc;
        $this->dados['empresa_comerc'] = $empresa_comerc;
 
        
        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/users',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }

    public function addUser(Request $request){

    	$nome = trim($request->nome);
        $email = filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) ? filter_var(trim($request->email), FILTER_VALIDATE_EMAIL) : '';
        $contacto = trim($request->contacto) ? trim($request->contacto) : 0;
        $foto = $request->file('foto');
        $permissao_user = trim($request->permissao_user);
        $id_morada = $request->id_morada;
        $doc_validate = $request->file('doc_validate');
        $img_value = $request->img_value;
        $id_seller = $request->id_seller;
        $ficheiro_v = $request->ficheiro_v;
        $token = str_random(12);


        $company = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
        
        if(empty($nome)){return trans('seller.Field_name_txt');}
        if(empty($email)) {return trans('seller.Field_email_txt');}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return trans('site_v2.FormatInvalidEmail'); }
        if($permissao_user != 'gestor'){ if(empty($doc_validate) && empty($ficheiro_v)){return trans('seller.DocumentValidation');}}

        $get_email = \DB::table('comerciantes')->select('email')->where('email',$email)->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('id','<>',$id_seller)->count();
        $get_email_ativo = \DB::table('comerciantes')->select('email')->where('email',$email)->where('estado','ativo')->where('id','<>',$id_seller)->count();
        $get_email_user = \DB::table('utilizadores')->where('email',$email)->orWhere('email_alteracao',$email)->count();
        $seller = \DB::table('comerciantes')->where('id',$id_seller)->first();
       
        if ($id_seller) {

            if (($seller->email != $email) && ($get_email == 1) || ($get_email_ativo == 1) || ($get_email_user == 1)) { return trans('site_v2.EmailCorrespondAccount');}

            if (($seller->nome != $nome) || ($seller->email != $email) || (($seller->telefone != $contacto) && ($contacto != 0)) || ($seller->tipo != $permissao_user)) {

                if (($permissao_user == 'gestor') && ($seller->email != $email)) {
                    $estado = 'pendente';
                    $aprovacao = '';
                    $estado_html = '<i class="fas fa-circle dashboard-invoice-spent"></i>'.trans('seller.pending');
                }
                elseif((($permissao_user != 'gestor') && ($seller->email != $email)) || ( ($permissao_user != 'gestor') && ($seller->tipo == 'gestor') ) ){

                    if (($permissao_user != 'gestor') && ($seller->tipo == 'gestor') && (($seller->nome == $nome) && ($seller->email == $email))) {
                        $estado = 'ativo';
                        $aprovacao = 'aprovado';
                        $estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active');
                    }
                    else{
                        $estado = 'ativo';
                        $aprovacao = 'em_aprovacao';
                        $estado_html = '<i class="fas fa-circle dashboard-invoice-resgisted"></i>'.trans('seller.on_approval');
                    }
                    
                }elseif(($permissao_user == 'gestor') && ($seller->estado == 'ativo')) {
                    $estado = 'ativo';
                    $aprovacao = '';
                    $estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active');
                }
                else{
                    if ($seller->aprovacao == 'reprovado') {
                        $estado = 'ativo';
                        $aprovacao = 'em_aprovacao';
                        $estado_html = '<i class="fas fa-circle dashboard-invoice-resgisted"></i>'.trans('seller.on_approval');
                    }else{
                        $estado = $seller->estado;
                        $aprovacao = $seller->aprovacao;
                        $estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active'); 
                    }
                    
                }
                
                \DB::table('comerciantes')
                    ->where('id',$id_seller)
                    ->update([
                        'nome' => $nome,
                        'email' => $email,
                        'telefone' => $contacto,
                        'tipo' => $permissao_user,
                        'aprovacao' => $aprovacao,
                        'estado' => $estado
                    ]);
            }
            else{
                if($seller->aprovacao == 'em_aprovacao'){$estado_html = '<i class="fas fa-circle dashboard-invoice-resgisted"></i>'.trans('seller.on_approval');}
                elseif(($seller->aprovacao == 'aprovado') && ($seller->estado == 'ativo')){$estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active');}
                elseif(($seller->aprovacao == '') && ($seller->estado == 'ativo')){$estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active');}
                elseif ($seller->estado == 'pendente') { $estado_html = '<i class="fas fa-circle dashboard-invoice-spent"></i>'.trans('seller.pending');
                }
                else{$estado_html = '<i class="fas fa-circle dashboard-invoice-reproved"></i>'.trans('seller.disapproved');}

                $estado = $seller->estado;
                $aprovacao = $seller->aprovacao;
            }
            
            $tipo = 'edit';
            \DB::table('comerc_morada')->where('id_comerciante',$id_seller)->delete();
        }
        else{

            if(($get_email == 1) || ($get_email_ativo == 1) || ($get_email_user == 1)){ return trans('site_v2.EmailCorrespondAccount');} 

            if ($permissao_user == 'gestor') {
                $estado = 'pendente';
                $aprovacao = '';
                $estado_html = '<i class="fas fa-circle dashboard-invoice-spent"></i>'.trans('seller.pending');
            }
            else{
                $estado = 'pendente';
                $aprovacao = 'em_aprovacao';
                $estado_html = '<i class="fas fa-circle dashboard-invoice-resgisted"></i>'.trans('seller.on_approval');
            }

            $password = str_random(6);

            $id_seller = \DB::table('comerciantes')
                        ->insertGetId([
                            'token' => $token,
                            'token_alteracao' => $token,
                            'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                            'nome' => $nome,
                            'email' => $email,
                            'password' => bcrypt($password),
                            'telefone' => $contacto,
                            'tipo' => $permissao_user,
                            'data_registo' => \Carbon\Carbon::now()->timestamp,
                            'ultimo_acesso' => \Carbon\Carbon::now()->timestamp,
                            'estado' => $estado,
                            'aprovacao' => $aprovacao 
                        ]);

            $tipo = 'add';
            //$get_comerc_new = \DB::table('comerciantes')->where('email',$email)->count();

            $dados = [ 
                'token' => $token,
                'nome_empresa' => $company->nome,
                'email_gestor' => $email,
                'pass_gestor' => $password
            ];

            \Mail::send('seller.emails.pages.newAccount',['dados' => $dados], function($message) use ($request){
                $message->to($request->email,$request->nome)->subject(trans('site_v2.Validate_Account'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });


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
        
            $seller_new = \DB::table('comerciantes')->where('id',$id_seller)->first();
        }



        $novoNome='';
        if (count($foto)) {

            if(!empty($seller->foto)) { \File::delete(base_path().'/public_html/img/comerciantes/'.$seller->foto); }

            $antigoNome = '';
            
            $cache = str_random(3);
            $extension = strtolower($foto->getClientOriginalExtension());
            
            $ficheiro = '';
            if (($extension != 'jpg') && ($extension != 'png')) { return trans('site_v2.Unsupported_extension'); }

            $novoNome = 'comerc_'.$id_seller.'_'.$cache.'.'.$extension;

            $pasta = base_path('public_html/img/comerciantes/');
            $width = 300; $height = 300;

            $uploadImage = New uploadImage;
            $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);

            $getName = $foto->getPathName();

            move_uploaded_file($getName, $pasta.$novoNome);

            \DB::table('comerciantes')->where('id',$id_seller)
                ->update([
                    'foto' => $novoNome,
                    'ultimo_acesso' => \Carbon\Carbon::now()->timestamp,
                ]);
            
        }
        else{ $novoNome = $img_value; }


        $ficheiro_validacao = '';
        if (count($doc_validate)) {

            $cache = str_random(3);

            if(!empty($seller->ficheiro)) { \File::delete(base_path().'/public_html/doc/companies/'.$seller->ficheiro);}

            $destinationPath = base_path('public_html/doc/companies/');
            $extension = strtolower($doc_validate->getClientOriginalExtension());
            $getName = $doc_validate->getPathName();

            $ficheiro_validacao = 'company_'.$company->id.'_user_'.$id_seller.'_'.$cache.'.'.$extension;

            \DB::table('comerciantes')->where('id',$id_seller)->update([ 'ficheiro' => $ficheiro_validacao ]);

            if (($permissao_user != 'gestor')){

                $aprovacao = 'em_aprovacao';
                $estado_html = '<i class="fas fa-circle dashboard-invoice-resgisted"></i>'.trans('seller.on_approval');

                \DB::table('comerciantes')->where('id',$id_seller)->update([ 'aprovacao' => $aprovacao ]);
            }
            
            move_uploaded_file($getName, $destinationPath.$ficheiro_validacao);
        }
        elseif(isset($seller->ficheiro)){

            if ($permissao_user == 'gestor') {
                \File::delete(base_path().'/public_html/doc/companies/'.$seller->ficheiro);
                \DB::table('comerciantes')->where('id',$id_seller)->update(['ficheiro' => '']);

                $ficheiro_validacao =  '';
            }
            else{
                $ficheiro_validacao =  $seller->ficheiro;
            }
        }
        else{
            $ficheiro_validacao =  '';
        }
        

        $respArmazem = '';
        \DB::table('comerc_morada')->where('id_comerciante',$id_seller)->delete();
        if ($id_morada) {
            foreach($id_morada as $value){

                \DB::table('comerc_morada')
                    ->insert([
                        'id_comerciante' => $id_seller,
                        'id_morada' => trim($value)
                    ]);

                $respArmazem .= '<input id="'.$id_seller.'_armazem'.trim($value).'" class="'.$id_seller.'_update up_check" type="hidden" name="" value="'.trim($value).'">';
            }
        }



        if ($novoNome == '') { $img = 'img/comerciantes/default.svg'; }
        else{$img='img/comerciantes/'.$novoNome;}

        if ($permissao_user == 'admin') { $permissao_trans = trans('seller.Admin');}
        elseif ($permissao_user == 'gestor') { $permissao_trans = trans('seller.Manager');}
        else{$permissao_trans = trans('seller.Seller');}


        if ($contacto == 0) {
            $contacto = '';
        }

        $foto_cookie_user = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

        $icon = '<i style="float:left;line-height:50px;" class="fas fa-times user-delete" onclick="$(\'#id_modal\').val('.$id_seller.');" data-toggle="modal" data-target="#myModalDelete"></i>';
        $text = '('.trans('seller.I').')';
        $margin = 'margin-left30';

        if (Cookie::get('cookie_comerc_type') == 'gestor') {
            if($id_seller != Cookie::get('cookie_comerc_id') && ($permissao_user == 'admin')){
                $icon = '';
                $margin = 'margin-left30';
                $foto_cookie = $foto_cookie_user->foto;
            }
            elseif($id_seller == Cookie::get('cookie_comerc_id')){
                $margin = 'margin-left30';
                $foto_cookie = $foto_cookie_user->foto;
            }else{
                $text = '';
                $margin = 'margin-left20';
                $foto_cookie = $foto_cookie_user->foto;
            }
        }else{
           if ($id_seller != Cookie::get('cookie_comerc_id')) {
            
                $text = '';
                $margin = 'margin-left20';
                $foto_cookie = $foto_cookie_user->foto;
            }
            else{
                $icon='';
                \Cookie::queue('cookie_comerc_photo',$novoNome);
                $foto_cookie = $novoNome;
            } 
        }
        

        if (isset($seller->email) && ($seller->email != $email)) {
           $password = str_random(6);
            \DB::table('comerciantes')->where('id',$id_seller)
            ->update([
                'token_alteracao' => $token,
                'password' => bcrypt($password),
            ]);
            
            $dados = [ 
                'token' => $token,
                'nome_empresa' => $company->nome,
                'email_gestor' => $email,
                'pass_gestor' => $password
            ];

            

            \Mail::send('seller.emails.pages.newAccount',['dados' => $dados], function($message) use ($request){
                $message->to($request->email,$request->nome)->subject(trans('site_v2.Validate_Account'));
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });
        }


        if ($id_seller == Cookie::get('cookie_comerc_id')) {
            Cookie::queue('cookie_comerc_type',$permissao_user);
        }



        $num_empresa_admin = \DB::table('comerciantes')
                                   ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                   ->where('tipo','<>','comerciante')
                                   ->where('estado','ativo')
                                   ->where(function($query){
                                        $query->where('aprovacao', '')
                                              ->orWhere('aprovacao','aprovado');
                                    })
                                   ->count();
   
        $resposta = [
            'estado' => 'sucesso',
            'foto' => $novoNome,
            'foto_cookie' => $foto_cookie,
            'id_seller' => $id_seller,
            'tipo' => $tipo,
            'num_empresa_admin' => $num_empresa_admin,
            'aprovacao' => $aprovacao,
            'conteudo_add' => 
            '<tr id="linha_'.$id_seller.'">
                <td class="display-none sorting_1">'.$id_seller.'</td>
                <td style="line-height:50px;">'.$icon.'<img id="img_'.$id_seller.'" class="user-img '.$margin.'" src="'.$img.'"> '.$nome.'</td>
                <td>'.$email.'</td>
                <td>'.$contacto.'</td>
                <td><a class="tx-navy" href="doc/companies/'.$ficheiro_validacao.'" download>'.$ficheiro_validacao.'</a></td>
                <td>'.$permissao_trans.'</td>
                <td>'.$estado_html.'</td>
                <td class="dashboard-table-details" onclick="editSeller('.$id_seller.',\''.$novoNome.'\',\''.$ficheiro_validacao.'\',\''.$permissao_user.'\');"><i class="fas fa-pencil-alt"></i> '.trans('seller.Edit').'</td>
            </tr>',
            'conteudo_edit' =>'
                <td class="display-none sorting_1">'.$id_seller.'</td>
                <td style="line-height:50px;">'.$icon.'<img id="img_'.$id_seller.'" class="user-img '.$margin.'" src="'.$img.'"> '.$nome.' '.$text.'</td>
                <td>'.$email.'</td>
                <td>'.$contacto.'</td>
                <td><a class="tx-navy" href="doc/companies/'.$ficheiro_validacao.'" download>'.$ficheiro_validacao.'</a></td>
                <td>'.$permissao_trans.'</td>
                <td>'.$estado_html.'</td>
                <td class="dashboard-table-details" onclick="editSeller('.$id_seller.',\''.$novoNome.'\',\''.$ficheiro_validacao.'\',\''.$permissao_user.'\');"><i class="fas fa-pencil-alt"></i> '.trans('seller.Edit').'</td>',
            'conteudo2' => '
                <div id="empresa_'.$id_seller.'">
              
                <input id="'.$id_seller.'_nome" class="'.$id_seller.'_update" type="hidden" name="" value="'.$nome.'">
                <input id="'.$id_seller.'_email" class="'.$id_seller.'_update" type="hidden" name="" value="'.$email.'">
                <input id="'.$id_seller.'_contacto" class="'.$id_seller.'_update" type="hidden" name="" value="'.$contacto.'">
                <input id="'.$id_seller.'_tipo" class="'.$id_seller.'_update up_select" type="hidden" name="" value="'.$permissao_user.'">
                <input id="'.$id_seller.'_ficheiro_v" class="'.$id_seller.'_update" type="hidden" name="" value="'.$ficheiro_validacao.'">
                <input id="'.$id_seller.'_foto" class="'.$id_seller.'_update" type="hidden" name="" value="'.$novoNome.'">
                '.$respArmazem.'
              </div>'
        ];
        
        return json_encode($resposta,true);
    }

    public function deleteSeller(Request $request){

        $id = $request->id;
        $tipo = $request->tipo;

        if ($tipo) {
            $user = \DB::table('empresas_responsaveis')->where('id',$id)->first();
            $count_user = \DB::table('empresas_responsaveis')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('tipo',$tipo)->count();
            if($user->doc_validacao && file_exists(base_path().'/public_html/doc/companies/'.$user->doc_validacao)){
                \File::delete(base_path().'/public_html/doc/companies/'.$user->doc_validacao);
            }
            \DB::table('empresas_responsaveis')->where('id',$id)->delete();
        }else{
            $seller = \DB::table('comerciantes')->where('id',$id)->first();
            if($seller->ficheiro && file_exists(base_path().'/public_html/doc/companies/'.$seller->ficheiro)){
                \File::delete(base_path().'/public_html/doc/companies/'.$seller->ficheiro);
            }

            if($seller->foto && file_exists(base_path().'/public_html/img/comerciantes/'.$seller->foto)){
                \File::delete(base_path().'/public_html/img/comerciantes/'.$seller->foto);
            }

            \DB::table('comerc_morada')->where('id_comerciante',$id)->delete();
            \DB::table('comerciantes')->where('id',$id)->delete();

            $count_user = 0;
        }

        $num_empresa_admin = \DB::table('comerciantes')
                                   ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                   ->where('tipo','<>','comerciante')
                                   ->where('estado','ativo')
                                   ->where(function($query){
                                        $query->where('aprovacao', '')
                                              ->orWhere('aprovacao','aprovado');
                                    })
                                   ->count();
        
        $resposta = [ 
            'estado' => 'sucesso',
            'n_users' => $count_user,
            'num_empresa_admin' => $num_empresa_admin
        ];
        return $resposta;  
    }


    public function photoDelete(Request $request){

        $id = $request->id;

        if ($id) {
            $seller = \DB::table('comerciantes')->where('id',$id)->first();

            if($seller->foto && file_exists(base_path().'/public_html/img/comerciantes/'.$seller->foto)){
                \File::delete(base_path().'/public_html/img/comerciantes/'.$seller->foto);
            }
            \DB::table('comerciantes')->where('id',$id)->update(['foto'=>'']);
        }

        $resposta = [ 
            'estado' => 'sucesso'
        ];
        return $resposta;  
    }      
}