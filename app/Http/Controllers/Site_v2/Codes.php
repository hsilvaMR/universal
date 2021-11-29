<?php 
namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Codes extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = trans('site_v2.t_codes');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_codes';

        $lang = Cookie::get('locale');
        if ($lang == '') {$lang = 'pt';}

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }
        
        $data = strtotime(date('Y-m-d H:i:s'));
        $conteudosPremium = \DB::table('premios')
                                ->where('data_validade','>',$data)
                                ->where('online',1)
                                ->where(function($query){
                                    $query->where('tipo', 'cliente')
                                          ->orWhere('tipo','ambos');
                                    })
                                ->get();

      
        $premium=[];
        $name = 'nome_'.$lang;
        $activeIndex=0;
        foreach ($conteudosPremium as $val) {

            if(Cookie::get('cookie_comerc_id')){
                $valor = $val->valor_empresa;
            }else{
                $valor = $val->valor_cliente;
            }

            $premium[] = [
                'id' => $val->id,
                'name' => $val->$name,
                'value' => $valor,
                'img' => $val->img,
                'index' => $activeIndex
            ];
            $activeIndex=$activeIndex+1;
        }
        $this->dados['premium'] = $premium;


        $conteudosSite = \DB::table('conteudos_site')->get();

        $sect1_codes_tit=$sect2_codes_slide_tit=$sect2_codes_slide_bt=$sect3_codes_banner_txt=$sect4_codes_tit=$sect4_codes_col1_tit=$sect4_codes_col1_txt=$sect4_codes_col1_bt=$sect4_codes_col2_tit=$sect4_codes_col2_txt=$sect4_codes_col2_bt=$sect4_codes_col3_tit=$sect4_codes_col3_txt=$sect4_codes_col3_bt='';

        foreach ($conteudosSite as $val) {
            switch ($val->tag) {

                case 'sect1_codes_tit_'.$lang:
                    $sect1_codes_tit = $val->value;
                    break;

                case 'sect2_codes_slide_tit_'.$lang:
                    $sect2_codes_slide_tit = $val->value;
                    break;

                case 'sect2_codes_slide_bt_'.$lang:
                    $sect2_codes_slide_bt = $val->value;
                    break;

                case 'sect3_codes_banner_txt_'.$lang:
                    $sect3_codes_banner_txt = $val->value;
                    break; 

                case 'sect4_codes_tit_'.$lang:
                    $sect4_codes_tit = $val->value;
                    break;

                case 'sect4_codes_col1_tit_'.$lang:
                    $sect4_codes_col1_tit = $val->value;
                    break;

                case 'sect4_codes_col1_txt_'.$lang:
                    $sect4_codes_col1_txt = $val->value;
                    break;

                case 'sect4_codes_col1_bt_'.$lang:
                    $sect4_codes_col1_bt = $val->value;
                    break;

                case 'sect4_codes_col2_tit_'.$lang:
                    $sect4_codes_col2_tit = $val->value;
                    break;

                case 'sect4_codes_col2_txt_'.$lang:
                    $sect4_codes_col2_txt = $val->value;
                    break;

                case 'sect4_codes_col2_bt_'.$lang:
                    $sect4_codes_col2_bt = $val->value;
                    break;

                case 'sect4_codes_col3_tit_'.$lang:
                    $sect4_codes_col3_tit = $val->value;
                    break;

                case 'sect4_codes_col3_txt_'.$lang:
                    $sect4_codes_col3_txt = $val->value;
                    break;

                case 'sect4_codes_col3_bt_'.$lang:
                    $sect4_codes_col3_bt = $val->value;
                    break;    
            }
        }

        $conteudos = [
            'sect1_codes_tit' => $sect1_codes_tit,
            'sect2_codes_slide_tit' => $sect2_codes_slide_tit,
            'sect2_codes_slide_bt' => $sect2_codes_slide_bt,
            'sect3_codes_banner_txt' => $sect3_codes_banner_txt,
            'sect4_codes_tit' => $sect4_codes_tit,
            'sect4_codes_col1_tit' => $sect4_codes_col1_tit,
            'sect4_codes_col1_txt' => $sect4_codes_col1_txt,
            'sect4_codes_col1_bt' => $sect4_codes_col1_bt,
            'sect4_codes_col2_tit' => $sect4_codes_col2_tit,
            'sect4_codes_col2_txt' => $sect4_codes_col2_txt,
            'sect4_codes_col2_bt' => $sect4_codes_col2_bt,
            'sect4_codes_col3_tit' => $sect4_codes_col3_tit,
            'sect4_codes_col3_txt' => $sect4_codes_col3_txt,
            'sect4_codes_col3_bt' => $sect4_codes_col3_bt
        ];

        $this->dados['conteudos'] = $conteudos;

        return view('site_v2/pages/codes',$this->dados);
    }
}