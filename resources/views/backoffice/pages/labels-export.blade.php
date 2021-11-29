@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.AllLabels') => route('labelsPageB'), trans('backoffice.ExportLabels') => route('labelsExportPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.ExportLabels') }}</div>

  <form id="labelsExportFormB" method="POST" enctype="multipart/form-data" action="{{ route('labelsExportFormB') }}">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Firstid').' (#)' }}</label>
        <input class="ip" type="text" name="primeiro" id="primeiro" value="">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Lastid').' (#)' }}</label>
        <input class="ip" type="text" name="ultimo" id="ultimo" value="">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <label class="lb">{{ trans('backoffice.Serie') }}</label>
        <div class="clearfix height-10"></div>
        @foreach($series as $val)
          <input type="checkbox" name="series[]" id="checkS{{ $val->serie }}" value="{{ $val->serie }}">
          <label for="checkS{{ $val->serie }}"><span></span>{{ $val->serie }}</label>
          <label class="width-30"></label>
        @endforeach
      </div>
    </div>

    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-azul float-right" type="button" onclick="exportar();"><i class="fas fa-check"></i> {{ trans('backoffice.Export') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('labelsPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
    <div class="clearfix"></div>
    <div class="height-20"></div>
    <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>    
  </form>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Saved') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('labelsPageB') }}" class="abt bt-verde"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
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
function exportar(){
  var primeiro = $('#primeiro').val();
  var ultimo = $('#ultimo').val();
  var check = document.getElementsByName("series[]"); 
  var aux='';
  for(var i=0;i<check.length;i++){ if(check[i].checked == true){ aux='sim'; }}

  if(primeiro || ultimo || aux){
    $('#loading').show();
    $('#botoes').hide();
    $('#labelsExportFormB').submit();
    setTimeout(function(){ $('#loading').hide(); $('#botoes').show(); },2000);
  }
}
</script>
@stop