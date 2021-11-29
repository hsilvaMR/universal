@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allGiveaways') => route('giveawaysAllPageB'), trans('backoffice.newGiveaway') => route('giveawaysNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allGiveaways') => route('giveawaysAllPageB'), trans('backoffice.editGiveaway') => route('giveawaysEditPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newGiveaway') }}@else{{ trans('backoffice.editGiveaway') }}@endif</div>

  <form id="giveawaysFormB" method="POST" enctype="multipart/form-data" action="{{ route('giveawaysFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Title') }} (PT)</label>
        <input class="ip" type="text" name="titulo_pt" value="@if(isset($obj->titulo_pt)){{ $obj->titulo_pt }}@endif">
        <label class="lb">{{ trans('backoffice.Award') }} (PT)</label>
        <textarea class="tx" name="premio_pt" rows="3">@if(isset($obj->premio_pt)){{ $obj->premio_pt }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Regulation') }} (PT)</label>
        <div class="height-10 width-30"></div>
        <textarea class="tx editorTexto" name="regulamento_pt" rows="10">@if(isset($obj->regulamento_pt)){{ $obj->regulamento_pt }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Title') }} (EN)</label>
        <input class="ip" type="text" name="titulo_en" value="@if(isset($obj->titulo_en)){{ $obj->titulo_en }}@endif">
        <label class="lb">{{ trans('backoffice.Award') }} (EN)</label>
        <textarea class="tx" name="premio_en" rows="3">@if(isset($obj->premio_en)){{ $obj->premio_en }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Regulation') }} (EN)</label>
        <div class="height-10 width-30"></div>
        <textarea class="tx editorTexto" name="regulamento_en" rows="10">@if(isset($obj->regulamento_en)){{ $obj->regulamento_en }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Title') }} (ES)</label>
        <input class="ip" type="text" name="titulo_es" value="@if(isset($obj->titulo_es)){{ $obj->titulo_es }}@endif">
        <label class="lb">{{ trans('backoffice.Award') }} (ES)</label>
        <textarea class="tx" name="premio_es" rows="3">@if(isset($obj->premio_es)){{ $obj->premio_es }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Regulation') }} (ES)</label>
        <div class="height-10 width-30"></div>
        <textarea class="tx editorTexto" name="regulamento_es" rows="10">@if(isset($obj->regulamento_es)){{ $obj->regulamento_es }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Title') }} (FR)</label>
        <input class="ip" type="text" name="titulo_fr" value="@if(isset($obj->titulo_fr)){{ $obj->titulo_fr }}@endif">
        <label class="lb">{{ trans('backoffice.Award') }} (FR)</label>
        <textarea class="tx" name="premio_fr" rows="3">@if(isset($obj->premio_fr)){{ $obj->premio_fr }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Regulation') }} (FR)</label>
        <div class="height-10 width-30"></div>
        <textarea class="tx editorTexto" name="regulamento_fr" rows="10">@if(isset($obj->regulamento_fr)){{ $obj->regulamento_fr }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.startDate') }}</label>
        <input class="ip" type="text" id="inicio" name="data_inicio" maxlength="10" value="@if(isset($obj->data_inicio)){{ date('Y-m-d',$obj->data_inicio) }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.endDate') }}</label>
        <input class="ip" type="text" id="fim" name="data_fim" maxlength="10" value="@if(isset($obj->data_fim)){{ date('Y-m-d',$obj->data_fim) }}@endif">
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
        <label class="lb">{{ trans('backoffice.featuredImage') }}</label>
        <input type="hidden" id="img_antiga" name="img_antiga" value="@if(isset($obj->imagem)){{ $obj->imagem }}@endif">
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
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.squareImage') }}</label>
        <input type="hidden" id="img_antiga_quadrada" name="img_antiga_quadrada" value="@if(isset($obj->img)){{ $obj->img }}@endif">
        <div>
          <div class="div-50">
            <div class="div-50" id="imagem_quadrada">
              @if(isset($obj->img) && $obj->img)<img src="{{ $obj->img }}" class="height-40 margin-top10">@else<label class="a-dotted-white" id="uploads_quadrada">&nbsp;</label>@endif
            </div>
            <label for="selecao-arquivo_quadrada" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo_quadrada" type="file" name="ficheiro_quadrada" onchange="lerFicheiros(this,'uploads_quadrada');" accept="image/*">
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('_quadrada');"><i class="fas fa-trash-alt"></i></label>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Status') }}</label>
        <select class="select2" name="estado">
          <option value="" selected disabled></option>
          <option value="ativo" @if((isset($obj->estado) && $obj->estado=='ativo')) selected @endif>{{ trans('backoffice.Active') }}</option>
          <option value="desativo" @if((isset($obj->estado) && $obj->estado=='desativo')) selected @endif>{{ trans('backoffice.Inactive') }}</option>
          <option value="ppqueijinho" @if((isset($obj->estado) && $obj->estado=='ppqueijinho')) selected @endif>{{ trans('backoffice.QfC') }}</option>
        </select>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.winnersType') }}</label>
        <select class="select2" name="tipo_vencedor">
          <option value="" selected disabled></option>
          <option value="nome" @if((isset($obj->tipo_vencedor) && $obj->tipo_vencedor=='nome')) selected @endif>{{ trans('backoffice.tipoWinner1') }}</option>
          <option value="nomes" @if((isset($obj->tipo_vencedor) && $obj->tipo_vencedor=='nomes')) selected @endif>{{ trans('backoffice.tipoWinner2') }}</option>
          <option value="nenhum" @if((isset($obj->tipo_vencedor) && $obj->tipo_vencedor=='nenhum')) selected @endif>{{ trans('backoffice.tipoWinner3') }}</option>
          <option value="muitos" @if((isset($obj->tipo_vencedor) && $obj->tipo_vencedor=='muitos')) selected @endif>{{ trans('backoffice.tipoWinner4') }}</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Winners') }}</label>
        <textarea class="tx" name="vencedor" rows="3">@if(isset($obj->vencedor)){{ $obj->vencedor }}@endif</textarea>
      </div>
    </div>
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('giveawaysAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('giveawaysAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
  $('#giveawaysFormB').on('submit',function(e) {
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
          if(resp.imagem_quadrada){
            $('#img_antiga_quadrada').val(resp.imagem_quadrada);
            $('#imagem_quadrada').html('<img src="'+resp.imagem_quadrada+'" class="height-40 margin-top10">');
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