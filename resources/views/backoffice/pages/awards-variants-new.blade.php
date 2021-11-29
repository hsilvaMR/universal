@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allVariants') => route('awardsVariantsAllPageB'), trans('backoffice.newVariant') => route('newVariantsPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allVariants') => route('awardsVariantsAllPageB'), trans('backoffice.editVariant') => route('editVariantsPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newVariant') }}@else{{ trans('backoffice.editVariant') }}@endif</div>

  <form id="awardsVariantFormB" method="POST" enctype="multipart/form-data" action="{{ route('awardsVariantFormB') }}">
    {{ csrf_field() }}

    <input type="hidden" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
      <div class="col-md-3">
        <label class="lb">{{ trans('backoffice.Variant') }} (PT)</label>
        <input class="ip" type="text" name="variante_pt" value="@if(isset($obj->variante_pt)){{ $obj->variante_pt }}@endif">
      </div>
      <div class="col-md-3">
        <label class="lb">{{ trans('backoffice.Variant') }} (EN)</label>
        <input class="ip" type="text" name="variante_en" value="@if(isset($obj->variante_en)){{ $obj->variante_en }}@endif">
      </div>
      <div class="col-md-3">
        <label class="lb">{{ trans('backoffice.Variant') }} (ES)</label>
        <input class="ip" type="text" name="variante_es" value="@if(isset($obj->variante_es)){{ $obj->variante_es }}@endif">
      </div>
      <div class="col-md-3">
        <label class="lb">{{ trans('backoffice.Variant') }} (FR)</label>
        <input class="ip" type="text" name="variante_fr" value="@if(isset($obj->variante_fr)){{ $obj->variante_fr }}@endif">
      </div>      
    </div>
    
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('awardsVariantsAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
<script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>


<script type="text/javascript">
  
  $('#awardsVariantFormB').on('submit',function(e) {
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
        console.log(resposta);
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
          $('#id').val(resp.id);
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
          document.getElementById('awardsVariantFormB').reset();
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