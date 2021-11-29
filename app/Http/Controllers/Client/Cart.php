<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Cart extends Controller{

    private $dados = [];

    public function index(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_cart');
        $this->dados['headDescricao'] = trans('site_v2.d_cart');
        $this->dados['separador'] = 'page_client_cart';

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


        $this->dados['carrinho_user'] = \DB::table('carrinho')
                                                    ->where('id_utilizador',Cookie::get('cookie_user_id'))
                                                    ->where('estado','atual')
                                                    ->first();

       
        $this->dados['pontos_falta'] = 0;
        $pontos_utilizados = 0;
        if ($this->dados['carrinho_user']) {
            
            $car = \DB::table('carrinho_linha')
                        ->select('carrinho_linha.*','carrinho_linha.id as id_linha','premios.*','premios.valor_cliente')
                        ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                        ->where('carrinho_linha.id_carrinho',$this->dados['carrinho_user']->id)
                        ->get();

            $valor = 0;
            $pontos_t = 0;
            foreach ($car as $value) {
                
                $pontos = 0;
                if (($value->quantidade*$value->valor_cliente) > $value->pontos_utilizados) {
                    $pontos = ($value->quantidade*$value->valor_cliente) - $value->pontos_utilizados;
                }

                $valor = $valor + $pontos;
                $pontos_utilizados = $pontos_utilizados + $value->pontos_utilizados;

                $valor_qtd = $value->quantidade * $value->valor_cliente;
                $pontos_t = $pontos_t + $valor_qtd;
            }

            


            if ($valor > 0) { $pontos_restantes = ($utilizadores->pontos - $pontos_utilizados) - $valor;}
            else{ $pontos_restantes = $utilizadores->pontos - $pontos_utilizados; }

            if ($pontos_restantes <= 0) {
                $pontos_rest = 0;
            }else{
                $pontos_rest = $pontos_restantes;
            }
            
            $this->dados['pontos_restantes'] = $pontos_rest;

            if ($utilizadores->pontos > $pontos_utilizados) {
                $pontos_r = $utilizadores->pontos - $pontos_utilizados;
                $variavel = $pontos_r;

                foreach ($car as $value) {
                    $pontos_alterar = $value->pontos_utilizados;
                    $pontos_falta = ($value->quantidade * $value->valor_cliente) - $value->pontos_utilizados;

                    if ($value->quantidade * $value->valor_cliente > $value->pontos_utilizados) {
                        if (($variavel > 0) && $variavel > $pontos_falta) {
                            if ($utilizadores->pontos - $pontos_utilizados >= $pontos_falta) {
                                $pontos_alterar = $value->pontos_utilizados + $pontos_falta;
                                $variavel = $variavel - $pontos_falta;
                            
                            }
                        }
                        else{
                            $pontos_alterar = $value->pontos_utilizados + $variavel;
                            $variavel = 0;
                           
                        }
                    }

                    \DB::table('carrinho_linha')->where('id',$value->id_linha)->update(['pontos_utilizados' => $pontos_alterar]);
                }
            }

            $car_new = \DB::table('carrinho_linha')
                        ->select('carrinho_linha.*','carrinho_linha.id as id_linha','premios.*','premios.valor_cliente')
                        ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                        ->where('carrinho_linha.id_carrinho',$this->dados['carrinho_user']->id)
                        ->get();

            $valor = 0;
            foreach ($car_new as $val) {
                $pontos = 0;
                if (($val->quantidade*$val->valor_cliente) > $val->pontos_utilizados) {
                    $pontos = ($val->quantidade*$val->valor_cliente) - $val->pontos_utilizados;
                }
                $valor = $valor + $pontos;
            }

            $ponto_em_euros = \DB::table('configuracoes')->where('tag','ponto_em_euros')->first();
            $this->dados['valor_euro'] = number_format($valor * $ponto_em_euros->valor, 2, ',', '');

            $this->dados['pontos_falta'] = $valor;
            $this->dados['car'] = $car_new;  
        }
          
        return view('client/pages/cart',$this->dados);
    }

    public function cartSucess(){
        $this->dados['headTitulo'] = trans('site_v2.t_sucesso');
        $this->dados['headDescricao'] = trans('site_v2.d_cart');
        $this->dados['separador'] = 'page_cart_sucess';


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

        $pontos_utilizados_car = \DB::table('carrinho_linha')
                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                    ->where('carrinho.estado','atual')
                                    ->sum('carrinho_linha.pontos_utilizados');

        $linha_car = \DB::table('carrinho_linha')
                        ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                        ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                        ->where('carrinho.estado','atual')->get();

        //update nos códigos, decrementado o valor
        //update no estado do carrinho

        $rotulos_user = \DB::table('rotulos_utilizador')->where('id_utilizador',Cookie::get('cookie_user_id'))->where('estado','disponivel')->get();
        $pontos_restantes = $utilizadores->pontos;
    
        $variavel = $pontos_utilizados_car;

        foreach ($rotulos_user as $value) {

            $valor_rotulo = $value->valor_final;

            if (($variavel >= $valor_rotulo)){
                
                \DB::table('rotulos_utilizador')->where('id',$value->id)
                    ->update([
                        'valor_final' => 0,
                        'estado' => 'indisponivel'
                    ]);

                $variavel = $variavel - $valor_rotulo;
            }
            elseif ($variavel < 0) {

                \DB::table('rotulos_utilizador')->where('id',$value->id)
                    ->update([
                        'valor_final' => $value->valor_final,
                        'estado' => 'disponivel'
                    ]);
            }
            else{
                
                $valor_final = $value->valor_final - $variavel;

                \DB::table('rotulos_utilizador')->where('id',$value->id)
                    ->update([
                        'valor_final' => $valor_final,
                        'estado' => 'disponivel'
                    ]);

                $variavel = $variavel - $valor_rotulo;
            }
        }

        
        $dados_fatura = \DB::table('carrinho')->where('id_utilizador',Cookie::get('cookie_user_id'))->where('estado','atual')->first();

        if($dados_fatura){

            //return '1';

            $car = \DB::table('carrinho_linha')
                        ->select('carrinho_linha.*','carrinho_linha.id as id_linha','premios.*','premios.nome_'.$lang.' AS produto')
                        ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                        ->where('carrinho_linha.id_carrinho',$dados_fatura->id)
                        ->get();

            $valor_premio = \DB::table('carrinho_linha')
                                ->select('carrinho_linha.*','premios.valor_cliente')
                                ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                                ->where('carrinho_linha.id_carrinho',$dados_fatura->id)->get();
        

            $valor = 0;
            $pontos_utilizados = 0;
            foreach ($valor_premio as $value) {

                $pontos = 0;
                if (($value->quantidade*$value->valor_cliente) > $value->pontos_utilizados) {
                    $pontos = ($value->quantidade*$value->valor_cliente) - $value->pontos_utilizados;
                }

                $valor = $valor + $pontos;
                $pontos_utilizados = $pontos_utilizados + $value->pontos_utilizados;
            }


            if ($valor > 0) {
                $pontos_restantes = 0;
            }else{
                $pontos_restantes = $utilizadores->pontos - $pontos_utilizados;
            }

    
            \DB::table('utilizadores')
                ->where('id', Cookie::get('cookie_user_id'))
                ->update([
                    'pontos' => $pontos_restantes
                ]);

            $ponto_em_euros = \DB::table('configuracoes')->where('tag','ponto_em_euros')->first();

            //Atualizar cookies pontos
            \Cookie::queue('cookie_user_points', $pontos_restantes);


            //ENVIAR EMAIL COM RESUMO DA COMPRA
            $dados = [
                'id_car' => $dados_fatura->id,
                'nome_fact' => $dados_fatura->nome_fact,
                'email_fact' => $dados_fatura->email_fact,
                'contacto_fact' => $dados_fatura->contacto_fact,
                'nif_fact' => $dados_fatura->nif_fact,
                'morada_fact' => $dados_fatura->morada_fact,
                'morada_opc_fact' => $dados_fatura->morada_opc_fact,
                'code_post_fact' => $dados_fatura->code_post_fact,
                'cidade_fact' => $dados_fatura->cidade_fact,
                'pais_fact' => 'Portugal - Continental',
                'nome_entrega' => $dados_fatura->nome_entrega,
                'email_entrega' => $dados_fatura->email_entrega,
                'contacto_entrega' => $dados_fatura->contacto_entrega,
                'morada_entrega' => $dados_fatura->morada_entrega,
                'morada_opc_entrega' => $dados_fatura->morada_opc_entrega,
                'code_post_entrega' => $dados_fatura->code_post_entrega,
                'cidade_entrega' => $dados_fatura->cidade_entrega,
                'pais_entrega' => 'Portugal - Continental',
                'car' => $car,
                'valor_euro' => money_format('%(#1n', $valor*$ponto_em_euros->valor),
                'pontos_falta' => $valor
            ];
            
            \Mail::send('client.emails.pages.cartSummary',['dados' => $dados], function($message) use ($dados){
                $message->to($dados['email_fact'],'')->subject('Resumo da Encomenda');
                $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
            });

        }

        
        \DB::table('carrinho')->where('id_utilizador',Cookie::get('cookie_user_id'))
            ->update([
                'estado' => 'processamento'
            ]);
        
        \Cookie::queue('cookie_user_cart', '0');
        $this->dados['pt_rest'] = $pontos_restantes;

        return view('client/pages/cart-sucess',$this->dados);
    }

    public function cartBilling(){

        $this->dados['headTitulo'] = trans('site_v2.t_data_invoice');
        $this->dados['headDescricao'] = trans('site_v2.d_cart');
        $this->dados['separador'] = 'page_cart_billing';

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

        $this->dados['dados_fatura'] = \DB::table('carrinho')
                                            ->where('id_utilizador', Cookie::get('cookie_user_id'))
                                            ->where('estado','atual')
                                            ->first();

        $this->dados['dados_faturacao'] = \DB::table('utilizadores_morada')
                                                ->where('id_utilizador', Cookie::get('cookie_user_id'))
                                                ->where('tipo','morada_faturacao')
                                                ->first();

        $this->dados['dados_entrega'] = \DB::table('utilizadores_morada')
                                            ->where('id_utilizador', Cookie::get('cookie_user_id'))
                                            ->where('tipo','morada_entrega')
                                            ->first();



        $this->dados['id_carrinho'] = \DB::table('carrinho_linha')
                                        ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                        ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                        ->where('carrinho.estado','atual')
                                        ->first();


        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                    ->where('carrinho.estado','atual')
                                    ->sum('carrinho_linha.quantidade');     
       
        Cookie::queue('cookie_user_cart', $this->dados['carrinho_utilizador'] );   

        return view('client/pages/cartBilling',$this->dados);
    }

    public function addBilling(Request $request){
        //colocar os avisos e adicionar a faturação a base de dados

        $id_carrinho = $request->id;
        $name = $request->name;
        $email = $request->email;
        $contacto = $request->contacto;
        $nif = $request->nif;
        $morada = $request->morada;
        $morada_opc = $request->adress_opc;
        $codigo_postal = $request->codigo_postal;
        $cidade = $request->cidade;
        $nota = $request->nota;
        $name_alternativo = $request->name_alternativo;
        $email_alternativo = $request->email_alternativo;
        $contacto_alternativo = $request->contacto_alternativo;
        $morada_alternativo = $request->morada_alternativo;
        $morada_opc_alternativo = $request->adress_opc_alternativo;
        $cod_postal_alternativo = $request->cod_postal_alternativo;
        $cidade_alternativo = $request->cidade_alternativo;
        $new_adress = $request->new_adress;


        $morada_faturacao = \DB::table('utilizadores_morada')->where('id_utilizador', Cookie::get('cookie_user_id'))->where('tipo','morada_faturacao')->first();
        $morada_entrega = \DB::table('utilizadores_morada')->where('id_utilizador', Cookie::get('cookie_user_id'))->where('tipo','morada_entrega')->first();
        

        if(empty($morada_faturacao->nome)){$nome_faturacao = $name;}else{$nome_faturacao = $morada_faturacao->nome;}
        if(empty($morada_faturacao->email)){$email_faturacao = $email;}else{$email_faturacao = $morada_faturacao->email;}
        if(empty($morada_faturacao->telefone)){$telefone_faturacao = $contacto;}else{$telefone_faturacao = $morada_faturacao->telefone;}
        if(empty($morada_faturacao->nif)){$nif_faturacao = $nif;}else{$nif_faturacao = $morada_faturacao->nif;}
        if(empty($morada_faturacao->morada)){$morada_fat = $morada;}else{$morada_fat = $morada_faturacao->morada;}
        if(empty($morada_faturacao->morada_opc)){$morada_opc_fat = $morada_opc;}else{$morada_opc_fat = $morada_faturacao->morada_opc;}
        if(empty($morada_faturacao->codigo_postal)){$codigo_postal_fat = $codigo_postal;}else{$codigo_postal_fat = $morada_faturacao->codigo_postal;}
        if(empty($morada_faturacao->cidade)){$cidade_faturacao = $cidade;}else{$cidade_faturacao = $morada_faturacao->cidade;}
        if(empty($morada_faturacao->data)){$data_faturacao = \Carbon\Carbon::now()->timestamp;}else{$data_faturacao = $morada_faturacao->data;}


        if (isset($morada_faturacao->id)) {
            \DB::table('utilizadores_morada')
                ->where('tipo','morada_faturacao')
                ->where('id_utilizador',Cookie::get('cookie_user_id'))
                ->update([
                    'nome' => $nome_faturacao,
                    'email' => $email_faturacao,
                    'telefone' => $telefone_faturacao,
                    'nif' => $nif_faturacao,
                    'morada' => $morada_fat,
                    'morada_opc' => $morada_opc_fat,
                    'codigo_postal' => $codigo_postal_fat,
                    'cidade' => $cidade_faturacao,
                    'pais' => 'Portugal - Continental',
                    'data' => $data_faturacao
                ]);
        }
        else{
            \DB::table('utilizadores_morada')
                ->insert([
                    'id_utilizador' => Cookie::get('cookie_user_id'),
                    'nome' => $nome_faturacao,
                    'email' => $email_faturacao,
                    'telefone' => $telefone_faturacao,
                    'nif' => $nif_faturacao,
                    'morada' => $morada_fat,
                    'morada_opc' => $morada_opc_fat,
                    'codigo_postal' => $codigo_postal_fat,
                    'cidade' => $cidade_faturacao,
                    'pais' => 'Portugal - Continental',
                    'tipo' => 'morada_faturacao',
                    'data' => $data_faturacao
                ]);
        }


        if(empty($morada_entrega->nome)){$nome_entrega = $name_alternativo;}else{$nome_entrega = $morada_entrega->nome;}
        if(empty($morada_entrega->email)){$email_entrega = $email_alternativo;}else{$email_entrega = $morada_entrega->email;}
        if(empty($morada_entrega->telefone)){$telefone_entrega = $contacto_alternativo;}else{$telefone_entrega = $morada_entrega->telefone;}
        if(empty($morada_entrega->morada)){$moradas_entrega = $morada_alternativo;}else{$moradas_entrega = $morada_entrega->morada;}
        if(empty($morada_entrega->morada_opc)){$morada_opc_entrega = $morada_opc_alternativo;}else{$morada_opc_entrega = $morada_entrega->morada_opc;}
        if(empty($morada_entrega->codigo_postal)){$codigo_postal_entrega = $cod_postal_alternativo;}else{$codigo_postal_entrega = $morada_entrega->codigo_postal;}
        if(empty($morada_entrega->cidade)){$cidade_entrega = $cidade_alternativo;}else{$cidade_entrega = $morada_entrega->cidade;}
        if(empty($morada_entrega->data)){$data_entrega = \Carbon\Carbon::now()->timestamp;}else{$data_entrega = $morada_entrega->data;}


        if(isset($morada_entrega->id)){
            \DB::table('utilizadores_morada')
                ->where('tipo','morada_entrega')
                ->where('id_utilizador',Cookie::get('cookie_user_id'))
                ->update([
                    'nome' => $nome_entrega,
                    'email' => $email_entrega,
                    'telefone' => $telefone_entrega,
                    'morada' => $moradas_entrega,
                    'morada_opc' => $morada_opc_entrega,
                    'codigo_postal' => $codigo_postal_entrega,
                    'cidade' => $cidade_entrega,
                    'pais' => 'Portugal - Continental',
                    'data' => $data_entrega
                ]);
        }else{
            \DB::table('utilizadores_morada')
                ->insert([
                    'id_utilizador' => Cookie::get('cookie_user_id'),
                    'nome' => $nome_entrega,
                    'email' => $email_entrega,
                    'telefone' => $telefone_entrega,
                    'morada' => $moradas_entrega,
                    'morada_opc' => $morada_opc_entrega,
                    'codigo_postal' => $codigo_postal_entrega,
                    'cidade' => $cidade_entrega,
                    'pais' => 'Portugal - Continental',
                    'tipo' => 'morada_entrega',
                    'data' => $data_entrega
                ]);
        }

        $valor_premio = \DB::table('carrinho_linha')
                            ->select('carrinho_linha.*','premios.valor_cliente')
                            ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                            ->where('carrinho_linha.id_carrinho',$id_carrinho)->get();


        $valor = 0;
        $pontos_utilizados = 0;
        $pontos_necessarios = 0;
        foreach ($valor_premio as $value) {

            $pontos = 0;
            if (($value->quantidade*$value->valor_cliente) > $value->pontos_utilizados) {
                $pontos = ($value->quantidade*$value->valor_cliente) - $value->pontos_utilizados;
            }

            $valor = $valor + $pontos;
            $pontos_utilizados = $pontos_utilizados + $value->pontos_utilizados;
            $valor_qtd = $value->quantidade * $value->valor_cliente;
            $pontos_necessarios = $pontos_necessarios + $valor_qtd;
        }
        
        $ponto_em_euros = \DB::table('configuracoes')->where('tag','ponto_em_euros')->first();
        $valor_total = $valor*$ponto_em_euros->valor;

        
                    
  
        if ($new_adress == 0) {
     
            if (empty($name)) { return trans('site_v2.Field_name_txt');}
            if (empty($email)) { return trans('site_v2.Field_email_txt');}
            if (empty($morada)) { return trans('site_v2.Field_adress_txt');}
            if (empty($codigo_postal)) { return trans('site_v2.Field_code_txt');}
            if (empty($cidade)) { return trans('site_v2.Field_city_txt');}
            
            
            \DB::table('carrinho')->where('id',$id_carrinho)
                ->update([
                    'nome_fact' => $name,
                    'email_fact' => $email,
                    'contacto_fact' => $contacto,
                    'nif_fact' => $nif,
                    'morada_fact' => $morada,
                    'morada_opc_fact' => $morada_opc,
                    'code_post_fact' => $codigo_postal,
                    'cidade_fact' => $cidade,
                    'pais_fact' => 'Portugal - Continental',
                    'nome_entrega' => $name,
                    'email_entrega' => $email,
                    'contacto_entrega' => $contacto,
                    'morada_entrega' => $morada,
                    'morada_opc_entrega' => $morada_opc,
                    'code_post_entrega' => $codigo_postal,
                    'cidade_entrega' => $cidade,
                    'pais_entrega' => 'Portugal - Continental',
                    'nota' => $nota,
                    'pontos_utilizados' => $pontos_utilizados,
                    'pontos_necessarios' => $pontos_necessarios,
                    'valor_pago' => money_format('%(#1n', $valor_total),
                    'data_pedido' => \Carbon\Carbon::now()->timestamp
                ]);
        }else{
        
            if (empty($name)) { return trans('site_v2.Field_name_txt');}
            if (empty($email)) { return trans('site_v2.Field_email_txt');}
            if (empty($morada)) { return trans('site_v2.Field_adress_txt');}
            if (empty($codigo_postal)) { return trans('site_v2.Field_code_txt');}
            if (empty($cidade)) { return trans('site_v2.Field_city_txt');}
            if (empty($name_alternativo)) { return trans('site_v2.Field_name_delivery');}
            if (empty($email_alternativo)) { return trans('site_v2.Field_email_delivery');}
            if (empty($morada_alternativo)) { return trans('site_v2.Field_adress_delivery');}
            if (empty($cod_postal_alternativo)) { return trans('site_v2.Field_code_delivery');}
            if (empty($cidade_alternativo)) { return trans('site_v2.Field_city_delivery');}

            \DB::table('carrinho')->where('id',$id_carrinho)
                ->update([
                    'nome_fact' => $name,
                    'email_fact' => $email,
                    'contacto_fact' => $contacto,
                    'nif_fact' => $nif,
                    'morada_fact' => $morada,
                    'morada_opc_fact' => $morada_opc,
                    'code_post_fact' => $codigo_postal,
                    'cidade_fact' => $cidade,
                    'pais_fact' => 'Portugal - Continental',
                    'nome_entrega' => $name_alternativo,
                    'email_entrega' => $email_alternativo,
                    'contacto_entrega' => $contacto_alternativo,
                    'morada_entrega' => $morada_alternativo,
                    'morada_opc_entrega' => $morada_opc_alternativo,
                    'code_post_entrega' => $cod_postal_alternativo,
                    'cidade_entrega' => $cidade_alternativo,
                    'pais_entrega' => 'Portugal - Continental',
                    'nota' => $nota,
                    'pontos_utilizados' => $pontos_utilizados,
                    'pontos_necessarios' => $pontos_necessarios,
                    'valor_pago' => money_format('%(#1n', $valor_total),
                    'data_pedido' => \Carbon\Carbon::now()->timestamp
                ]);
        }

        $resposta = [ 'estado' => 'sucesso' ];
        return json_encode($resposta,true);
    }

    public function cartSummary(){
        $this->dados['headTitulo'] = trans('site_v2.t_resume');
        $this->dados['headDescricao'] = trans('site_v2.d_cart');
        $this->dados['separador'] = 'page_cart_summary';


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


        $carrinho_user = \DB::table('carrinho')
                            ->where('id_utilizador',Cookie::get('cookie_user_id'))
                            ->where('estado','atual')
                            ->first();

        if ($carrinho_user) {
            
            $this->dados['car'] = \DB::table('carrinho_linha')
                                    ->select('carrinho_linha.*','carrinho_linha.id as id_linha','premios.*')
                                    ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                                    ->where('carrinho_linha.id_carrinho',$carrinho_user->id)
                                    ->get();


            $valor_premio = \DB::table('carrinho_linha')
                                            ->select('carrinho_linha.*','premios.valor_cliente')
                                            ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                                            ->where('carrinho_linha.id_carrinho',$carrinho_user->id)->get();


            $valor = 0;
            $pontos_utilizados = 0;
            foreach ($valor_premio as $value) {

                $pontos = 0;
                if (($value->quantidade*$value->valor_cliente) > $value->pontos_utilizados) {
                    $pontos = ($value->quantidade*$value->valor_cliente) - $value->pontos_utilizados;
                }

                $valor = $valor + $pontos;
                $pontos_utilizados = $pontos_utilizados + $value->pontos_utilizados;

            }
            
            $ponto_em_euros = \DB::table('configuracoes')->where('tag','ponto_em_euros')->first();

            $valor_total = $valor*$ponto_em_euros->valor;


        
            if ($valor > 0) {
                $pontos_restantes = 0;
            }else{
                $pontos_restantes = $utilizadores->pontos - $pontos_utilizados;
            }
            


            $this->dados['valor_euro'] = money_format('%(#1n', $valor_total);

            $this->dados['valor_stripe'] = str_replace('.', '', money_format('%(#1n', $valor_total));

            $this->dados['pontos_falta'] = $valor;

            $this->dados['valor_premio'] = $valor_premio;
            $this->dados['carrinho_user'] = $carrinho_user;
            $this->dados['pontos_restantes'] = $pontos_restantes;
        }


        return view('client/pages/cartSummary',$this->dados);
    }


    public function addPremiumCart(Request $request){

        $quantidade = $request->quantidade;
        $id_premio = $request->id_premio;
        $valor_premio = $request->valor_premio;
        $variante = $request->variante;
       
        if ($quantidade == 'QUANTIDADE') { return trans('site_v2.Insert_qtd_txt');}
        if ($variante == 'VARIANTE') { return trans('site_v2.Insert_variant_txt');}
        //return $variante;


        
        $cart_user = \DB::table('carrinho')->where('id_utilizador', Cookie::get('cookie_user_id'))->where('estado','atual')->first();
        $user = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        $premios = \DB::table('premios')->where('id',$id_premio)->first();

        $valor_total = $quantidade * $valor_premio;

        $variante_premio = \DB::table('variante_premio')
                                ->leftJoin('variante','variante_premio.id_variante','variante.id')
                                ->where('variante_premio.id',$variante)
                                ->first();
        
        $variante_txt = '';
        if(isset($variante_premio->id)) {
            $variante_txt = $variante_premio->variante_pt.' : '.$variante_premio->valor_pt;
        }

        /*if(($user->pontos < 0) || ($user->pontos == 0)) { $pontos_utilizados = 0; }
        else if(($user->pontos > 0) && ($user->pontos < $valor_total )){ $pontos_utilizados = $user->pontos; }
        else{ $pontos_utilizados = $valor_total; }  */


        if ($cart_user) {
            
            $car_line_all = \DB::table('carrinho_linha')->where('id_carrinho', $cart_user->id)->get();

            $count_pontos_utilizados = 0;
            foreach ($car_line_all as $value) {
                $count_pontos_utilizados+=$value->pontos_utilizados;
            }

            $pontos_a_utilizar = $user->pontos - $count_pontos_utilizados;

            if ($pontos_a_utilizar >= $valor_total) {
                $pontos_utilizados = $valor_total;
            }
            elseif($pontos_a_utilizar < $valor_total){
                $pontos_utilizados = $pontos_a_utilizar;
            }

            $cart_line = \DB::table('carrinho_linha')->where('id_carrinho', $cart_user->id)->where('id_premio',$id_premio)->first();
         
            if ( ($cart_line) && ($cart_line->variante == $variante_txt) ) {

                $qtd_total = $cart_line->quantidade + $quantidade;
                $pontos_total = $cart_line->pontos_utilizados + $pontos_utilizados;

         

                \DB::table('carrinho_linha')->where('id_premio',$id_premio)
                    ->update([
                        'nome' => $premios->nome_pt,
                        'quantidade' => $qtd_total,
                        'variante' => $variante_txt,
                        'pontos_utilizados' => $pontos_total,
                        'pontos_necessarios' => $qtd_total*$valor_premio
                    ]);
            }else{


                \DB::table('carrinho_linha')
                    ->insert([
                        'id_carrinho' => $cart_user->id,
                        'id_premio' => $id_premio,
                        'nome' => $premios->nome_pt,
                        'quantidade' => $quantidade,
                        'variante' => $variante_txt,
                        'pontos_utilizados' => $pontos_utilizados,
                        'pontos_necessarios' => $valor_total
                    ]);
            }
        }
        else{



            if ($user->pontos >= $valor_total) {
                $pontos_utilizados = $valor_total;
            }
            elseif($user->pontos < $valor_total){
                $pontos_utilizados = $user->pontos;
            }

            $id_carrinho = \DB::table('carrinho')
                            ->insertGetId([
                                'id_utilizador' => Cookie::get('cookie_user_id'),
                                'estado' => 'atual',
                                'data' => \Carbon\Carbon::now()->timestamp
                            ]);


            \DB::table('carrinho_linha')
                ->insert([
                    'id_carrinho' => $id_carrinho,
                    'id_premio' => $id_premio,
                    'nome' => $premios->nome_pt,
                    'variante' => $variante_txt,
                    'quantidade' => $quantidade,
                    'pontos_utilizados' => $pontos_utilizados,
                    'pontos_necessarios' => $valor_total
                ]);
        }

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        //$total_pontos = $utilizadores->pontos - ($valor_premio * $quantidade);

        $carrinho_utilizador = \DB::table('carrinho_linha')
                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                    ->where('carrinho.estado','atual')
                                    ->sum('carrinho_linha.quantidade');  


        /*\DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))
            ->update([
                'pontos' => $total_pontos
            ]);

        if ($total_pontos < 0) { $total_pontos = 0; }

        Cookie::queue('cookie_user_points', $total_pontos);*/
        Cookie::queue('cookie_user_cart', $carrinho_utilizador);

      
        $resposta = [ 
            'estado' => 'sucesso',
            //'pontos_user' => $total_pontos,
            'sum_cart' => $carrinho_utilizador 
        ];
        


        return json_encode($resposta,true);
    }

    public function updateQtdPremium(Request $request){

        $valor_premio=trim($request->valor);
        $quantidade_input=trim($request->qtd);
        $id_linha=trim($request->id_linha);
        $id_carrinho=trim($request->id_carrinho);
        $tipo=trim($request->tipo);
        $pontos_falta=trim($request->pontos_falta);
        $ids_product=[];
        $pontos_utilizados=0;
        $pontos_utilizado=0;
        $pontos_prox_linha=0;
        $pontos_prox_linha_up=0;
        $cor=''; 


        $user = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        $car_line = \DB::table('carrinho_linha')->where('id',$id_linha)->first();

        $carrinho = \DB::table('carrinho_linha')
                        ->select('carrinho_linha.*','premios.valor_cliente')
                        ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                        ->where('carrinho_linha.id_carrinho',$id_carrinho)
                        ->where('carrinho_linha.id','<>',$id_linha)
                        ->get();

        $carrinho_sum = \DB::table('carrinho_linha')
                        ->select('carrinho_linha.*','premios.valor_cliente')
                        ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                        ->where('carrinho_linha.id_carrinho',$id_carrinho)
                        ->sum('pontos_utilizados');
        
        
        if(($quantidade_input == 0) && ($quantidade_input !='')){
            $quantidade = 0;
        }
        else{
            
            $quantidade_int = abs(intval($quantidade_input));

            if ($quantidade_int == 0) {
                $quantidade = 1;
            }
            else{
                $quantidade = $quantidade_int;
            }
        }
        

        $valor_total_unidade = $quantidade * $valor_premio;

        if($tipo == 'decrementar'){

            $variavel = $valor_premio;

            if ($car_line->pontos_utilizados < ($car_line->quantidade * $valor_premio) ) {
                
                if (($quantidade * $valor_premio) == 0) {
                    //return '2d';
                    $pontos_utilizados = 0;
                    $variavel = $car_line->pontos_utilizados;
                    //return $variavel;
                }
                elseif ($car_line->pontos_utilizados > ($quantidade * $valor_premio)) {
                    //return '1';
                    $pontos_utilizados = $valor_premio * $quantidade;
                    $variavel = $car_line->pontos_utilizados - $valor_premio;
                    //return $car_line->pontos_utilizados;
                }
                else{
                //return '1d';
                   $pontos_utilizados = $car_line->pontos_utilizados; 
                   $variavel = 0;
                }
                //return '2';
                 //return $pontos_utilizados;
                \DB::table('carrinho_linha')->where('id',$id_linha)
                    ->update([
                        'quantidade' => $quantidade,
                        'pontos_utilizados' => $pontos_utilizados
                    ]);
            }
            elseif($car_line->pontos_utilizados == ($car_line->quantidade * $valor_premio) ){
               
                
                $pontos_utilizados = $car_line->pontos_utilizados - $variavel;
                $variavel = $valor_premio;
                //return $variavel;

                \DB::table('carrinho_linha')->where('id',$id_linha)
                    ->update([
                        'quantidade' => $quantidade,
                        'pontos_utilizados' => $pontos_utilizados
                    ]);

                
            }
            
            //$user_pontos = $user->pontos + $valor_premio;
            //\DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->update([ 'pontos' => $user_pontos ]);

            if ($pontos_utilizados == 0 && $quantidade == 0) { \DB::table('carrinho_linha')->where('id',$id_linha)->delete(); }

            foreach ($carrinho as $value) {
                //return $variavel;

                if (($value->pontos_utilizados < ($value->quantidade * $value->valor_cliente)) || ($value->pontos_utilizados == 0)) {

                    //return $variavel;
                    
                    if ($variavel > ($value->quantidade * $value->valor_cliente)) {

                        //return '1';
                        //$pontos = $variavel - ($value->quantidade * $value->valor_cliente);
                        
                        //$pontos_prox_linha = $pontos + $value->pontos_utilizados;
                        $pontos_prox_linha = $value->quantidade * $value->valor_cliente;
                        $variavel = $variavel - ($value->quantidade * $value->valor_cliente);
                    }
                    elseif($variavel == ($value->quantidade * $value->valor_cliente)){

                        //return '2';
                        $pontos_prox_linha = $variavel;
                        $variavel = $value->pontos_utilizados;
                    }
                    else{
                        if ($variavel == 0) {
                            //return '3';
                            $pontos_prox_linha = $value->pontos_utilizados;
                            $variavel = 0; 
                        }
                        else{
                            //return '4';
                            $pontos_falta = ($value->quantidade * $value->valor_cliente) - $value->pontos_utilizados;

                            if($pontos_falta > $variavel){
                                $pontos_prox_linha = $value->pontos_utilizados + $variavel;
                                $variavel = 0;
                            }
                            else{
                                $pontos_prox_linha = $value->pontos_utilizados + $pontos_falta;
                                $variavel = $variavel - $pontos_falta;
                            }
                            //$pontos_prox_linha = (($value->quantidade * $value->valor_cliente) - $value->pontos_utilizados) + $value->pontos_utilizados;
                            //$variavel = $variavel - (($value->quantidade * $value->valor_cliente) - $value->pontos_utilizados); 
                            //return $variavel;
                        }
                        //return $pontos_prox_linha;
                  
                    }

                    \DB::table('carrinho_linha')->where('id',$value->id)
                        ->update([
                            'pontos_utilizados' => $pontos_prox_linha
                        ]);
                    
                    //$user_pontos = $user->pontos + $valor_premio;
                    //\DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->update([ 'pontos' => $user_pontos ]);
                }
                else{
                    //return 'else';
                    $pontos_prox_linha = $value->pontos_utilizados;
                }
                //return '6';

                if ($value->quantidade * $value->valor_cliente > $pontos_prox_linha) { $cor_prod = 'coral'; }
                else{ $cor_prod = 'preto'; }

                $ids_product[] = [
                    'id_prod' => $value->id,
                    'pontos_uti' => $pontos_prox_linha,
                    'cor_prod' => $cor_prod
                ];
            }

            //return $pontos_utilizados;

            if ($quantidade*$valor_premio > $pontos_utilizados) { $cor = 'coral'; }
            else{ $cor = 'preto'; }
        }
        elseif($tipo == 'incrementar') {

            //$variavel = $valor_premio;
    
            //$pontos_utilizados = $car_line->pontos_utilizados + $variavel;

            
            if ($carrinho_sum < $user->pontos) {
                $pontos_livres = $user->pontos - $carrinho_sum;

                if ($valor_premio <= $pontos_livres) {
                    $pontos_utilizados = $valor_premio + $car_line->pontos_utilizados;
                }
                elseif ($valor_premio > $pontos_livres) {
                    $pontos_utilizados = $pontos_livres + $car_line->pontos_utilizados;
                }
            }
            else{
                $pontos_utilizados = $car_line->pontos_utilizados;
                //$variavel = 0;
            }
            /*if ($pontos_utilizados >= $user->pontos) {
                //return '1';
                $pontos_utilizados = $car_line->pontos_utilizados + $user->pontos;
            }*/

            //return $pontos_utilizados;

            $variavel = 0;

            \DB::table('carrinho_linha')->where('id',$id_linha)
                ->update([
                    'quantidade' => $quantidade,
                    'pontos_utilizados' => $pontos_utilizados
                ]);

            
            

            //$user_pontos = $user->pontos - $valor_premio;
            //\DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->update([ 'pontos' => $user_pontos ]);

            if ($quantidade*$valor_premio > $pontos_utilizados) { $cor = 'coral'; }
            else{ $cor = 'preto'; }
       
        }
        elseif($tipo == 'update'){
            
            $variavel = $valor_premio;
            $qtd_antiga = \DB::table('carrinho_linha')->where('id',$id_linha)->first();
            //$pontos_user = $user->pontos + $qtd_antiga->pontos_utilizados;

            //$pontos_ant = $qtd_antiga->quantidade * $variavel;
            //$p = $user->pontos - $pontos_ant;

            //$qtd_atual = $quantidade - $qtd_antiga->quantidade; 

            if ($quantidade > $qtd_antiga->quantidade) { 
                
                
                /*$user_pontos = ($user->pontos + ($qtd_antiga->quantidade * $variavel)) - ($quantidade*$variavel);

                if ($user->pontos >= ($quantidade*$variavel)) { $pontos_utilizados = $quantidade*$variavel; }
                elseif ($user->pontos <= 0 ) { $pontos_utilizados = $qtd_antiga->pontos_utilizados; }
                else{
                    if(($quantidade*$variavel) >= ($pontos_ant + $user->pontos)){
                        $pontos_utilizados = $pontos_ant + $user->pontos;
                    }
                }*/
                

                if ($carrinho_sum < $user->pontos) {
                    
                    $pontos_livres = $user->pontos - $carrinho_sum;

                    if ($valor_premio <= $pontos_livres) {
                        
                        $pontos_restantes = $carrinho_sum - $car_line->pontos_utilizados;

                        if ($pontos_restantes == 0) {

                            if (($quantidade * $variavel) <= $user->pontos) {
                                $pontos_utilizados = $quantidade * $variavel;
                                $variavel = $user->pontos - $pontos_utilizados;
                            }
                            else{
                                $pontos_utilizados = $pontos_livres + $car_line->pontos_utilizados;
                                $variavel = 0;
                            }
                        }
                        else{
                            $pontos_utilizados = $pontos_restantes + $car_line->pontos_utilizados;
                            $variavel = 0;
                        }
                    }
                    elseif ($valor_premio > $pontos_livres) {
                        //return '3';
                        $pontos_utilizados = $pontos_livres + $car_line->pontos_utilizados;
                        $variavel = $variavel - $pontos_livres;
                    }
                }
                else{
                    //return '2';
                    $pontos_utilizados = $car_line->pontos_utilizados;
                    $variavel = 0;
                }
    
            }
            elseif($quantidade < $qtd_antiga->quantidade){ 
                //return '2';
                
                //$user_pontos = ( $user->pontos + ($qtd_antiga->quantidade * $variavel) ) - ($quantidade*$variavel);
                /*$pontos_utilizados = $quantidade*$variavel;

                if ($qtd_antiga->pontos_utilizados == 0) {
                    $variavel=0;
                }
                else{
                    $variavel = $qtd_antiga->pontos_utilizados - $pontos_utilizados;  
                }*/

                $valor_qtd=$quantidade * $variavel;
                $pontos_necessarios = $valor_qtd - $car_line->pontos_utilizados;
                $pts = $carrinho_sum + $pontos_necessarios;
                $pontos_livres = $user->pontos - $carrinho_sum;
                
                
                if ($quantidade == 0) {
                  
                    $pontos_utilizados = 0;
                    $variavel = $car_line->pontos_utilizados;
                    //return $variavel;
                }
                elseif ($pts >= $user->pontos) {
                 
                    $pontos_utilizados = $car_line->pontos_utilizados;
                    $variavel = 0;
                }
                else{
                    if ($pontos_livres >= $valor_qtd) {
                     
                        $pontos_utilizados = $valor_qtd;
                        $variavel = $pontos_livres - $valor_qtd;
                    }
                    else{

                        $pontos_utilizados = $variavel;
                        $variavel = $car_line->pontos_utilizados - $variavel; 
                        //return $variavel;
                    }
                    //$pontos_utilizados = $car_line->pontos_utilizados - $variavel;
                    //$variavel = $variavel - $pontos_livres;  
                 
                }
                
                if ($pontos_utilizados == 0 && $quantidade == 0) { \DB::table('carrinho_linha')->where('id',$id_linha)->delete(); }
            }
            else{
       
                //$user_pontos = $user->pontos;
                $pontos_utilizados = $qtd_antiga->pontos_utilizados;  
                $variavel = 0;
            }

   

            //if ($user->pontos > 0){

                \DB::table('carrinho_linha')->where('id',$id_linha)
                    ->update([
                        'quantidade' => $quantidade,
                        'pontos_utilizados' => $pontos_utilizados
                    ]);
            //}
            //elseif( ($user->pontos == 0) || ($user->pontos < 0) ){


                //$pontos_uti = $qtd_antiga->pontos_utilizados - ($variavel*$quantidade);
                
                
                //if ($pontos_uti < 0) { $pontos_utilizados = $qtd_antiga->pontos_utilizados; }
                //else{ $pontos_utilizados = $qtd_antiga->pontos_utilizados - $variavel; }

                //return $pontos_utilizado;

                /*\DB::table('carrinho_linha')->where('id',$id_linha)
                    ->update([
                        'pontos_utilizados' => $pontos_utilizados,
                        'quantidade' => $quantidade,
                        'data' => \Carbon\Carbon::now()->timestamp
                    ]);*/
                
                //$ids_product = [];
                //return $variavel;
                if (($quantidade < $qtd_antiga->quantidade) && ($variavel > 0)) {
                    //return '1';
                    //$variavel_pontos_uti = $quantidade*$valor_premio;
                    $ids_product = [];

                    foreach ($carrinho as $value) {
                        // $value->id;
                        if (($value->pontos_utilizados < ($value->quantidade * $value->valor_cliente)) || ($value->pontos_utilizados == 0)) {
                            
                            if ($variavel >= ($value->quantidade * $value->valor_cliente)) {
                                

                                //$pontos = $variavel - $value->pontos_utilizados;
                                $pontos_prox_linha_up = $value->quantidade * $value->valor_cliente;

                                /*if (($pontos_prox_linha_up + $user->pontos) < 0) {
                                    $pontos_prox_linha_up = $pontos + $value->pontos_utilizados;
                                }*/
                                if ($variavel == $pontos_prox_linha_up) {
                                    $variavel = $value->pontos_utilizados;
                                }
                                else{
                                    $variavel = $variavel - $pontos_prox_linha_up;
                                }
                                
                                
                                /*if (empty($user_pontos)) {
                                    $user_pontos = ($user->pontos + $qtd_antiga->pontos_utilizados) + (($qtd_antiga->quantidade*$valor_premio) - ($quantidade*$valor_premio));
                                }*/
                            }else{
                               
                                $pontos_falta = ($value->quantidade * $value->valor_cliente) - $value->pontos_utilizados;
                                if($pontos_falta >= $variavel){
                                    $pontos_prox_linha_up = $variavel + $value->pontos_utilizados;
                                    $variavel = 0;
                                }
                                else{
                                    $pontos_prox_linha_up = $pontos_falta + $value->pontos_utilizados;
                                    $variavel = $variavel - $pontos_falta;
                                }
                                
                                
                            }
                            
                            if ( (($value->quantidade * $value->valor_cliente) < $value->pontos_utilizados )) {
                                return '5';
                                \DB::table('carrinho_linha')->where('id',$value->id)->update(['pontos_utilizados' => $pontos_prox_linha_up]);
                            }

                            \DB::table('carrinho_linha')->where('id',$value->id)->update(['pontos_utilizados' => $pontos_prox_linha_up]);
                        
                            //\DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->update([ 'pontos' => $user_pontos ]);
                        }
                        else{
                            $pontos_prox_linha_up = $value->pontos_utilizados;
                            $variavel = $variavel;
                        }

                        //return '3';

                        if ($value->quantidade * $value->valor_cliente > $pontos_prox_linha_up) { $cor_prod = 'coral'; }
                        else{ $cor_prod = 'preto'; }

                        $ids_product[] = [
                            'id_prod' => $value->id,
                            'pontos_uti' => $pontos_prox_linha_up,
                            'cor_prod' => $cor_prod
                        ];
   
                    }
                }
            //}

            //\DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->update([ 'pontos' => $user_pontos ]);

            if ($quantidade*$valor_premio > $pontos_utilizados) { $cor = 'coral'; }
            else{ $cor = 'preto'; }
        }



        $car = \DB::table('carrinho_linha')
                    ->select('carrinho_linha.*','carrinho_linha.id as id_linha','premios.*')
                    ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                    ->where('carrinho_linha.id_carrinho',$id_carrinho)
                    ->get();

        

        $valor_total = 0;
        foreach ($car as $car_uti) {
            $valor_qtd = $car_uti->quantidade * $car_uti->valor_cliente;
            $valor_total = $valor_total + $valor_qtd;
        }



        $valor_premio = \DB::table('carrinho_linha')
                            ->select('carrinho_linha.*','premios.valor_cliente')
                            ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                            ->where('carrinho_linha.id_carrinho',$id_carrinho)->get();

        $valor = 0;
        $pontos_uti = 0;
        
        foreach ($valor_premio as $value) {

            $pontos = 0;
            if (($value->quantidade*$value->valor_cliente) > $value->pontos_utilizados) {
                $pontos = ($value->quantidade*$value->valor_cliente) - $value->pontos_utilizados;
            }

            $valor = $valor + $pontos;
            $pontos_uti = $pontos_uti + $value->pontos_utilizados;
        }


        
        //Cookie::queue('cookie_user_points', $user_pontos);

        $ponto_em_euros = \DB::table('configuracoes')->where('tag','ponto_em_euros')->first();

        /*if ($user_pontos > 0 || $user_pontos == 0) { $valor_euro = 0; }
        else{ $valor_euro = $valor*$ponto_em_euros->valor; }*/
        $valor_euro = number_format($valor*$ponto_em_euros->valor, 2, ',', '');

        if ($valor > 0) {
            $pontos_restantes = 0;
        }else{
            $pontos_restantes = $user->pontos - $pontos_uti;
        }

        $carrinho_utilizador = \DB::table('carrinho_linha')
                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                    ->where('carrinho.estado','atual')
                                    ->sum('carrinho_linha.quantidade');     
       
        Cookie::queue('cookie_user_cart', $carrinho_utilizador);   

        $resposta = [ 
            'estado' => 'sucesso',
            //'user_pontos' => $user_pontos,
            'pontos_restantes' => $pontos_restantes,
            'valor_euro' => $valor_euro,
            'pontos_falta' => $valor,
            'pontos_falta_td' => $valor,
            'qtd_car' => $carrinho_utilizador,
            'id_linha' => $id_linha,
            'pontos_utilizados' => $pontos_utilizado,
            'pontos_utilizado' => $pontos_utilizados,
            'ids_product' => $ids_product,
            'cor' => $cor,
            'total_pontos' => $valor_total,
            'valor_total_unidade' => $valor_total_unidade,
            'quantidade'=>$quantidade
        ];

        return json_encode($resposta,true);
    }

    public function deletePremium(Request $request){

        $id = $request->id;
        $id_premio = $request->id_premio;
        $valor_utilizado = $request->valor_utilizado;
        $quantidade = $request->quantidade;
        $cont_cart = Cookie::get('cookie_user_cart');

        //$count_final = $cont_cart - $quantidade;

        $user = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        $car = \DB::table('carrinho_linha')->select('id_carrinho')->where('id',$id)->first();
        $qtd_car = \DB::table('carrinho_linha')->where('id_carrinho',$car->id_carrinho)->count();
        $premio = \DB::table('premios')->where('id',$id_premio)->first();

        /*if (($user->pontos >= 0) && ($qtd_car > 1)) { $pontos_user = $user->pontos + $valor_utilizado; }
        elseif (($user->pontos <= 0) && ($qtd_car == 1)) { $pontos_user = $valor_utilizado; }
        else{ $pontos_user = $user->pontos + ($quantidade*$premio->valor_cliente); }*/
        

        $carrinho = \DB::table('carrinho_linha')
                        ->select('carrinho_linha.*','premios.valor_cliente')
                        ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                        ->where('carrinho_linha.id_carrinho',$car->id_carrinho)
                        ->where('carrinho_linha.id','<>',$id)
                        ->get();

        $variavel = $valor_utilizado;
        $ids_product = [];
        $v=0;
        foreach ($carrinho as $value) {

            if (($value->pontos_utilizados < ($value->quantidade * $value->valor_cliente)) || ($value->pontos_utilizados == 0)) {

                if ($variavel >= ($value->quantidade * $value->valor_cliente)) {
                    //return '1';
                    $pt =  $variavel - ($value->quantidade * $value->valor_cliente);

                 
                    if ($variavel <= ($value->quantidade * $value->valor_cliente)) {
                        //$v = 1;
                        $pontos_utilizados = $value->quantidade * $value->valor_cliente;
                        
                        $pontos_rest = $variavel - $value->pontos_utilizados;
                        $variavel = $variavel - $pontos_rest;
                        //$variavel = $pt;
                        //return $variavel;
                    }
                    elseif($variavel == 0){
                        //$v = 2;
                        $pontos_utilizados = $pt; 
                        $variavel = $variavel-$pt;
                    }
                    else{
                        //$v = 10;
                        $pontos_utilizados = $value->quantidade * $value->valor_cliente;
                        $variavel = $variavel - $pt;
                    }
                    //$pontos_user = ( $user->pontos + $variavel );
                    
                   //return $pontos_user;
                     
                }else{
                    //return ($value->quantidade * $value->valor);
                    if ($value->pontos_utilizados <= 0) {
                        $pontos_utilizados = $variavel + $value->pontos_utilizados;
                        $variavel = 0;
                        //$v = 3;

                    }
                    else{
                        $pontos_rest = ($value->quantidade * $value->valor_cliente) - $value->pontos_utilizados;

                        if ($variavel == 0 || ($variavel  >= ($value->quantidade * $value->valor_cliente))) {
                            $pontos_utilizados = $variavel + $value->pontos_utilizados;
                            //$pontos_user = ( $user->pontos + $variavel );
                            //$pontos_utilizados = $value->quantidade * $value->valor;
                            $variavel = $variavel - $pontos_utilizados;

                            if ($variavel < 0 ) { $variavel = 0; }
                            else{ $variavel = $variavel - $pontos_utilizados; }
                            //$v=7;

                            //return $variavel.$value->id;
                        }
                        else{
                            if (($value->pontos_utilizados + $variavel) >= ($value->quantidade * $value->valor_cliente)) {
                                $pontos_utilizados = $value->quantidade * $value->valor_cliente;
                                $variavel = $variavel - $pontos_rest;
                                //$v = 6;
                            }
                            else{
                                $pontos_utilizados = $variavel + $value->pontos_utilizados;
                                $variavel = 0;
                                //$v=61;
                            }
                        }                        
                    }
                }

                //\DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->update(['pontos' => $pontos_user]);
            }
            else{
                $pontos_utilizados = $value->pontos_utilizados;
                //$v=5;
            }

            \DB::table('carrinho_linha')->where('id',$value->id)->update(['pontos_utilizados' => $pontos_utilizados]);

            if ($value->quantidade * $value->valor_cliente > $pontos_utilizados) { $cor_prod = 'coral'; }
            else{ $cor_prod = 'preto'; }

            $ids_product[] = [
                'id_prod' => $value->id,
                'pontos_uti' => $pontos_utilizados,
                'cor_prod' => $cor_prod,
                //'pontos_user' => $pontos_user,
                'variavel' => $variavel
                //'v' => $v
            ];
        }
        

        //\DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->update(['pontos' => $pontos_user]);

       

        \DB::table('carrinho_linha')->where('id',$id)->delete();
        $qtd_delete = \DB::table('carrinho_linha')->where('id_carrinho',$car->id_carrinho)->count();

        $valor_premio = \DB::table('carrinho_linha')
                            ->select('carrinho_linha.*','premios.valor_cliente')
                            ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                            ->where('carrinho_linha.id_carrinho',$car->id_carrinho)->get();

        $valor = 0;
        $cookies_qtd = 0;
        $pontos_uti = 0;
        foreach ($valor_premio as $value) {
            $pontos = 0;
            if (($value->quantidade*$value->valor_cliente) > $value->pontos_utilizados) {
                $pontos = ($value->quantidade*$value->valor_cliente) - $value->pontos_utilizados;
            }

            $valor = $valor + $pontos;

            $cookies_qtd = $cookies_qtd + $value->quantidade;
            $pontos_uti = $pontos_uti + $value->pontos_utilizados;
        }
        


        $ponto_em_euros = \DB::table('configuracoes')->where('tag','ponto_em_euros')->first();

        $valor_euro = $valor*$ponto_em_euros->valor;

        $car_qtd = \DB::table('carrinho_linha')
                    ->select('carrinho_linha.*','carrinho_linha.id as id_linha','premios.*')
                    ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                    ->where('carrinho_linha.id_carrinho',$car->id_carrinho)
                    ->get();

        $valor_total = 0;
        foreach ($car_qtd as $car_uti) {
            $valor_qtd = $car_uti->quantidade * $car_uti->valor_cliente;
            $valor_total = $valor_total + $valor_qtd;
        }

        Cookie::queue('cookie_user_cart', $cookies_qtd);   

        if($qtd_car == 1){ \DB::table('carrinho')->where('id',$car->id_carrinho)->delete(); }

        if ($valor > 0) { $pontos_restantes = 0; }
        else{ $pontos_restantes = $user->pontos-$pontos_uti; }

        $resposta = [
           'estado' => 'sucesso',
           //'valor_total' => $pontos_user,
           'pontos_restantes' => $pontos_restantes,
           'count_final' => $cookies_qtd,
           'qtd_car' => $qtd_car,
           'pontos_falta' => $valor,
           'valor_euro' => number_format($valor_euro, 2, ',', ''),
           'qtd_delete' => $qtd_delete,
           'ids_product' => $ids_product,
           'total_pontos' => $valor_total
        ];
        return json_encode($resposta,true);
    }
}