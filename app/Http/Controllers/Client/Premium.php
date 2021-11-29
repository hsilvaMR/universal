<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Premium extends Controller{

    private $dados = [];

    public function index(){
        
        $this->dados['headTitulo'] = trans('site_v2.t_history_premium');
        $this->dados['headDescricao'] = trans('site_v2.d_cheese');
        $this->dados['separador'] = 'page_client_premium';

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


        $this->dados['premios_utilizador'] = \DB::table('carrinho_linha')
                                                 ->select('*','premios.nome_'.$lang.' AS name','carrinho_linha.id AS id_pedido','carrinho.data_pedido AS data_pedido')
                                                 ->leftJoin('premios','carrinho_linha.id_premio','premios.id')
                                                 ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                 ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                 ->where('carrinho.estado','<>','atual')
                                                 ->orderBy('carrinho.estado','DESC')
                                                 ->orderBy('carrinho.data_pedido','DESC')
                                                 ->get();
                                                 
        $this->dados['carrinho_utilizador'] = \DB::table('carrinho_linha')
                                                    ->leftJoin('carrinho','carrinho_linha.id_carrinho','carrinho.id')
                                                    ->where('carrinho.id_utilizador', Cookie::get('cookie_user_id'))
                                                    ->where('carrinho.estado','atual')
                                                    ->sum('carrinho_linha.quantidade');

        $this->dados['primeiros_dez'] = $this->dados['premios_utilizador']->take(10);

        $this->dados['notificacao_dias'] = \DB::table('configuracoes')->where('tag','premio_notificacao_dias')->first();
        
        return view('client/pages/premiumHistory',$this->dados);
    }
}