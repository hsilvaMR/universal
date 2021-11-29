<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Dashboard extends Controller{

  private $dados = [];
     
  public function index(){

      $this->dados['headTitulo'] = trans('seller.t_dashboard');
      $this->dados['headDescricao'] = 'Universal';
      $this->dados['separador'] = 'Dashboard';

      $this->dados['configuracoes'] = \DB::table('empresas_config')
                                         ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                         ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                         ->get();
                                         
      $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
      
      $encomendas = \DB::table('encomenda')
                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                       ->get();

    $users = \DB::table('comerciantes')
      			->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
      			->get();

      $this->dados['count_enc_concluida'] = 0;
      $this->dados['count_enc_proc'] = 0;
      $this->dados['count_enc_vencida'] = 0;
      $this->dados['count_enc_registada'] = 0;
      $this->dados['count_enc_exp_parcial'] = 0;
      $this->dados['count_enc_exp'] = 0;
      $this->dados['valor_divida'] = 0;
      
      
      foreach ($encomendas as $value) {
      	if($value->estado == 'em_processamento'){ $this->dados['count_enc_proc'] += count($value->id); }
      	if ($value->estado == 'concluida') { $this->dados['count_enc_concluida'] += count($value->id); }
      	if ($value->estado == 'fatura_vencida') { $this->dados['count_enc_vencida'] += count($value->id); }
      	if ($value->estado == 'registada') { $this->dados['count_enc_registada'] += count($value->id); }
      	if ($value->estado == 'expedida_parcialmente') { $this->dados['count_enc_exp_parcial'] += count($value->id); }
      	if ($value->estado == 'expedida') { $this->dados['count_enc_exp'] += count($value->id); }

      	$encomendas_armazem = \DB::table('encomenda_armazem')
			                       ->where('id_encomenda',$value->id)
			                       ->get();

		foreach ($encomendas_armazem as $val) {

			if ($val->doc_comprovativo == '') {
				$this->dados['valor_divida'] += $val->total;
                  
			}
		}
      }
      
      $user_array = [];
      foreach ($users as $user) {

      	$encomendas_user = \DB::table('encomenda')
	                       ->where('id_comerciante',$user->id)
	                       ->count();
				
		$user_array[] = [
			'id' => $user->id,
			'count_enc' => $encomendas_user
	    ];	
	}

      


	$this->dados['user_array'] = $user_array;

      $end_compras = \DB::table('moradas')
                        ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                        ->where('tipo','morada_armazem')
                        ->get();

      $this->dados['end_compras_count'] = count($end_compras);

      $end_array = [];
      foreach ($end_compras as $end) {

      	$encomendas_armaz = \DB::table('encomenda_armazem')
		                       ->where('id_morada',$end->id)
		                       ->count();
				
		$end_array[] = [
			'id' => $end->id,
			'count_enc' => $encomendas_armaz
	    ];
			
	}
	$this->dados['end_array'] = $end_array;
	
	$this->dados['end_compras'] = $end_compras;
     	$this->dados['user_admin'] = 0;
      $this->dados['user_gestor'] = 0;
      $this->dados['user_comerciante'] = 0;

      foreach ($users as $value) {
      	if (($value->tipo == 'admin') || ($value->tipo == 'resp_legal')) {
      		$this->dados['user_admin'] += count($value->id);
      	}

      	if ($value->tipo == 'gestor') {
      		$this->dados['user_gestor'] = count($value->id);
      	}

      	if ($value->tipo == 'comerciante') {
      		$this->dados['user_comerciante'] = count($value->id);
      	}
      }

      $seller = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

      if ($seller->tipo != 'comerciante') {
          $this->dados['ultimas_enc'] = \DB::table('encomenda')
                                            ->select('encomenda.*','encomenda.estado AS estado_enc','comerciantes.*','encomenda.id AS id_encomenda')
                                            ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                                            ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                            ->where('encomenda.estado','<>','inicio')
                                            ->orderBy('encomenda.data', 'ASC')
                                            ->take(5)
                                            ->get();
      }
      else{
          $this->dados['ultimas_enc'] = \DB::table('encomenda')
                                            ->select('encomenda.*','encomenda.estado AS estado_enc','comerciantes.*','encomenda.id AS id_encomenda')
                                            ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                                            ->where('encomenda.id_comerciante',Cookie::get('cookie_comerc_id'))
                                            ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                            ->where('encomenda.estado','<>','inicio')
                                            ->orderBy('encomenda.data', 'ASC')
                                            ->take(5)
                                            ->get();
      }

     	
     	$this->dados['users'] = $users;

      if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/dashboard',$this->dados); }
      else{ return redirect()->route('personalDataV2'); }
  }

  public function changeConfig(Request $request){

  	$id = $request->id;
  	$tag = $request->tag;
  	$enc = $request->enc;
  	$order_user = $request->order_user;
  	$orders_receipt = $request->orders_receipt;

  	if($tag == 'enc_email'){ $valor = $enc; }
  	elseif($tag == 'enc_uti'){ $valor = $order_user; }
  	else{ $valor = $orders_receipt; }

  	if($valor == 0){ $valor_new = 1;}
  	if($valor == 1){ $valor_new = 0;}

  	if ($id == 0) {
		$id_line = \DB::table('empresas_config')
					    ->insertGetId([
					    	'id_comerciante' => Cookie::get('cookie_comerc_id'),
					    	'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
							'tag' => $tag,
							'valor' => $valor_new
					    ]);

	}
  	else{
  		\DB::table('empresas_config')
  		    ->where('id',$id)
  		    ->update([
  		    	'valor' => $valor_new
  		    ]);

  		$id_line = $id;
  	}


  	$resposta = [ 
          'estado' => 'sucesso',
          'id_line' => $id_line,
          'tag' => $tag

      ];
      return json_encode($resposta,true);
  }
         
}