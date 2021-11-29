@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'), trans('backoffice.NewOrder') => route('ordersNewPageB',['id'=>$id])]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB')]?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.NewOrder') }}@else{{ trans('backoffice.editOrder') }}@endif</div>
  
  <form id="ordersNewFormB" method="POST" enctype="multipart/form-data" action="{{ route('ordersAddLineProductPageB') }}">
    {{ csrf_field() }}

    <label class="lb">{{ trans('backoffice.Trader') }}</label>
    <select name="id_comerciante">
      @foreach($comerciantes as $val)
        <option value="{{ $val->id }}">{{ $val->nome }}</option>
      @endforeach
    </select>
    
    <div id="products" class="fd-branco">
      <div class="modulo-table">
        <div class="modulo-scroll" style="width:100%;">
          @if(count($moradas_armaz) >0)
            @foreach($moradas_armaz as $armazem)
              <h4>{!! $armazem->nome_personalizado !!}</h4><br>
            
              <table class="modulo-body" id="table_orders" style="margin-bottom:20px;">
                <thead>
                  <tr>
                    <th style="width:250px;">{{ trans('backoffice.Product') }}</th>
                    <th style="width:250px;">{{ trans('backoffice.United') }}</th>
                    <th style="width:250px;">{{ trans('backoffice.Price') }}</th>
                    <th style="width:250px;">{{ trans('backoffice.Amount') }}</th>
                    <th style="width:250px;">{{ trans('backoffice.Value') }}</th>
                  </tr>
                </thead>
                <tbody id="adress_tbody{!! $armazem->id !!}">
                  <input type="hidden" value="{{ $id }}" id="id_empresa" name="id_empresa">
                  <!--<tr>
                    <td></td>
                    <td><input type="hidden" value="{{ $id }}" id="id_empresa" name="id_empresa">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                  </tr>-->
                </tbody>
              </table>
              <label class="margin-bottom20">
                <select id="select{!! $armazem->id !!}" style="margin-top:0px;width:auto;" name="fatura_opc" onclick="showProductNew({!! $armazem->id !!});">
                  <option value="0" selected>{{ trans('backoffice.AddProduct') }}</option>
                  @foreach($array_prod as $array)
                    <option id="option_{!! $array['id_produto'] !!}_{{ $armazem->id}}" value="{!! $array['id_produto'] !!}">{!! $array['nome'] !!}</option>
                  @endforeach
                </select>
              </label>
            @endforeach
          @else
            <label>{{ trans('backoffice.EmptyAdresses_txt') }}</label> 
          @endif
        </div>

        <div style="text-align:right;font-size:14px;">
          <label style="padding:5px 0px;border-bottom:1px solid #dedede;width:100%;">Valor Total &emsp;<b id="valor_sem_iva">0</b><b> €</b></label><br>
          <label style="padding:5px 0px;border-bottom:1px solid #dedede;width:100%;">IVA (6%) &emsp;<b id="iva">0</b><b> €</b></label><br>
          <label style="padding:5px 0px;border-bottom:1px solid #dedede;width:100%;">Total &emsp;<b id="valor_com_iva">0</b><b> €</b></label>
        </div>
      </div>
    </div>


    <div class="clearfix height-20"></div>
    <div id="botoes">
      @if(count($moradas_armaz) > 0)<button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.finalizeOrder') }}</button>
      @else <label class="bt bt-verde float-right orders_qtd_decrease"><i class="fas fa-check"></i> {{ trans('backoffice.finalizeOrder') }}</label>
      @endif
      
      <label class="bt bt-disabled float-right margin-right10 orders_qtd_decrease" onclick="cleanData();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.CleanData') }}</label>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabelS">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabelS">{{ trans('backoffice.finalizeOrder') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('ordersAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" onclick="location.reload();"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
        </div>
      </div>
    </div>
  </div>

@stop

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@stop

