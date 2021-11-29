@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allQuestions') => route('cheeseQuestionsAllPageB'), trans('backoffice.newQuestion') => route('cheeseQuestionsNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allQuestions') => route('cheeseQuestionsAllPageB'), trans('backoffice.editQuestion') => route('cheeseQuestionsEditPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newQuestion') }}@else{{ trans('backoffice.editQuestion') }}@endif</div>

  <form id="cheeseQuestionsFormB" method="POST" enctype="multipart/form-data" action="{{ route('cheeseQuestionsFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Image') }}</label>
        <input type="hidden" id="img_antiga" name="img_antiga" value="@if(isset($obj->img)){{ $obj->img }}@endif">
        <div>
          <div class="div-50">
            <div class="div-50" id="imagem">
              @if(isset($obj->imagem) && $obj->imagem)<img src="{{ $obj->imagem }}" class="height-40 margin-top10">@else<label class="a-dotted-white" id="uploads">&nbsp;</label>@endif
            </div>
            <label for="selecao-arquivo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads');" accept="image/*">
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('');"><i class="fas fa-trash-alt"></i></label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Answer') }} (PT)</label>
        <input class="ip" type="text" name="resposta_pt" value="@if(isset($obj->resposta_pt)){{ $obj->resposta_pt }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Answer') }} (EN)</label>
        <input class="ip" type="text" name="resposta_en" value="@if(isset($obj->resposta_en)){{ $obj->resposta_en }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Answer') }} (ES)</label>
        <input class="ip" type="text" name="resposta_es" value="@if(isset($obj->resposta_es)){{ $obj->resposta_es }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Answer') }} (FR)</label>
        <input class="ip" type="text" name="resposta_fr" value="@if(isset($obj->resposta_fr)){{ $obj->resposta_fr }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Facebook') }}</label>
        <input class="ip" type="text" name="link_fb" value="@if(isset($obj->link_fb)){{ $obj->link_fb }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Instagram') }}</label>
        <input class="ip" type="text" name="link_insta" value="@if(isset($obj->link_insta)){{ $obj->link_insta }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.datePublicationQuestion') }}</label>
        <input class="ip" type="text" id="inicio" name="publicacao_pergunta" maxlength="10" value="@if(isset($obj->publicacao_pergunta)){{ date('Y-m-d',$obj->publicacao_pergunta) }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.datePublicationAnswer') }}</label>
        <input class="ip" type="text" id="fim" name="publicacao_resposta" maxlength="10" value="@if(isset($obj->publicacao_resposta)){{ date('Y-m-d',$obj->publicacao_resposta) }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Additional') }}</label>
        <div class="clearfix height-10"></div>
        <input type="checkbox" name="online" id="checkOn" value="1" @if(isset($obj->online) && ($obj->online)) checked @endif>
        <label for="checkOn"><span></span>{{ trans('backoffice.Online') }}</label>
      </div>
    </div>

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('cheeseQuestionsAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('cheeseQuestionsAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.css') }}">
@stop

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
<script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>

<script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
  $('#inicio').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
  $('#fim').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
</script>

<script type="text/javascript">
  function lerFicheiros(input,id) {
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
  }
  function limparFicheiros(id) {
    $('#selecao-arquivo'+id).val('');
    $('#uploads'+id).html('&nbsp;');
    $('#img_antiga'+id).val('');
    $('#imagem'+id).html('<label class="a-dotted-white" id="uploads'+id+'">&nbsp;</label>');
  }
  $('#cheeseQuestionsFormB').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      //var editorContent = tinyMCE.get('message').getContent();
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
          //$('#myModalSave').modal('show');
          $('#id').val(resp.id);
          limparFicheiros('');
          if(resp.imagem){
            $('#img_antiga').val(resp.imagem);
            $('#imagem').html('<img src="'+resp.imagem+'" class="height-40 margin-top10">');
          }
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }else if(resposta){
          $("#spanErro").html(resposta);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
      });
    });
</script>
<script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backoffice/vendor/tinymce/tm.tinymce.js') }}"></script>
@stop