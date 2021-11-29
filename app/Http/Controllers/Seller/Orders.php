<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Orders extends Controller{

  private $dados = [];
   
  public function index(){

    $this->dados['headTitulo'] = trans('seller.t_orders');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';
    
    $seller = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

    if ($seller->tipo != 'comerciante') {
      $this->dados['encomenda'] = \DB::table('encomenda')
                                   ->select('encomenda.id as id_enc','encomenda.*','encomenda.estado AS estado_enc','comerciantes.*')
                                   ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                                   ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                   ->where('encomenda.estado','<>','inicio')
                                   ->get();
    }
    else{
      $this->dados['encomenda'] = \DB::table('encomenda')
                                   ->select('encomenda.id as id_enc','encomenda.*','encomenda.estado AS estado_enc','comerciantes.*')
                                   ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                                   ->where('encomenda.id_comerciante',Cookie::get('cookie_comerc_id'))
                                   ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                   ->where('encomenda.estado','<>','inicio')
                                   ->get();
    }

    

    $this->dados['fatura_vencida'] = \DB::table('encomenda')
                                         ->where('encomenda.id_comerciante',Cookie::get('cookie_comerc_id'))
                                         ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                         ->where('encomenda.estado','fatura_vencida')
                                         ->get();

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['morada_armazem_count'] = \DB::table('moradas')
                                          ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                          ->where('tipo','morada_armazem')
                                          ->where('estado','ativo')
                                          ->count();

    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
    $this->dados['seller'] = $seller;

    return view('seller/pages/orders',$this->dados);  
  }

  public function ordersNew(){

    $this->dados['headTitulo'] = trans('seller.t_orders_new');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['fatura_vencida'] = \DB::table('encomenda')
                                         ->where('encomenda.id_comerciante',Cookie::get('cookie_comerc_id'))
                                         ->where('encomenda.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                         ->where('encomenda.estado','fatura_vencida')
                                         ->get();

    $company = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
    $seller = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

    $morada_armazem = \DB::table('moradas')
                          ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
                          ->where('tipo','morada_armazem')
                          ->where('estado','ativo')
                          ->get();
    
    $produtos_empresa = \DB::table('produtos_empresa')
                                   ->select('produtos.*','produtos_empresa.*','produtos.nome_pt as nome')
                                   ->leftJoin('produtos','produtos_empresa.id_produto','produtos.id')
                                   ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                   ->where('produtos.online',1)
                                   ->get();

    $produtos = [];
    foreach ($produtos_empresa as $value) {
      if($company->precos == 'percentagem'){
        $valor = $value->preco_unitario * (1 + ($value->valor / 100));
      }
      else{
        $valor = $value->preco_unitario;
      }

      $produtos[] = [
        'nome' => $value->nome_pt,
        'preco_unitario' => $value->preco_unitario,
        'qtd_caixa' => $value->qtd_caixa,
        'iva' => $value->iva,
        'id_produto' => $value->id_produto,
        'valor' => number_format((float)round( $valor ,2, PHP_ROUND_HALF_DOWN),2,'.',',')
      ];
    }
    
    //return $produtos;
    $encomenda = \DB::table('encomenda')
                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                       ->where('estado','inicio')
                       ->first();
    
    $qtd_prod_final=[];

    if ($encomenda) {
      $encomenda_linha = \DB::table('encomenda_linha')
                          ->select('encomenda_linha.*','encomenda_linha.qtd_caixa AS line_qtd_caixa','produtos_empresa.valor AS valor_empresa','produtos.preco_unitario')
                          ->leftJoin('produtos_empresa','encomenda_linha.id_produto','produtos_empresa.id_produto')
                          ->leftJoin('produtos','encomenda_linha.id_produto','produtos.id')
                          ->where('encomenda_linha.id_encomenda',$encomenda->id)
                          ->where('produtos_empresa.id_empresa',$encomenda->id_empresa)
                          ->get();

      //return $encomenda_linha;

      $arrayEncLine = [];
      $arrayProdutos[] = ['id' => 0];
      foreach ($encomenda_linha as $value){ 
       
        if($company->precos == 'percentagem'){
          $valor = $value->preco_unitario * (1 + ($value->valor_empresa / 100));
        }
        else{
          $valor = $value->preco_unitario;
        }


        $arrayEncLine[] = [
          'id_line' => $value->id,
          'id_encomenda' => $value->id_encomenda,
          'id_produto' => $value->id_produto,
          'id_morada' => $value->id_morada,
          'nome' => $value->nome_produto,
          'quantidade' => $value->quantidade,
          'qtd_caixa' => $value->line_qtd_caixa,
          'preco_produto' => $value->preco_unitario,
          'valor_empresa' => number_format((float)round( $valor ,2, PHP_ROUND_HALF_DOWN),2,'.',',')
        ];

        $arrayProdutos[] = ['id' => $value->id_produto]; 
      }
  

      $produtosMorada = [];
      foreach ($produtos_empresa as $val) {
      
        foreach ($morada_armazem as $value) {
          $valor = array_search($val->id_produto, array_column($arrayProdutos, 'id')) ? 1 : 0;
          if ($valor == 1) {

            $morada = \DB::table('encomenda_linha')
                          ->select('id_morada')
                          ->where('id_encomenda', $encomenda->id)
                          ->where('id_produto',$val->id_produto)
                          ->where('id_morada',$value->id)
                          ->get();
            
            if (count($morada) > 0) {
              foreach ($morada as $morada) {
                
                $produtosMorada[] = [
                  'id' => $val->id_produto,
                  'nome' => $val->nome,
                  'valor' => $valor,
                  'morada' => $morada->id_morada
                ];
              }  
            }
            else{
              $produtosMorada[] = [
                'id' => $val->id_produto,
                'nome' => $val->nome,
                'valor' => 0,
                'morada' => $value->id
              ];
            }   
          }            
          else{
            $produtosMorada[] = [
              'id' => $val->id_produto,
              'nome' => $val->nome,
              'valor' => $valor,
              'morada' => $value->id
            ];
          }
        } 

        $encomenda_id = \DB::table('encomenda_linha')
                            ->where('id_encomenda',$encomenda->id)
                            ->where('encomenda_linha.id_produto',$val->id_produto)
                            ->get();

        $quantidade = 0;
        if ((count($encomenda_id) > 1) || (count($encomenda_id) == 1)) {
          $quantidade = $encomenda_id->sum('quantidade');
        }
        
        
        if ($quantidade != 0) {
          $qtd_prod_final[] = [
            'id' => $val->id_produto,
            'nome' => $val->nome,
            'quantidade' => $quantidade
          ];
        }  
      }
      
      $this->dados['prod_line'] = $produtosMorada; 
      $this->dados['encomenda_linha'] = $arrayEncLine;
      
    }

    $this->dados['qtd_prod_final'] = $qtd_prod_final; 
    //$this->dados['produtos_empresa'] = $produtos_empresa;
    $this->dados['morada_armazem'] = $morada_armazem;
    $this->dados['encomenda'] = $encomenda;
    $this->dados['produtos'] = $produtos;

    /*if ((count($morada_armazem) == 0) || (count($produtos_empresa) == 0) || (Cookie::get('cookie_company_status') == 'em_aprovacao') || (count($fatura_vencida) >= 1)) {
      $this->dados['morada_armazem'] = \DB::table('moradas')
                                          ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                          ->where('tipo','morada_armazem')
                                          ->where('estado','ativo')
                                          ->count();

      return view('seller/pages/orders',$this->dados);  
    }
    else{
      
      
    }*/
    $this->dados['company'] = $company;
    if ($seller->tipo == 'gestor') {

      $this->dados['morada_armazem_count'] = \DB::table('moradas')
                                          ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                          ->where('tipo','morada_armazem')
                                          ->where('estado','ativo')
                                          ->count();
      $this->dados['seller'] = $seller;
      return view('seller/pages/orders',$this->dados);
    }
    else{
   
      return view('seller/pages/ordersNew',$this->dados); 
    }
  }

  public function ordersDetails($id){
    $this->dados['headTitulo'] = trans('seller.Orders');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';



    $this->dados['encomenda'] = \DB::table('encomenda')
                                    ->select('encomenda.*','encomenda.id AS id_enc','encomenda.estado AS estado_enc','comerciantes.*')
                                    ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                                    ->where('encomenda.id',$id)
                                    ->first();


    $this->dados['encomenda_armazem'] = \DB::table('encomenda_armazem')
                                            ->select('encomenda_armazem.*','encomenda_armazem.estado AS estado_armaz','moradas.*','encomenda.estado AS estado_enc','encomenda.documento AS doc_total','encomenda_armazem.id AS id_line')
                                            ->leftJoin('moradas','encomenda_armazem.id_morada','moradas.id')
                                            ->leftJoin('encomenda','encomenda_armazem.id_encomenda','encomenda.id')
                                            ->where('id_encomenda',$id)
                                            ->get();

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

    return view('seller/pages/ordersDetails',$this->dados);  
  }

  public function ordersDetailsAll($id){
    $this->dados['headTitulo'] = trans('seller.t_orders_summary');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

    $morada_armazem = \DB::table('moradas')
                          ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
                          ->where('tipo','morada_armazem')
                          ->where('estado','ativo')
                          ->get();

    
    $encomenda = \DB::table('encomenda')
                    ->where('id',$id)
                    ->first();

    $this->dados['comerciante'] = \DB::table('comerciantes')->where('id',$encomenda->id_comerciante)->first();

      
    $encomenda_linha = \DB::table('encomenda_linha')
                        ->select('moradas.morada','moradas.id AS id_morada','encomenda_linha.quantidade','produtos.nome_pt as nome','produtos.qtd_caixa','produtos_empresa.valor')
                        ->leftJoin('produtos','encomenda_linha.id_produto','produtos.id')
                        ->leftJoin('produtos_empresa','encomenda_linha.id_produto','produtos_empresa.id_produto')
                        ->leftJoin('moradas','encomenda_linha.morada','moradas.id')
                        ->where('id_encomenda',$id)
                        ->get();

  
    $enc_linha=[];

    $valor_total_t = 0;
    $arrayProdutos[] = ['id' => 0];

    foreach ($encomenda_linha as $value) {

      $enc_linha[] = [
          'id_morada' => $value->id_morada,
          'produto' => $value->nome,
          'quantidade' => $value->quantidade,
          'qtd_caixa' => $value->qtd_caixa,
          'valor' => $value->valor
      ];

      $arrayProdutos[] = ['id' => $value->id_morada];     
    }

    
    $array_morada = [];

    foreach ($morada_armazem as $value) {
      $valor = array_search($value->id, array_column($arrayProdutos, 'id')) ? 1 : 0;

      //return $value->id;
      $array_morada[] = [
        'id_morada' => $value->id,
        'valor' => $valor
      ];

    }

    $this->dados['encomenda_linha'] = $encomenda_linha;
    $this->dados['enc_linha'] = $enc_linha;
    $this->dados['encomenda'] = $encomenda;
    $this->dados['array_morada'] = $array_morada;
    $this->dados['morada_armazem'] = $morada_armazem;
           
   return view('seller/pages/ordersDetailsAll',$this->dados);   
  }

  public function ordersSummary(){
      
    $this->dados['headTitulo'] = trans('seller.t_orders_summary');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

    $encomenda = \DB::table('encomenda')
                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                       ->where('estado','inicio')
                       ->first();


    if ($encomenda) {
      //Encomenda Armazem
      $enc_armz = \DB::table('encomenda_armazem')->where('id_encomenda',$encomenda->id)->get();


      $encomenda_linha = \DB::table('encomenda_linha')
                          ->select('encomenda_linha.*','encomenda_linha.id AS id_line','encomenda_linha.qtd_caixa AS line_qtd_caixa','produtos_empresa.valor AS valor_empresa','produtos.nome_pt AS nome','produtos.*')
                          ->leftJoin('produtos_empresa','encomenda_linha.id_produto','produtos_empresa.id_produto')
                          ->leftJoin('produtos','encomenda_linha.id_produto','produtos.id')
                          ->where('encomenda_linha.id_encomenda',$encomenda->id)
                          ->where('produtos_empresa.id_empresa',$encomenda->id_empresa)
                          ->get();

      $enc_linha=[];
      $valor_total_t = 0;
  
      foreach ($encomenda_linha as $value) {
        $nome_morada = \DB::table('moradas')->where('id', $value->id_morada)->first();
        $enc_linha[] = [
          'id_morada' => $value->id_morada,
          'morada' => $nome_morada->morada,
          'produto' => $value->nome_produto,
          'quantidade' => $value->quantidade,
          'qtd_caixa' => $value->qtd_caixa,
          'valor' => $value->preco_produto
        ];   
      }
      
      foreach($enc_linha as $enc){
                    
        $valor_line = $enc['quantidade'] * ($enc['qtd_caixa']* $enc['valor']);
        $valor_dec = number_format((float)round( $valor_line ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
        $valor_total_t = number_format((float)round( $valor_total_t + $valor_dec ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
        

        $valor_iva = round($valor_total_t * 0.06, 2); 
        $valor_com_iva = number_format((float)round( $valor_total_t + $valor_iva ,2, PHP_ROUND_HALF_DOWN),2,'.',',');

      }


      
      $valor_total_armz = 0;
      foreach($enc_linha as $enc){
        $valor_line_armz = $enc['quantidade'] * ($enc['qtd_caixa']* $enc['valor']);
        $valor_dec_armz = number_format((float)round( $valor_line_armz ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
        $valor_total_armz = number_format((float)round( $valor_total_armz + $valor_dec_armz ,2, PHP_ROUND_HALF_DOWN),2,'.',',');

        $valor_iva_armz = round($valor_total_armz * 0.06, 2); 
        $valor_com_iva_armz = round($valor_total_armz + $valor_iva_armz, 2);

        \DB::table('encomenda_armazem')
            ->where('id_morada',$enc['id_morada'])
            ->where('id_encomenda',$encomenda->id)
            ->update([
              'subtotal' => $valor_total_armz,
              'total' => $valor_com_iva_armz,
              'morada' => $enc['morada'] 
            ]);
      }
      

      
      \DB::table('encomenda')->where('id',$encomenda->id)->update(['subtotal' => $valor_total_t,'total' => $valor_com_iva]);

                    
      $this->dados['encomenda_linha'] = $encomenda_linha;
      $this->dados['enc_linha'] = $enc_linha;
    }

    $this->dados['encomenda'] = $encomenda;
    return view('seller/pages/ordersSummary',$this->dados);  
  }

  public function ordersSucess($id){

    $this->dados['headTitulo'] = trans('seller.t_orders_sucess');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';
    $this->dados['id'] = $id;

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $company = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

    $encomenda=\DB::table('encomenda')->where('id',$id)->first();
    $seller = \DB::table('comerciantes')->where('id',$encomenda->id_comerciante)->first();

    //Encomenda Armazem
    $this->dados['enc_armz']=\DB::table('encomenda_armazem')->where('id_encomenda',$id)->get();

    //Arredondar o valor da encomenda para cima e dividir o valor pelo euros_em_pontos
    $config=\DB::table('configuracoes')->where('tag','euros_em_pontos_empresa')->first();
    $pontos = (round($encomenda->subtotal) / $config->valor);   
    $validade = date('Y-m-d',strtotime(date('Y-m-d',$encomenda->data). ' + 24 month'));

    $enc_armz = \DB::table('encomenda_armazem')->where('id_encomenda',$id)->get();

    foreach ($enc_armz as $value) {
      if(file_exists(base_path('public_html/doc/orders/'.$value->doc_encomenda))) {
        \File::delete(base_path().'/public_html/doc/orders/'.$value->doc_encomenda);
      }
    }

    \DB::table('encomenda')->where('id',$id)
       ->update([
          'estado' => 'registada'
        ]);


    \DB::table('encomenda_pontos')
       ->insert([
          'id_encomenda' => $id,
          'pontos' => round($pontos),
          'validade' => strtotime($validade)
        ]);

    //Adicionar notificação para o backoffice 
    $empresa=\DB::table('empresas')->where('id',$encomenda->id_empresa)->first();
    $admin=\DB::table('admin')->get();

    foreach ($admin as $value) {
      \DB::table('admin_not')
          ->insert([
            'id_admin'=>$value->id,
            'tipo'=>'encomenda',
            'mensagem'=>'A empresa '.$empresa->nome.' criou uma nova encomenda.',
            'data'=>\Carbon\Carbon::now()->timestamp 
          ]);
    }

    //Enviar email com o estado
    $config = \DB::table('empresas_config')->where('id_comerciante',$seller->id)->where('tag','enc_email')->first();

    if(isset($config->valor) && $config->valor == 1){
      $dados = ['id_encomenda' => $id, 'nome_empresa' => $company->nome];
                   
      \Mail::send('seller.emails.pages.statusOrder',['dados' => $dados], function($message) use ($seller){
        $message->to($seller->email,'')->subject('Estado da encomenda');
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
    }
    

    //Enviar email com o resumo da encomenda e a fatura de cada encomenda

    $config_resumo = \DB::table('empresas_config')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('tag','enc_uti')->where('valor',1)->get();
    $encomenda_doc=\DB::table('encomenda')->where('id',$id)->first();
  
    foreach ($config_resumo as $value) {
      $seller_config = \DB::table('comerciantes')->where('id',$value->id_comerciante)->first();

      $dados = ['id_comerciante' => $value->id_comerciante, 'nome_comerciante' => $seller_config->nome,'nome_empresa' => $company->nome, 'id_empresa' => Cookie::get('cookie_comerc_id_empresa')];

      $file='http://www.universal.com.pt/doc/orders/'.$encomenda_doc->documento;

      \Mail::send('seller.emails.pages.sendResume',['dados' => $dados], function($message) use ($seller_config,$file){
        $message->to($seller_config->email,'')->subject('Resumo da encomenda');
        $message->attach($file);
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
    }

    $this->dados['company'] = $company;
    return view('seller/pages/ordersSucess',$this->dados);  
  }

  public function nextOrder(Request $request){
    $id = trim($request->id);
   
    //Adicionar notificação utilizador
    $gest_admin = \DB::table('comerciantes')
                      ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                      ->where('id','<>',Cookie::get('cookie_comerc_id'))
                      ->where(function($query){
                            $query->where('tipo', 'gestor')
                                  ->orWhere('tipo','admin');
                      })
                      ->get();

    foreach ($gest_admin as $value) {
      \DB::table('notificacoes')
          ->insert([
              'id_notificado' => $value->id,
              'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
              'id_comerciante' => Cookie::get('cookie_comerc_id'),
              'id_encomenda' => $id,
              'tipo' => 'enc_iniciada',
              'url' => '\orders',
              'data' => \Carbon\Carbon::now()->timestamp
          ]); 
    }

    \DB::table('notificacoes')
        ->insert([
            'id_notificado' => Cookie::get('cookie_comerc_id'),
            'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
            'id_comerciante' => Cookie::get('cookie_comerc_id'),
            'id_encomenda' => $id,
            'tipo' => 'enc_iniciada',
            'url' => '\orders',
            'data' => \Carbon\Carbon::now()->timestamp
        ]); 

    //count notificações
    $not_count=\DB::table('notificacoes')->where('id_notificado',Cookie::get('cookie_comerc_id'))->where('vista',0)->count();

    Cookie::queue(Cookie::make('cookie_not_ative', $not_count, 43200));

    $resposta = [ 
      'estado' => 'sucesso',
      'id' => $id
    ];
    return json_encode($resposta,true);
    
  }

  public function ordersAdressPdf($id,$id_morada,$id_empresa){
    $this->dados['headTitulo'] = trans('seller.t_orders_sucess');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';
    $this->dados['id'] = $id;
    $this->dados['id_morada'] = $id_morada;

    $this->dados['encomenda'] = \DB::table('encomenda')
                                ->select('comerciantes.*','encomenda.*','encomenda_armazem.estado AS estado_armazem')
                                ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                                ->leftJoin('encomenda_armazem','encomenda.id','encomenda_armazem.id_encomenda')
                                ->where('encomenda.id',$id)
                                ->first();

    $this->dados['encomenda_linha'] = \DB::table('encomenda_linha')
                                          ->select('moradas.*','moradas.id AS id_morada','encomenda_linha.*','encomenda_linha.id AS id_encomenda_linha')
                                          ->leftJoin('moradas','encomenda_linha.id_morada','moradas.id')
                                          ->where('encomenda_linha.id_morada',$id_morada)
                                          ->where('encomenda_linha.id_encomenda',$id)
                                          ->get();

    
    $this->dados['company'] = \DB::table('empresas')->where('id',$id_empresa)->first();

    $enc_armz = \DB::table('encomenda_armazem')->where('id_encomenda',$id)->where('id_morada',$id_morada)->first();

    if(empty($enc_armz->doc_encomenda)){
      $cache = str_random(3);
      $nome_pdf = 'order_'.$id.'_'.$id_morada.'_parcial_'.$cache.'.pdf';

      \DB::table('encomenda_armazem')
          ->where('id_encomenda',$id)
          ->where('id_morada',$id_morada)
          ->update(['doc_encomenda' => $nome_pdf]);
    }else{
      $nome_pdf = $enc_armz->doc_encomenda;
    }
    
    $this->dados['nome_pdf'] = $nome_pdf;

    return view('seller/pages/ordersAdressPdf',$this->dados);  
  }

  public function ordersPdf($id,$id_empresa){

    $this->dados['headTitulo'] = trans('seller.t_orders_sucess');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Orders';
    $this->dados['id'] = $id;

    $morada_armazem = \DB::table('moradas')
                          ->where('id_empresa', $id_empresa)
                          ->where('tipo','morada_armazem')
                          ->where('estado','ativo')
                          ->get();

    $encomenda = \DB::table('encomenda')
                    ->select('encomenda.*','encomenda.id AS id_enc','encomenda.estado AS estado_enc','encomenda.data AS data_enc','comerciantes.*')
                    ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                    ->where('encomenda.id',$id)
                    ->first();
   
    if ($encomenda) {
      
      $encomenda_linha = \DB::table('encomenda_linha')
                          ->select('moradas.morada','moradas.id AS id_morada','encomenda_linha.*','encomenda_linha.id AS id_encomenda_linha')
                          ->leftJoin('produtos','encomenda_linha.id_produto','produtos.id')
                          ->leftJoin('moradas','encomenda_linha.id_morada','moradas.id')
                          ->where('encomenda_linha.id_encomenda',$id)
                          ->get();

      $enc_linha=[];
  
      foreach ($encomenda_linha as $value) {
        //foreach ($morada_armazem as $val) {
          //if ($value->id_morada == $val->id) {
            $enc_linha[] = [
                'id' => $value->id_encomenda_linha,
                'id_morada' => $value->id_morada,
                'produto' => $value->nome_produto,
                'quantidade' => $value->quantidade,
                'qtd_caixa' => $value->qtd_caixa,
                'valor' => $value->preco_produto
            ];
          //}
        //}
      }

      
      if(empty($encomenda->documento)){
        $cache = str_random(3);
        $nome_pdf = 'order_'.$id.'_total_'.$cache.'.pdf';
        \DB::table('encomenda')->where('id',$id)->update(['documento' => $nome_pdf]);
      }
      else{
        $nome_pdf = $encomenda->documento;
      }


      $this->dados['encomenda'] = $encomenda;
      $this->dados['encomenda_linha'] = $encomenda_linha;
      $this->dados['enc_linha'] = $enc_linha;
      $this->dados['morada_armazem'] = $morada_armazem;
      $this->dados['nome_pdf'] = $nome_pdf;
    }

    $this->dados['encomenda_linha'] = \DB::table('encomenda_linha')->where('id_encomenda',$id)->get();
    $this->dados['company'] = \DB::table('empresas')->where('id',$id_empresa)->first();
    return view('seller/pages/ordersPdf',$this->dados);
  }

  public function addLineProduct(Request $request){

    $id_morada = $request->id_morada;
    $id_product = $request->id_product;
    $qtd = $request->qtd;
    $id_encomenda = $request->id_encomenda;
    $linha_qtd = $request->linha_qtd;
    $linha_valor = $request->linha_valor;
    $valor_fatura = $request->valor_fatura;
    $qtd_fatura = $request->qtd_fatura;

  

    $max_id = \DB::table('encomenda')->max('id');
    $id = $max_id + 1;

    $produtos = \DB::table('produtos_empresa')
                   ->select('produtos.*','produtos_empresa.*','produtos.nome_pt as nome')
                   ->leftJoin('produtos','produtos_empresa.id_produto','produtos.id')
                   ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                   ->where('produtos.online',1)
                   ->get();

    $company = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

    $option='';             
    foreach ($produtos as $value) {
      if ($value->id_produto == $id_product) {
        $option.= $value->nome;
      }
    }


    $encomenda_linha = \DB::table('encomenda_linha')->where('id_encomenda',$id_encomenda)->where('id_produto',$id_product)->get();


    if (count($encomenda_linha) > 0) {
      foreach ($encomenda_linha as $value) {

        if ($value->id_produto == $id_product) {
          
          $qtd_t = $qtd + $value->quantidade;
          $info_td = '<td>'.$option.'</td>
                      <td class="dashboard-col-add-2">'.$qtd_t.'</td>';
        }
      }
    }
    else{
      $info_td = '<tr id="info_'.$id_product.'" class="tx-left">
                    <td>'.$option.'</td>
                    <td class="dashboard-col-add-2">'.$qtd.'</td>
                  </tr>';
    }
    
    
    if ($id_product != 0) {

    
      if (empty($id_encomenda)) {        
          $id_encomenda = \DB::table('encomenda')
                              ->insertGetId([
                                'id_comerciante' => Cookie::get('cookie_comerc_id'),
                                'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                                'estado' => 'inicio',
                                'data' => \Carbon\Carbon::now()->timestamp 
                              ]);

        \DB::table('encomenda')->where('id',$id_encomenda)->update(['referencia' => 'Referencia_'.$id_encomenda]);
      }
      
      $armaz = \DB::table('encomenda_armazem')->where('id_encomenda',$id_encomenda)->where('id_morada',$id_morada)->first();

      $cache = str_random(3);
      if(empty($armaz)){
          $doc_encomenda = 'order_'.$id_encomenda.'_'.$id_morada.'_parcial_'.$cache.'.pdf';

          $encomenda_armazem = \DB::table('encomenda_armazem')
                      ->insertGetId([
                        'id_encomenda' => $id_encomenda,
                        'id_morada' => $id_morada,
                        'doc_encomenda' => $doc_encomenda,
                        'data_encomenda' => \Carbon\Carbon::now()->timestamp
                      ]);

      }
      
      $produto_valor = \DB::table('produtos_empresa')
                           ->where('id_produto',$id_product)
                           ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                           ->first();

      $produto_v = \DB::table('produtos')
                           ->where('id',$id_product)
                           ->first();

     
      if($company->precos == 'percentagem'){
        $valor = $produto_v->preco_unitario * (1 + ($produto_valor->valor / 100));
      }
      else{
        $valor = $produto_v->preco_unitario;
      }

      $v_produto =  number_format((float)round( $valor ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
      $v_caixa_produto = number_format((float)round( $produto_v->qtd_caixa * $v_produto ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
      $v_prod = $qtd*($produto_v->qtd_caixa*$v_produto);
      $valor_prod = number_format((float)round( $v_prod ,2, PHP_ROUND_HALF_DOWN),2,'.',',');

      /*$prods_desc = \DB::table('produtos')
                   ->where('id',$id_product)
                   ->first();*/

      //$prod_tot = ($prods_desc->qtd_caixa * $produto_valor->valor)*$qtd;
      $id_enc = \DB::table('encomenda_linha')
                    ->insertGetId([
                      'id_encomenda' => $id_encomenda,
                      'id_produto' => $id_product,
                      'id_morada' => $id_morada,
                      'nome_produto' => $produto_v->nome_pt,
                      'preco_produto' => $v_produto,
                      'qtd_caixa' => $produto_v->qtd_caixa,
                      'quantidade' => $qtd,
                      'total' => $valor_prod,
                      'estado' => 'ativo',
                      'data' => \Carbon\Carbon::now()->timestamp
                  ]);
    }
    
   
    $product_value = \DB::table('produtos_empresa')
                        ->select('produtos_empresa.valor AS valor_caixa','produtos.qtd_caixa AS qtd_caixa')
                        ->leftJoin('produtos','produtos_empresa.id_produto','produtos.id')
                        ->where('produtos_empresa.id_produto',$id_product)
                        ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                        ->first();


    



    $linha_qtd_total = $linha_qtd + $qtd;
    $linha_valor_total = $linha_valor + $valor_prod;

    $qtd_fatura_total = $qtd_fatura + $qtd; 
    $valor_fatura_total = $valor_fatura + $valor_prod;
    $valor_iva = round($valor_fatura_total * 0.06, 2); 
    $valor_com_iva = round($valor_fatura_total + $valor_iva, 2);

    $valor_caixa = $product_value->qtd_caixa*$product_value->valor_caixa;

    $resposta = [ 
      'estado' => 'sucesso',
      'id_enc' => $id_enc,
      'id_morada' => $id_morada,
      'linha_qtd_total' => $linha_qtd_total,
      'linha_valor_total' => number_format((float)$linha_valor_total, 2, '.', ''),
      'qtd_fatura_total' => $qtd_fatura_total,
      'valor_fatura_total' => number_format((float)$valor_fatura_total, 2, '.', ''),
      'valor_iva' => $valor_iva,
      'valor_com_iva' => number_format((float)$valor_com_iva, 2, '.', ''),
      'info_td' => $info_td,
      'id_encomenda' => $id_encomenda,
      'id_produto' => $id_product,
      'conteudo_add' => '
        <tr id="product_'.$id_enc.'" class="cleanDatos">
            <td>
              <i id="removeProduct_'.$id_enc.'" class="fas fa-times tx-red orders-new-delete" onclick="deleteLine('.$id_enc.','.$id_encomenda.','.$id_morada.','.$qtd.','.$v_caixa_produto.')" style="margin-right:20px;margin-left:10px;"></i>
              <div class="div-50">
                <input id="select_'.$id_enc.'" type="hidden" value="'.$id_product.'">     
                <label name="fatura_opc" style="height:40px;width:100%;line-height:40px;border-radius:20px;background-color:#eeeeee;padding:0px 20px;">'.$option.'</label>
              </div>
            </td>

            <td class="tx-right" id="united_'.$id_enc.'">
              <span>1 Caixa</span><br>
              <span>'.$product_value->qtd_caixa.' artigos</span>
            </td>
            <td class="tx-right" id="price_'.$id_enc.'">
              <span>'.$v_caixa_produto.' €</span><br>
              <span>'.$v_produto.' €/artigos</span>
            </td>
            <td>
              <div class="cart-div-input">
                <div class="orders_qtd_decrease float-left"><span class="orders_qtd_bt" id="decrease" onclick="decreaseValue('.$id_enc.','.$v_caixa_produto.','.$id_morada.')" value="Decrease Value"><i class="fas fa-minus tx-gray"></i></span></div>
                <input class="orders_input" type="number" id="qtd_'.$id_enc.'" value="'.$qtd.'" onchange="updateProduct('.$id_enc.','.$v_caixa_produto.','.$id_morada.',\'manual\');">
                <input type="hidden" id="qtd_anterior'.$id_enc.'" value="'.$qtd.'">
                <input type="hidden" id="quantidade_'.$id_enc.'" value="'.$qtd.'">
                <div class="orders_qtd_decrease float-right"> <span class="orders_qtd_bt" id="increase" onclick="increaseValue('.$id_enc.','.$v_caixa_produto.','.$id_morada.')" value="Increase Value"><i class="fas fa-plus tx-gray"></i></span></div>
              </div>
            </td>
          <td class="tx-right" id="value_prod_'.$id_enc.'">'.number_format((float)$valor_prod, 2, '.', '').' €</td>
        </tr>
      '
    ];
    return json_encode($resposta,true);
  }

  public function deleteLine(Request $request){

    $id_line = $request->id_line;
    $id_encomenda = $request->id_encomenda;
    $id_morada = $request->id_morada;
    $quantidade = $request->quantidade;
    $valor_produto = $request->valor_produto;
    $linha_qtd = $request->linha_qtd;
    $linha_valor = $request->linha_valor;
    $valor_fatura = $request->valor_fatura;
    $qtd_fatura = $request->qtd_fatura;
    $iva = $request->iva;
    $valor_com_iva = $request->valor_com_iva;
    
    $qtd_final = $linha_qtd - $quantidade;
    $valor_final = $linha_valor - $valor_produto;

    $valor_final_fat = $valor_fatura - $valor_produto;
    $qtd_final_fat = $qtd_fatura - $quantidade; 
    $valor_iva = round($valor_final_fat * 0.06, 2); 
    $valor_iva_fat = round($valor_final_fat + $valor_iva, 2);


    $id_prod = \DB::table('encomenda_linha')->where('id',$id_line)->first();

    $produtos = \DB::table('produtos_empresa')
                   ->select('produtos.*','produtos_empresa.*','produtos.nome_pt as nome')
                   ->leftJoin('produtos','produtos_empresa.id_produto','produtos.id')
                   ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                   ->where('produtos.online',1)
                   ->get();

    $option='';             
    foreach ($produtos as $value) {
      if ($value->id_produto == $id_prod->id_produto) {
        $option.= $value->nome;
      }
    }
    
    
    $encomenda_linha = \DB::table('encomenda_linha')->where('id_encomenda',$id_encomenda)->where('id_produto',$id_prod->id_produto)->get();

    foreach ($encomenda_linha as $value) {

      if ($value->id_produto == $id_prod->id_produto) {
        
        $qtd_t = $encomenda_linha->sum('quantidade') - $id_prod->quantidade;
        $info_td = '<td>'.$option.'</td>
                    <td class="dashboard-col-add-2">'.$qtd_t.'</td>';
      }
    }



    \DB::table('encomenda_linha')->where('id',$id_line)->delete();
    $count_line = \DB::table('encomenda_linha')->where('id_encomenda',$id_encomenda)->count();
    $count_armazem = \DB::table('encomenda_linha')->where('id_encomenda',$id_encomenda)->where('id_morada',$id_morada)->count();

    if($count_line == 0){
      \DB::table('encomenda')->where('id',$id_encomenda)->delete();
    }

    if($count_armazem == 0){
      \DB::table('encomenda_armazem')->where('id_encomenda',$id_encomenda)->where('id_morada',$id_morada)->delete();
    }

    $resposta = [ 
      'estado' => 'sucesso',
      'id_line' => $id_line,
      'id_morada' => $id_morada,
      'qtd_final' => $qtd_final,
      'valor_final' => number_format((float)$valor_final, 2, '.', ''),
      'valor_final_fat' => number_format((float)$valor_final_fat, 2, '.', ''),
      'qtd_final_fat' => $qtd_final_fat,
      'valor_iva' => number_format((float)$valor_iva, 2, '.', ''),
      'valor_iva_fat' => number_format((float)$valor_iva_fat, 2, '.', ''),
      'id_prod' => $id_prod->id_produto,
      'info_td' => $info_td,
      'qtd_t' => $qtd_t
    ];
    return json_encode($resposta,true);
  }

  public function updateLine(Request $request){

    $id_line = $request->id_line;
    $id_prod = $request->id_prod;
    $qtd = $request->qtd;
    $preco_unitario = $request->preco_unitario;
    $qtd_anterior = $request->qtd_anterior;
    $qtd_parcial_t = $request->qtd_parcial_t;
    $valor_parcial_t = $request->valor_parcial_t;
    $qtd_fatura = $request->qtd_fatura;
    $valor_fatura = $request->valor_fatura;
    $qtd_line = $request->qtd_line;
    $tipo = $request->tipo;

    if($tipo == 'manual'){
      $qtd = $qtd_line; 
    }

    $valor_line = $preco_unitario*$qtd;
    $qtd_line_t = ( $qtd_parcial_t - $qtd_anterior ) + $qtd;
    $valor_line_t = $valor_parcial_t - ($qtd_anterior * $preco_unitario ) + ($qtd * $preco_unitario);

    $qtd_fatura_t = ( $qtd_fatura - $qtd_anterior ) + $qtd; 

    $valor_fatura_t = $valor_fatura - ($preco_unitario * $qtd_anterior) + ($qtd * $preco_unitario);

    $valor_iva = round($valor_fatura_t * 0.06, 2); 
    $valor_com_iva = round($valor_fatura_t + $valor_iva, 2);

    $produtos = \DB::table('produtos_empresa')
                   ->select('produtos.*','produtos_empresa.*','produtos.nome_pt as nome')
                   ->leftJoin('produtos','produtos_empresa.id_produto','produtos.id')
                   ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                   ->where('produtos.online',1)
                   ->get();

    $option='';             
    foreach ($produtos as $value) {
      if ($value->id_produto == $id_prod) {
        $option.= $value->nome;
      }
    }
    
    $encomenda = \DB::table('encomenda_linha')->where('id',$id_line)->first();
    $encomenda_linha = \DB::table('encomenda_linha')->where('id_encomenda',$encomenda->id_encomenda)->where('id_produto',$id_prod)->get();

    
    $qtd_t = ($qtd - $qtd_anterior) + $encomenda_linha->sum('quantidade');
    
    foreach ($encomenda_linha as $value) {

      if ($value->id_produto == $id_prod) {
        if (count($encomenda_linha) == 1) {
          $qtd_t = $qtd;
        }
        
        $info_td = '<td>'.$option.'</td>
                    <td class="dashboard-col-add-2">'.$qtd_t.'</td>';
      }
    }

    if($qtd == 0){
      \DB::table('encomenda_linha')->where('id',$id_line)->delete();
    }
    else{

      $produto_v = \DB::table('produtos')->where('id',$id_prod)->first();
      $company = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

      $produto_valor = \DB::table('produtos_empresa')
                           ->where('id_produto',$id_prod)
                           ->where('produtos_empresa.id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                           ->first();
     
      if($company->precos == 'percentagem'){
        $valor = $produto_v->preco_unitario * (1 + ($produto_valor->valor / 100));
      }
      else{
        $valor = $produto_v->preco_unitario;
      }

      $v_produto =  number_format((float)round( $valor ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
      $v_caixa_produto = number_format((float)round( $produto_v->qtd_caixa * $v_produto ,2, PHP_ROUND_HALF_DOWN),2,'.',',');
      $valor_prod = $qtd*($produto_v->qtd_caixa*$valor);

      \DB::table('encomenda_linha')->where('id',$id_line)
        ->update([
            'id_produto' => $id_prod,
            'quantidade' => $qtd,
            'preco_produto' => $v_produto,
            'total' => $valor_prod
        ]);
    }

    $resposta = [ 
      'estado' => 'sucesso',
      'id_line' => $id_line,
      'id_prod' => $id_prod,
      'qtd' => $qtd,
      'valor_line' => number_format((float)$valor_line, 2, '.', ''),
      'qtd_line_t' => $qtd_line_t,
      'valor_line_t' => number_format((float)$valor_line_t, 2, '.', ''),
      'qtd_fatura_t' => $qtd_fatura_t,
      'valor_fatura_t' => number_format((float)$valor_fatura_t, 2, '.', ''),
      'valor_iva' => $valor_iva,
      'valor_com_iva' => number_format((float)$valor_com_iva, 2, '.', ''),
      'info_td' => $info_td
    ];

    return json_encode($resposta,true);
  }

  public function cleanDatos(Request $request){

    $id_encomenda = $request->id_encomenda;
    \DB::table('encomenda')->where('id',$id_encomenda)->delete();
    \DB::table('encomenda_linha')->where('id_encomenda',$id_encomenda)->delete();

    $resposta = [ 
      'estado' => 'sucesso'
    ];

    return json_encode($resposta,true);
  }

  public function cancelOrder(Request $request){
    $id_encomenda = $request->id;

    $enc = \DB::table('encomenda')->where('id',$id_encomenda)->first();
    $enc_total = \DB::table('encomenda')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('estado',$enc->estado)->get();

    $total_estado = count($enc_total) - 1;

    $enc_armz = \DB::table('encomenda_armazem')->where('id_encomenda',$id_encomenda)->get();


    $enc_array = [];
    foreach ($enc_armz as $end) {
      $encomendas_armaz = \DB::table('encomenda_armazem')
                             ->where('id_morada',$end->id_morada)
                             ->count();

      $enc_array[] = [
        'id_morada' => $end->id_morada,
        'count_enc' => $encomendas_armaz -1
        ];

      \File::delete(base_path().'/public_html/doc/orders/'.$end->doc_encomenda);
    }
    \File::delete(base_path().'/public_html/doc/orders/'.$enc->documento);
    
    $encomendas = \DB::table('encomenda')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->get();

    \DB::table('encomenda_armazem')->where('id_encomenda',$id_encomenda)->delete(); 
    \DB::table('encomenda')->where('id',$id_encomenda)->delete();

    $resposta = [ 
      'estado' => 'sucesso',
      'total_enc' => count($encomendas),
      'total_estado' => $total_estado,
      'estado_enc' => $enc->estado,
      'enc_array' => $enc_array
    ];

    return json_encode($resposta,true);
  }

  public function addComprovativo(Request $request){
    /*Comprovativo por armazem*/
    $id = $request->id;
    $comprovativo = $request->file('comprovativo'.$id);

    /*Comprovativo por encomenda*/
    $id_encomenda = $request->id_encomenda;
    $comprovativo_total = $request->file('comprovativo_total');

    $cache = str_random(3);

    if (count($comprovativo)) {

      $enc_armazem = \DB::table('encomenda_armazem')->where('id',$id)->first();

      if(isset($enc_armazem->doc_comprovativo) && file_exists(base_path().'/public_html/doc/orders/'.$enc_armazem->doc_comprovativo)){
        \File::delete(base_path().'/public_html/doc/orders/'.$enc_armazem->doc_comprovativo);
      }

      $destinationPath = base_path('public_html/doc/orders/');
      
      $extension = strtolower($comprovativo->getClientOriginalExtension());
      $getName = $comprovativo->getPathName();

      $novoNome = 'order_'.$enc_armazem->id_encomenda.'_'.$enc_armazem->id_morada.'_comprovativo_'.$cache.'.'.$extension;
            
      move_uploaded_file($getName, $destinationPath.$novoNome);

      \DB::table('encomenda_armazem')->where('id',$id)
          ->update([
            'doc_comprovativo' => $novoNome,
            'data_comprovativo' => \Carbon\Carbon::now()->timestamp
          ]);

    }elseif(count($comprovativo_total)){

      $enc = \DB::table('encomenda')->where('id',$id_encomenda)->first();
      
      if(isset($enc->comprovativo) && file_exists(base_path().'/public_html/doc/orders/'.$enc->comprovativo)){
        \File::delete(base_path().'/public_html/doc/orders/'.$enc->comprovativo);
      }

      $destinationPath = base_path('public_html/doc/orders/');
      
      $extension = strtolower($comprovativo_total->getClientOriginalExtension());
      $getName = $comprovativo_total->getPathName();

      $novoNome = 'order_'.$id_encomenda.'_comprovativo_'.$cache.'.'.$extension;
            
      move_uploaded_file($getName, $destinationPath.$novoNome);

      \DB::table('encomenda')->where('id',$id_encomenda)
          ->update([
            'comprovativo' => $novoNome,
            'data_comprovativo' => \Carbon\Carbon::now()->timestamp
          ]);

      $id = $id_encomenda;
    }

  
    $resposta = [
      'estado' => 'sucesso',
      'id_enc' => $id,
      'doc' => $novoNome,
      'date' => date('Y-m-d',\Carbon\Carbon::now()->timestamp)
    ];
    return json_encode($resposta,true);
  }

  public function deleteComprovativo(Request $request){

    $id = trim($request->id);
    $tipo = trim($request->tipo);
    $enc_armazem = \DB::table('encomenda_armazem')->where('id',$id)->first();
    $enc = \DB::table('encomenda')->where('id',$id)->first();

    if ($tipo == 'parcial') {
      if($enc_armazem->doc_comprovativo && file_exists(base_path().'/public_html/doc/orders/'.$enc_armazem->doc_comprovativo)){
        \File::delete(base_path().'/public_html/doc/orders/'.$enc_armazem->doc_comprovativo);
      }

      \DB::table('encomenda_armazem')->where('id',$id)
          ->update([
            'doc_comprovativo' => '',
            'data_comprovativo' => NULL
          ]);
    }elseif($tipo == 'total'){

      if($enc->comprovativo && file_exists(base_path().'/public_html/doc/orders/'.$enc->comprovativo)){
        \File::delete(base_path().'/public_html/doc/orders/'.$enc->comprovativo);
      }

      \DB::table('encomenda')->where('id',$id)
          ->update([
            'comprovativo' => '',
            'data_comprovativo' => NULL
          ]);
    }
    return 'sucesso';
  }       
}