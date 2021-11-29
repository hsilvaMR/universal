<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Home extends Controller{

    private $dados = [];

    public function index(){

        $this->dados['headTitulo'] = trans('site_v2.t_premium');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_home';

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
        
        $conteudosSite = \DB::table('conteudos_site')->get();

        $utilizadores = \DB::table('utilizadores')->where('id', Cookie::get('cookie_user_id'))->first();
        if (!empty($utilizadores->email_alteracao)){$this->dados['aviso'] = 'email_alteracao';}

        $string = explode(" ", $utilizadores->nome);

        $this->dados['nome'] = $string[0];
        $this->dados['utilizadores'] = $utilizadores;

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
        $this->dados['premium'] = $premium;
        return view('client/pages/home',$this->dados);
    }

    public function insertCodes(Request $request){

        $codigo_str = $request->codes;
        $codigo = str_replace(" ","",$codigo_str);

        $codigo_tamanho = strlen($codigo);

        $data_expiracao = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 24  months'));
        
        $rotulos = \DB::table('rotulos')
                        ->where('codigo', $codigo)
                        ->where(function($query){
                                    $query->where('estado', 'disponivel')
                                        ->orWhere('estado','codigo');
                              })
                        ->first();

        $rotulos_indisponivel = \DB::table('rotulos')->where('codigo', $codigo)->where('estado', 'indisponivel')->first();
 
        $user_points = \DB::table('utilizadores')->select('pontos')->where('id',Cookie::get('cookie_user_id'))->first();

        if ($rotulos) {
            $rotulo_user = \DB::table('rotulos_utilizador')
                                ->where('id_utilizador',Cookie::get('cookie_user_id'))
                                ->where('id_rotulo',$rotulos->id)
                                ->first();
        }
        
        if(empty(Cookie::get('cookie_user_id')) && empty(Cookie::get('cookie_comerc_id'))) {return trans('site_v2.Begin_Session_Points_txt');}
        elseif(empty($codigo)) {return trans('site_v2.Insert_a_code');}
        elseif($codigo_tamanho<6){return trans('site_v2.Code_inf_erro');}
        elseif(isset($rotulo_user->id)) { return trans('site_v2.Code_used_erro');}
        elseif(isset($rotulos_indisponivel->id)){return trans('site_v2.Code_used_erro');}
        elseif($rotulos){
            
            if (Cookie::get('cookie_user_id') && (strtotime("+32 month",$rotulos->data) > strtotime("now"))) {

                \DB::table('rotulos_utilizador')
                    ->insert([
                        'id_rotulo'=> $rotulos->id,
                        'id_utilizador' => Cookie::get('cookie_user_id'),
                        'valor_final'=> $rotulos->valor,
                        'data' => strtotime($data_expiracao),
                        'estado' => 'disponivel'
                    ]);

                $total_pontos = $user_points->pontos + $rotulos->valor;
                \DB::table('utilizadores')->where('id',Cookie::get('cookie_user_id'))->update(['pontos'=> $total_pontos]);
            }

            $valor_final = $rotulos->valor;
           

            if ($rotulos->estado != 'codigo') {
                \DB::table('rotulos')->where('id',$rotulos->id)->update(['estado'=> 'indisponivel']);
            }
            

            //Atualizar cookies do pontos
            Cookie::queue('cookie_user_points', $total_pontos); 

            $resposta = [	
				'estado' => 'sucesso',
				'totalPoints' => $total_pontos,
                'novos_rotulos' => '
                    <tr>
                        <td scope="row">'.$codigo.'</td>
                        <td scope="row">'.$valor_final.'/'.$valor_final.'</td>
                        <td>'.date('Y-m-d', strtotime($data_expiracao)).'</td>
                        <td> <i class="fas fa-circle tx-lightgreen margin-right10"></i>'.trans('site_v2.available').'</td>
                    </tr>'
        	];
     
            return json_encode($resposta,true);
        }
        else{return trans('site_v2.Code_invalid_erro');}
    }

}