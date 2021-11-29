@extends('backoffice/layouts/default')

@section('content')

  <?php $arrayCrumbs = [ trans('backoffice.AllLabels') => route('labelsPageB'), trans('backoffice.GenerateCodes') => route('generateCodesPageB')  ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">
    {{ trans('backoffice.GenerateCodes') }}
  </div>

  <form id="codesFormB" method="POST" enctype="multipart/form-data" action="{{ route('generateCodesFormB') }}">
    {{ csrf_field() }}

    <label class="lb">{{ trans('backoffice.HowManyCodeGenerate_txt') }}</label>
    <input class="ip" type="text" name="quantidade" value="">

    <label class="lb">{{ trans('backoffice.PortionOfCheese') }}</label>

    <select class="select2" name="produto">
      <option selected disabled>{{ trans('backoffice.SelectedProduct') }}</option>
      @foreach($array as $val)
        <option @if(isset($obj->porcao_queijo) && ($obj->porcao_queijo == $val['nome_pt'])) selected value="{{ $val['nome_pt'] }}" @endif value="{{ $val['nome_pt'] }}">{{ $val['nome_pt'] }}</option>
      @endforeach
    </select>

    
    <div class="clearfix height-20"></div>
    <div id="botoes">
      <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      <label class="width-10 height-40 float-right"></label>
      <a href="{{ route('labelsPageB') }}" class="abt bt-vermelho float-right"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{ json_decode(\Cookie::get('admin_cookie'))->lingua }}.js"></script>
<script type="text/javascript"> $('.select2').select2(); </script>

<script type="text/javascript">
  $('#codesFormB').on('submit',function(e) {
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
        

        //var arrStr = encodeURIComponent(JSON.stringify(resposta));
        //window.location.href = '/export-codes/'+arrStr;


        resp = $.parseJSON(resposta);
        if(resp.estado=='sucesso'){
          if(resp.reload && !resp.erro){ location.reload(); }
          else{
            $('#loading').hide();
            $('#botoes').show();
            if(resp.erro){
              $("#spanErro").html(resp.mensagem);
              $("#labelErros").show();
              $('#loading').hide();
            }else{
              
              $("#labelSucesso").show();
            }            
          }

          var codigos='Codigo';

          for (var i = 0; i < resp.array.length; i++) {
            var var_codigo = resp.array[i];
            codigos+=','+var_codigo['codigo']; 
          }
        
          window.location.href = '/admin/export-codes/'+codigos;

        }else
          if(resposta){
            $("#spanErro").html(resp.mensagem);
            $("#labelErros").show();
            $('#loading').hide();
            $('#botoes').show();
          }
      });
    });
</script>
@stop