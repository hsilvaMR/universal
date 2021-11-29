@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')
  <?php $arrayCrumbs = [ 'Encomendas' => route('cookingOrdersPageB'), 'Nova Encomenda' => route('newOrdercookingPageB') ]; ?>@else
  <?php $arrayCrumbs = [ 'Encomendas' => route('cookingOrdersPageB'), 'Editar Encomenda' => route('editOrdersCookingPageB',['id'=>$encomenda->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">
    @if($funcao=='new')Nova Encomenda @else Editar Encomenda @endif
  </div>

  <form id="adminFormA" method="POST" enctype="multipart/form-data" action="{{ route('orderFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($encomenda->id)){{ $encomenda->id }}@endif">
    <div class="row">
      <div class="col-lg-4">
        <label class="lb">{{ trans('backoffice.Name') }}</label>
        <input class="ip" type="text" name="nome" value="@if(isset($encomenda->nome)){{ $encomenda->nome }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
      <div class="col-lg-4">
        <label class="lb">{{ trans('backoffice.Email') }}</label>
        <input class="ip" type="email" name="email" value="@if(isset($encomenda->email)){{ $encomenda->email }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
      <div class="col-lg-4">
        <label class="lb">{{ trans('backoffice.Contact') }}</label>
        <input class="ip" type="text" name="contacto" value="@if(isset($encomenda->contacto)){{ $encomenda->contacto }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
    </div>

    <label class="lb">{{ trans('backoffice.Adress') }}</label>
    <input class="ip" type="text" name="morada" value="@if(isset($encomenda->morada)){{ $encomenda->morada }}@endif" @if($funcao=='edit') disabled @endif>

    <div class="row">
      <div class="col-lg-4">
        <label class="lb">Número da porta</label>
        <input class="ip" type="text" name="numero_porta" value="@if(isset($encomenda->numero_porta)){{ $encomenda->numero_porta }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
      <div class="col-lg-4">
        <label class="lb">Código postal</label>
        <input class="ip" type="text" name="codigo_postal" value="@if(isset($encomenda->codigo_postal)){{ $encomenda->codigo_postal }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
      <div class="col-lg-4">
        <label class="lb">Localidade</label>
        <input class="ip" type="text" name="localidade" value="@if(isset($encomenda->localidade)){{ $encomenda->localidade }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
    </div>

    

    <label class="lb">{{ trans('backoffice.Message') }}</label>
    <textarea name="mensagem" class="tx" @if($funcao=='edit') disabled @endif>@if(isset($encomenda->mensagem)){!! $encomenda->mensagem !!}@endif</textarea>

    <div class="row">
      <div class="col-lg-6">
        <label class="lb">Codigo Promocional</label>
        <input class="ip" type="text" name="codigo" value="@if(isset($codigo->codigo)){{ $codigo->codigo }}@endif" @if($funcao=='edit') disabled @endif>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Status') }}</label>
        <select class="select2" name="estado">
          <option value="" selected disabled></option>
          <option value="realizada" @if(isset($encomenda->estado) && $encomenda->estado=='realizada') selected @endif>Encomenda realizada</option>
          <option value="em_processamento" @if(isset($encomenda->estado) && $encomenda->estado=='em_processamento') selected @endif>Encomenda em processamento</option>
          <option value="enviada" @if(isset($encomenda->estado) && $encomenda->estado=='enviado') selected @endif>Encomenda enviada</option>
          <option value="cancelada" @if(isset($encomenda->estado) && $encomenda->estado=='cancelado') selected @endif>Encomenda cancelada</option>

        </select>
      </div>
      
    </div>

   
   
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('adminAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Saved') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('cookingOrdersPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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