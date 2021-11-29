<?php 
namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Pastime extends Controller{

    private $dados = [];

    public function index(){
        $this->dados['headTitulo'] = trans('site_v2.t_pastimes');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_pastime';

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();

        if ($utilizadores) {
            $string = explode(" ", $utilizadores->nome);

            $this->dados['nome'] = $string[0];
            $this->dados['utilizadores'] = $utilizadores;
        }else{
           $this->dados['nome'] = ''; 
        }

        $lang = Cookie::get('locale');
        if($lang == ''){$lang = 'pt';}

        $this->dados['pastime'] = \DB::table('passatempos')
                                    ->select('*','titulo_'.$lang.' AS tit_passatempo','premio_'.$lang.' AS desc_premio')
                                    ->where('estado','desativo')
                                    ->orderBy('data_fim','DESC')
                                    ->get();

        $passatempos = \DB::table('passatempos')->get();
       
        foreach ($passatempos as $value) {
            if ($value->data_fim < \Carbon\Carbon::now()->timestamp) {
                
                \DB::table('passatempos')
                    ->where('id',$value->id)
                    ->update([
                        'estado' => 'desativo'
                    ]);
            }
        }

        $this->dados['regulation'] = \DB::table('passatempos')
                                    ->select('id','imagem','estado')
                                    ->orderBy('estado','ASC')
                                    ->orderBy('data_fim','DESC')
                                    ->first();

        $conteudosQuery = \DB::table('conteudos_site')->get();
        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {
                case 'sect_pastime_txt_'.$lang:
                    $pastime_txt = $val->value;
                    break;

                case 'sect_ppq_tit_'.$lang:
                    $ppq_tit = $val->value;
                    break;

                case 'sect_ppq_txt_'.$lang:
                    $ppq_txt = $val->value;
                    break;

                case 'sect_ppq_bt_'.$lang:
                    $ppq_bt = $val->value;
                    break;
            }
        }
        $conteudos = [
            'pastime_txt' => $pastime_txt,
            'ppq_tit' => $ppq_tit,
            'ppq_txt' => $ppq_txt,
            'ppq_bt' => $ppq_bt
        ];
        $this->dados['conteudos'] = $conteudos;
        return view('site_v2/pages/pastime',$this->dados);
    }

    public function pageRegulation($id){
        $this->dados['headTitulo'] = trans('site_v2.t_regulation');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_regulation';

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

        $this->dados['obj'] = \DB::table('passatempos')
                                    ->select('*','premio_'.$lang.' AS premio','titulo_'.$lang.' AS titulo','regulamento_'.$lang.' AS regulamento')
                                    ->where('id',$id)
                                    ->first();
        return view('site_v2/pages/regulation',$this->dados);
    }

    public function pageQuestionCheese(){
        $this->dados['headTitulo'] = trans('site_v2.t_question');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        //$this->dados['headPalavras'] = trans('site_v2.p_home');
        $this->dados['separador'] = 'page_ppq';

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

        $conteudosQuery = \DB::table('conteudos_site')->get();
        foreach ($conteudosQuery as $val) {
            switch ($val->tag) {

                case 'sect1_page_ppq_tit_'.$lang:
                    $sect1_ppq_tit = $val->value;
                    break;

                case 'sect1_page_ppq_img':
                    $sect1_ppq_img = $val->value;
                    break;

                case 'sect1_page_ppq_txt_'.$lang:
                    $sect1_ppq_txt = $val->value;
                    break;

                case 'sect1_page_ppq_link_'.$lang:
                    $sect1_ppq_link = $val->value;
                    break;

                case 'sect2_page_ppq_tit_'.$lang:
                    $sect2_ppq_tit = $val->value;
                    break;

                case 'sect3_page_ppq_tit_'.$lang:
                    $sect3_ppq_tit = $val->value;
                    break;

                case 'sect4_page_ppq_tit_'.$lang:
                    $sect4_ppq_tit = $val->value;
                    break;  
            }
        }
        $conteudos = [
            'sect1_ppq_tit' => $sect1_ppq_tit,
            'sect1_ppq_img' => $sect1_ppq_img,
            'sect1_ppq_txt' => $sect1_ppq_txt,
            'sect1_ppq_link' => $sect1_ppq_link,
            'sect2_ppq_tit' => $sect2_ppq_tit,
            'sect3_ppq_tit' => $sect3_ppq_tit,
            'sect4_ppq_tit' => $sect4_ppq_tit
        ];

        $this->dados['conteudos'] = $conteudos;

        $ppqPremios = \DB::table('ppq_premios')
                            ->select('id','tag','premio_'.$lang.' AS premio','desc_'.$lang.' AS desc_premio')
                          ->where('online','1')
                          ->where('data_publicacao','<',\Carbon\Carbon::now()->timestamp)
                          ->get();

        $ppq_faqs = \DB::table('ppq_faqs')
                            ->select('id','pergunta_'.$lang.' AS pergunta','resposta_'.$lang.' AS resposta')
                          ->where('online','1')
                          ->where('data_publicacao','<',\Carbon\Carbon::now()->timestamp)
                          ->get();

        $ppqueijinho = \DB::table('ppqueijinho')
                      ->select('*','resposta_'.$lang.' AS resposta')
                      ->where('online','1')
                      ->orderBy('publicacao_pergunta','ASC')
                      ->get();

        $count_ppq = $ppqueijinho ->count();

        $this->dados['regulation'] = \DB::table('passatempos')
                                        ->select('id')
                                        ->where('passatempos.estado','ppqueijinho')
                                        ->get();

        

         
        $this->dados['ppqPremios'] = $ppqPremios;
        $this->dados['ppq_faqs'] = $ppq_faqs;
        $this->dados['ppqueijinho'] = $ppqueijinho;
        $this->dados['count_ppq'] = $count_ppq;  
        return view('site_v2/pages/questionCheese',$this->dados);
    }
}