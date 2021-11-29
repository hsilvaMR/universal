@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3">
          @include('seller/includes/menu')
      </div>
      <div class="col-lg-9">
        <div class="mod-tit">
          <h3>{{ trans('seller.New_Order') }}</h3>
        </div>
        <div class="mod-area">
          <div class="modulo-table">
            <div class="modulo-scroll" style="width:100%;">
              @php
                $valor_fatura = 0;
                $qtd_fatura = 0;
                $valor_iva = 0;
                $valor_com_iva = 0;
              @endphp

              <table class="modulo-body" id="table_orders">
                @foreach($morada_armazem as $val)
                <thead>
                  <tr>
                    <th style="width:400px;"><span class="tx-bold">{{ $val->nome_personalizado }}</span></th>
                    <th class="tx-right">{{ trans('seller.Unity') }}</th>
                    <th class="tx-right">{{ trans('seller.Price') }}</th>
                    <th class="tx-right">{{ trans('seller.Amount') }}</th>
                    <th class="tx-right orders-new-width">{{ trans('seller.Value') }}</th>
                  </tr>
                </thead>
                <tbody> 
                  <input id="id_morada" type="hidden" name="id_morada" value="{{ $val->id }}">
                  <input id="id_encomenda" type="hidden" name="id_morada" @if($encomenda) value="{{ $encomenda->id }}" @endif>

                  @php 
                    $valorT_line = number_format((float)0, 2, '.', '');
                    $qtd_line = 0;
                  @endphp

                  @if(isset($encomenda_linha))
                    @foreach($encomenda_linha as $enc)
                      <!--<input type="hidden" id="id_encomenda" value="{ { $enc['id_encomenda'] }}">-->
                      @if($val->id == $enc['id_morada'])
                      @php 
                        $valor = number_format((float)$enc['qtd_caixa'] * $enc['valor_empresa'], 2, '.', '');
                        $valor_prod = number_format((float)$enc['quantidade'] * $valor, 2, '.', '');

                        $qtd_line = $qtd_line + $enc['quantidade'];
                        $valorT_line = number_format((float)$valorT_line + $valor_prod, 2, '.', '');
                      @endphp

                      <tr id="product_{{ $enc['id_line'] }}" class="cleanDatos">
                        <td>
                          <i id="removeProduct_{{ $enc['id_line'] }}" class="fas fa-times orders-new-delete" onclick="deleteLine({{ $enc['id_line'] }},{{ $encomenda->id }},{{ $val->id }},{{ $enc['quantidade'] }},{{ $valor_prod }})"></i>
                          
                          <div class="div-50 float-left">
                            @foreach($produtos as $value)
                              @if($enc['id_produto'] == $value['id_produto']) 
                                <input id="select_{{ $enc['id_line'] }}" type="hidden" value="{{ $value['id_produto'] }}">                                   
                                <label class="orders-new-label" name="fatura_opc">{{ $value['nome'] }}</label>
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="tx-right" id="united_{{ $enc['id_line'] }}">
                          <span>1 {{ trans('seller.Box') }}</span><br>
                          <span>{{ $enc['qtd_caixa']}} {{ trans('seller.articles') }}</span>
                        </td>
                        <td class="tx-right" id="price_{{ $enc['id_line'] }}">
                          <span>{{ $valor }} €</span><br>
                          <span>{{ $enc['valor_empresa'] }} €/{{ trans('seller.articles') }}</span>
                        </td>
                        <td>
                          <div class="cart-div-input">
                            <div class="orders_qtd_decrease float-left">
                              <span class="orders_qtd_bt" id="decrease_{{ $val->id }}" onclick="decreaseValue({{ $enc['id_line'] }},{{ $valor }},{{ $val->id }})" value="Decrease Value">
                                <i class="fas fa-minus tx-gray"></i>
                              </span>
                            </div>
                            <input class="orders_input" type="number" id="qtd_{{ $enc['id_line'] }}" value="{{ $enc['quantidade'] }}" onchange="updateProduct({{ $enc['id_line'] }},{{ $valor }},{{ $val->id }},'manual');">
                            <input type="hidden" id="qtd_anterior{{ $enc['id_line'] }}" value="{{ $enc['quantidade'] }}">
                            <input type="hidden" id="quantidade_{{ $enc['id_line'] }}" value="{{ $enc['quantidade'] }}">
                            <div class="orders_qtd_decrease float-right">
                              <span class="orders_qtd_bt" id="increase_{{ $val->id }}" onclick="increaseValue({{ $enc['id_line'] }},{{ $valor }},{{ $val->id }})" value="Increase Value">
                                <i class="fas fa-plus tx-gray"></i>
                              </span>
                            </div>
                          </div>
                        </td>
                        <td class="tx-right" id="value_prod_{{ $enc['id_line'] }}">{{ $valor_prod }} €</td>
                      </tr>
                      @endif
                    @endforeach
                  @endif

                  <tbody id="tbody_add_{{ $val->id }}">
                  </tbody>

                  @php 
                    $valor_fatura = number_format((float)$valor_fatura + $valorT_line, 2, '.', '');
                    $qtd_fatura = $qtd_fatura + $qtd_line; 
                    $valor_iva = number_format((float)$valor_fatura * 0.06, 2, '.', ''); 
                    $valor_com_iva = number_format((float)$valor_fatura + $valor_iva, 2, '.', '');
                  @endphp

                  <tr>
                    <input type="hidden" id="linha_qtd_{{ $val->id }}" class="cleanDatosInput" value="{{ $qtd_line }}">
                    <input type="hidden" id="linha_valor_{{ $val->id }}" class="cleanDatosInput" value="{{ $valorT_line }}">

                    <td>
                      <button id="addProduct_{{ $val->id }}" class="bg-navy orders_bt_add"><i class="fas fa-plus"></i></button>
                      <label class="label_select div-50 margin-bottom0">
                        <select id="select{{ $val->id }}" class="orders_select" name="fatura_opc" onclick="showProductNew({{ $val->id }});">
                          <option value="0" selected>{{ trans('seller.Product_Add') }}</option>
                            @if(isset($prod_line))
                              @foreach($prod_line as $value)
                                @if($val->id == $value['morada'])
                                  <option id="option_{{ $value['id'] }}_{{ $val->id }}" value="{{ $value['id'] }}" @if($value['valor'] == 1) disabled @endif>{{ $value['nome'] }}</option>
                                @endif
                              @endforeach
                            @else
                              @foreach($produtos as $prod)
                                <option id="option_{{ $prod['id_produto'] }}_{{ $val->id }}" value="{{ $prod['id_produto'] }}">{{ $prod['nome'] }}</option>
                              @endforeach
                            @endif
                        </select>
                      </label>
                    </td>
                    <td class="tx-right" id="united_{{ $val->id }}">
                      -
                    </td>
                    <td class="tx-right" id="price_{{ $val->id }}">
                      -
                    </td>
                    <td class="tx-right">
                      <div class="cart-div-input">
                        <div class="orders_qtd_decrease float-left"><span class="orders_qtd_bt"><i class="fas fa-minus tx-gray"></i></span></div>
                        <input class="orders_input" type="number" id="quantidade" value="1" disabled>
                        <div class="orders_qtd_decrease float-right"><span class="orders_qtd_bt"><i class="fas fa-plus tx-gray"></i></span></div>
                      </div>
                      
                    </td>
                    <td class="tx-right" id="value_prod_{{ $val->id }}">-</td>
                  </tr>

                  <tr>
                    <td>{{ trans('seller.Total_partial') }}</td>
                    <td></td>
                    <td></td>
                    <td id="qtd_parcial_{{ $val->id }}" class="cleanDatosUni tx-right tx-bold">{{ $qtd_line }} {{ trans('seller.units') }}</td>
                    <td><span id="valor_parcial_{{ $val->id }}" class="cleanDatosValue tx-bold float-right"> {{ $valorT_line }} € </span></td>
                  </tr>

                  <!--<tr>
                    <td>{{ trans('seller.Carrying') }}</td>
                    <td></td>
                    <td></td>
                    <td id="qtd_transportar_{{ $val->id }}" class="cleanDatosUni tx-right tx-bold">{{ $qtd_line }} {{ trans('seller.units') }}</td>
                    <td><span id="valor_transportar_{{ $val->id }}" class="cleanDatosValue tx-bold float-right"> {{ $valorT_line }} € </span></td>
                  </tr>-->
                  @endforeach

                  <input type="hidden" id="valor_fatura" value="{{ $valor_fatura }}">
                  <input type="hidden" id="qtd_fatura" value="{{ $qtd_fatura }}">
                  <input type="hidden" id="iva" value="{{ $valor_iva }}">
                  <input type="hidden" id="valor_com_iva" value="{{ $valor_com_iva }}">

                  <tr class="orders-line-top">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="tx-right tx-bold">
                      <i class="fas fa-info-circle orders-details-info" data-toggle="modal" data-target="#modalDelete"></i>
                      <span id="qtd_fat">{{ $qtd_fatura }}</span> <span>{{ trans('seller.units') }}</span>
                    </td>
                    <td class="orders-new-width">
                      <span class="orders-total">{{ trans('seller.Amount_Total') }}</span>
                      <span id="valor_fat" class="tx-bold float-right"> {{ $valor_fatura }} €</span>
                    </td>
                  </tr>

                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                      <span class="orders-total">{{ trans('seller.IVA') }} (6%)</span>
                      <span id="iva_fat" class="tx-bold float-right"> {{ $valor_iva }} €</span>
                    </td>
                  </tr>

                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                      <span class="orders-total">{{ trans('seller.Total') }}</span>
                      <span id="valor_fat_iva" class="tx-bold float-right"> {{ $valor_com_iva }} €</span>
                    </td>
                  </tr>

                </tbody>

              </table>
              

              <!--<table class="modulo-body" id="table_orders">

                <input type="hidden" id="valor_fatura" value="{{ $valor_fatura }}">
                <input type="hidden" id="qtd_fatura" value="{{ $qtd_fatura }}">
                <input type="hidden" id="iva" value="{{ $valor_iva }}">
                <input type="hidden" id="valor_com_iva" value="{{ $valor_com_iva }}">

                <tbody>
                  <tr class="orders-line-top">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="tx-right tx-bold">
                      <i class="fas fa-info-circle orders-details-info" data-toggle="modal" data-target="#modalDelete"></i>
                      <span id="qtd_fat">{{ $qtd_fatura }}</span> <span>{{ trans('seller.units') }}</span>
                    </td>
                    <td class="orders-new-width">
                      <span class="margin-left40">{{ trans('seller.Amount_Total') }}</span>
                      <span id="valor_fat" class="tx-bold float-right"> {{ $valor_fatura }} €</span>
                    </td>
                  </tr>

                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                      <span class="margin-left40">{{ trans('seller.IVA') }} (6%)</span>
                      <span id="iva_fat" class="tx-bold float-right"> {{ $valor_iva }} €</span>
                    </td>
                  </tr>

                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                      <span class="margin-left40">{{ trans('seller.Total') }}</span>
                      <span id="valor_fat_iva" class="tx-bold float-right"> {{ $valor_com_iva }} €</span>
                    </td>
                  </tr>
                </tbody>
              </table>-->


            </div>
          </div>
          

          <div class="orders-button-lg">
            <button class="bt-transparent margin-right10 tx-gray" onclick="cleanDatos('clean');">
              <i class="far fa-trash-alt"></i> {{ trans('seller.Data_Clean') }}
            </button>

            <button class="bt-transparent margin-right10 tx-gray" onclick="cleanDatos('cancel');">
              <i class="fas fa-times"></i> {{ trans('seller.Cancel') }}
            </button>
            <a id="review" @if($encomenda) href="{{ route('ordersSummaryV2') }}" @endif>
              <button class="bt-blue">{{ trans('seller.Review_Orders') }} <i class="fas fa-angle-right"></i></button>
            </a>
          </div>

          <div class="orders-button-xs">
            <a id="review" @if($encomenda) href="{{ route('ordersSummaryV2') }}" @endif>
              <button class="bt-blue margin-bottom10">{{ trans('seller.Review_Orders') }} <i class="fas fa-angle-right"></i></button>
            </a>
            <br>
            <button class="bt-transparent tx-gray" onclick="cleanDatos('cancel');">
              <i class="fas fa-times"></i> {{ trans('seller.Cancel') }}
            </button>
            <br>
            <button class="bt-transparent tx-gray" onclick="cleanDatos('clean');">
              <i class="far fa-trash-alt"></i> {{ trans('seller.Data_Clean') }}
            </button>
          </div>
          
        </div>  

        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="margin-bottom0">{{ trans('seller.Total_Units') }}</h3>
                <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body mod-area">
                <table class="modulo-body" id="sortable">
                  <thead>
                    <tr>
                      <th class="tx-left">{{ trans('seller.Article') }}</th>
                      <th class="tx-right">{{ trans('seller.Amount') }}</th>
                    </tr>
                  </thead>
                  <tbody id="new_info">
                    @foreach($qtd_prod_final as $enc)
                      <tr id="info_{{ $enc['id'] }}" class="tx-left">
                        <td>{{ $enc['nome'] }}</td>
                        <td class="dashboard-col-add-2">{{ $enc['quantidade'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>

                <div class="tx-center margin-top30">
                  <button class="bt-blue" data-dismiss="modal"><i class="fas fa-times margin-right5"></i>{{ trans('seller.Close') }}</button>
                </div>     
              </div> 
            </div>
          </div>
        </div>
      </div>
    </div>                  
  </div>
@stop

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>
  function addProduct(id_morada){
    var qtd = $('#quantidade').val();
    var id_product = $('#select'+id_morada).val();
    var id_encomenda = $('#id_encomenda').val();
    var linha_qtd = $('#linha_qtd_'+id_morada).val();
    var linha_valor = $('#linha_valor_'+id_morada).val();
    var valor_fatura = $('#valor_fatura').val();
    var qtd_fatura = $('#qtd_fatura').val();

    $.ajax({
      type: "POST",
      url: '{{ route('addLineProductPost') }}',
      data: {id_morada:id_morada,qtd:qtd,id_product:id_product,id_encomenda:id_encomenda,linha_qtd:linha_qtd,linha_valor:linha_valor,valor_fatura:valor_fatura,qtd_fatura:qtd_fatura},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta); 
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){ $("#spanErro").html(resposta); }
          else{ $("#spanErro").html('ERROR'); }
          $("#labelErro").show();
          $('#loading').hide();
          $('#botoes').show();
          return;
      }

      if(resp.estado=='sucesso'){
       
        $('#tbody_add_'+resp.id_morada).append(resp.conteudo_add);
        $('#removeProduct_'+resp.id_encomenda).show(); 
        $('#linha_valor_'+resp.id_morada).val(resp.linha_valor_total);
        $('#linha_qtd_'+resp.id_morada).val(resp.linha_qtd_total);
        $('#qtd_parcial_'+resp.id_morada).html(resp.linha_qtd_total + ' {{ trans('seller.units') }}');
        //$('#qtd_transportar_'+resp.id_morada).html(resp.linha_qtd_total + ' {{ trans('seller.units') }}');
        $('#valor_parcial_'+resp.id_morada).html(resp.linha_valor_total + ' €');
        //$('#valor_transportar_'+resp.id_morada).html(resp.linha_valor_total + ' €');
        $('#qtd_fatura').val(resp.qtd_fatura_total);
        $('#qtd_fat').html(resp.qtd_fatura_total);
        $('#valor_fat').html(resp.valor_fatura_total + ' €');
        $('#valor_fatura').val(resp.valor_fatura_total);
        $('#iva').val(resp.valor_iva);
        $('#iva_fat').html(resp.valor_iva + ' €');
        $('#valor_com_iva').val(resp.valor_com_iva);
        $('#valor_fat_iva').html(resp.valor_com_iva + ' €');
        $('#united_'+resp.id_morada).html('-');
        $('#price_'+resp.id_morada).html('-');
        $('#value_prod_'+resp.id_morada).html('-');
        $("#select"+resp.id_morada).val(0);
        $("#review").attr('href','{{ route('ordersSummaryV2') }}');
        if($("#info_"+resp.id_produto).text() == ''){ $('#new_info').append(resp.info_td);}
        else{ $('#info_'+resp.id_produto).html(resp.info_td); }
        $('#info_'+resp.id_produto).show();
        $('#id_encomenda').val(resp.id_encomenda);
      }
    });
  }
