<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Points extends Controller{

    private $dados = [];
     
    public function index(){

        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Points';


        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();
        
        $this->dados['pontos'] = \DB::table('encomenda_pontos')
                                    ->select('encomenda.*','encomenda.id AS id_enc','encomenda_pontos.*','encomenda_pontos.estado AS estado_pontos')
                                    ->leftJoin('encomenda','encomenda_pontos.id_encomenda','encomenda.id')
                                    ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                    ->get();

        $this->dados['total_pontos'] = \DB::table('empresas')
                                            ->where('id',Cookie::get('cookie_comerc_id_empresa'))
                                            ->sum('pontos');

        $this->dados['count_pontos'] = $this->dados['pontos']->count();
        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/pointsHistory',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }


    public function premiumHistory(){

        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'premiumHistory';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['premios'] = \DB::table('premios_empresa')
                                        ->select('premios_empresa.*','premios.*','premios_empresa.estado AS estado_pedido')
                                        ->leftJoin('premios','premios_empresa.id_premio','premios.id')
                                        ->where('premios_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                        ->get();

        $this->dados['count_premios'] = $this->dados['premios']->count();
        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
        
        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/premiumHistory',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }

    public function changePoints(){

        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'changePoints';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();


        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $data = strtotime(date('Y-m-d H:i:s'));
        $conteudosQuery = \DB::table('premios')
                                ->where('data_validade','>',$data)
                                ->where('online',1)
                                ->where(function($query){
                                    $query->where('tipo', 'cliente')
                                          ->orWhere('tipo','ambos');
                                    })
                                ->get();

        $premium=[];
        $name = 'nome_'.$lang;

        foreach ($conteudosQuery as $val) {

            $premium[] = [
                'id' => $val->id,
                'name' => $val->$name,
                'value' => $val->valor_empresa,
                'img' => $val->img
            ];
        }
        $this->dados['premium'] = $premium;
        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
    
        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/changePoints',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }


    public function premiumInfo($id){

        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'changePoints';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $variantes = [];
        $valor = 'valor_'.$lang;

        $this->dados['info_premium'] = \DB::table('premios')->select('*','nome_'.$lang.' AS name','descricao_'.$lang.' AS desc')->where('id',$id)->first();

        $variantePremium = \DB::table('variante_premio')
                                ->leftJoin('variante','variante_premio.id_variante','variante.id')
                                ->where('variante_premio.id_premio',$id)
                                ->get();
        

        $name_var = '';
        foreach ($variantePremium as $value) {
            $name_var = \DB::table('variante')->select('variante_'.$lang.' AS var_name')->where('id',$value->id_variante)->first();
            $variantes[] = [
                'valor' => $value->$valor
            ];
        }

        $this->dados['variantes'] = $variantes;
        $this->dados['name_var'] = $name_var;  
        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/premiumInfo',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }

    public function addPremiumCompany(Request $request){

        $quantidade = trim($request->quantidade);
        $id_premio = $request->id_premio;
        $valor_premio = $request->valor_premio;
        $variante = $request->variante;
        $id_empresa = trim($request->id_empresa);
        $id_comerciante = trim($request->id_comerciante);


        if (empty($quantidade)) { return trans('site_v2.Insert_qtd_txt');}
        //if (empty($variante)) { return trans('site_v2.Insert_variant_txt');}
        
        $empresa = \DB::table('empresas')->where('id',$id_empresa)->first();
        $pontos= \DB::table('encomenda_pontos')
                    ->select('encomenda.*','encomenda.id AS id_enc','encomenda_pontos.*','encomenda_pontos.estado AS estado_pontos')
                    ->leftJoin('encomenda','encomenda_pontos.id_encomenda','encomenda.id')
                    ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                    ->where('encomenda_pontos.estado','disponível')
                    ->get();

        $valor_empresa = 0;
        foreach ($pontos as $value) {
            $valor_empresa = $valor_empresa + $value->pontos;
        }
        
        $valor = $valor_premio*$quantidade;

        $p_emp = \DB::table('premios')->where('id',$id_premio)->first();
        
        $valor_t = $empresa->pontos + $valor_empresa;

        if ($valor_t >= $valor) {
                    
            \DB::table('premios_empresa')
                ->insert([
                    'id_premio' => $id_premio,
                    'id_empresa' => $id_empresa,
                    'id_comerciante' => $id_comerciante, 
                    'nome' => $p_emp->nome_pt,
                    'data_pedido' => \Carbon\Carbon::now()->timestamp,
                    'quantidade' => $quantidade,
                    'pontos' => $valor,
                    'variante' => $variante
                ]);


            foreach ($pontos as $value) {

                $valor_rotulo = $value->pontos;

                if ($valor == $valor_rotulo){
                    
                    \DB::table('encomenda_pontos')->where('id_encomenda',$value->id_encomenda)
                        ->update([
                            'pontos' => 0,
                            'estado' => 'indisponivel'
                        ]);

                    $valor = $valor - $valor_rotulo;
                }
                else{
                    
                    $valor_final = $value->pontos - $valor;

                    \DB::table('encomenda_pontos')->where('id_encomenda',$value->id_encomenda)
                        ->update([
                            'pontos' => $valor_final,
                            'estado' => 'disponivel' 
                        ]);

                    $valor = $valor - $valor_rotulo;
                }
            }

            $t_pontos = $empresa->pontos - $valor;
            \DB::table('empresas')->where('id',$id_empresa)->update(['pontos' => $t_pontos]);
        }
        else{
            return trans('seller.No_points_available_txt');
        }

       
       return 'sucesso';
    }

    public function askSendPremium(Request $request){

        $id_pedido = $request->id_pedido;
        $data_pedido = $request->data;
        

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $premio_empresa = \DB::table('premios_empresa')->where('id',$id_pedido)->first();
        $premio = \DB::table('premios')->where('id',$premio_empresa->id_premio)->first();
        $comerciante = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

        $dados = [
            'nome_premio' => $premio->nome_pt,
            'nome_cliente' => Cookie::get('cookie_comerc_id_empresa'),
            'id_pedido' => $id_pedido,
            'data_pedido' => $data_pedido
        ];


        \Mail::send('seller.emails.pages.askSendPremium',['dados' => $dados], function($message) use ($comerciante){
            $message->to($comerciante->email,'')->subject(trans('seller.InformationPremium'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });

        $resposta = [
            'estado' => 'sucesso',
            'mensagem' => trans('seller.Answer_contact_txt'),
        ];
        return json_encode($resposta,true);
    }
}