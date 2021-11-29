@extends('backoffice/layouts/default-out')

@section('content')
<div class="container">
  <a href="{{ route('loginPageB') }}"><img class="login-logo" src="{{ asset('/backoffice/img/icons/logo.svg') }}"></a>

  <div class="page-titulo">{{ $documento->referencia }} - {{ $documento->nome }}</div>

  @if( $version->estado != 'aprovado')
    <span class="tag tag-vermelho tag_opacity">{{ trans('backoffice.DocumentInApproval') }}</span>
    
    <div class="row">
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.File') }} ({{ trans('backoffice.Current') }})</label>
        @if($documento->ficheiro != '')<a href="{{ $documento->ficheiro }}" download><input class="ip" value="{{ $documento->nome }}" disabled=""></a>
        @else
          <input class="ip" value="" disabled="">
        @endif
      </div>
      <div class="col-md-6">
        <label class="lb">{{ trans('backoffice.FileInApproval') }}</label>
        <a href="{{ $version->ficheiro }}" download><input class="ip" value="{{ $nome_ficheiro }}" disabled=""></a>
      </div>
    </div>

    <form id="aprovedDoc" method="POST" enctype="multipart/form-data" action="{{ route('versionsFormB') }}">
      {{ csrf_field() }}
      <input id="tipo_status" type="hidden" name="tipo_status" value="">
      <input type="hidden" name="id_versao" value="{{ $version->id }}">

      <label class="lb">{{ trans('backoffice.ReprovedAproved_txt') }}</label>


      @if(isset($version_aux))
        <br>
        @foreach($version_aux as $aux)
          <label class="margin-top10">
            <a class="tx-verde" href="/backoffice/gestao_documental/doc_aux/{{ $aux->ficheiro }}" download><i class="far fa-file"></i> {{ $aux->ficheiro }}</a>&emsp;
          </label>
        @endforeach
      @endif

      <div class="row">
        <div class="col-md-12">
          <div>
            <div class="div-50">
              <div class="div-50" id="doc">
                <label class="a-dotted-white" id="docs">&nbsp;</label>
              </div>
              <label for="selecao-docs" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
              <input id="selecao-docs" type="file" name="doc[]" onchange="lerFicheiros(this,'docs');" multiple>
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('doc');"><i class="fa fa-trash-alt"></i></label>          
          </div>
        </div>
      </div>

      <textarea class="tx" name="nota" rows="3">{{ $version->nota }}</textarea>
      

      <div>
        <button class="bt bt-vermelho float-right margin-left10" type="button" onclick="aprovarDoc('reprovado');"><i class="fas fa-times"></i> {{ trans('backoffice.Disapprove') }}</button>
        <button class="bt bt-verde float-right" type="button" onclick="aprovarDoc('aprovado');"><i class="fas fa-check"></i> {{ trans('backoffice.Approve') }}</button>
      </div>
    </form>
  @else
    <span class="tag tag-verde tag_opacity">{{ trans('backoffice.ApprovedDocument') }}</span>

    <div>
      <label class="lb">{{ trans('backoffice.File') }} ({{ trans('backoffice.Current') }})</label>
      <a href="{{ $documento->ficheiro }}" download><input class="ip" value="{{ $documento->nome }}" disabled=""></a>
    </div>
  @endif


</div>

<div class="slogan_version">
  <img height="25" src="{{ asset('site_v2/img/emails/slogan.png') }}">
  <div class="margin-top20">
    <a href="https://www.facebook.com/queijouniversal" target="_blank"><img height="20" width="20" src="{{ asset('site_v2/img/emails/fb.png') }}"></a>
    &ensp;
    <a href="https://www.instagram.com/universal_queijo" target="_blank"><img height="20" width="20" src="{{ asset('site_v2/img/emails/instagram.png') }}"></a>
  </div>
</div>



<!--Modal Aprovation-->
<div class="modal fade" id="myModalAproved" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <input type="hidden" name="id_modal_aproved" id="id_modal_aproved">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel"> {{ trans('backoffice.ApprovalDocument') }}</h4></div>
        <div class="modal-body"><p>{!! trans('backoffice.ApprovalDocument_txt') !!}</p></div>
        <div class="modal-footer">
          <a onclick="$('#myModalAproved').modal('toggle');" class="abt bt-cinza"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>&nbsp;
            <a class="abt bt-verde" onclick="$('#aprovedDoc').submit();$('#myModalAproved').modal('hide');"><i class="fas fa-check"></i> {{ trans('backoffice.Approve') }}</a>
        </div>
      </div>
  </div>
</div>

<!--Modal Disapprove-->
<div class="modal fade" id="myModalRep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <input type="hidden" name="id_modal_aproved" id="id_modal_aproved">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel"> {{ trans('backoffice.DocumentDisapproval') }}</h4></div>
        <div class="modal-body"><p>{!! trans('backoffice.DocumentDisapproval_txt') !!}</p></div>
        <div class="modal-footer">
          <a onclick="$('#myModalRep').modal('toggle');" class="abt bt-cinza"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</a>&nbsp;
            <a class="abt bt-verde" onclick="$('#aprovedDoc').submit();$('#myModalRep').modal('hide');"><i class="fas fa-check"></i> {{ trans('backoffice.Disapprove') }}</a>
        </div>
      </div>
  </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">

  function aprovarDoc(tipo){

    if (tipo == 'reprovado') {
      $('#myModalRep').modal('show');
    }

    if (tipo == 'aprovado') {
      $('#myModalAproved').modal('show');
    }

    $('#tipo_status').val(tipo);
  }

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


  $('#aprovedDoc').on('submit',function(e) {
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
          $('#loading').hide();
          $('#botoes').show();
          location.reload(true);
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