</script>

<script>
  function showProductNew(id){

    var select = document.getElementById("select"+id);
    var selectValue = select.options[select.selectedIndex].value;

    
    @foreach($produtos as $val)
      if (selectValue == {!! $val['id_produto'] !!}) {

        $('#united_'+id).html('');
        $('#price_'+id).html('');
        $('#value_prod_'+id).html('');

      
        $('#united_'+id).append('<span>1 {{ trans('seller.Box') }}</span><br><span>{!! $val['qtd_caixa'] !!} {{ trans('seller.articles') }}</span>');
        
        var price_prod = ({!! $val['qtd_caixa'] !!}*{!! $val['valor'] !!});


        //$('#price_'+id).append('<span>'+price_prod.toFixed(2)+'€</span><br><span>{!! $val['valor'] !!} €/{{ trans('seller.articles') }}</span>');

        
        //$('#value_prod_'+id).append('<span>'+price_prod.toFixed(2)+' €</span>');

        $('#increase_'+id).attr('onclick','increaseValue('+ price_prod +','+id+')');
        $('#decrease_'+id).attr('onclick','decreaseValue('+ price_prod +','+id+')');

        /*$('#addProduct_'+id).attr('onclick','addProduct('+id+')');*/

        addProduct(id);
        $('#option_'+selectValue+'_'+id).attr('disabled',true);
      }
    @endforeach
  }
