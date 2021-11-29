<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Orders extends Controller
{
  private $dados=[];
  
  /************/
  /* Companies */
  /************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.ordersTitulo');
    $this->dados['separador']="bizOrders";
    $this->dados['funcao']="all";

    $query = \DB::table('encomenda')
                ->select('encomenda.*','encomenda.estado AS estado_enc','encomenda.id AS id_encomenda','comerciantes.nome AS nome_comerciante','empresas.nome AS nome_empresa','empresas.tipo_fatura','empresas.id AS id_empresa')
                ->leftJoin('comerciantes','encomenda.id_comerciante','comerciantes.id')
                ->leftJoin('empresas','encomenda.id_empresa','empresas.id')
                ->where('encomenda.estado','<>','inicio')
                ->orderBy('encomenda.id','DESC')->get(); 

    $array =[];
    foreach ($query as $valor){

      switch ($valor->estado_enc){
        case "concluida": $estado = '<span class="tag tag-verde nowrap">'.trans('backoffice.Completed').'</span>'; break;
        case "fatura_vencida": $estado = '<span class="tag tag-vermelho nowrap">'.trans('backoffice.fatura_vencida').'</span>'; break;
        case "em_processamento": $estado = '<span class="tag tag-amarelo nowrap">'.trans('backoffice.In_processing').'</span>'; break;
        case "expedida_parcialmente": $estado = '<span class="tag tag-laranja nowrap">'.trans('backoffice.partially_dispatched').'</span>'; break;
        case "expedida": $estado = '<span class="tag tag-azul nowrap">'.trans('backoffice.Dispatched').'</span>'; break;
        case "registada": $estado = '<span class="tag tag-roxo nowrap">'.trans('backoffice.Registered').'</span>'; break;
        default: $estado = '<span class="tag tag-cinza nowrap">'.$valor->estado_enc.'</span>';
      }

      if(file_exists(base_path('public_html/doc/orders/'.$valor->documento))){
        $documento = '<a href="/doc/orders/'.$valor->documento.'" download>'.$valor->documento.'</a>';
      }else{
        $documento = '<a href="'.route('ordersPdfB', [$valor->id,$valor->id_empresa] ).'">'.$valor->documento.'</a>';
      } 

      $array[] = [
        'id' => $valor->id,
        'id_encomenda' => $valor->id_encomenda,
        'empresa' => $valor->nome_empresa,
        'comerciante' => $valor->nome_comerciante,
        'referencia' => $valor->referencia,
        'documento' => $documento,
        'fatura' => $valor->fatura,
        'data_fatura' => $valor->data_fatura,
        'recibo' => $valor->recibo,
        'data_recibo' => $valor->data_recibo,
        'subtotal' => $valor->subtotal,
        'total' => $valor->total,
        'data' => $valor->data ? date('d/m/Y H:i:s',$valor->data) : '',
        'estado' => $estado,
        'tipo_fatura'=> $valor->tipo_fatura
      ];
    }

    $this->dados['companys'] = \DB::table('empresas')->get();
    $this->dados['array'] = $array;
    return view('backoffice/pages/orders-all', $this->dados);
  }

  public function ordersWarehouse($id){
    $this->dados['headTitulo']=trans('backoffice.ordersTitulo');
    $this->dados['separador']="bizOrders";
    $this->dados['funcao']="all";

    $query = \DB::table('encomenda_armazem')
              ->select('encomenda_armazem.*','encomenda_armazem.id AS id','encomenda_armazem.estado AS estado_armaz','encomenda.id_empresa AS id_empresa')
              ->leftJoin('encomenda','encomenda_armazem.id_encomenda','encomenda.id')
              ->where('id_encomenda',$id)->get();
  
    $array =[];
    foreach ($query as $valor){

      switch ($valor->estado_armaz){
        case "concluida": $estado = '<span class="tag tag-verde">'.trans('backoffice.Completed').'</span>'; break;
        case "fatura_vencida": $estado = '<span class="tag tag-vermelho">'.trans('backoffice.Fatura Vencida').'</span>'; break;
        case "em_processamento": $estado = '<span class="tag tag-amarelo cursor-pointer">'.trans('backoffice.In_processing').'</span>'; break;
        case "expedida_parcialmente": $estado = '<span class="tag tag-laranja cursor-pointer">'.trans('backoffice.partially_dispatched').'</span>'; break;
        case "expedida": $estado = '<span class="tag tag-azul cursor-pointer">'.trans('backoffice.Dispatched').'</span>'; break;
        case "registada": $estado = '<span class="tag tag-roxo">'.trans('backoffice.Registered').'</span>'; break;
        default: $estado = '<span class="tag tag-cinza">'.$valor->estado_armaz.'</span>';
      }

      if(file_exists(base_path('public_html/doc/orders/'.$valor->doc_encomenda))){
        $doc_parcial = '<a href="/doc/orders/'.$valor->doc_encomenda.'" download>'.$valor->doc_encomenda.'</a>';
      }else{
        $doc_parcial = '<a href="'.route('ordersAdressPdfB', [$id,$valor->id_morada,$valor->id_empresa] ).'">'.$valor->doc_encomenda.'</a>';
      } 

      $array[] = [
        'id' => $valor->id,
        'id_morada'=>$valor->id_morada,
        'id_encomenda' => $valor->id_encomenda,
        'doc_parcial' => $doc_parcial,
        'data_encomenda' => $valor->data_encomenda,
        'data_inicio_process' => $valor->data_inicio_process,
        'doc_proforma' => $valor->doc_proforma,
        'data_proforma' => $valor->data_proforma,
        'doc_guia' => $valor->doc_guia,
        'data_guia' => $valor->data_guia,
        'data_expedicao' => $valor->data_expedicao,
        'doc_fatura' => $valor->doc_fatura,
        'data_fatura' => $valor->data_fatura,
        'doc_comprovativo' => $valor->doc_comprovativo,
        'data_comprovativo' => $valor->data_comprovativo,
        'doc_recibo' => $valor->doc_recibo,
        'data_recibo' => $valor->data_recibo,
        'subtotal' => $valor->subtotal,
        'total' => $valor->total,
        'estado' => $estado
      ];
    }

    $this->dados['array'] = $array;
    $this->dados['id'] = $id;
    return view('backoffice/pages/orders-warehouse-all', $this->dados);
  }

  public function edit($id){
    $this->dados['headTitulo']=trans('backoffice.ordersTitulo');
    $this->dados['separador']="bizOrders";
    $this->dados['funcao']="edit";

    $obj = \DB::table('encomenda_armazem')
              ->select('encomenda_armazem.*','encomenda_armazem.id AS id_enc_armz','encomenda.id_empresa AS id_empresa','encomenda_armazem.estado AS estado_enc')
              ->leftJoin('encomenda','encomenda_armazem.id_encomenda','encomenda.id')
              ->where('encomenda_armazem.id',$id)->first();


    $this->dados['moradas']=\DB::table('moradas')->where('id_empresa',$obj->id_empresa)->get();
    $this->dados['empresa']=\DB::table('empresas')->where('id',$obj->id_empresa)->first();
    $this->dados['obj'] = $obj;

    return view('backoffice/pages/orders-edit', $this->dados);
  }

  public function editOrderTotal($id){
    $this->dados['headTitulo']=trans('backoffice.ordersTitulo');
    $this->dados['separador']="bizOrders";
    $this->dados['funcao']="edit";

    $this->dados['obj']= \DB::table('encomenda')->where('id',$id)->first();
    
    return view('backoffice/pages/orders-edit-total', $this->dados);
  }

  public function newPageOrders($id){
    $this->dados['headTitulo']=trans('backoffice.ordersTitulo');
    $this->dados['separador']="bizOrders";
    $this->dados['funcao']="new";

    $this->dados['moradas_armaz'] = \DB::table('moradas')->where('id_empresa',$id)->where('tipo','morada_armazem')->get();

    $produtos_empresa = \DB::table('produtos_empresa')
                                   ->select('produtos.*','produtos_empresa.*','produtos.nome_pt as nome')
                                   ->leftJoin('produtos','produtos_empresa.id_produto','produtos.id')
                                   ->where('produtos_empresa.id_empresa',$id)
                                   ->where('produtos.online',1)
                                   ->get();

    $array_prod = [];
   
    foreach ($produtos_empresa as $prod) {
      $array_prod[] = [
        'id_produto' => $prod->id_produto,
        'nome' => $prod->nome_pt,
        'valor' => $prod->valor,
        'qtd_caixa' => $prod->qtd_caixa
      ]; 
    }

    $this->dados['comerciantes'] = \DB::table('comerciantes')->where('id_empresa',$id)->get();

    
    $this->dados['id'] = $id;
    $this->dados['array_prod'] = $array_prod;
    
    return view('backoffice/pages/orders-new', $this->dados);
  }

  public function ordersInvoice($id){
    $this->dados['headTitulo'] = trans('backoffice.ordersTitulo');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'bizOrders';
    $this->dados['funcao'] = 'edit';
   

    $obj = \DB::table('encomenda')->where('id',$id)->first();
    $this->dados['obj'] = $obj;
    return view('backoffice/pages/orders-invoice',$this->dados);
  }

  public function ordersInvoiceForm(Request $request){
    $id=trim($request->id);
    $fatura=$request->file('fatura');
    $recibo=$request->file('recibo');
    $data_fatura = strtotime(trim($request->data_fatura)) ? strtotime(trim($request->data_fatura)) : '0';
    $data_recibo = strtotime(trim($request->data_recibo)) ? strtotime(trim($request->data_recibo)) : '0';

    $encomenda = \DB::table('encomenda')->where('id',$id)->first();

    $novo_fatura='';
    if(count($fatura)){
      $antigoNome=$encomenda->fatura;
      if(file_exists(base_path('public_html/encomenda/doc_fatura/'.$antigoNome))){ \File::delete('../public_html/encomenda/doc_fatura/'.$antigoNome); }

      $destinationPath = base_path('public_html/encomenda/doc_fatura/');
      $extension = strtolower($fatura->getClientOriginalExtension());
      $getName = $fatura->getPathName();
      $novo_fatura = 'f-'.$id.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_fatura);
      \DB::table('encomenda_armazem')->where('id',$id)->update([ 'doc_fatura'=>$novo_fatura ]);
    }

    $novo_recibo='';
    if(count($recibo)){
      $antigoNome=$encomenda->recibo;

      if(file_exists(base_path('public_html/encomenda/doc_recibo/'.$antigoNome))){ \File::delete('../public_html/encomenda/doc_recibo/'.$antigoNome); }

      $destinationPath = base_path('public_html/encomenda/doc_recibo/');
      $extension = strtolower($recibo->getClientOriginalExtension());
      $getName = $recibo->getPathName();
      $novo_recibo = 'r-'.$id.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_recibo);
      
      \DB::table('encomenda_armazem')->where('id',$id)->update([ 'doc_recibo'=>$novo_recibo ]);
    }

    \DB::table('encomenda')
        ->where('id',$id)
        ->update([
          'fatura'=>$novo_fatura,
          'data_fatura'=>$data_fatura,
          'recibo'=>$novo_recibo,
          'data_recibo'=>$data_recibo,
        ]);

    $titulo = 'Encomenda '.$encomenda->referencia; 

    if(($novo_fatura) || ($novo_recibo)){
      
      if ($novo_fatura){$texto='Factura disponível. Envie o comprovativo.';}
      elseif($novo_recibo){$texto='Recibo disponível.';}
      
      /*\DB::table('notificacoes')
          ->insert([
            'id_empresa' =>$encomenda->id_empresa,
            'img'=>'\seller\img\dashboard\doc.svg',
            'titulo'=>$titulo,
            'texto'=>$texto,
            'data'=>\Carbon\Carbon::now()->timestamp
          ]);*/
    }

    //Mudar o valor do cookie das notificações
    /*$not = \DB::table('notificacoes')->where('online','1')->count();
    Cookie::queue('cookie_notification_ative', $not);*/

    //Enviar email com a fatura e resumo da encomenda geral
    $config_enc=\DB::table('comerciantes')->where('id',$encomenda->id_comerciante)->first();
    
    $dados_user = [ 'id'=>$id ];

    if ($novo_fatura != '') {
      $config_fatura=\DB::table('empresas_config')
                          ->leftJoin('comerciantes','empresas_config.id_comerciante','comerciantes.id')
                          ->where('comerciantes.id_empresa',$encomenda->id_empresa)
                          ->where('empresas_config.tag','enc_uti')
                          ->where('empresas_config.valor',1)
                          ->first();

      
      $file='http://www.universal.com.pt/encomenda/doc_fatura/'.$novo_fatura;
      $file_resume='http://www.universal.com.pt/seller/encomendas/encomenda_total/'.$encomenda->documento;

      if (isset($config_fatura)) {
        \Mail::send('backoffice.emails.pages.send-invoice',['dados' => $dados_user], function($message) use ($config_fatura,$file,$file_resume){
          $message->to($config_fatura->email,$config_fatura->nome)->subject(trans('Fatura da encomenda disponível'));
          $message->attach($file);
          $message->attach($file_resume);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
      
      if ((isset($config_fatura) && ($config_fatura->id_comerciante != $config_enc->id)) || empty($config_fatura->id_comerciante)) {
        \Mail::send('backoffice.emails.pages.send-invoice',['dados' => $dados_user], function($message) use ($config_enc,$file){
          $message->attach($file);
          $message->to($config_enc->email,$config_enc->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    }

    //Enviar email com o recibo

    if ($novo_recibo != '') {
      
      $config_user=\DB::table('empresas_config')
                        ->leftJoin('comerciantes','empresas_config.id_comerciante','comerciantes.id')
                        ->where('comerciantes.id_empresa',$encomenda->id_empresa)
                        ->where('empresas_config.tag','recibo_uti')
                        ->where('empresas_config.valor',1)
                        ->first();
      
      $file='http://www.universal.com.pt/encomenda/doc_recibo/'.$novo_recibo;

      if (isset($config_user)) {
        \Mail::send('backoffice.emails.pages.send-receipt',['dados' => $dados_user], function($message) use ($config_user,$file){
          $message->to($config_user->email,$config_user->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
      

      if ((isset($config_user) && ($config_user->id_comerciante != $config_enc->id)) || empty($config_user->id_comerciante)) {
        
        \Mail::send('backoffice.emails.pages.send-receipt',['dados' => $dados_user], function($message) use ($config_enc,$file){
          $message->attach($file);
          $message->to($config_enc->email,$config_enc->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    } 

    return 'sucesso';
  }

  public function addLineProduct(Request $request){
    $id_empresa = trim($request->id_empresa);
    $id_comerciante = trim($request->id_comerciante);
  
    $moradasQuery = \DB::table('moradas')->where('id_empresa',$id_empresa)->where('tipo','morada_armazem')->get();
    $produtosQuery = \DB::table('produtos')->get();

    $auxEncomenda = 0;
    $subTotalEncomenda = 0;
    $totalEncomenda = 0;
    $qtdTotal = 0;
    foreach ($moradasQuery as $morada){
      $auxMorada = 0;
      $subTotalMorada = 0;
      $totalMorada = 0;
      foreach ($produtosQuery as $produto) {
        $qtd_nome = 'quantidade'.$produto->id.$morada->id;
        $quantidade = intval(trim($request->$qtd_nome));
        
        if($quantidade){
          $qtdTotal = $qtdTotal + $quantidade;
          //entra apenas uma vez
          if($auxEncomenda==0){
            $auxEncomenda = 1;
            $id_encomenda = \DB::table('encomenda')
                            ->insertGetId([
                              'id_comerciante' => $id_comerciante,
                              'id_empresa' => $id_empresa,
                              'estado' => 'registada',
                              'data' => \Carbon\Carbon::now()->timestamp ]);
            
          }
          //entra uma vez por morada
          if($auxMorada==0){
            $auxMorada = 1;

            $cache = str_random(3);
            $doc_encomenda = 'order_'.$id_encomenda.'_'.$morada->id.'_parcial_'.$cache.'.pdf';

            $id_armazem = \DB::table('encomenda_armazem')
                            ->insertGetId([
                              'id_encomenda' => $id_encomenda,
                              'id_morada' => $morada->id,
                              'doc_encomenda' => $doc_encomenda,
                              'data_encomenda' => \Carbon\Carbon::now()->timestamp,
                              'morada' => $morada->morada
                            ]);           
          }


          //$preco_total = $preco_total + (($quantidade * $produto->qtd_caixa) * $produto->valor);
          $produto_emp = \DB::table('produtos_empresa')
                            ->where('id_empresa',$id_empresa)
                            ->where('id_produto',$produto->id)
                            ->first();

          $total_linha = ($produto_emp->valor*$produto->qtd_caixa)*$quantidade;

          $subTotalMorada = $subTotalMorada + $total_linha;
          $totalMorada =  (($subTotalMorada*$produto->iva)/100) + $subTotalMorada;

          

          \DB::table('encomenda_linha')
            ->insert([
              'id_encomenda' => $id_encomenda,
              'id_produto' => $produto->id,
              'id_morada' => $morada->id,
              'nome_produto' => $produto->nome_pt,
              'preco_produto' => $produto_emp->valor,
              'qtd_caixa' => $produto->qtd_caixa,
              'total' => $total_linha,
              'quantidade' => $quantidade,
              'estado' => 'ativo',
              'data' => \Carbon\Carbon::now()->timestamp
            ]);
        }
      }

      $subTotalEncomenda = $subTotalEncomenda + $subTotalMorada;
          $totalEncomenda = $totalEncomenda + $totalMorada;
          
      //aqui já tens os produtos todos adicionados na morada
      //podes agora actualizar os subtotais e total

      if($qtdTotal > 0){
        \DB::table('encomenda_armazem')->where('id',$id_armazem)
          ->update([
            'subtotal' => number_format((float)round( $subTotalMorada ,2, PHP_ROUND_HALF_DOWN),2,'.',','),
            'total' => number_format((float)round( $totalMorada ,2, PHP_ROUND_HALF_DOWN),2,'.',','),
          ]);
      }
    }

    if($qtdTotal == 0){

      $resposta = [ 
        'estado' => 'erro',
        'mensagem' => 'Adicione produtos a encomenda.'
      ];

      return json_encode($resposta,true);
    }

    //Update referencia,documento,subtotal e total da encomenda
    $cache = str_random(3);
    $doc = 'order_'.$id_encomenda.'_'.$cache.'.pdf';
    \DB::table('encomenda')
        ->where('id',$id_encomenda)
        ->update([
          'referencia' => 'Referencia_'.$id_encomenda,
          'documento' => $doc,
          'subtotal' => number_format((float)round( $subTotalEncomenda ,2, PHP_ROUND_HALF_DOWN),2,'.',','),
          'total' => number_format((float)round( $totalEncomenda ,2, PHP_ROUND_HALF_DOWN),2,'.',',')
        ]);

    \DB::table('notificacoes')
        ->insert([
            'id_notificado' => $id_comerciante,
            'id_empresa' => $id_empresa,
            'id_comerciante' => $id_comerciante,
            'id_encomenda' => $id_encomenda,
            'tipo' => 'enc_iniciada',
            'url' => '\orders',
            'data' => \Carbon\Carbon::now()->timestamp
        ]); 
    
    $not_count=\DB::table('notificacoes')->where('id_notificado',Cookie::get('cookie_comerc_id'))->where('vista',0)->count();

    Cookie::queue(Cookie::make('cookie_not_ative', $not_count, 43200));
    
    $reload = 'sim';
    $resposta = [ 
      'estado' => 'sucesso',
      'id_encomenda' => $id_encomenda,
      'id_empresa' => $id_empresa,
      'reload' => $reload
    ];
    
    return json_encode($resposta,true);
  }

  public function formEdit(Request $request){
    $id = trim($request->id);
    $armazem = trim($request->armazem);
    $data_inicio_process = strtotime(trim($request->data_inicio_process)) ? strtotime(trim($request->data_inicio_process)) : '0';
    $data_expedicao = strtotime(trim($request->data_expedicao)) ? strtotime(trim($request->data_expedicao)) : '0';
    $estado = trim($request->estado);
    $proforma = $request->file('proforma');
    $guia = $request->file('guia');
    $fatura = $request->file('fatura');
    $comprovativo = $request->file('comprovativo');
    $recibo = $request->file('recibo');
    $obs = trim($request->obs);

    $armaz = \DB::table('encomenda_armazem')->where('id',$id)->first();
    $morada = \DB::table('moradas')->where('id',$armazem)->first();
    $encomenda = \DB::table('encomenda')->where('id',$armaz->id_encomenda)->first();
    $seller = \DB::table('comerciantes')->where('id',$encomenda->id_comerciante)->first();

    $cache = str_random(3);
    $novo_proforma='';
    if(count($proforma)){
      $antigoNome=$armaz->doc_proforma;
      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }

      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($proforma->getClientOriginalExtension());
      $getName = $proforma->getPathName();
      $novo_proforma = 'order_'.$id.'_'.$armazem.'_proforma_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_proforma);
      \DB::table('encomenda_armazem')
          ->where('id',$id)
          ->update([ 
            'doc_proforma'=>$novo_proforma,
            'data_proforma' => \Carbon\Carbon::now()->timestamp
          ]);
    }

    $novo_guia='';
    if(count($guia)){
      $antigoNome=$armaz->doc_guia;
      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }

      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($guia->getClientOriginalExtension());
      $getName = $guia->getPathName();
      $novo_guia = 'order_'.$id.'_'.$armazem.'_guia_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_guia);      
      \DB::table('encomenda_armazem')
          ->where('id',$id)
          ->update([ 
            'doc_guia' => $novo_guia, 
            'data_guia' => \Carbon\Carbon::now()->timestamp
          ]);
    }

    $novo_fatura='';
    if(count($fatura)){
      $antigoNome=$armaz->doc_fatura;
      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }

      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($fatura->getClientOriginalExtension());
      $getName = $fatura->getPathName();
      $novo_fatura = 'order_'.$id.'_'.$armazem.'_fatura_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_fatura);
      \DB::table('encomenda_armazem')
          ->where('id',$id)
          ->update([ 
            'doc_fatura'=>$novo_fatura,
            'data_fatura' => \Carbon\Carbon::now()->timestamp
          ]);

      
      if (isset($seller)) {

       \DB::table('notificacoes')
          ->insert([
            'id_notificado' => $seller->id,
            'id_empresa' => $encomenda->id_empresa,
            'id_comerciante' => $seller->id,
            'id_encomenda' => $encomenda->id,
            'tipo' => 'enc_fat_compr',
            'url' => '\orders',
            'data' => \Carbon\Carbon::now()->timestamp
          ]);

        $dados_enc = [ 
          'id' => $encomenda->id ,
          'armazem' => 'do armazém <b>'.$morada->morada.'</b>,' 
        ];

        $file='http://www.universal.com.pt/doc/orders/'.$novo_fatura;

        \Mail::send('backoffice.emails.pages.send-invoice',['dados' => $dados_enc], function($message) use ($seller,$file){
          $message->to($seller->email,$seller->nome)->subject(trans('Fatura da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }

      $users_config = \DB::table('empresas_config')
                          ->where('id_empresa',$encomenda->id_empresa)
                          ->where('id_comerciante','<>',$seller->id)
                          ->where('tag','enc_uti')
                          ->where('valor',1)
                          ->get();

      
      foreach ($users_config as $value) {
        $comerc = \DB::table('comerciantes')->where('id',$value->id_comerciante)->first();

        $dados_enc = [ 
          'id' => $encomenda->id ,
          'armazem' => 'do armazém <b>'.$morada->morada.'</b>,' 
        ];

        $file='http://www.universal.com.pt/doc/orders/'.$novo_fatura;

        \Mail::send('backoffice.emails.pages.send-invoice',['dados' => $dados_enc], function($message) use ($comerc,$file){
          $message->to($comerc->email,$comerc->nome)->subject(trans('Fatura da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    }

    $novo_comprovativo='';
    if(count($comprovativo)){
      $antigoNome=$armaz->doc_comprovativo;
      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }

      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($comprovativo->getClientOriginalExtension());
      $getName = $comprovativo->getPathName();
      $novo_comprovativo = 'order_'.$id.'_'.$armazem.'_comprovativo_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_comprovativo);  
      \DB::table('encomenda_armazem')
          ->where('id',$id)
          ->update([ 
            'doc_comprovativo' => $novo_comprovativo,
            'data_comprovativo' => \Carbon\Carbon::now()->timestamp
          ]);
    }

    $novo_recibo='';
    if(count($recibo)){

      $antigoNome = $armaz->doc_recibo;

      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }

      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($recibo->getClientOriginalExtension());
      $getName = $recibo->getPathName();
      $novo_recibo = 'order_'.$id.'_'.$armazem.'_recibo_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_recibo);
      
      \DB::table('encomenda_armazem')
          ->where('id',$id)
          ->update([ 
            'doc_recibo'=>$novo_recibo,
            'data_recibo' => \Carbon\Carbon::now()->timestamp
          ]);


      if (isset($seller)) {

        \DB::table('notificacoes')
          ->insert([
            'id_notificado' => $seller->id,
            'id_empresa' => $encomenda->id_empresa,
            'id_comerciante' => $seller->id,
            'id_encomenda' => $encomenda->id,
            'tipo' => 'enc_recibo_disp',
            'url' => '\orders',
            'data' => \Carbon\Carbon::now()->timestamp
          ]);

        $dados_enc = [ 
          'id' => $encomenda->id ,
          'armazem' => 'do armazém <b>'.$morada->morada.'</b>,' 
        ];

        $file='http://www.universal.com.pt/doc/orders/'.$novo_recibo;

        \Mail::send('backoffice.emails.pages.send-receipt',['dados' => $dados_enc], function($message) use ($seller,$file){
          $message->to($seller->email,$seller->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }

      $users_config = \DB::table('empresas_config')
                          ->where('id_empresa',$encomenda->id_empresa)
                          ->where('id_comerciante','<>',$seller->id)
                          ->where('tag','recibo_uti')
                          ->where('valor',1)
                          ->get();


      foreach ($users_config as $value) {
        $comerc = \DB::table('comerciantes')->where('id',$value->id_comerciante)->first();

        $dados_enc = [ 
          'id' => $encomenda->id ,
          'armazem' => 'do armazém <b>'.$morada->morada.'</b>,' 
        ];

        $file='http://www.universal.com.pt/doc/orders/'.$novo_recibo;

        \Mail::send('backoffice.emails.pages.send-receipt',['dados' => $dados_enc], function($message) use ($comerc,$file){
          $message->to($comerc->email,$comerc->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    }


    \DB::table('encomenda_armazem')
        ->where('id',$id)
        ->update([
          'id_morada' => $armazem,
          'data_inicio_process' => $data_inicio_process,
          'data_expedicao' => $data_expedicao,
          'obs' => $obs
        ]);

    if ($estado != $armaz->estado) {
      $enc_armz = \DB::table('encomenda_armazem')->where('id',$id)->first();

      \DB::table('encomenda_armazem')
        ->where('id',$id)
        ->update([
          'estado' => $estado
        ]);

      if ($estado == 'em_processamento') {
        $tipo = 'enc_processamento';
        $estado_html=trans('backoffice.In_processing');

        if ($enc_armz->data_inicio_process == 0) {
          \DB::table('encomenda_armazem')
              ->where('id',$id)
              ->update([
                'data_inicio_process' => \Carbon\Carbon::now()->timestamp
              ]);
        }
      }
      elseif($estado == 'expedida'){
        $tipo = 'enc_expedida';
        $estado_html=trans('backoffice.Dispatched');

        if ($enc_armz->data_expedicao == 0) {
          \DB::table('encomenda_armazem')
              ->where('id',$id)
              ->update([
                'data_expedicao' => \Carbon\Carbon::now()->timestamp
              ]);
        }
      }
      elseif($estado == 'expedida_parcialmente'){
        $tipo = 'enc_parcial_exp';
        $estado_html=trans('backoffice.partially_dispatched');
      }
      elseif($estado == 'fatura_vencida'){
        $tipo = 'enc_fat_vencida';
        $estado_html=trans('backoffice.fatura_vencida');
      }
      elseif($estado == 'concluida'){
        $tipo = 'enc_concluida';
        $estado_html=trans('backoffice.Completed');
      }
      else{
        $tipo = 'enc_iniciada';
        $estado_html=trans('backoffice.Registered');
      }

      if(isset($tipo) && isset($seller)){

        \DB::table('notificacoes')
            ->insert([
              'id_notificado' => $seller->id,
              'id_empresa' => $encomenda->id_empresa,
              'id_comerciante' => $seller->id,
              'id_encomenda' => $encomenda->id,
              'tipo' => $tipo,
              'url' => '\orders',
              'data' => \Carbon\Carbon::now()->timestamp
            ]);

        if (isset($seller)) {
          $dados_enc = [ 
            'id'=>$armaz->id_encomenda,
            'armazem'=>$morada->morada,
            'estado'=>$estado_html
          ];

          \Mail::send('backoffice.emails.pages.send-status',['dados' => $dados_enc], function($message) use ($seller){
            $message->to($seller->email,$seller->nome)->subject(trans('Estado da encomenda.'));
            $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
          });
        }
      }
    }

    if(($data_expedicao != 0) && ($data_expedicao != $armaz->data_expedicao) && isset($seller)){
      $dados_enc = [ 
        'id'=>$armaz->id_encomenda,
        'armazem'=>$morada->morada,
        'data'=> date('Y-m-d',$data_expedicao)
      ];

      \Mail::send('backoffice.emails.pages.send-data',['dados' => $dados_enc], function($message) use ($seller){
        $message->to($seller->email,$seller->nome)->subject(trans('Estado da encomenda.'));
        $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
      });
    }
    

    //verificar se as encomendas por armazem já estão validadas
    $armaz_count=\DB::table('encomenda_armazem')->where('id_encomenda',$armaz->id_encomenda)->count();
    $estado_armaz_count=\DB::table('encomenda_armazem')->where('id_encomenda',$armaz->id_encomenda)->where('estado','concluida')->count();
    
    if ($armaz_count == $estado_armaz_count) { \DB::table('encomenda')->update(['estado'=>'concluida']);}


    //Mudar o valor do cookie das notificações
    $not = \DB::table('notificacoes')->where('id_notificado',$seller->id)->where('vista','0')->count();
    if(Cookie::get('cookie_comerc_id') == $seller->id){
      Cookie::queue('cookie_not_ative', $not);
    }


    //Update valor dos pontos na da empresa dependendo do comprovativo e do estado
    $comprovativo_armaz=\DB::table('encomenda_armazem')->where('id_encomenda',$armaz->id_encomenda)->where('estado','concluida')->get();
    $pontos_enc=\DB::table('encomenda_pontos')->where('id_encomenda',$armaz->id_encomenda)->first();

    $count = 0;
    foreach ($comprovativo_armaz as $value) {
      if($value->doc_comprovativo != ''){
        $count = $count + 1;
      }
    }

    $empresa=\DB::table('empresas')->where('id',$encomenda->id_empresa)->first();
    if($armaz_count == $count){
      $total=$empresa->pontos + $pontos_enc->pontos;
      \DB::table('empresas')->where('id',$encomenda->id_empresa)->update(['pontos'=>$total]);
      \DB::table('encomenda_pontos')->where('id_encomenda',$armaz->id_encomenda)->update(['estado'=>'disponivel']);
    }

    $relaod = 'sim';

    $resposta = [
      'estado' => 'sucesso',
      'mensagem' => '',
      'reload' => $relaod 
    ];
    return json_encode($resposta,true);
  }

  public function formTotalEdit(Request $request){

    $id_encomenda = trim($request->id);
    $fatura = $request->file('fatura');
    $recibo = $request->file('recibo');
    $comprovativo = $request->file('comprovativo');
    $estado = trim($request->estado);

    $enc = \DB::table('encomenda')->where('id',$id_encomenda)->first();
    $seller = \DB::table('comerciantes')->where('id',$enc->id_comerciante)->first();

    $novo_recibo='';
    if(count($recibo) && ($recibo != $enc->recibo)){
      $antigoNome = $enc->recibo;

      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }
      $cache = str_random(3);

      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($recibo->getClientOriginalExtension());
      $getName = $recibo->getPathName();

      $novo_recibo = 'order_'.$id_encomenda.'_recibo_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_recibo);
      
      \DB::table('encomenda')
          ->where('id',$id_encomenda)
          ->update([ 
            'recibo'=>$novo_recibo,
            'data_recibo' => \Carbon\Carbon::now()->timestamp
          ]);

      \DB::table('notificacoes')
          ->insert([
            'id_notificado' => $enc->id_comerciante,
            'id_empresa' => $enc->id_empresa,
            'id_comerciante' => $enc->id_comerciante,
            'id_encomenda' => $id_encomenda,
            'tipo' => 'enc_recibo_disp',
            'url' => '\orders',
            'data' => \Carbon\Carbon::now()->timestamp
          ]);

      $file='http://www.universal.com.pt/doc/orders/'.$novo_recibo;

      if (isset($seller)) {

        $dados_enc = [ 
          'id'=>$id_encomenda,
          'armazem' => ''
        ];

        \Mail::send('backoffice.emails.pages.send-receipt',['dados' => $dados_enc], function($message) use ($seller,$file){
          $message->to($seller->email,$seller->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }

      $users_config = \DB::table('empresas_config')
                          ->where('id_empresa',$enc->id_empresa)
                          ->where('id_comerciante','<>',$enc->id_comerciante)
                          ->where('tag','recibo_uti')
                          ->get();

      foreach ($users_config as $value) {
        $comerc = \DB::table('comerciantes')->where('id',$value->id_comerciante)->first();

        $dados_enc = [ 
          'id'=>$id_encomenda,
          'armazem' => ''
        ];

        \Mail::send('backoffice.emails.pages.send-receipt',['dados' => $dados_enc], function($message) use ($comerc,$file){
          $message->to($comerc->email,$comerc->nome)->subject(trans('Recibo da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    }
   
    $novo_fatura='';
    if(count($fatura) && ($fatura != $enc->fatura)){

      $antigoNome = $enc->fatura;

      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }
      $cache = str_random(3);
      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($fatura->getClientOriginalExtension());
      $getName = $fatura->getPathName();
      $novo_fatura = 'order_'.$id_encomenda.'_fatura_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_fatura);
      
      \DB::table('encomenda')
          ->where('id',$id_encomenda)
          ->update([ 
            'fatura'=>$novo_fatura,
            'data_fatura' => \Carbon\Carbon::now()->timestamp
          ]);

      \DB::table('notificacoes')
          ->insert([
            'id_notificado' => $enc->id_comerciante,
            'id_empresa' => $enc->id_empresa,
            'id_comerciante' => $enc->id_comerciante,
            'id_encomenda' => $id_encomenda,
            'tipo' => 'enc_fat_compr',
            'url' => '\orders',
            'data' => \Carbon\Carbon::now()->timestamp
          ]);

      if (isset($seller)) {

        $dados_enc = [ 
          'id'=>$id_encomenda,
          'armazem' => ''
        ];

        $file='http://www.universal.com.pt/doc/orders/'.$novo_fatura;

        \Mail::send('backoffice.emails.pages.send-invoice',['dados' => $dados_enc], function($message) use ($seller,$file){
          $message->to($seller->email,$seller->nome)->subject(trans('Fatura da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }

      $users_config = \DB::table('empresas_config')
                          ->where('id_empresa',$enc->id_empresa)
                          ->where('id_comerciante','<>',$enc->id_comerciante)
                          ->where('tag','enc_uti')
                          ->get();

      foreach ($users_config as $value) {
        $comerc = \DB::table('comerciantes')->where('id',$value->id_comerciante)->first();

        $dados_enc = [ 
          'id'=>$id_encomenda,
          'armazem' => ''
        ];

        $file='http://www.universal.com.pt/doc/orders/'.$novo_fatura;

        \Mail::send('backoffice.emails.pages.send-invoice',['dados' => $dados_enc], function($message) use ($comerc,$file){
          $message->to($comerc->email,$comerc->nome)->subject(trans('Fatura da encomenda disponível'));
          $message->attach($file);
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    }

    $novo_comprovativo='';
    if(count($comprovativo)){
      $antigoNome = $enc->comprovativo;

      if(file_exists(base_path('public_html/doc/orders/'.$antigoNome))){ \File::delete('../public_html/doc/orders/'.$antigoNome); }
      $cache = str_random(3);
      $destinationPath = base_path('public_html/doc/orders/');
      $extension = strtolower($comprovativo->getClientOriginalExtension());
      $getName = $comprovativo->getPathName();
      $novo_comprovativo = 'order_'.$id_encomenda.'_comprovativo_'.$cache.'.'.$extension;
      
      move_uploaded_file($getName, $destinationPath.$novo_comprovativo);
      
      \DB::table('encomenda')
          ->where('id',$id_encomenda)
          ->update([ 
            'comprovativo'=>$novo_comprovativo,
            'data_comprovativo' => \Carbon\Carbon::now()->timestamp
          ]);
    }

    if ($estado != $enc->estado) {
      \DB::table('encomenda_armazem')
          ->where('id_encomenda',$id_encomenda)
          ->update(['estado' => $estado]);
      
      \DB::table('encomenda')
        ->where('id',$id_encomenda)
        ->update(['estado' => $estado]);

      if ($estado == 'em_processamento') {
        $tipo = 'enc_processamento';
        $estado_html=trans('backoffice.In_processing');
      }
      elseif($estado == 'expedida'){
        $tipo = 'enc_expedida';
        $estado_html=trans('backoffice.Dispatched');
      }
      elseif($estado == 'expedida_parcialmente'){
        $tipo = 'enc_parcial_exp';
        $estado_html=trans('backoffice.partially_dispatched');
      }
      elseif($estado == 'fatura_vencida'){
        $tipo = 'enc_fat_vencida';
        $estado_html=trans('backoffice.fatura_vencida');
      }
      elseif($estado == 'concluida'){
        $tipo = 'enc_concluida';
        $estado_html=trans('backoffice.Completed');
      }
      else{
        $tipo = 'enc_iniciada';
        $estado_html=trans('backoffice.Registered');
      }


      if (isset($tipo) && isset($seller)) {
        \DB::table('notificacoes')
            ->insert([
              'id_notificado' => $enc->id_comerciante,
              'id_empresa' => $enc->id_empresa,
              'id_comerciante' => $enc->id_comerciante,
              'id_encomenda' => $id_encomenda,
              'tipo' => $tipo,
              'url' => '\orders',
              'data' => \Carbon\Carbon::now()->timestamp
            ]);
      
        $dados_enc = [ 'id'=>$id_encomenda,'armazem'=>'','estado'=>$estado_html ];

        \Mail::send('backoffice.emails.pages.send-status',['dados' => $dados_enc], function($message) use ($seller){
          $message->to($seller->email,$seller->nome)->subject(trans('Estado da encomenda.'));
          $message->from(config('mailAccounts.geral')['mail'],config('mailAccounts.geral')['nome']);
        });
      }
    }

  

    $relaod = 'sim';

    //Mudar o valor do cookie das notificações
    $not = \DB::table('notificacoes')->where('id_notificado',$enc->id_comerciante)->where('vista','0')->count();
    if(Cookie::get('cookie_comerc_id') == $enc->id_comerciante){
      Cookie::queue('cookie_not_ative', $not);
    }

    //Update valor dos pontos na da empresa dependendo do comprovativo e do estado
    
    if (($estado == 'concluida') && ($enc->comprovativo != '')) {
      $pontos_enc = \DB::table('encomenda_pontos')->where('id_encomenda',$id_encomenda)->first();
      $empresa = \DB::table('empresas')->where('id',$enc->id_empresa)->first();

      $total = $empresa->pontos + $pontos_enc->pontos;

      \DB::table('empresas')->where('id',$empresa->id)->update([ 'pontos' => $total ]);
      \DB::table('encomenda_pontos')
          ->where('id_encomenda',$id_encomenda)
          ->update([ 
            'estado' => 'disponivel' 
          ]);
    }
    
    $resposta = [
      'estado' => 'sucesso',
      'mensagem' => '',
      'reload' => $relaod 
    ];
    return json_encode($resposta,true);
  }


  public function deleteOrder(Request $request){
    $id = trim($request->id);

    \DB::table('encomenda')->where('id',$id)->delete();
    return 'sucesso';
  }
 
}