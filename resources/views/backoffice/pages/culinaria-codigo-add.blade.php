@extends('backoffice/layouts/default')

@section('content')
@if($funcao=='new')
<?php $arrayCrumbs = [ 'Códigos Promocionais' => route('cookingPageB'), 'Novo Código' => route('newCodecookingPageB') ]; ?>@else
<?php $arrayCrumbs = [ 'Códigos Promocionais' => route('cookingPageB'), 'Editar Código' => route('editCodecookingPageB',['id'=>$codigo->id]) ]; ?>@endif
@include('backoffice/includes/crumbs')

<div class="page-titulo">
  @if($funcao=='new')Novo Código @else Editar Código @endif
</div>

<form id="adminFormA" method="POST" enctype="multipart/form-data" action="{{ route('codeFormB') }}">
  {{ csrf_field() }}
  <input type="hidden" id="id" name="id" value="@if(isset($codigo->id)){{ $codigo->id }}@endif">
  <div class="row">
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Code') }}</label>
      <input class="ip" type="text" name="codigo" value="@if(isset($codigo->codigo)){{ $codigo->codigo }}@endif">
    </div>
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Description') }}</label>
      <input class="ip" type="text" name="descricao"
        value="@if(isset($codigo->descricao)){{ $codigo->descricao }}@endif">
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.startDate') }}</label>
      <input class="ip" type="text" id="data_inicio" name="data_inicio" maxlength="10"
        value="@if(isset($codigo->data_inicio) && ($codigo->data_inicio != 0)){{ date('Y-m-d',$codigo->data_inicio) }}@endif">
    </div>
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.endDate') }}</label>
      <input class="ip" type="text" id="data_fim" name="data_fim" maxlength="10"
        value="@if(isset($codigo->data_fim) && ($codigo->data_fim != 0)){{ date('Y-m-d',$codigo->data_fim) }}@endif">
    </div>
  </div>

  <label class="lb">{{ trans('backoffice.Additional') }}</label>
  <div class="clearfix height-10"></div>
  <input type="checkbox" name="online" id="checkOn" value="1" @if(isset($codigo->online) && ($codigo->online)) checked
  @endif>
  <label for="checkOn"><span></span>{{ trans('backoffice.Online') }}</label>

  <div class="clearfix height-20"></div>
  <div id="botoes">
    <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i>
      {{ trans('backoffice.Save') }}</button>
    <label class="width-10 height-40 float-right"></label>
    <a href="{{ route('adminAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i>
      {{ trans('backoffice.Cancel') }}</a>
  </div>
  <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
  <div class="clearfix"></div>
  <div class="height-20"></div>
  <label id="labelSucesso" class="av-100 av-verde display-none">
    <span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span>
    <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
  </label>
  <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times"
      onclick="$(this).parent().hide();"></i></label>
</form>

<!-- Modal Save -->
<div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <input type="hidden" name="id_modal" id="id_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Saved') }}</h4>
      </div>
      <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
      <div class="modal-footer">
        <a href="{{ route('cookingPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i>
          {{ trans('backoffice.Back') }}</a>&nbsp;
        <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i>
          {{ trans('backoffice.Ok') }}</a>
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
<script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
  $('#data_inicio').datepicker({
        //format:'yyyy-mm-dd',
        days: {!! trans('backoffice.days') !!},
        daysShort: {!! trans('backoffice.daysShort') !!},
        daysMin: {!! trans('backoffice.daysMin') !!},
        months: {!! trans('backoffice.months') !!},
        monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
</script>

<script type="text/javascript">
  $('#data_fim').datepicker({
        //format:'yyyy-mm-dd',
        days: {!! trans('backoffice.days') !!},
        daysShort: {!! trans('backoffice.daysShort') !!},
        daysMin: {!! trans('backoffice.daysMin') !!},
        months: {!! trans('backoffice.months') !!},
        monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
</script>

<script type="text/javascript">
  $('#adminFormA').on('submit',function(e) {
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
        //$('#myModalSave').modal('show');
        //console.log(resposta);
        resp = $.parseJSON(resposta);
        if(resp.estado=='sucesso'){          
          $('#id').val(resp.id);
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();          
        }else if(resp.estado=='erro'){
          $("#spanErro").html(resp.mensagem);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }else if(resposta){
          $("#spanErro").html(resposta);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
      });
    });
</script>
@stop