</script>

<script>
  function updateProduct(id_line,preco_unitario,id_morada,tipo){

    var id_prod = $('#select_'+id_line).val();
    var qtd = $('#quantidade_'+id_line).val();
    var qtd_parcial_t = $('#linha_qtd_'+id_morada).val();
    var valor_parcial_t = $('#linha_valor_'+id_morada).val();
    var qtd_fatura = $('#qtd_fatura').val();
    var valor_fatura = $('#valor_fatura').val();
    var qtd_anterior = $('#qtd_anterior'+id_line).val();
    var qtd_line = $('#qtd_'+id_line).val();
    var id_encomenda = $('#id_encomenda').val();

    
    $.ajax({
      type: "POST",
      url: '{{ route('updateLinetPost') }}',
      data: {id_line:id_line,id_prod:id_prod,qtd:qtd,preco_unitario:preco_unitario,qtd_anterior:qtd_anterior,qtd_parcial_t:qtd_parcial_t,valor_parcial_t:valor_parcial_t,qtd_fatura:qtd_fatura,valor_fatura:valor_fatura,qtd_line:qtd_line,tipo:tipo},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta); 
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){ $("#spanErro").html(resposta); }
          else{ $("#spanErro").html('ERROR'); }
          return;
      }

      if(resp.estado=='sucesso'){

        $('#qtd_anterior'+resp.id_line).val(resp.qtd);
        $('#qtd_'+resp.id_line).val(resp.qtd);
        $('#quantidade_'+resp.id_line).val(resp.qtd);
        $('#select_'+resp.id_line).val(resp.id_prod);
        $('#value_prod_'+resp.id_line).html(resp.valor_line + ' €');
        $('#qtd_parcial_'+id_morada).html(resp.qtd_line_t + ' {{ trans('seller.units') }}');
        //$('#qtd_transportar_'+id_morada).html(resp.qtd_line_t + ' {{ trans('seller.units') }}');
        $('#linha_qtd_'+id_morada).val(resp.qtd_line_t);
        $('#linha_valor_'+id_morada).val(resp.valor_line_t);
        $('#valor_parcial_'+id_morada).html(resp.valor_line_t + ' €');
        //$('#valor_transportar_'+id_morada).html(resp.valor_line_t + ' €');
        $('#qtd_fatura').val(resp.qtd_fatura_t);
        $('#qtd_fat').html(resp.qtd_fatura_t);
        $('#valor_fat').html(resp.valor_fatura_t + ' €');
        $('#valor_fatura').val(resp.valor_fatura_t);
        $('#iva').val(resp.valor_iva);
        $('#iva_fat').html(resp.valor_iva + ' €');
        $('#valor_com_iva').val(resp.valor_com_iva);
        $('#valor_fat_iva').html(resp.valor_com_iva + ' €');
        $('#info_'+resp.id_prod).html(resp.info_td);
        $('#info_'+resp.id_prod).show();
        if (resp.qtd == 0) {
          $('#product_'+resp.id_line).hide();
          $('#option_'+resp.id_prod+'_'+id_morada).attr('disabled',false);
        }

        $('#removeProduct_'+id_line).attr('onclick','deleteLine('+id_line+','+id_encomenda+','+id_morada+','+resp.qtd+','+resp.valor_line+')');
      }
    });
  }
