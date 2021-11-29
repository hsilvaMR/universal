@extends('backoffice/layouts/default')

@section('content')
  @if($separador=='userNew')<?php $arrayCrumbs = [ trans('backoffice.Managers') => route('adminAllPageB'), trans('backoffice.newManager') => route('adminNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.Managers') => route('adminAllPageB'), trans('backoffice.editManager') => route('adminEditPageB',['id'=>$user->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">
    @if($separador=='userNew'){{ trans('backoffice.newManager') }}@else{{ trans('backoffice.editManager') }}@endif
  </div>

  <form id="adminFormA" method="POST" enctype="multipart/form-data" action="{{ route('adminFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($user->id)){{ $user->id }}@endif">
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }}</label>
        <input class="ip" type="text" name="nome" value="@if(isset($user->nome)){{ $user->nome }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Email') }}</label>
        <input class="ip" type="email" name="email" value="@if(isset($user->email)){{ $user->email }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Type') }}</label>
        <select class="select2" name="tipo">
          <option value="" selected disabled></option>
          <option value="admin" @if(isset($user->tipo) && ($user->tipo=='admin')) selected @endif>{{ trans('backoffice.Administrator') }}</option>
          <option value="user" @if(isset($user->tipo) && ($user->tipo=='user')) selected @endif>{{ trans('backoffice.User') }}</option>
          <option value="visualizador" @if(isset($user->tipo) && ($user->tipo=='visualizador')) selected @endif>Visualizador</option>
        </select>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Language') }}</label>
        <select class="select2" name="lingua">
          <option value="" selected disabled></option>
          <option value="en" @if(isset($user->lingua) && $user->lingua=='en') selected @endif>{{ trans('backoffice.English') }}</option>
          <option value="pt" @if(isset($user->lingua) && $user->lingua=='pt') selected @endif>{{ trans('backoffice.Portuguese') }}</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Image') }}</label>
        <input type="hidden" id="img_antiga" name="img_antiga" value="@if(isset($user->avatar)){{ $user->avatar }}@endif">
        <div>
          <div class="div-50">
            <div class="div-50" id="imagem">
              @if(isset($user->avatar) && $user->avatar)<img src="/backoffice/img/admin/{{ $user->avatar }}" class="height-40 margin-top10">@else<label class="a-dotted-white" id="uploads">&nbsp;</label>@endif
            </div>
            <label for="selecao-arquivo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads');" accept="image/*">
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros();"><i class="fa fa-trash-alt"></i></label>          
        </div>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Additional') }}</label>
        <div class="clearfix height-10"></div>
        <input type="checkbox" name="estado" id="check1" value="1" @if(isset($user->estado) && ($user->estado=='bloqueado')) checked @endif @if($separador=='userNew') disabled @endif>
        <label for="check1"><span></span>{{ trans('backoffice.Blocked') }}</label>
      </div>
    </div>

    <!--<label class="lb">{ { trans('backoffice.Permissions') }}</label>
    @ foreach($admin_perm as $value)
      {! ! $value['check_html'] !!}
    @ endforeach-->
   
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
          <a href="{{ route('adminAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
  <script type="text/javascript">
    $('.select2').select2({'language':'{{ $idioma }}'});
  </script>
<script type="text/javascript">
  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
  }
  function limparFicheiros() {
    $('#selecao-arquivo').val('');
    $('#uploads').html('&nbsp;');
    $('#img_antiga').val('');
    $('#imagem').html('<label class="a-dotted-white" id="uploads">&nbsp;</label>');
  }
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
          limparFicheiros();
          if(resp.imagem){
            $('#img_antiga').val(resp.imagem);
            $('#imagem').html('<img src="/backoffice/img/admin/'+resp.imagem+'" class="height-40 margin-top10">');
          }
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