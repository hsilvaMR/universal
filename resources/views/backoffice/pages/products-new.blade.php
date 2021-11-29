@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.AllProducts') => route('productsAllPageB'), trans('backoffice.NewProduct') => route('productNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.AllProducts') => route('productsAllPageB'), trans('backoffice.EditProduct') => route('productEditPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.NewProduct') }}@else{{ trans('backoffice.EditProduct') }}@endif</div>

  <form id="productsFormB" method="POST" enctype="multipart/form-data" action="{{ route('formProdFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (PT)</label>
        <input class="ip" type="text" name="nome_pt" value="@if(isset($obj->nome_pt)){{ $obj->nome_pt }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (PT)</label>
        <textarea class="tx" name="descricao_pt" rows="4">@if(isset($obj->descricao_pt)){{ $obj->descricao_pt }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (EN)</label>
        <input class="ip" type="text" name="nome_en" value="@if(isset($obj->nome_en)){{ $obj->nome_en }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (EN)</label>
        <textarea class="tx" name="descricao_en" rows="4">@if(isset($obj->descricao_en)){{ $obj->descricao_en }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (ES)</label>
        <input class="ip" type="text" name="nome_es" value="@if(isset($obj->nome_es)){{ $obj->nome_es }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (ES)</label>
        <textarea class="tx" name="descricao_es" rows="4">@if(isset($obj->descricao_es)){{ $obj->descricao_es }}@endif</textarea>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }} (FR)</label>
        <input class="ip" type="text" name="nome_fr" value="@if(isset($obj->nome_fr)){{ $obj->nome_fr }}@endif">
        <label class="lb">{{ trans('backoffice.Description') }} (FR)</label>
        <textarea class="tx" name="descricao_fr" rows="4">@if(isset($obj->descricao_fr)){{ $obj->descricao_fr }}@endif</textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <label class="lb">{{ trans('backoffice.Price') }} (€)</label>
        <input class="ip" type="text" name="preco_unitario" value="@if(isset($obj->preco_unitario)){{ number_format($obj->preco_unitario, 2, '.', '') }}@endif">
      </div>
      <div class="col-lg-4">
        <label class="lb">{{ trans('backoffice.IVA') }} (%)</label>
        <input class="ip" type="text" name="iva" value="@if(isset($obj->iva)){{ $obj->iva }}@endif">
      </div>
      <div class="col-lg-4">
        <label class="lb">{{ trans('backoffice.Amount').' ('.trans('backoffice.box').')' }}</label>
        <input class="ip" type="text" name="qtd_caixa" value="@if(isset($obj->qtd_caixa)){{ $obj->qtd_caixa }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.PointsUser').' ('.trans('backoffice.unit').')' }}</label>
        <input class="ip" type="text" name="pontos" value="@if(isset($obj->pontos)){{ $obj->pontos }}@endif">
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
      <a href="{{ route('productsAllPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('productsAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
  $('#productsFormB').on('submit',function(e) {
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