</script>


<script>
  function increaseValue(id_line,preco_unitario,id_morada) {
    var id_encomenda = $('#id_encomenda').val();

    var value = parseInt(document.getElementById('quantidade_'+id_line).value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    document.getElementById('quantidade_'+id_line).value = value;

    updateProduct(id_line,preco_unitario,id_morada,'incrementar');
  }

  function decreaseValue(id_line,preco_unitario,id_morada) {
    var value = parseInt(document.getElementById('quantidade_'+id_line).value, 10);
    if (value > 0) {

      value = isNaN(value) ? 0 : value;
      value < 1 ? value = 1 : '';
      value--;
      
      document.getElementById('quantidade_'+id_line).value = value;
      updateProduct(id_line,preco_unitario,id_morada,'descrementar');
    }
  }
</script>

<script>
  function deleteLine(id_line,id_encomenda,id_morada,quantidade,valor_produto){

    var linha_qtd = $('#linha_qtd_'+id_morada).val();
    var linha_valor = $('#linha_valor_'+id_morada).val();
    var valor_fatura = $('#valor_fatura').val();
    var qtd_fatura = $('#qtd_fatura').val();
    var iva = $('#iva').val();
    var valor_com_iva = $('#valor_com_iva').val();


    $.ajax({
      type: "POST",
      url: '{{ route('deleteLineCartPost') }}',
      data: {id_line:id_line,id_encomenda:id_encomenda,id_morada:id_morada,quantidade:quantidade,valor_produto:valor_produto,linha_qtd:linha_qtd,linha_valor:linha_valor,valor_fatura:valor_fatura,qtd_fatura:qtd_fatura,iva:iva,valor_com_iva:valor_com_iva},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta); 
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){ $("#spanErro").html(resposta); }
          else{ $("#spanErro").html('ERROR'); }
          return;
      }

      if(resp.estado=='sucesso'){
        
        $('#product_'+resp.id_line).remove();
        $('#qtd_parcial_'+resp.id_morada).html('');
        //$('#qtd_transportar_'+resp.id_morada).html('');
        $('#valor_parcial_'+resp.id_morada).html('');
        //$('#valor_transportar_'+resp.id_morada).html('');
        $('#qtd_fat').html('');
        $('#valor_fat').html('');
        $('#iva_fat').html('');
        $('#valor_fat_iva').html('');
        $('#qtd_parcial_'+resp.id_morada).html(resp.qtd_final+' {{ trans('seller.units') }}');
        //$('#qtd_transportar_'+resp.id_morada).html(resp.qtd_final+' {{ trans('seller.units') }}');
        $('#valor_parcial_'+resp.id_morada).html(resp.valor_final + ' €');
        //$('#valor_transportar_'+resp.id_morada).html(resp.valor_final + ' €');
        $('#qtd_fat').html(resp.qtd_final_fat);
        $('#valor_fat').html(resp.valor_final_fat + ' €');
        $('#iva_fat').html(resp.valor_iva + ' €');
        $('#valor_fat_iva').html(resp.valor_iva_fat + ' €');

        $('#linha_valor_'+resp.id_morada).val(resp.valor_final);
        $('#linha_qtd_'+resp.id_morada).val(resp.qtd_final);
        $('#valor_fatura').val(resp.valor_final_fat);
        $('#qtd_fatura').val(resp.qtd_final_fat);
        $('#iva').val(resp.valor_iva);
        $('#valor_com_iva').val(resp.valor_iva_fat);

        if (resp.qtd_t == 0) { $('#info_'+resp.id_prod).remove();}
        else{ $('#info_'+resp.id_prod).html(resp.info_td);}
        
        $('#option_'+resp.id_prod+'_'+id_morada).attr('disabled',false);   

        if(resp.valor_iva_fat <= 0){
          $("#review").attr('href','');
          $('#id_encomenda').val('');
        } 

      }
    });
  }
