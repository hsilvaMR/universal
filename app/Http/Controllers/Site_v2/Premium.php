<?php 
namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Premium extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = trans('site_v2.t_premium');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_premium';

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

        $conteudosQuery = \DB::table('premios')
                              ->where('data_validade','>','strtotime(date("Y-m-d H:i:s")')
                              ->where('online',1)
                              ->where(function($query){
                                    $query->where('tipo', 'cliente')
                                        ->orWhere('tipo','ambos');
                              })
                             // ->whereIn('tipo', ['cliente', 'ambos'])
                              ->get();

        $conteudosSite = \DB::table('conteudos_site')->get();

        $premium=[];
        $name = 'nome_'.$lang;

        foreach ($conteudosQuery as $val) {

            $premium[] = [
                'id' => $val->id,
                'name' => $val->$name,
                'value' => $val->valor_cliente,
                'img' => $val->img
            ];
        }
        $this->dados['premium'] = $premium;


        $sect_premium_tit=$sect_premium_txt=$sect_premium_slide_tit=$sect_premium_banner_tit=$sect_premium_banner_bt='';

        foreach ($conteudosSite as $val) {
            switch ($val->tag) {

                case 'sect_premium_tit_'.$lang:
                    $sect_premium_tit = $val->value;
                    break;

                case 'sect_premium_txt_'.$lang:
                    $sect_premium_txt = $val->value;
                    break;

                case 'sect_premium_slide_tit_'.$lang:
                    $sect_premium_slide_tit = $val->value;
                    break;

                case 'sect_premium_banner_tit_'.$lang:
                    $sect_premium_banner_tit = $val->value;
                    break; 

                case 'sect_premium_banner_bt_'.$lang:
                    $sect_premium_banner_bt = $val->value;
                    break; 
            }
        }

        $conteudos = [
            'sect_premium_tit' => $sect_premium_tit,
            'sect_premium_txt' => $sect_premium_txt,
            'sect_premium_slide_tit' => $sect_premium_slide_tit,
            'sect_premium_banner_tit' => $sect_premium_banner_tit,
            'sect_premium_banner_bt' => $sect_premium_banner_bt
        ];

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        $this->dados['conteudos'] = $conteudos;
        if(Cookie::get('cookie_user_id')){
            return redirect('/area-reserved');
        }
        if (Cookie::get('cookie_comerc_id')) {
            return redirect('/change-points');
        }
        else{
            return view('site_v2/pages/premium',$this->dados);
        }   
    }

    public function infoPremium($id){
        $this->dados['headTitulo'] = trans('site_v2.t_premium');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_premium_info';

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

        $info_premium = \DB::table('premios')->select('*','nome_'.$lang.' AS name','descricao_'.$lang.' AS desc')->where('id',$id)->first();

        $conteudosQuery = \DB::table('premios')
                              ->where('data_validade','>','strtotime(date("Y-m-d H:i:s")')
                              ->where('online',1)
                              ->where('tipo','cliente')
                              ->orWhere('tipo','ambos')
                              ->get();

        $variantePremium = \DB::table('variante_premio')
                                ->select('variante_premio.*','variante_premio.id AS id_variante_premio')
                                ->leftJoin('variante','variante_premio.id_variante','variante.id')
                                ->where('variante_premio.id_premio',$id)
                                ->get();
        

        $conteudosSite = \DB::table('conteudos_site')->get();

        $sect_info_premium_bt=$sect_info_premium_tit=$sect_info_premium_banner_tit=$sect_info_premium_banner_txt='';

        foreach ($conteudosSite as $val) {
            switch ($val->tag) {

                case 'sect_info_premium_bt_'.$lang:
                    $sect_info_premium_bt = $val->value;
                    break;

                case 'sect_info_premium_tit_'.$lang:
                    $sect_info_premium_tit = $val->value;
                    break;

                case 'sect_info_premium_banner_tit_'.$lang:
                    $sect_info_premium_banner_tit = $val->value;
                    break;

                case 'sect_info_premium_banner_txt_'.$lang:
                    $sect_info_premium_banner_txt = $val->value;
                    break; 
            }
        }

        $conteudos = [
            'sect1_info_bt' => $sect_info_premium_bt,
            'sect1_info_tit' => $sect_info_premium_tit,
            'sect1_info_banner_tit' => $sect_info_premium_banner_tit,
            'sect1_info_banner_txt' => $sect_info_premium_banner_txt
        ];

        $this->dados['conteudos'] = $conteudos;

        $premium=[];
        $name = 'nome_'.$lang;

        foreach ($conteudosQuery as $val) {

            $premium[] = [
                'id' => $val->id,
                'name' => $val->$name,
                'value' => $val->valor_cliente,
                'img' => $val->img
            ];
        }
        $this->dados['premium'] = $premium;

        $variantes = [];
        $valor = 'valor_'.$lang;

        

        $name_var = '';
        foreach ($variantePremium as $value) {

            $name_var = \DB::table('variante')->select('variante_'.$lang.' AS var_name')->where('id',$value->id_variante)->first();
            $variantes[] = [
                'id' => $value->id,
                'valor' => $value->$valor
            ];
        }

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        $this->dados['variantes'] = $variantes;
        $this->dados['name_var'] = $name_var;  
        $this->dados['info_premium'] = $info_premium;

        if (Cookie::get('cookie_comerc_id')) {
            return redirect('/change-points');
        }else{
           return view('site_v2/pages/premiumInfo',$this->dados); 
        }
        
    }

    public function buyPoints(){

        $this->dados['headTitulo'] = trans('site_v2.t_buy_points');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_buy_points';

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        return view('site_v2/pages/buyPoints',$this->dados);
   
    }
}