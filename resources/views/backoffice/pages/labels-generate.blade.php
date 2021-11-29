@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.AllLabels') => route('labelsPageB'), trans('backoffice.GenerateLabels') => route('labelsGeneratePageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.GenerateLabels') }}</div>

  <form id="labelsGenerateFormB" method="POST" enctype="multipart/form-data" action="{{ route('labelsGenerateFormB') }}">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Quantity') }}</label>
        <input class="ip" type="text" name="quantidade" value="">
      </div>
      <div class="col-lg-6">
        <label class="lb">{{ trans('backoffice.Serie') }}</label>
        <input class="ip" type="text" name="serie" value="">
      </div>
    </div>
    
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
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
  $('#labelsGenerateFormB').on('submit',function(e) {
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
            console.log(resposta);
          $('#myModalSave').modal('show');
          //$('#id').val(resp.id);
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }else if(resposta){
            console.log(resposta);
          $("#spanErro").html(resposta);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
      })
      // UPDATE
      .fail(function(resposta) {
    console.log(resposta);
  })
  // END UPDATE 
    });
</script>
@stop