@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allCompanies') => route('awardsCompanyPageB'),trans('backoffice.allAwards') => route('awardsCompanyAllPageB',$id), trans('backoffice.AwardAcquisition') => route('awardsCompanyNewPageB',$id) ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allCompanies') => route('awardsCompanyPageB'),trans('backoffice.allAwards') => route('awardsCompanyAllPageB',$obj->id_empresa), trans('backoffice.editAward') => route('awardsEditPageB',['id'=>$obj->id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.AwardAcquisition') }}@else{{ trans('backoffice.editAward') }}@endif</div>

  <form id="awardsCompanyFormB" method="POST" enctype="multipart/form-data" action="{{ route('formAwardsCompanyFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" id="id" name="id" value="@if(isset($obj->id)){{ $obj->id }}@endif">
    <input type="hidden" id="id_empresa" name="id_empresa" value="@if(isset($obj->id_empresa)){{ $obj->id_empresa }}@else{!! $id !!}@endif">
     
    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.PremiumAvailable') }}</label>
        <select id="select" class="" name="id_premio" onclick="showProductNew();">
          <option value="0">Prémios</option>
          @foreach($premios as $val)
            <option value="{{ $val->id }}" @if(isset($obj->id) && $obj->id_premio == $val->id) selected @endif>{{ $val->nome_pt }} ({{ $val->valor_empresa }} Pontos) </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
       
        <label class="lb">{{ trans('backoffice.Variant') }}</label>
          
        @foreach($nome_variante as $nome)
          <select id="variante_{{ $nome['id'] }}" class="display-none" name="variante[]"></select>
        @endforeach

        @if(isset($premio_var))
          <div id="variante_premio">
            @foreach($premio_var as $premio)
              <select name="variante[]">
                <option>{{ $premio['variante'] }}</option>
                <option value="{{ $premio['valor'] }}" @if(isset($obj->variante) && ($premio['valor'] == $obj->variante)) selected @endif>{{ $premio['valor'] }}</option>
              </select>
            @endforeach
          </div>
        @else
          <select id="variante">
            <option>{{ trans('backoffice.WithoutVariant') }}</option>
          </select>
        @endif

      </div>
      <div class="col-md-3">
        <label class="lb">{{ trans('backoffice.Amount') }}</label>
        <input class="ip" type="text" name="quantidade" value="@if(isset($obj->quantidade)){{ $obj->quantidade }}@endif">
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.DateOfRequest') }}</label>
        <input class="ip" type="text" id="data_pedido" name="data_pedido" maxlength="10" value="@if(isset($obj->data_pedido)){{ date('Y-m-d',$obj->data_pedido) }}@endif">
      </div>
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.SendDate') }}</label>
        <input class="ip" type="text" id="data_envio" name="data_envio" maxlength="10" value="@if(isset($obj->data_envio) && ($obj->data_envio!=0)){{ date('Y-m-d',$obj->data_envio) }}@endif">
      </div>
      <div class="col-md-4">
        <label class="lb">{{ trans('backoffice.Status') }}</label>
        <select class="select2" name="estado">
          <option value="em_processamento" @if(isset($obj->estado) && ($obj->estado == 'em_processamento')) selected @endif>{{ trans('backoffice.in_processing') }}</option>
          <option value="enviado" @if(isset($obj->estado) && ($obj->estado == 'enviado')) selected @endif>{{ trans('backoffice.sent') }}</option>
        </select>
      </div>
    </div>
    
 

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('awardsCompanyAllPageB',$id) }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
          <a href="{{ route('awardsCompanyAllPageB',$id) }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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

<script>
  function showProductNew(){

    var select = document.getElementById("select");
    var selectValue = select.options[select.selectedIndex].value;

    $('#select_variante').html('');
    var html_select = '';
    var html = 'Variante';

    if (selectValue != 0) {
      $('#variante').css('display','none');
      $('#variante_premio').css('display','none');

      @foreach($nome_variante as $nome)
        $('#variante_'+{!! $nome['id'] !!}).html('');
      @endforeach

      @foreach($nome_variante as $nome)
        $('#variante_'+{!! $nome['id'] !!}).css('display','block');
        $('#variante_'+{!! $nome['id'] !!}).append('<option disabled>{!! $nome['valor'] !!}</option>');
        @foreach($variantes as $var)
          if({!! $var['id_premio'] !!} == selectValue){
            
            if ({!! $var['id_variante'] !!} == {!! $nome['id'] !!}) {
              if ('{!! $var['valor'] !!}' != '{!! $nome['valor'] !!}') {
                $('#variante_'+{!! $nome['id'] !!}).append('<option>{!! $var['valor'] !!}</option>');
              }
              
            }
            
          }
          /*else{
            console.log(2);
            $('#variante_'+{ !! $nome['id'] !!}).css('display','none');
            $('#variante').css('display','block');
          }*/
        @endforeach
      @endforeach
    }
  }
</script>

<script type="text/javascript">
  $('#data_envio').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });

    $('#data_pedido').datepicker({
      //format:'yyyy-mm-dd',
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });   
</script>

<script type="text/javascript">
  $('#awardsCompanyFormB').on('submit',function(e) {
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