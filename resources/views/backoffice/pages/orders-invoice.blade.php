@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'), trans('backoffice.EditDocuments') => route('invoiceOrderPageB',$obj->id) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.EditDocuments') }} @else {{ trans('backoffice.EditDocuments') }} @endif</div>
  
  <form id="ordersFormB" method="POST" enctype="multipart/form-data" action="{{ route('orderInvoiceFormB') }}">
    {{ csrf_field() }}
    
    <input type="hidden" name="id" value="{{ $obj->id }}">
    
    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.Invoice') }}</label><br>
        <div class="div-50">
          <div class="div-50" id="ficheiro_fatura">
            <label class="a-dotted-white" id="uploads_fatura">
              @if(isset($obj->fatura))<a href="/doc/orders/{{ $obj->fatura }}" download>{{ $obj->fatura }}</a>@endif
            </label>
          </div>
          <label for="selecao-arquivo_fatura" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
          <input id="selecao-arquivo_fatura" type="file" name="fatura" onchange="lerFicheiros(this,'uploads_fatura');">
        </div>
        <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('_fatura');"><i class="fas fa-trash-alt"></i></label>
      </div>
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.InvoiceDate') }}</label>
        <input class="ip" type="text" id="data_fatura" name="data_fatura" maxlength="10" value="@if(isset($obj->data_fatura) && $obj->data_fatura !=0) {{ date('Y-m-d',$obj->data_fatura) }}@endif">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.Receipt') }}</label><br>
        <div class="div-50">
          <div class="div-50" id="ficheiro_recibo">
            <label class="a-dotted-white" id="uploads_recibo">
              @if(isset($obj->recibo))<a href="/doc/orders/{{ $obj->recibo }}" download>{{ $obj->recibo }}</a>@endif
            </label>
          </div>
          <label for="selecao-arquivo_recibo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
          <input id="selecao-arquivo_recibo" type="file" name="recibo" onchange="lerFicheiros(this,'uploads_recibo');">
        </div>
        <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('_recibo');"><i class="fas fa-trash-alt"></i></label>
      </div>
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.DateReceived') }}</label>
        <input class="ip" type="text" id="data_recibo" name="data_recibo" maxlength="10" value="@if(isset($obj->data_recibo) && $obj->data_recibo !=0) {{ date('Y-m-d',$obj->data_recibo) }}@endif">
      </div>
    </div>

   
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

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
  <script type="text/javascript">

    function limparFicheiros(id) {
      $('#selecao-arquivo'+id).val('');
      $('#uploads'+id).html('&nbsp;');
      $('#ficheiro'+id).html('<label class="a-dotted-white" id="uploads'+id+'">&nbsp;</label>');
      $('#data'+id).val('');
    }

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
        //(resp = $.parseJSON(resposta);
        if(resposta == 'sucesso') {
          limparFicheiros('');
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }
        else{
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
      });
    });


    $('#data_recibo').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });

    $('#data_fatura').datepicker({
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