</script>

<script>
  function cleanDatos(tipo){
    var id_encomenda = $('#id_encomenda').val();

    $.ajax({
    type: "POST",
    url: '{{ route('cleanDatosPost') }}',
    data: {id_encomenda:id_encomenda},
    headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
  })
  .done(function(resposta) {
    //console.log(resposta); 
    try{ resp=$.parseJSON(resposta); }
    catch (e){
        if(resposta){ $("#spanErro").html(resposta); }
        else{ $("#spanErro").html('ERROR'); }
        return;
    }
    if(resp.estado=='sucesso'){
      $('.cleanDatos').html('');
      $('.cleanDatosUni').html('0 {{ trans('seller.units') }}');
      $('.cleanDatosValue').html('0 €');
      $('#qtd_fat').html('0');
      $('#valor_fat').html('0 €');
      $('#iva_fat').html('0 €');
      $('#valor_fat_iva').html('0 €');
      $('.cleanDatosInput').val(0);
      $('#valor_fatura').val(0);
      $('#qtd_fatura').val(0);
      $('#iva').val(0);
      $('#valor_com_iva').val(0);
      $('#new_info').html('');
      $('select option').attr('disabled',false);
      $("#review").attr('href','');
      $('#id_encomenda').val('');

      if (tipo == 'cancel') {

        window.location="{{ route('ordersV2') }}"; 
      }

    }
  });
  } 
</script>
@stop