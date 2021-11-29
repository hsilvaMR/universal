@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'), trans('backoffice.newUser') => route('usersNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'),trans('backoffice.Warehouse_Orders') => route('ordersWarehouseAllPageB',['id'=>$obj->id_encomenda]), trans('backoffice.editOrder') => route('ordersEditPageB',['id'=>$obj->id_enc_armz]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newUser') }}@else{{ trans('backoffice.editOrder') }}@endif</div>
  
  <form id="ordersFormB" method="POST" enctype="multipart/form-data" action="{{ route('ordersFormB') }}">
    {{ csrf_field() }}
    
    <input type="hidden" name="id" value="{{ $obj->id_enc_armz }}">
    
    <label class="lb">{{ trans('backoffice.WarehouseAddress') }}</label>
    <select name="armazem">
      @foreach($moradas as $value)
        <option value="{{ $value->id }}" @if($obj->id_morada == $value->id) selected @endif>{{ $value->morada }}</option>
      @endforeach
    </select>

    <label class="lb">{{ trans('backoffice.OrderDocument') }}</label>
    <div class="row">
      <div class="col-md-4">
        <input class="ip" value="{{ date('Y-m-d',$obj->data_encomenda) }}" disabled>
      </div>
      <div class="col-md-8">
        <a href="/doc/orders/{{ $obj->doc_encomenda }}" download><input class="ip"  value="{{ $obj->doc_encomenda }}" disabled></a> 
      </div>
    </div>


    <label class="lb">{{ trans('backoffice.ProcessingStartDate') }}</label>
    <input class="ip" type="text" id="data_inicio_process" name="data_inicio_process" maxlength="10" value="@if(isset($obj->data_inicio_process) && ($obj->data_inicio_process != 0)){{ date('Y-m-d',$obj->data_inicio_process) }}@endif">

    <div class="row">
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.ProformaDate') }}</label>
        <input class="ip" value="@if(isset($obj->data_proforma) && ($obj->data_proforma != 0)){{ date('Y-m-d',$obj->data_proforma) }}@endif" disabled>
      </div>
      <div class="col-md-8">
        <label class="lb">{{ trans('backoffice.Proforma') }}</label>
        <div class="div-50">
         <label class="a-dotted-white" id="proforma"><a href="/doc/orders/{{ $obj->doc_proforma }}" download>{{ $obj->doc_proforma }}</a></label>
        </div>
        <label for="selecao-arquivo-proforma" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
        <input id="selecao-arquivo-proforma" type="file" name="proforma" onchange="lerFicheiros(this,'proforma');">
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.GuideDate') }}</label>
        <input class="ip" value="@if(isset($obj->data_guia) && ($obj->data_guia != 0)){{ date('Y-m-d',$obj->data_guia) }}@endif" disabled>
      </div>
      <div class="col-md-8">
        <label class="lb">{{ trans('backoffice.Guide') }}</label><br>
        <div class="div-50">
         <label class="a-dotted-white" id="uploads_guia">{{ $obj->doc_guia }}</label>
        </div>
        <label for="selecao-arquivo-guia" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
        <input id="selecao-arquivo-guia" type="file" name="guia" onchange="lerFicheiros(this,'uploads_guia');">
      </div>
    </div>
     
    <label class="lb">{{ trans('backoffice.ShippingDate') }}</label>
    <input class="ip" type="text" id="data_expedicao" name="data_expedicao" maxlength="10" value="@if(isset($obj->data_expedicao) && ($obj->data_expedicao != 0)){{ date('Y-m-d',$obj->data_expedicao) }}@endif">

    @if($empresa->tipo_fatura != 'fat_unificada')
      <div class="row">
        <div class="col-md-4">
          <label class="lb">{{ trans('backoffice.InvoiceDate') }}</label>
          <input class="ip" value="@if(isset($obj->data_fatura) && ($obj->data_fatura != 0)){{ date('Y-m-d',$obj->data_fatura) }}@endif" disabled>
        </div>
        <div class="col-md-8">
          <label class="lb">{{ trans('backoffice.Invoice') }}</label><br>
          <div class="div-50">
           <label class="a-dotted-white" id="uploads_fatura"><a href="/doc/orders/{{ $obj->doc_fatura }}" download>{{ $obj->doc_fatura }}</a></label>
          </div>
          <label for="selecao-arquivo-fatura" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
          <input id="selecao-arquivo-fatura" type="file" name="fatura" onchange="lerFicheiros(this,'uploads_fatura');">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <label class="lb">{{ trans('backoffice.DateReceived') }}</label>
          <input class="ip" value="@if(isset($obj->data_recibo) && ($obj->data_recibo != 0)){{ date('Y-m-d',$obj->data_recibo) }}@endif" disabled>
        </div>
        <div class="col-md-8">
          <label class="lb">{{ trans('backoffice.Receipt') }}</label><br>
          <div class="div-50">
           <label class="a-dotted-white" id="uploads_recibo"><a href="/doc/orders/{{ $obj->doc_recibo }}" download>{{ $obj->doc_recibo }}</a></label>
          </div>
          <label for="selecao-arquivo-recibo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
          <input id="selecao-arquivo-recibo" type="file" name="recibo" onchange="lerFicheiros(this,'uploads_recibo');">
        </div>
      </div>
    @endif

    <div class="row">
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.ProofDate') }}</label>
        <input class="ip" value="@if(isset($obj->data_comprovativo) && ($obj->data_comprovativo != 0)){{ date('Y-m-d',$obj->data_comprovativo) }}@endif" disabled>
      </div>
      <div class="col-md-8">
        <label class="lb">{{ trans('backoffice.PaymentReceipt') }}</label><br>
        <div class="div-50">
         <label class="a-dotted-white" id="uploads_comprovativo"><a href="/doc/orders/{{ $obj->doc_comprovativo }}" download>{{ $obj->doc_comprovativo }}</a></label>
        </div>
        <label for="selecao-arquivo-comprovativo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
        <input id="selecao-arquivo-comprovativo" type="file" name="comprovativo" onchange="lerFicheiros(this,'uploads_comprovativo');">
      </div>
    </div>

    <label class="lb">{{ trans('backoffice.Obs') }}</label>
    <textarea class="tx" name="obs">{{ $obj->obs }}</textarea>

    <label class="lb">{{ trans('backoffice.Status') }}</label>
    <select name="estado">
      <option @if($obj->estado_enc == 'concluida') selected @endif value="concluida">{{ trans('backoffice.Completed') }}</option>
      <option @if($obj->estado_enc == 'em_processamento') selected @endif value="em_processamento">{{ trans('backoffice.In_processing') }}</option>
      <option @if($obj->estado_enc == 'expedida_parcialmente') selected @endif value="expedida_parcialmente">{{ trans('backoffice.partially_dispatched') }}</option>
      <option @if($obj->estado_enc == 'expedida') selected @endif value="expedida">{{ trans('backoffice.Dispatched') }}</option>
      <option @if($obj->estado_enc == 'registada') selected @endif value="registada">{{ trans('backoffice.Registered') }}</option>
      <option @if($obj->estado_enc == 'fatura_vencida') selected @endif value="fatura_vencida">{{ trans('backoffice.fatura_vencida') }}</option>
    </select>
   

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
    </div>
    <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabelS">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabelS">{{ trans('backoffice.Saved') }}</h4></div>
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
  <script type="text/javascript">$('.select2').select2();</script>

  <script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript">

    $('#ordersFormB').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
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
        try{ resp=$.parseJSON(resposta); }
        catch (e){            
          if(resposta){
            $("#spanErro").html(resposta);
            $("#labelErros").show();
          }
          $('#loading').hide();
          $('#botoes').show();
          return;
        }
        if(resp.estado=='sucesso'){
          if(resp.reload){ $('#myModalSave').modal('show'); }
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }else if(resp.estado=='erro'){
          $("#spanErro").html(resp.mensagem);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
      });
    });


    $('#data_inicio_process').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });

    $('#data_expedicao').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });

    function lerFicheiros(input,id) {
      var quantidade = input.files.length;
      var nome = input.value;

      if(quantidade==1){$('#'+id).html(nome);}
      else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
    }
  </script>
@stop