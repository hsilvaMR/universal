@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'), trans('backoffice.newUser') => route('usersNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'),trans('backoffice.editOrder') => route('ordersEditTotalPageB',['id'=>$obj->id])]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newUser') }}@else{{ trans('backoffice.editOrder') }}@endif</div>
  
  <form id="ordersTotalFormB" method="POST" enctype="multipart/form-data" action="{{ route('ordersTotalFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{ $obj->id }}">
    <label class="lb">{{ trans('backoffice.Reference') }}</label>
    <div>
      <input class="ip" value="{{ $obj->referencia }}" disabled>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.SubTotal') }}</label>
        <input class="ip" value="{{ $obj->subtotal }} €" disabled>
      </div>
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.Total') }}</label>
        <input class="ip" value="{{ $obj->total }} €" disabled>
      </div>
      
    </div>

    <label class="lb">{{ trans('backoffice.OrderDocument') }}</label>
    <div class="row">
      <div class="col-md-3">
        <input class="ip" value="{{ date('Y-m-d',$obj->data) }}" disabled>
      </div>
      <div class="col-md-9">
        <a href="/doc/orders/{{ $obj->documento }}" download><input class="ip"  value="{{ $obj->documento }}" disabled></a> 
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Invoice') }}</label>
        <input type="hidden" id="fatura_antiga" name="fatura_antiga" value="@if(isset($obj->fatura)){{ $obj->fatura }}@endif">
        <div>
          <div class="div-50">
            <div class="row">
              <div class="col-lg-4">
                <input class="ip" value="@if(isset($obj->data_fatura) && ($obj->data_fatura != 0)){{ date('Y-m-d',$obj->data_fatura) }}@endif" disabled>
              </div>
              <div class="col-lg-8">
                <div class="div-50" id="fatura">
                  @if(isset($obj->fatura))<a href="/doc/orders/{{ $obj->fatura }}" target="_blank" class="a-dotted-white" id="fatura_upload" download>{{ $obj->fatura }}</a>@else<label class="a-dotted-white" id="fatura_upload">&nbsp;</label>@endif
                </div>
                <label for="selecao-fatura" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
                <input id="selecao-fatura" type="file" name="fatura" onchange="lerFicheiros(this,'fatura_upload');">
              </div>
            </div>
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('fatura');"><i class="fa fa-trash-alt"></i></label>          
        </div>
        <label class="lb">{{ trans('backoffice.Receipt') }}</label>
        <input type="hidden" id="recibo_antiga" name="recibo_antiga" value="@if(isset($obj->recibo)){{ $obj->recibo }}@endif">
        <div>
          <div class="div-50">
            <div class="row">
              <div class="col-lg-4">
                <input class="ip" value="@if(isset($obj->data_recibo) && ($obj->data_recibo != 0)){{ date('Y-m-d',$obj->data_recibo) }}@endif" disabled>
              </div>
              <div class="col-lg-8">
                <div class="div-50" id="recibo">
                  @if(isset($obj->recibo))<a href="/doc/orders/{{ $obj->recibo }}" target="_blank" class="a-dotted-white" id="recibo_upload" download>{{ $obj->recibo }}</a>@else<label class="a-dotted-white" id="recibo_upload">&nbsp;</label>@endif
                </div>
                <label for="selecao-recibo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
                <input id="selecao-recibo" type="file" name="recibo" onchange="lerFicheiros(this,'recibo_upload');">
              </div>
            </div>
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('recibo');"><i class="fa fa-trash-alt"></i></label>          
        </div>
        <label class="lb">{{ trans('backoffice.Proof') }}</label>
        <input type="hidden" id="comprovativo_antiga" name="comprovativo_antiga" value="@if(isset($obj->comprovativo)){{ $obj->comprovativo }}@endif">
        <div>
          <div class="div-50">
            <div class="row">
              <div class="col-lg-4">
                <input class="ip" value="@if(isset($obj->data_comprovativo) && ($obj->data_comprovativo != 0)){{ date('Y-m-d',$obj->data_comprovativo) }}@endif" disabled>
              </div>
              <div class="col-lg-8">
                <div class="div-50" id="comprovativo">
                  @if(isset($obj->comprovativo))<a href="/doc/orders/{{ $obj->comprovativo }}" target="_blank" class="a-dotted-white" id="comprovativo_upload" download>{{ $obj->comprovativo }}</a>@else<label class="a-dotted-white" id="comprovativo_upload">&nbsp;</label>@endif
                </div>
                <label for="selecao-comprovativo" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
                <input id="selecao-comprovativo" type="file" name="comprovativo" onchange="lerFicheiros(this,'comprovativo_upload');">
              </div>
            </div>
          </div>
          <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('comprovativo');"><i class="fa fa-trash-alt"></i></label>          
        </div>
      </div>
    </div>

    <label class="lb">{{ trans('backoffice.Status') }}</label>
    <select name="estado">
      <option @if($obj->estado == 'concluida') selected @endif value="concluida">{{ trans('backoffice.Completed') }}</option>
      <option @if($obj->estado == 'em_processamento') selected @endif value="em_processamento">{{ trans('backoffice.In_processing') }}</option>
      <option @if($obj->estado == 'expedida_parcialmente') selected @endif value="expedida_parcialmente">{{ trans('backoffice.partially_dispatched') }}</option>
      <option @if($obj->estado == 'expedida') selected @endif value="expedida">{{ trans('backoffice.Dispatched') }}</option>
      <option @if($obj->estado == 'registada') selected @endif value="registada">{{ trans('backoffice.Registered') }}</option>
      <option @if($obj->estado == 'fatura_vencida') selected @endif value="fatura_vencida">{{ trans('backoffice.fatura_vencida') }}</option>
    </select>
    
   
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
    </div>
    <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
  </form>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabelS">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabelS">{{ trans('backoffice.Saved') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('ordersAllPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" onclick="location.reload();"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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
  <script type="text/javascript">$('.select2').select2();</script>

  <script type="text/javascript">
    $('#ordersTotalFormB').on('submit',function(e) {
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
        console.log(resposta);
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
          if(resp.reload){ $('#myModalSave').modal('show'); }
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
    
    function lerFicheiros(input,id) {
      var quantidade = input.files.length;
      var nome = input.value;

      if(quantidade==1){$('#'+id).html(nome);}
      else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
    }

    function limparFicheiros(id) {
      $('#selecao-'+id).val('');
      $('#'+id+'_upload').html('&nbsp;');
      $('#'+id+'_antiga').val('');
      $('#'+id).html('<label class="a-dotted-white" id="'+id+'_upload">&nbsp;</label>');
    }
  </script>
@stop