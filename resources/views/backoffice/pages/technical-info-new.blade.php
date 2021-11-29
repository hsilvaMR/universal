@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.AllInformation') => route('techInfoPageB'), trans('backoffice.newSlide') => route('techInfoNewPageB') ]; ?>@else <?php $arrayCrumbs = [ trans('backoffice.AllInformation') => route('techInfoPageB'), trans('backoffice.EditInformation') => route('techInfoEditPageB',$obj->id)]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newSlide') }}@else{{ trans('backoffice.EditInformation') }}@endif</div>

  <form id="infoFormB" method="POST" enctype="multipart/form-data" action="{{ route('techInfoFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
  
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Title') }}</label>
        <input class="ip" type="text" name="descricao" value="@if(isset($obj->descricao)){{ $obj->descricao }}@endif"></input>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.File') }}</label>
        <input type="hidden" id="ficheiro_antigo" name="ficheiro_antigo" value="@if(isset($obj->ficheiro)){{ $obj->ficheiro }}@endif">
        <div>
          <div class="div-50">
            <div class="div-50" id="ficheiro">
              @if(isset($obj->ficheiro) && $obj->ficheiro)<a href="/doc/informations/{{ $obj->ficheiro }}" target="_blank" class="a-dotted-white" download>{{ $obj->ficheiro }}</a>@else<label class="a-dotted-white" id="uploads">&nbsp;</label>@endif
            </div>
            <label for="selecao-ficheiro" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
            <input id="selecao-ficheiro" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads');">
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros();"><i class="fa fa-trash-alt"></i></label>          
        </div>
      </div>
    </div>    
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Type') }}</label>
        <select class="select2" name="tipo">
          <option value="" selected disabled></option>
          <option value="produto" @if(isset($obj->tipo) && ($obj->tipo == 'produto')) selected @endif>{{ trans('backoffice.product') }}</option>
          <option value="documento" @if(isset($obj->tipo) && ($obj->tipo == 'documento')) selected @endif>{{ trans('backoffice.document') }}</option>
        </select>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Status') }}</label>
        <select class="select2" name="estado">
          <option value="" selected disabled></option>
          <option value="novo" @if(isset($obj->estado) && ($obj->estado == 'novo')) selected @endif>{{ trans('backoffice.new') }}</option>
          <option value="actulizado" @if(isset($obj->estado) && ($obj->estado == 'atualizado')) selected @endif>{{ trans('backoffice.updated') }}</option>
          <option value="ativo" @if(isset($obj->estado) && ($obj->estado == 'ativo')) selected @endif>{{ trans('backoffice.active') }}</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Additional') }}</label>
        <div class="clearfix height-10"></div>
        <input type="checkbox" name="online" id="checkO" value="1" @if(isset($obj->online) && ($obj->online)) checked @endif>
        <label for="checkO"><span></span>{{ trans('backoffice.Online') }}</label>
      </div>
    </div>

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('webSlideAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
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
          <a href="{{ route('webSlideAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
<script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>

<script type="text/javascript">
function lerFicheiros(input,id) {
  var quantidade = input.files.length;
  var nome = input.value;
  if(quantidade==1){$('#'+id).html(nome);}
  else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
}
function limparFicheiros() {
  $('#selecao-ficheiro').val('');
  $('#uploads').html('&nbsp;');
  $('#ficheiro_antigo').val('');
  $('#ficheiro').html('<label class="a-dotted-white" id="uploads">&nbsp;</label>');
}

$('#infoFormB').on('submit',function(e) {
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
      $('#id').val(resp.id);
      limparFicheiros();
      if(resp.ficheiro){
        $('#ficheiro_antigo').val(resp.ficheiro);
        $('#ficheiro').html('<a href="/doc/informations/'+resp.ficheiro+'" target="_blank" class="a-dotted-white" download>'+resp.ficheiro+'</a>');
      }
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
</script>
@stop