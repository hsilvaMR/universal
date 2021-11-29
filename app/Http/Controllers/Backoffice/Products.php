<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Hash;
use Validator;
use Mail;
use Cookie;

class Products extends Controller
{
	private $dados=[];
	
	public function index(){
		$this->dados['headTitulo']=trans('backoffice.productsTitulo');
		$this->dados['separador']="setProducts";
        $this->dados['funcao']="all";

        $lang = json_decode(Cookie::get('admin_cookie'))->lingua;

        $queryProducts = \DB::table('produtos')->select('*','nome_'.$lang.' AS nome')->get();
        $products =[];
        foreach ($queryProducts as $valor) {

          // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
          /*switch ($valor->tipo){
            case "admin": $tipo = '<span class="tag tag-ouro">'.trans('backoffice.Administrator').'</span>'; break;
            case "suporte": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Support').'</span>'; break;
            case "comercial": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Commercial').'</span>'; break;
            default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
          }*/

          $products[] = [
            'id' => $valor->id,
            'nome' => $valor->nome,
            'preco' => number_format($valor->preco_unitario, 2, '.', '').' €',
            'iva' => $valor->iva.' %',
            'caixa' => $valor->qtd_caixa.' '.trans('backoffice.units'),
            'pontos' => $valor->pontos.' '.trans('backoffice.points').' / '.trans('backoffice.unit'),
            'online' => $valor->online
          ];
        }
        $this->dados['products'] = $products;
		return view('backoffice/pages/products-all', $this->dados);
	}

    public function newPageProd(){
        $this->dados['headTitulo']=trans('backoffice.productsTitulo');
        $this->dados['separador']="setProducts";
        $this->dados['funcao']="new";

        return view('backoffice/pages/products-new', $this->dados);
    }

    public function editPageProd($id){
        $this->dados['headTitulo']=trans('backoffice.productsTitulo');
        $this->dados['separador']="setProducts";
        $this->dados['funcao']="edit";

        $this->dados['obj'] = \DB::table('produtos')->where('id',$id)->first();    
        return view('backoffice/pages/products-new', $this->dados);
    }

    public function formProd(Request $request){
        $id=trim($request->id);
        $nome_pt=trim($request->nome_pt);
        $nome_en=trim($request->nome_en);
        $nome_es=trim($request->nome_es);
        $nome_fr=trim($request->nome_fr);
        $descricao_pt=trim($request->descricao_pt);
        $descricao_en=trim($request->descricao_en);
        $descricao_es=trim($request->descricao_es);
        $descricao_fr=trim($request->descricao_fr);
        $preco_unitario=trim($request->preco_unitario) ? str_replace("," , "." , trim($request->preco_unitario)) : 0.00;
        $iva=trim($request->iva) ? str_replace("," , "." , trim($request->iva)) : 0;
        $qtd_caixa=trim($request->qtd_caixa) ? trim($request->qtd_caixa) : 0;
        $pontos=trim($request->pontos) ? trim($request->pontos) : 0;
        $online = (isset($request->online)) ? 1 : 0;

        if($id){
          \DB::table('produtos')
            ->where('id',$id)
            ->update(['nome_pt'=>$nome_pt,
                      'nome_en'=>$nome_en,
                      'nome_es'=>$nome_es,
                      'nome_fr'=>$nome_fr,
                      'descricao_pt'=>$descricao_pt,
                      'descricao_en'=>$descricao_en,
                      'descricao_es'=>$descricao_es,
                      'descricao_fr'=>$descricao_fr,
                      'preco_unitario'=>$preco_unitario,
                      'iva'=>$iva,
                      'qtd_caixa'=>$qtd_caixa,
                      'pontos'=>$pontos,
                      'online'=>$online ]);
        }else{
          $id=\DB::table('produtos')
                  ->insertGetId([
                    'nome_pt'=>$nome_pt,
                    'nome_en'=>$nome_en,
                    'nome_es'=>$nome_es,
                    'nome_fr'=>$nome_fr,
                    'descricao_pt'=>$descricao_pt,
                    'descricao_en'=>$descricao_en,
                    'descricao_es'=>$descricao_es,
                    'descricao_fr'=>$descricao_fr,
                    'preco_unitario'=>$preco_unitario,
                    'iva'=>$iva,
                    'qtd_caixa'=>$qtd_caixa,
                    'pontos'=>$pontos,
                    'online'=>$online,
                    'data'=>\Carbon\Carbon::now()->timestamp
                ]);
        }

        $id_enc = \DB::table('encomenda_linha')
                      ->select('id_encomenda')
                      ->where('id_produto',$id)
                      ->get();

        foreach ($id_enc as $value) {

          $enc = \DB::table('encomenda')->where('id',$value->id_encomenda)->where('estado','inicio')->first();
      
          if(isset($enc->id)){
            \DB::table('encomenda_linha')->where('id_encomenda',$enc->id)->where('id_produto',$id)->delete();
          }
        }

        $resposta = [
            'estado' => 'sucesso',
            'id' => $id ];
        return json_encode($resposta,true);
    }
}