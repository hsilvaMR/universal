@extends('backoffice/layouts/default')

@section('content')
@if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allSlides') => route('webSlideAllPageB'), trans('backoffice.newSlide') => route('webSlideNewPageB') ]; ?>@else
<?php $arrayCrumbs = [ trans('backoffice.allSlides') => route('webSlideAllPageB'), trans('backoffice.editSlide') => route('webSlideEditPageB',['id'=>$obj->id]) ]; ?>@endif
@include('backoffice/includes/crumbs')

<div class="page-titulo">
  @if($funcao=='new'){{ trans('backoffice.newSlide') }}@else{{ trans('backoffice.editSlide') }}@endif</div>

<form id="webSlideFormB" method="POST" enctype="multipart/form-data" action="{{ route('webSlideFormB') }}">
  {{ csrf_field() }}
  <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
  <div class="row">
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Title') }} (PT)</label>
      <input class="ip" type="text" name="titulo_pt" value="@if(isset($obj->titulo_pt)){{ $obj->titulo_pt }}@endif">
      <label class="lb">{{ trans('backoffice.Text') }} (PT)</label>
      <textarea class="tx" name="texto_pt" rows="3">@if(isset($obj->texto_pt)){{ $obj->texto_pt }}@endif</textarea>
      <label class="lb">{{ trans('backoffice.buttonText') }} (PT)</label>
      <input class="ip" type="text" name="bt_texto_pt"
        value="@if(isset($obj->bt_texto_pt)){{ $obj->bt_texto_pt }}@endif">
    </div>
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Title') }} (EN)</label>
      <input class="ip" type="text" name="titulo_en" value="@if(isset($obj->titulo_en)){{ $obj->titulo_en }}@endif">
      <label class="lb">{{ trans('backoffice.Text') }} (EN)</label>
      <textarea class="tx" name="texto_en" rows="3">@if(isset($obj->texto_en)){{ $obj->texto_en }}@endif</textarea>
      <label class="lb">{{ trans('backoffice.buttonText') }} (EN)</label>
      <input class="ip" type="text" name="bt_texto_en"
        value="@if(isset($obj->bt_texto_en)){{ $obj->bt_texto_en }}@endif">
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Title') }} (ES)</label>
      <input class="ip" type="text" name="titulo_es" value="@if(isset($obj->titulo_es)){{ $obj->titulo_es }}@endif">
      <label class="lb">{{ trans('backoffice.Text') }} (ES)</label>
      <textarea class="tx" name="texto_es" rows="3">@if(isset($obj->texto_es)){{ $obj->texto_es }}@endif</textarea>
      <label class="lb">{{ trans('backoffice.buttonText') }} (ES)</label>
      <input class="ip" type="text" name="bt_texto_es"
        value="@if(isset($obj->bt_texto_es)){{ $obj->bt_texto_es }}@endif">
    </div>
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Title') }} (FR)</label>
      <input class="ip" type="text" name="titulo_fr" value="@if(isset($obj->titulo_fr)){{ $obj->titulo_fr }}@endif">
      <label class="lb">{{ trans('backoffice.Text') }} (FR)</label>
      <textarea class="tx" name="texto_fr" rows="3">@if(isset($obj->texto_fr)){{ $obj->texto_fr }}@endif</textarea>
      <label class="lb">{{ trans('backoffice.buttonText') }} (FR)</label>
      <input class="ip" type="text" name="bt_texto_fr"
        value="@if(isset($obj->bt_texto_fr)){{ $obj->bt_texto_fr }}@endif">
    </div>
  </div>
  <div class="row">
    {{--  ficheiro XS --}}
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.smallImage') }}</label>
      <input type="hidden" id="img_antiga_xs" name="img_antiga_xs"
        value="@if(isset($obj->img_xs)){{ $obj->img_xs }}@endif">
      <div>
        <div class="div-50">
          <div class="div-50" id="imagem_xs">
            @if(isset($obj->img_xs) && $obj->img_xs)<img src="{{ $obj->img_xs }}"
              class="height-40 margin-top10">@else<label class="a-dotted-white" id="uploads_xs">&nbsp;</label>@endif
          </div>
          <label for="selecao-arquivo_xs" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
          <input id="selecao-arquivo_xs" type="file" name="ficheiro_xs" onchange="lerFicheiros(this,'uploads_xs');"
            accept="image/*">
        </div>
        <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('_xs');"><i
            class="fas fa-trash-alt"></i></label>
      </div>
    </div>
    {{--  ficheiro XXL --}}
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Image') }}</label>
      <input type="hidden" id="img_antiga" name="img_antiga" value="@if(isset($obj->img)){{ $obj->img }}@endif">
      <div>
        <div class="div-50">
          <div class="div-50" id="imagem">
            @if(isset($obj->img) && $obj->img)<img src="{{ $obj->img }}" class="height-40 margin-top10">@else<label
              class="a-dotted-white" id="uploads">&nbsp;</label>@endif
          </div>
          <label for="selecao-arquivo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
          <input id="selecao-arquivo" type="file" name="ficheiro" onchange="lerFicheiros(this,'uploads');"
            accept="image/*">
        </div>
        <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('');"><i class="fas fa-trash-alt"></i></label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.backgroundColor') }}</label>
      <div class="div-50">
        <select id="sel_cor" class="select2" name="fd_cor" onchange="$('#fd_cor').css('background',this.value);">
          <option value="" selected disabled></option>
          @foreach($colors as $color)
          <option value="linear-gradient(135deg,{{ $color->gradiente_1 }},{{ $color->gradiente_2 }})" @if((isset($obj->
            fd_cor) && $obj->fd_cor=='linear-gradient(135deg,'.$color->gradiente_1.','.$color->gradiente_2.')'))
            selected @endif>{{ $color->nome }}</option>
          @endforeach
        </select>
      </div>
      <label id="fd_cor" class="lb-40 bt-branco float-right"></label>
    </div>
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Link') }}</label>
      <input class="ip" type="text" name="url" value="@if(isset($obj->url)){{ $obj->url }}@endif">
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <label class="lb">{{ trans('backoffice.Type') }}</label>
      <select class="select2" name="tipo">
        <option value="" selected disabled></option>
        <option value="img_texto" @if((isset($obj->tipo) && $obj->tipo=='img_texto')) selected
          @endif>{{ trans('backoffice.tipoSlideImgTexto') }}</option>
        <option value="img" @if((isset($obj->tipo) && $obj->tipo=='img')) selected
          @endif>{{ trans('backoffice.tipoSlideImg') }}</option>
      </select>
    </div>
    <div class="col-lg-3">
      <label class="lb">{{ trans('backoffice.Command') }}</label>
      <select class="select2" name="ordem">
        <option value="" selected disabled></option>
        @foreach($array_ordem as $val)
        <option value="{{ $val['valor'] }}" @if((isset($obj->ordem) && $obj->ordem == $val['valor'])) selected
          @endif>{{ $val['valor'] }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-lg-3">
      <label class="lb">{{ trans('backoffice.Additional') }}</label>
      <div class="clearfix height-10"></div>
      <input type="checkbox" name="online" id="check3" value="1" @if(isset($obj->online) && ($obj->online)) checked
      @endif>
      <label for="check3"><span></span>{{ trans('backoffice.Online') }}</label>
    </div>
  </div>
  <div class="clearfix height-20"></div>
  <div id="botoes">
    <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i>
      {{ trans('backoffice.Save') }}</button>
    <label class="width-10 height-40 float-right"></label>
    <a href="{{ route('webSlideAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i>
      {{ trans('backoffice.Cancel') }}</a>
  </div>
  <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}
  </div>
  <div class="clearfix"></div>
  <div class="height-20"></div>
  <label id="labelSucesso" class="av-100 av-verde display-none"><span
      id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times"
      onclick="$(this).parent().hide();"></i></label>
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
        <a href="{{ route('webSlideAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i>
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
<script
  src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js">
</script>
<script type="text/javascript">
  $('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#fd_cor').css('background',$('#sel_cor').val());
  });
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
  $('#webSlideFormB').on('submit',function(e) {
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
          limparFicheiros('_xs');
          if(resp.imagem){
            $('#img_antiga').val(resp.imagem);
            $('#imagem').html('<img src="'+resp.imagem+'" class="height-40 margin-top10">');
          }
          if(resp.imagem_xs){
            $('#img_antiga_xs').val(resp.imagem_xs);
            $('#imagem_xs').html('<img src="'+resp.imagem_xs+'" class="height-40 margin-top10">');
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