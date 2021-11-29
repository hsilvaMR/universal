<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Points extends Controller{

    private $dados = [];

    public function index(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_history_points');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_point';

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

        $this->dados['rotulos_utilizador'] = \DB::table('rotulos_utilizador')
                                                ->select('rotulos.codigo','rotulos.id','rotulos.valor','rotulos_utilizador.*','rotulos_utilizador.data as data_user')
                                                ->leftJoin('rotulos','rotulos_utilizador.id_rotulo','rotulos.id')
                                                ->where('rotulos_utilizador.id_utilizador', Cookie::get('cookie_user_id'))
                                                ->orderBy('rotulos_utilizador.estado')
                                                ->orderBy('rotulos_utilizador.data','ASC')
                                                ->get();
                                  
        $this->dados['primeiros_dez'] = $this->dados['rotulos_utilizador']->take(10);


        return view('client/pages/pointsHistory',$this->dados);
    }

    
}