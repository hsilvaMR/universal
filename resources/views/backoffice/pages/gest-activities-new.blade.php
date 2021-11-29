@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'),$process->referencia => route('processesPageB',$process->id_certificacao), trans('backoffice.allActivities') => route('activitiesPageB',$id_proc), trans('backoffice.newActivity') => route('activitiesNewPageB',$id_proc) ]; ?>@else
  <?php $arrayCrumbs = [ $cert->nome => route('certificationsPageB'),$process->referencia => route('processesPageB',$process->id_certificacao), trans('backoffice.allActivities') => route('activitiesPageB',$id_proc), $dados->referencia => route('activitiesEditPageB',['id_proc'=>$id_proc,'id'=>$dados->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newActivity') }}@else{{ trans('backoffice.editActivity') }}@endif</div>

  <form id="form" method="POST" enctype="multipart/form-data" action="{{ route('activitiesFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_processo" value="@if(isset($id_proc)){{ $id_proc }}@endif">
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
      <div class="col-lg-12">
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
      <a href="{{ route('activitiesPageB',$id_proc) }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('activitiesPageB',$id_proc) }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" data-dismiss="modal"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
@stop

@section('javascript')
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