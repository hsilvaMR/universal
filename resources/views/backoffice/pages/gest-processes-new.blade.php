@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'), trans('backoffice.allProcesses') => route('processesPageB',$id_cert), trans('backoffice.newProcess') => route('processesNewPageB',$id_cert) ]; ?>@else
  <?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'), trans('backoffice.allProcesses') => route('processesPageB',$id_cert), $dados->referencia => route('processesEditPageB',['id_cert'=>$id_cert,'id'=>$dados->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newProcess') }}@else{{ trans('backoffice.editProcess') }}@endif</div>

  <form id="form" method="POST" enctype="multipart/form-data" action="{{ route('processesFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_certificacao" value="@if(isset($id_cert)){{ $id_cert }}@endif">
    <input type="hidden" id="id" name="id" value="@if(isset($dados->id)){{ $dados->id }}@endif">
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Reference') }}</label>
        <input class="ip" type="text" name="referencia" value="@if(isset($dados->referencia)){{ $dados->referencia }}@endif">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Name') }}</label>
        <input class="ip" type="text" name="nome" value="@if(isset($dados->nome)){{ $dados->nome }}@endif">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Diagram') }}</label>
        <select class="select2" name="id_documento">
          <option value="" selected>&nbsp;</option>
            @foreach($documentos as $val)
              <option @if(isset($dados->id_documento) && ($dados->id_documento == $val->id)) selected @endif value="{{ $val->id }}">{{ $val->referencia.' - '.$val->nome }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Additional') }}</label>
        <div class="clearfix height-10"></div>
        <input type="checkbox" name="online" id="checkOn" value="1" @if(isset($dados->online) && ($dados->online)) checked @endif>
        <label for="checkOn"><span></span>{{ trans('backoffice.Online') }}</label>
      </div>
    </div>

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('processesPageB',$id_cert) }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('processesPageB',$id_cert) }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
  $('#form').on('submit',function(e) {
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
@stop