@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allAwards') => route('cheeseAwardsAllPageB'), trans('backoffice.newAward') => route('cheeseAwardsNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allAwards') => route('cheeseAwardsAllPageB'), trans('backoffice.editAward') => route('cheeseAwardsEditPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newAward') }}@else{{ trans('backoffice.editAward') }}@endif</div>

  <form id="cheeseAwardsFormB" method="POST" enctype="multipart/form-data" action="{{ route('cheeseAwardsFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Award') }} (PT)</label>
        <textarea class="tx" name="premio_pt" rows="2">@if(isset($obj->premio_pt)){{ $obj->premio_pt }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Description') }} (PT)</label>
        <textarea class="tx" name="desc_pt" rows="2">@if(isset($obj->desc_pt)){{ $obj->desc_pt }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Award') }} (EN)</label>
        <textarea class="tx" name="premio_en" rows="2">@if(isset($obj->premio_en)){{ $obj->premio_en }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Description') }} (EN)</label>
        <textarea class="tx" name="desc_en" rows="2">@if(isset($obj->desc_en)){{ $obj->desc_en }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Award') }} (ES)</label>
        <textarea class="tx" name="premio_es" rows="2">@if(isset($obj->premio_es)){{ $obj->premio_es }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Description') }} (ES)</label>
        <textarea class="tx" name="desc_es" rows="2">@if(isset($obj->desc_es)){{ $obj->desc_es }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Award') }} (FR)</label>
        <textarea class="tx" name="premio_fr" rows="2">@if(isset($obj->premio_fr)){{ $obj->premio_fr }}@endif</textarea>
        <label class="lb">{{ trans('backoffice.Description') }} (FR)</label>
        <textarea class="tx" name="desc_fr" rows="2">@if(isset($obj->desc_fr)){{ $obj->desc_fr }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.publicationDate') }}</label>
        <input class="ip" type="text" id="publicacao" name="data_publicacao" maxlength="10" value="@if(isset($obj->data_publicacao)){{ date('Y-m-d',$obj->data_publicacao) }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Image') }}</label>
        <input type="hidden" id="img_antiga" name="img_antiga" value="@if(isset($obj->img)){{ $obj->img }}@endif">
        <div>
          <div class="div-50">
            <div class="div-50" id="imagem">
              @if(isset($obj->img) && $obj->img)<img src="{{ $obj->img }}" class="height-40 margin-top10">@else<label class="a-dotted-white" id="uploads">&nbsp;</label>@endif
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
        <label class="lb">{{ trans('backoffice.Tag') }}</label>
        <select class="select2" name="tag">
          <option value="" selected disabled></option>
          <option value="1_premio" @if((isset($obj->tag) && $obj->tag=='1_premio')) selected @endif>{{ trans('backoffice.1award') }}</option>
          <option value="2_premio" @if((isset($obj->tag) && $obj->tag=='2_premio')) selected @endif>{{ trans('backoffice.2award') }}</option>
          <option value="3_premio" @if((isset($obj->tag) && $obj->tag=='3_premio')) selected @endif>{{ trans('backoffice.3award') }}</option>
          <option value="4_premio" @if((isset($obj->tag) && $obj->tag=='4_premio')) selected @endif>{{ trans('backoffice.4award') }}</option>
          <option value="5_premio" @if((isset($obj->tag) && $obj->tag=='5_premio')) selected @endif>{{ trans('backoffice.5award') }}</option>
        </select>
      </div>
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
      <a href="{{ route('cheeseAwardsAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('cheeseAwardsAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
  $('#publicacao').datepicker({
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
  $('#cheeseAwardsFormB').on('submit',function(e) {
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