<?php 
namespace App\Http\Controllers\Site_v2;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Mail;
use Cookie;

class ProductGourmet extends Controller{

    private $dados = [];

    public function index($tipo){
        if ($tipo=='gourmet_alho') {
            $this->dados['headTitulo'] = trans('site_v2.t_chesse_alho');
            $product = 'alho';
        }
        elseif ($tipo=='gourmet_azeitona') {
            $this->dados['headTitulo'] = trans('site_v2.t_chesse_azeitona');
            $product = 'azeitona';
        }
        else{
           $this->dados['headTitulo'] = trans('site_v2.t_chesse_curado');
           $product = 'curado';
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

                case 'energia_'.$product:
                    $energia = $val->value;
                    break;

                case 'lipidos_'.$product:
                    $lipidos = $val->value;
                    break;

                case 'lipidos_saturados_'.$product:
                    $lipidos_sat = $val->value;
                    break;

                case 'hidratos_carbono_'.$product:
                    $hidratos_carbono = $val->value;
                    break;

                case 'hidratos_carbono_acucares_'.$product:
                    $hidratos_carbono_acucares = $val->value;
                    break;

                case 'proteinas_'.$product:
                    $proteinas = $val->value;
                    break;

                case 'sal_'.$product:
                    $sal = $val->value;
                    break;

                case 'fibras_'.$product:
                    $fibras = $val->value;
                    break;
            }
        }

            $section1_img = '';
            $section1_tit = '';
            $section1_txt = '';
            $section1_bt  = '';
            $section2_img = '';
            $section2_tit = '';
            $section2_txt = '';
            $section2_li  = '';
            $section3_img = '';
            $section3_fd  = '';
            $section3_tit = '';
            $section3_txt = '';
            $fibras       = '';

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
            'energia' => $energia,
            'lipidos' => $lipidos,
            'lipidos_sat' => $lipidos_sat,
            'hidratos_carbono' => $hidratos_carbono,
            'hidratos_carbono_acucares' => $hidratos_carbono_acucares,
            'proteinas' => $proteinas,
            'sal' => $sal,
            'fibras' => $fibras
        ];

        $this->dados['array_produto'] = $array_produto;
        return view('site_v2/pages/product_gourmet',$this->dados);
    }
}