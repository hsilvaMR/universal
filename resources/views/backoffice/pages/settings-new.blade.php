@extends('backoffice/layouts/default')

@section('content')
  
  <?php $arrayCrumbs = [ trans('backoffice.AllSettings') => route('settingsPageB'), trans('backoffice.EditSetting') => route('settingsEditPageB',$obj->id) ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.EditSetting') }}</div>
  
  <form id="configFormB" method="POST" enctype="multipart/form-data" action="{{ route('settingsFormB') }}">
    {{ csrf_field() }}

    <input type="hidden" name="id" value="{{ $obj->id }}">
    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.Description') }}</label>
        <input class="ip" type="text" name="tag" value="{{ $obj->tag }}" disabled>
      </div>
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.Value') }}</label>
        <input class="ip" type="text" name="valor" value="{{ $obj->valor }}">
      </div>
    </div>
    
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('settingsPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
    </div>
    <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/en.js"></script>
  <script type="text/javascript">
    $('.select2').select2();
  </script>
  <script type="text/javascript">
    $('#configFormB').on('submit',function(e) {
      var form = $('#configFormB');
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta) {
        console.log(resposta);
        if(resposta=='sucesso'){
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();            
        }
      });
    });
  </script>
@stop