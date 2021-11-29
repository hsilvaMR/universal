@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.Notifications') => route('notificationsPageB'), trans('backoffice.newNotification') => route('notificationsNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.Notifications') => route('notificationsPageB'), trans('backoffice.editNotification') => route('notificationsEditPageB',['id'=>$array['id']]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">
    @if($funcao=='new'){{ trans('backoffice.newNotification') }}@else{{ trans('backoffice.editNotification') }}@endif
  </div>

  <form id="notificationsFormA" method="POST" enctype="multipart/form-data" action="{{ route('notificationsFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($array->id)){{ $array->id }}@endif">

    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.User') }}</label>
        <select class="select2" name="id_agente">
          <option value="" selected disabled></option>
          @foreach($agentes as $val)
            <option value="{{ $val->id }}" @if(isset($array['id_agente']) && $array['id_agente']==$val->id || (!isset($array['id_agente']) && json_decode(\Cookie::get('admin_cookie'))->id==$val->id)) selected @endif>{{ $val->nome.' ('.$val->email.')' }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Type') }}</label>
        <select class="select2" name="tipo">
          <option value="" selected disabled></option>
          <option value="website" @if(isset($array['tipo']) && $array['tipo']=='website') selected @endif>{{ trans('backoffice.Website') }}</option>
          <option value="premios" @if(isset($array['tipo']) && $array['tipo']=='premios') selected @endif>{{ trans('backoffice.Awards') }}</option>
          <option value="passatempo" @if(isset($array['tipo']) && $array['tipo']=='passatempo') selected @endif>{{ trans('backoffice.Giveaways') }}</option>
          <option value="queijinho" @if(isset($array['tipo']) && $array['tipo']=='queijinho') selected @endif>{{ trans('backoffice.littleCheese') }}</option>
          <option value="contacto" @if(isset($array['tipo']) && $array['tipo']=='contacto') selected @endif>{{ trans('backoffice.Contacts') }}</option>
          <option value="utilizadores" @if(isset($array['tipo']) && $array['tipo']=='utilizadores') selected @endif>{{ trans('backoffice.Users') }}</option>
          <option value="empresas" @if(isset($array['tipo']) && $array['tipo']=='empresas') selected @endif>{{ trans('backoffice.Companies') }}</option>
          <option value="encomendas" @if(isset($array['tipo']) && $array['tipo']=='encomendas') selected @endif>{{ trans('backoffice.Orders') }}</option>
          <option value="produtos" @if(isset($array['tipo']) && $array['tipo']=='produtos') selected @endif>{{ trans('backoffice.Products') }}</option>
          <option value="informacao" @if(isset($array['tipo']) && $array['tipo']=='informacao') selected @endif>{{ trans('backoffice.TechnicalInformation') }}</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Notification') }}</label>
        <textarea class="tx editorTexto" name="mensagem" rows="3">@if(isset($array['mensagem'])){{ $array['mensagem'] }}@endif</textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Link') }}</label>
        <input class="ip" type="text" name="url" value="@if(isset($array['url'])){{ $array['url'] }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Additional') }}</label>
        <div class="clearfix height-10"></div>
        <input type="checkbox" name="visto" id="check1" value="1" @if(isset($array['visto']) && $array['visto']) checked @endif @if($funcao=='new') disabled @endif>
        <label for="check1"><span></span>{{ trans('backoffice.View') }}</label>
      </div>
    </div>

    
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('notificationsPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('notificationsPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" onclick="location.reload(true);"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{ json_decode(\Cookie::get('admin_cookie'))->lingua }}.js"></script>
<script type="text/javascript"> $('.select2').select2(); </script>

<script type="text/javascript">
  $('#notificationsFormA').on('submit',function(e) {
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
      .done(function(resposta){
        //console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            if(resposta){ $("#spanErro").html(resposta); }
            else{ $("#spanErro").html('ERROR'); }
            $("#labelErros").show();
            $('#loading').hide();
            $('#botoes').show();
            return;
        }
        if(resp.estado=='sucesso'){
          $('#myModalSave').modal('show');
          //var url = '{ { route('notificationsEditPageB',['id'=>':id']) }}';
          //url = url.replace(':id', resp.id);
          //window.location.href=url;
          $('#id').val(resp.id);
          $("#labelSucesso").show();
        }else if(resposta){
          $("#spanErro").html(resposta);
          $("#labelErros").show();
        }
        $('#loading').hide();
        $('#botoes').show();
      });
    });
</script>
@stop