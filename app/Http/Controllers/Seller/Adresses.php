<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Adresses extends Controller{

  private $dados = [];

  public function adressOffice(){

    $this->dados['headTitulo'] = trans('seller.t_adress_office');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Endereco_sede';

    $lang = 'pt';

    $this->dados['paises'] = \DB::table('pais')
                              ->select('id','nome_'.$lang.' AS nome')
                              ->orderBy('nome_'.$lang,'ASC')
                              ->get();

    $this->dados['morada_sede'] = \DB::table('moradas')
                                    ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
                                    ->where('tipo','morada_sede')
                                    ->first();


    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();
    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
      
    if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/adressesOffice',$this->dados); }
    else{ return redirect()->route('personalDataV2'); }
  }

  public function adressCont(){

    $this->dados['headTitulo'] = trans('seller.t_adress_cont');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Endereco_cont';

    $lang = 'pt';

    $this->dados['paises'] = \DB::table('pais')
                                  ->select('id','nome_'.$lang.' AS nome')
                                  ->orderBy('nome_'.$lang,'ASC')
                                  ->get();

    $this->dados['morada_cont'] = \DB::table('moradas')
                                    ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
                                    ->where('tipo','morada_contabilidade')
                                    ->first();

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

    if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/adressesCont',$this->dados); }
    else{ return redirect()->route('personalDataV2'); }
  }

  public function addressPurchase(){

    $this->dados['headTitulo'] = trans('seller.t_adress_purchase');
    $this->dados['headDescricao'] = 'Universal';
    $this->dados['separador'] = 'Endereco_purchase';

    $lang = 'pt';

    $this->dados['paises'] = \DB::table('pais')
                              ->select('id','nome_'.$lang.' AS nome')
                              ->orderBy('nome_'.$lang,'ASC')
                              ->get();

    $morada_armazem = \DB::table('moradas')
                          ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
                          ->where('tipo','morada_armazem')
                          ->get();

    $encomenda = \DB::table('encomenda_armazem')
                    ->where('estado','<>','concluida')
                    ->get();
    $array_enc = [];                
    foreach ($encomenda as $val) {
      $array_enc[] = [
        'morada' => $val->id_morada
      ]; 
    }


    $array_moradas = [];
    $delete = 0;
    foreach ($morada_armazem as $value) {
      $delete = (in_array($value->id, array_column($array_enc, 'morada'))) ? 1 : 0;   

      $array_moradas[] = [
        'id' => $value->id,
        'id_empresa' => $value->id_empresa,
        'nome_personalizado' => $value->nome_personalizado,
        'morada' => $value->morada,
        'morada_opc' => $value->morada_opc,
        'codigo_postal' => $value->codigo_postal,
        'cidade' => $value->cidade,
        'pais' => $value->pais,
        'telefone' => $value->telefone,
        'fax' => $value->fax,
        'nome_gerente' => $value->nome_gerente,
        'telefone_gerente' => $value->telefone_gerente,
        'email_gerente' => $value->email_gerente,
        'cargo_gerente' => $value->cargo_gerente,
        'tipo' => $value->tipo,
        'estado' => $value->estado,
        'delete' => $delete
      ];
    }

  
    $this->dados['morada_armazem'] = $array_moradas;
                                    

    $this->dados['configuracoes'] = \DB::table('empresas_config')
                                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                       ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                       ->get();

    $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
  
    if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/addressPurchase',$this->dados); }
    else{ return redirect()->route('personalDataV2'); }
  }

  public function addAdressOffice(Request $request){

    /*MORADA*/
    $id_morada = trim($request->id_morada);
    $morada = trim($request->morada);
    $morada_opc = trim($request->morada_opc);
    $codigo_postal = trim($request->codigo_postal);
    $cidade = trim($request->cidade);
    $pais = trim($request->pais);
    $telefone = trim($request->contacto_empresa);
    $fax = trim($request->fax_empresa);
    $tipo = trim($request->tipo);

    /*MORADA ARMAZEM*/
    $nome_personalizado = trim($request->nome_personalizado);
    $estado_armazem = trim($request->estado_armazem);

    //return $id_morada;

    
    if($id_morada) {

      $dados_morada = \DB::table('moradas')->where('id',$id_morada)->first();
      $nome_gerente = $dados_morada->nome_gerente;
      $email_gerente = $dados_morada->email_gerente;
      $telefone_gerente = $dados_morada->telefone_gerente;
      $cargo_gerente = $dados_morada->cargo_gerente;
    }
    else{
      $nome_gerente = '';
      $email_gerente = '';
      $telefone_gerente = '';
      $cargo_gerente = '';
    }

    if ($tipo == 'morada_armazem') {

      /*PESSOA DE CONTACTO MORADA ARMAZEM*/
      $nome_gerente = trim($request->nome_gerente);
      $email_gerente = filter_var(trim($request->email_gerente), FILTER_VALIDATE_EMAIL) ? filter_var(trim($request->email_gerente), FILTER_VALIDATE_EMAIL) : '';
      $cargo_gerente = trim($request->cargo_gerente); 
      $telefone_gerente = trim($request->telefone_gerente);

      if(empty($nome_personalizado)){ return trans('seller.Field_name_show_txt');}
      if(empty($morada)){return trans('seller.Field_adress_txt');}
      if(empty($codigo_postal)){return trans('seller.Field_code_txt');}
      if(empty($cidade)){return trans('seller.Field_city_txt');}
      if(empty($pais)){return trans('seller.Field_country_txt');}
      if(empty($telefone)) {return trans('seller.Format_empty_Adress');}
      if(empty($nome_gerente)){ return trans('seller.Field_name_contact_person_txt');}
      if(empty($cargo_gerente)){ return trans('seller.Field_office_contact_person_txt');}
      if(empty($email_gerente)){ return trans('seller.Field_email_contact_person_txt');}
      if(empty($telefone_gerente)){ return trans('seller.Format_Invalid_Contact_Person');}

    }
        
    $destino='';
    if ($tipo == 'morada_sede') { 
      $tipo_morada = 'morada_sede'; 
      $estado_armazem = 'em_aprovacao'; 
    }
    elseif($tipo == 'morada_contabilidade'){ 
      $tipo_morada = 'morada_contabilidade'; 
      $estado_armazem = 'em_aprovacao'; 
    }
    else{
      $tipo_morada = 'morada_armazem'; 
    }

    if($id_morada){
      
      \DB::table('moradas')
          ->where('id',$id_morada)
          ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
          ->update([
              'nome_personalizado' => $nome_personalizado,
              'morada' => $morada,
              'morada_opc' => $morada_opc,
              'codigo_postal' => $codigo_postal,
              'cidade' => $cidade,
              'pais' => $pais,
              'telefone' => $telefone,
              'fax' => $fax,
              'nome_gerente' => $nome_gerente,
              'email_gerente' => $email_gerente,
              'telefone_gerente' => $telefone_gerente,
              'cargo_gerente' => $cargo_gerente,
              'estado' => $estado_armazem
            ]);

      $destino=$id_morada;
    }else{
      
      $id_morada = \DB::table('moradas')
                      ->insertGetId([ 
                          'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                          'nome_personalizado' => $nome_personalizado,
                          'morada' => $morada,
                          'morada_opc' => $morada_opc,
                          'codigo_postal' => $codigo_postal,
                          'cidade'=>$cidade,
                          'pais' => $pais,
                          'telefone' => $telefone,
                          'fax' => $fax,
                          'nome_gerente' => $nome_gerente,
                          'email_gerente' => $email_gerente,
                          'telefone_gerente' => $telefone_gerente,
                          'cargo_gerente' => $cargo_gerente,
                          'tipo' => $tipo_morada,
                          'estado' => $estado_armazem
                      ]);
    }

    $morada_html = '';
    $fax_value = $fax;
    $telefone_value = $telefone;
    $morada_value = $morada;
    $cidade_value = $cidade;
    if (empty($morada)) {$morada = '-'; $morada_value = '';}
    if (!empty($morada_opc)) {$morada_html =  '<span>'.$morada_opc.'</span><br>';}
    if (empty($cidade)) {$cidade = '-'; $cidade_value = '';}
    if (empty($pais)) {$pais = '-';}
    if (empty($telefone)) {$telefone = '-'; $telefone_value = '';}
    if (empty($fax)) {$fax = '-'; $fax_value = '';}


    $encomenda = \DB::table('encomenda_armazem')
                    ->where('estado','<>','concluida')
                    ->get();

    $delete = 0;
    foreach ($encomenda as $val) {
      if($val->id_morada == $id_morada){
        $delete = 1;
      }  
    }

    $html_delete = '';
    if ($delete == 0) {
      $html_delete = '<i class="fas fa-times table-icon-delete" onclick="$(\'#id_modal\').val('.$id_morada.');" data-toggle="modal" data-target="#myModalDelete"></i>';
    }


    if(($tipo_morada == 'morada_contabilidade') || ($tipo_morada == 'morada_sede')){
     
      $resposta = [ 
        'estado' => 'sucesso',
        'id_morada' => $id_morada,
        'conteudo' => '
          <input id="'.$id_morada.'_id_morada" class="'.$id_morada.'_update" type="hidden" name="" value="'.$id_morada.'">
          <input id="'.$id_morada.'_morada" class="'.$id_morada.'_update" type="hidden" name="" value="'.$morada_value.'">
          <input id="'.$id_morada.'_morada_opc" class="'.$id_morada.'_update" type="hidden" name="" value="'.$morada_opc.'">
          <input id="'.$id_morada.'_codigo_postal" class="'.$id_morada.'_update" type="hidden" name="" value="'.$codigo_postal.'">
          <input id="'.$id_morada.'_cidade" class="'.$id_morada.'_update" type="hidden" name="" value="'.$cidade_value.'">
          <input id="'.$id_morada.'_pais" class="'.$id_morada.'_update up_select" type="hidden" name="" value="'.$pais.'">
          <input id="'.$id_morada.'_contacto_empresa" class="'.$id_morada.'_update" type="hidden" name="" value="'.$telefone_value.'">
          <input id="'.$id_morada.'_fax" class="'.$id_morada.'_update" type="hidden" name="" value="'.$fax_value.'">',
        'conteudo_adress' => '
          <span class="tx-bold">'.trans('seller.adress').':</span><span>'.$morada.'</span><br>
          '.$morada_html.'
          <span class="tx-bold">'.trans('seller.postal_code').':</span><span>'.$codigo_postal.'</span><br>
          <span class="tx-bold">'.trans('seller.city').':</span><span>'.$cidade.'</span><br>
          <span class="tx-bold">'.trans('seller.country').':</span><span>'.$pais.'</span><br>
          <span class="tx-bold">'.trans('seller.telephone').':</span><span>'.$telefone.'</span><br>
          <span class="tx-bold">'.trans('seller.fax').':</span><span>'.$fax.'</span>
        '
      ];
      return json_encode($resposta,true);
    }
    else{
      
      if ($estado_armazem == 'ativo') {
        $estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active').'<br> 
          <span class="tx-navy cursor-pointer" onclick="changeStatus('.$id_morada.',\''.$estado_armazem.'\');">
          <i class="fas fa-eye-slash"></i>'.trans('seller.Deactivate').'</span>';
      }
      else{
        $estado_html = '<i class="fas fa-circle dashboard-invoice-spent"></i>'.trans('seller.inactive').' <br> 
          <span class="tx-navy cursor-pointer" onclick="changeStatus('.$id_morada.',\''.$estado_armazem.'\');">
          <i class="fas fa-eye"></i>'.trans('seller.Enable').'</span>';
      }



      $resposta = [ 
        'estado' => 'sucesso',
        'id_morada' => $id_morada,
        'conteudo' => '
          <div id="morada_'.$id_morada.'" >
            <input id="'.$id_morada.'_id_morada" class="'.$id_morada.'_update" type="hidden" name="" value="'.$id_morada.'">
            <input id="'.$id_morada.'_nome_personalizado" class="'.$id_morada.'_update" type="hidden" name="" value="'.$nome_personalizado.'">
            <input id="'.$id_morada.'_estado" class="'.$id_morada.'_update up_select" type="hidden" name="" value="'.$estado_armazem.'">
            <input id="'.$id_morada.'_morada" class="'.$id_morada.'_update" type="hidden" name="" value="'.$morada_value.'">
            <input id="'.$id_morada.'_morada_opc" class="'.$id_morada.'_update" type="hidden" name="" value="'.$morada_opc.'">
            <input id="'.$id_morada.'_codigo_postal" class="'.$id_morada.'_update" type="hidden" name="" value="'.$codigo_postal.'">
            <input id="'.$id_morada.'_cidade" class="'.$id_morada.'_update" type="hidden" name="" value="'.$cidade_value.'">
            <input id="'.$id_morada.'_pais" class="'.$id_morada.'_update up_select" type="hidden" name="" value="'.$pais.'">
            <input id="'.$id_morada.'_contacto_empresa" class="'.$id_morada.'_update" type="hidden" name="" value="'.$telefone_value.'">
            <input id="'.$id_morada.'_fax_empresa" class="'.$id_morada.'_update" type="hidden" name="" value="'.$fax_value.'">
            <input id="'.$id_morada.'_nome_gerente" class="'.$id_morada.'_update" type="hidden" name="" value="'.$nome_gerente.'">
            <input id="'.$id_morada.'_cargo_gerente" class="'.$id_morada.'_update up_select" type="hidden" name="" value="'.$cargo_gerente.'">
            <input id="'.$id_morada.'_email_gerente" class="'.$id_morada.'_update" type="hidden" name="" value="'.$email_gerente.'">
            <input id="'.$id_morada.'_telefone_gerente" class="'.$id_morada.'_update" type="hidden" name="" value="'.$telefone_gerente.'">
          </div>
        ',
        'conteudo_add' => '
          <tr id="tr_'.$id_morada.'">
            <td>'.$html_delete.''.$nome_personalizado.'</td>
            <td><span>'.$morada.'</span><br>'.$morada_html.' <span>'.$codigo_postal.' '.$cidade.'</span><br><span>'.$pais.'</span></td>
            <td>
              <span class="tx-bold">'.trans('seller.telephone').':</span> '.$telefone.'<br> 
              <span class="tx-bold">'.trans('seller.fax').':</span> '.$fax.'
            </td>
            <td>
              <span>'.$nome_gerente.'</span><br>
              <span>'.$cargo_gerente.'</span><br>
              <span class="tx-bold">'.trans('seller.email').'</span><span> '.$email_gerente.'</span><br>
              <span class="tx-bold">'.trans('seller.telephone').':</span><span> '.$telefone_gerente.'</span>
            </td>
            <td id="estado_'.$id_morada.'">'.$estado_html.'</td>
            <td class="dashboard-table-details cursor-pointer" onclick="edit('.$id_morada.');"><i class="fas fa-pencil-alt"></i>'.trans('seller.Edit').'</td>
          </tr>
        ',
        'conteudo_edit' => '
          <td>'.$html_delete.''.$nome_personalizado.'</td>
          <td><span>'.$morada.'</span><br> '.$morada_html.' <span>'.$codigo_postal.' '.$cidade.'</span><br><span>'.$pais.'</span></td>
          <td>
            <span class="tx-bold">'.trans('seller.telephone').':</span> '.$telefone.'<br> 
            <span class="tx-bold">'.trans('seller.fax').':</span> '.$fax.'
          </td>
          <td>
            <span>'.$nome_gerente.'</span><br>
            <span>'.$cargo_gerente.'</span><br>
            <span class="tx-bold">'.trans('seller.email').'</span><span> '.$email_gerente.'</span><br>
            <span class="tx-bold">'.trans('seller.telephone').':</span><span> '.$telefone_gerente.'</span>
          </td>
          <td id="estado_'.$id_morada.'">'.$estado_html.'</td>
          <td class="dashboard-table-details cursor-pointer" onclick="edit('.$id_morada.');"><i class="fas fa-pencil-alt"></i>'.trans('seller.Edit').'</td>
        '
      ];
      return json_encode($resposta,true);
    }  
  }
  public function addRespOffice(Request $request){
    /*MORADA*/
    $id_morada = trim($request->id_morada);
    $tipo = trim($request->tipo);


    /*PESSOA DE CONTACTO*/
    $nome_gerente = trim($request->nome_gerente);
    $email_gerente = filter_var(trim($request->email_gerente), FILTER_VALIDATE_EMAIL) ? filter_var(trim($request->email_gerente), FILTER_VALIDATE_EMAIL) : '';
    $cargo_gerente = trim($request->cargo_gerente); 
    $telefone_gerente = trim($request->telefone_gerente);

   

    $destino='';
    if ($tipo == 'morada_sede') { 
      $tipo_morada = 'morada_sede'; 
      $estado_armazem = 'em_aprovacao'; 
    }
    elseif($tipo == 'morada_contabilidade'){ 
      $tipo_morada = 'morada_contabilidade'; 
      $estado_armazem = 'em_aprovacao'; 
    }
    else{
      $tipo_morada = 'morada_armazem'; 

      if(empty($nome_gerente)){ return trans('seller.Field_name_contact_person_txt');}
      if(empty($cargo_gerente)){ return trans('seller.Field_office_contact_person_txt');}
      if(empty($email_gerente)){ return trans('seller.Field_email_contact_person_txt');}
      if(empty($telefone_gerente)){ return trans('seller.Format_Invalid_Contact_Person');}
    }

    if($id_morada){
      
      \DB::table('moradas')
          ->where('id',$id_morada)
          ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
          ->update([
              'nome_gerente' => $nome_gerente,
              'email_gerente' => $email_gerente,
              'telefone_gerente' => $telefone_gerente,
              'cargo_gerente' => $cargo_gerente,
              'estado' => $estado_armazem
            ]);

      $destino=$id_morada;
    }else{

      $id_morada = \DB::table('moradas')
                      ->insertGetId([ 
                          'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                          'nome_gerente' => $nome_gerente,
                          'email_gerente' => $email_gerente,
                          'telefone_gerente' => $telefone_gerente,
                          'cargo_gerente' => $cargo_gerente,
                          'tipo' => $tipo_morada,
                          'estado' => $estado_armazem
                      ]);
    }
    
    $nome_value = $nome_gerente;
    $email_value = $email_gerente;
    $cargo_value = $cargo_gerente;
    $telefone_value = $telefone_gerente;

    if (empty($nome_gerente)) {$nome_gerente = '-'; $nome_value = '';}
    if (empty($email_gerente)) {$email_gerente = '-'; $email_value = '';}
    if (empty($cargo_gerente)) {$cargo_gerente = '-'; $cargo_value = '';}
    if (empty($telefone_gerente)) {$telefone_gerente = '-'; $telefone_value = '';}

    

    $resposta = [ 
      'estado' => 'sucesso',
      'id_morada' => $id_morada,
      'conteudo' => '
        <input id="'.$id_morada.'_id_morada" class="'.$id_morada.'_update" type="hidden" name="" value="'.$id_morada.'">
        <input id="'.$id_morada.'_nome_gerente" class="'.$id_morada.'_update" type="hidden" name="" value="'.$nome_value.'">
        <input id="'.$id_morada.'_cargo_gerente" class="'.$id_morada.'_update up_select" type="hidden" name="" value="'.$cargo_value.'">
        <input id="'.$id_morada.'_email_gerente" class="'.$id_morada.'_update" type="hidden" name="" value="'.$email_value.'">
        <input id="'.$id_morada.'_telefone_gerente" class="'.$id_morada.'_update" type="hidden" name="" value="'.$telefone_value.'">',
      'conteudo_resp' => '
        <span class="tx-bold">'.trans('seller.name').':</span><span>'.$nome_gerente.'</span><br>
        <span class="tx-bold">'.trans('seller.office').':</span><span>'.$cargo_gerente.'</span><br>
        <span class="tx-bold">'.trans('seller.e-mail').':</span><span>'.$email_gerente.'</span><br>
        <span class="tx-bold">'.trans('seller.telephone').':</span><span>'.$telefone_gerente.'</span>
      '

    ];
    return json_encode($resposta,true);

  }

  public function changeStatus(Request $request){

    $id_morada = $request->id;
    $estado = $request->estado;
    
    if($estado == 'ativo'){
      $new_status = 'inativo';
      $estado_html = '<i class="fas fa-circle dashboard-invoice-spent"></i>'.trans('seller.inactive').' <br> <span class="tx-navy cursor-pointer" onclick="changeStatus('.$id_morada.',\'inativo\');"><i class="fas fa-eye"></i>'.trans('seller.Enable').'</span>';

    }else{
      $new_status = 'ativo';
      $estado_html = '<i class="fas fa-circle dashboard-invoice-completed"></i>'.trans('seller.active').'<br> <span class="tx-navy cursor-pointer" onclick="changeStatus('.$id_morada.',\'ativo\');"><i class="fas fa-eye-slash"></i>'.trans('seller.Deactivate').'</span>';

    }

    \DB::table('moradas')->where('id',$id_morada)
        ->update([
            'estado' => $new_status
          ]);


    $resposta = [ 
      'estado' => 'sucesso',
      'id_morada' => $id_morada,
      'conteudo' => $new_status,
      'conteudo_tr' => $estado_html
      
    ];
    return json_encode($resposta,true);
  }

  public function deleteAdress(Request $request){

    $id_morada = $request->id;
    $count_m = \DB::table('moradas')->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))->where('tipo','morada_armazem')->count();
    \DB::table('moradas')->where('id',$id_morada)->delete();

    $resposta = [ 
      'estado' => 'sucesso',
      'id_morada' => $id_morada,
      'count_m' => $count_m
    ];
    return json_encode($resposta,true);

  }       
}