@section('javascript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/en.js"></script>
  <script type="text/javascript">
    $('.select2').select2();
  </script>

  <script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>

  <script>
    $('#ordersNewFormB').on('submit',function(e) {
      $("#labelErros").hide();
      $("#labelSucesso").hide();
      $('#loading').show();
      $('#botoes').hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: new FormData(this),
        contentType: false,
        processData: false,
        cache: false
      })
      .done(function(resposta) {
        console.log(resposta);
        resp = $.parseJSON(resposta);
        if(resp.estado=='sucesso'){
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
          cleanData();

          if(resp.reload){ $('#myModalSave').modal('show'); }

        }else if(resp.estado == 'erro'){
          $('#loading').hide();
          $('#botoes').show();
          $("#spanErro").html(resp.mensagem);
          $("#labelErros").show();
        }
      });
    });
  </script>

  <script type="text/javascript">

    function showProductNew(id){

      var select = document.getElementById("select"+id);
      var selectValue = select.options[select.selectedIndex].value;

      if (selectValue!=0) {
        var valor_sem_iva = $('#valor_sem_iva').html();
        var iva = $('#iva').html();
        var valor_com_iva = $('#valor_com_iva').html();

        @foreach($array_prod as $prod)
          if ({!! $prod['id_produto'] !!} == selectValue) {

            var valor = {!! $prod['qtd_caixa'] !!} * {!! $prod['valor'] !!};

            $('#adress_tbody'+id).append('<tr id="prod_tr{!! $prod['id_produto'] !!}'+id+'"><input id="enc_line{!! $prod['id_produto'] !!}'+id+'" type="hidden" value=""></input><td><i id="removeProduct{!! $prod['id_produto'] !!}'+id+'" class="fas fa-times tx-vermelho margin-right10 cursor-pointer" onclick="deleteLine({!! $prod['id_produto'] !!},'+id+','+valor+',1)"></i>{!! $prod['nome'] !!}</td><td><span>1 Caixa</span><br><span>{!! $prod['qtd_caixa'] !!} artigos</span></td><td><span>'+parseFloat(valor.toFixed(2))+' €<span><br><span>{!! $prod['valor'] !!} € / artigos</span></td><td><div class="order-div-input"><div class="orders_qtd_decrease float-left" id="decrease{!! $prod['id_produto'] !!}'+id+'" onclick="decreaseValue()" value="Decrease Value"><span class="orders_qtd_bt"><i class="fas fa-minus tx-gray"></i></span></div><input type="hidden" id="quantidade_antiga{!! $prod['id_produto'] !!}'+id+'" value="1"><input class="orders_input" type="number" name="quantidade{!! $prod['id_produto'] !!}'+id+'" id="quantidade{!! $prod['id_produto'] !!}'+id+'" value="1" onchange="updateProduct({!! $prod['id_produto'] !!},'+id+','+valor+');"><div class="orders_qtd_decrease float-right" id="increase{!! $prod['id_produto'] !!}'+id+'" onclick="increaseValue()" value="Increase Value"><span class="orders_qtd_bt"><i class="fas fa-plus tx-gray"></i></span></div></div></td><td style="width="250px;"><span id="preco{!! $prod['id_produto'] !!}'+id+'">'+parseFloat(valor.toFixed(2))+' €</span></td></tr>');


            var price_prod = ({!! $prod['qtd_caixa'] !!}*{!! $prod['valor'] !!});

            $('#increase{!! $prod['id_produto'] !!}'+id).attr('onclick','increaseValue('+ price_prod +','+id+','+{!! $prod['id_produto'] !!}+')');
            $('#decrease{!! $prod['id_produto'] !!}'+id).attr('onclick','decreaseValue('+ price_prod +','+id+','+{!! $prod['id_produto'] !!}+')');
           
            $('#option_'+selectValue+'_'+id).attr('disabled',true);
            $("#select"+id).val(0);

            var total = parseFloat(valor_sem_iva)+parseFloat(valor);
            var iva = total * 0.06;
            var total_iva = parseFloat(iva.toFixed(2));
            var total_com_iva = parseFloat(total) + parseFloat(total_iva);


            $('#valor_sem_iva').html(parseFloat(total.toFixed(2)));
            $('#iva').html(total_iva);
            $('#valor_com_iva').html(parseFloat(total_com_iva.toFixed(2)));
          }
        @endforeach
      }
    }

    function updateProduct(id_produto,id_morada,valor){
      var quantidade = $('#quantidade'+id_produto+id_morada).val();
      var quantidade_antiga = $('#quantidade_antiga'+id_produto+id_morada).val();

      console.log(quantidade);

      var valor_total = quantidade * valor;

      var valor_sem_iva = $('#valor_sem_iva').html();
      var iva = $('#iva').html();
      var valor_com_iva = $('#valor_com_iva').html();

      $('#preco'+id_produto+id_morada).html('');
      $('#preco'+id_produto+id_morada).append(parseFloat(valor_total.toFixed(2))+ ' €');

      $('#quantidade'+id_produto+id_morada).attr('value', quantidade);
      $('#quantidade_antiga'+id_produto+id_morada).attr('value', quantidade);

      //addProduct(id_morada,id_produto);
      var total = parseFloat((valor_sem_iva -(quantidade_antiga * valor)).toFixed(2)) + parseFloat(valor_total.toFixed(2));
      var total_iva = total * 0.06;
      var total_com_iva = total + total_iva;

      $('#valor_sem_iva').html(parseFloat(total.toFixed(2)));
      $('#iva').html(parseFloat(total_iva.toFixed(2)));
      $('#valor_com_iva').html(parseFloat(total_com_iva.toFixed(2)));

      $('#removeProduct'+id_produto+id_morada).attr('onclick','deleteLine('+id_produto+','+id_morada+','+valor+','+quantidade+');');

      if (quantidade == 0) {
        $('#increase'+id_produto+id_morada).attr('onclick','increaseValue('+ valor +','+id_morada+','+id_produto+')');
        $('#decrease'+id_produto+id_morada).attr('onclick','decreaseValue('+ valor +','+id_morada+','+id_produto+')');

        $('#prod_tr'+id_produto+id_morada).remove();
        $('#option_'+id_produto+'_'+id_morada).attr('disabled',false);
      }
    }


    function increaseValue(preco_unitario,id_morada,id_produto) {
 
      var value = parseInt(document.getElementById('quantidade'+id_produto+id_morada).value, 10);

      value = isNaN(value) ? 0 : value;
      value++;
      
      document.getElementById('quantidade'+id_produto+id_morada).value = value;

      var preco_total = preco_unitario * value;

      var valor_sem_iva = $('#valor_sem_iva').html();
      var iva = $('#iva').html();
      var valor_com_iva = $('#valor_com_iva').html();

      $('#preco'+id_produto+id_morada).html('');
      $('#preco'+id_produto+id_morada).append(parseFloat(preco_total.toFixed(2))+ ' €');
      
      var total = parseFloat(valor_sem_iva)+parseFloat(preco_unitario);
      var iva = total * 0.06;
      var total_iva = parseFloat(iva.toFixed(2));
      var total_com_iva = parseFloat(total) + parseFloat(total_iva);


      $('#valor_sem_iva').html(parseFloat(total.toFixed(2)));
      $('#iva').html(total_iva);
      $('#valor_com_iva').html(parseFloat(total_com_iva.toFixed(2)));

      $('#quantidade'+id_produto+id_morada).attr('value', value);
      $('#quantidade_antiga'+id_produto+id_morada).attr('value', value);
      $('#removeProduct'+id_produto+id_morada).attr('onclick','deleteLine('+id_produto+','+id_morada+','+preco_unitario+','+value+');');
    }

    function decreaseValue(preco_unitario,id_morada,id_produto) {
      var value = parseInt(document.getElementById('quantidade'+id_produto+id_morada).value, 10);


      value = isNaN(value) ? 0 : value;
      value < 1 ? value = 1 : '';
      value--;

       
      document.getElementById('quantidade'+id_produto+id_morada).value = value;

      var preco_total = preco_unitario * value;

      var valor_sem_iva = $('#valor_sem_iva').html();
      var iva = $('#iva').html();
      var valor_com_iva = $('#valor_com_iva').html();

      $('#preco'+id_produto+id_morada).html('');
      $('#preco'+id_produto+id_morada).append(parseFloat(preco_total.toFixed(2))+ ' €');

      var total = parseFloat(valor_sem_iva)-parseFloat(preco_unitario);
      var iva = total * 0.06;
      var total_iva = parseFloat(iva.toFixed(2));
      var total_com_iva = parseFloat(total) + parseFloat(total_iva);

      $('#valor_sem_iva').html(parseFloat(total.toFixed(2)));
      $('#iva').html(total_iva);
      $('#valor_com_iva').html(parseFloat(total_com_iva.toFixed(2)));


      $('#quantidade'+id_produto+id_morada).attr('value', value);
      $('#quantidade_antiga'+id_produto+id_morada).attr('value', value);
      $('#removeProduct'+id_produto+id_morada).attr('onclick','deleteLine('+id_produto+','+id_morada+','+preco_unitario+','+value+');');

      if (value == 0) {
        $('#increase'+id_produto+id_morada).attr('onclick','increaseValue('+ preco_unitario +','+id_morada+','+id_produto+')');
        $('#decrease'+id_produto+id_morada).attr('onclick','decreaseValue('+ preco_unitario +','+id_morada+','+id_produto+')');

        $('#prod_tr'+id_produto+id_morada).remove();
        $('#option_'+id_produto+'_'+id_morada).attr('disabled',false);
      }
    }

    function deleteLine(id_produto,id_morada,valor,quantidade){

      var preco_total = valor * quantidade;
      console.log(preco_total);

      var valor_sem_iva = $('#valor_sem_iva').html();
      var iva = $('#iva').html();
      var valor_com_iva = $('#valor_com_iva').html();

      var total = parseFloat((valor_sem_iva-preco_total).toFixed(2));
      var total_iva = total * 0.06;
      var total_com_iva = total + total_iva;

      $('#valor_sem_iva').html(parseFloat(total.toFixed(2)));
      $('#iva').html(parseFloat(total_iva.toFixed(2)));
      $('#valor_com_iva').html(parseFloat(total_com_iva.toFixed(2)));
      
      $('#prod_tr'+id_produto+id_morada).remove();
      $('#option_'+id_produto+'_'+id_morada).attr('disabled',false);
    }

    function cleanData(){
      @foreach($moradas_armaz as $value)
        @foreach($array_prod as $val)
          $('#prod_tr'+{!! $val['id_produto'] !!}+{!! $value->id !!}).remove();
          $('#option_'+{!! $val['id_produto'] !!}+'_'+{!! $value->id !!}).attr('disabled',false);
        @endforeach
      @endforeach

      $('#valor_sem_iva').html(0);
      $('#iva').html(0);
      $('#valor_com_iva').html(0);
    }

  </script>
@stop