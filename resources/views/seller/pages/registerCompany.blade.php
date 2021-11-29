@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid" style="background-color:#eeeeee;height:100%;">
    
    @include('seller/includes/headerSubMenu')

    
      <div class="row">
        <div class="col-lg-3">
            @include('seller/includes/menu')
        </div>
        <div class="col-lg-9">
        </div>
      </div>
     
    <!--@if($empresa->estado == 'aprovado')
      <h3 class="margin-top50 text-center tx-azul">{{ trans('site.accountRegister') }}</h3>
      <h5 class="text-center tx-azul">{{ trans('site.Complete_registration_approval') }}</h5>
    @elseif($empresa->estado == 'reprovado')
    <h3 class="margin-top50 text-center tx-azul">{{ trans('site.accountDisapproved') }}</h3>
      <h5 class="text-center tx-azul"> Informação de reprovamento {{ $empresa->obs }}</h5>
    @endif

    <div class="area-comercial">
      <div class="cart-tit"><h3>{{ trans('site.Company_data') }}</h3></div>

      <form id="form-logotipo" method="POST" enctype="multipart/form-data" action="{{ route('formLogo') }}">
        {{ csrf_field() }}
        <div class="text-center margin-top40">
          <div id="avatarAccount" class="area-user-avatar" @if($empresa->logotipo) style="background-image:url('/empresa/logotipo/{{ $empresa->logotipo }}');"@endif>
          </div>

          <div id="botoesAvatar">
            <label for="selecao-logotipo" class="bt bt-upload"><i class="fas fa-upload"></i> {{ trans('site.Logo') }}</label>
            <input id="selecao-logotipo" type="file" accept="image/*" name="ficheiro_logotipo" onchange="$(this).submit();">
            <button class="bt bt-delete" type="button" onclick="$('#id_modal_photo').val({{ $comerciantes->id_empresa }});" data-toggle="modal" data-target="#myModalDeletePhoto"><i class="fas fa-trash-alt"></i> {{ trans('site.Delete') }}</button>
          </div>

          <div id="loadingAvatar" class="loading-center"><i class="fas fa-sync fa-spin"></i> {{ trans('site.SavingR') }}</div>
          <div class="clearfix height-10"></div>
          <label id="labelErrosAvatar" class="av-100 av-vermelho display-none"><span id="spanErroAvatar"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
        </div>

        <input id="foto" type="hidden" name="foto" value="{{ $empresa->logotipo }}">
      </form>

      <form id="form-registoComercial" enctype="multipart/form-data" action="{{ route('formCompany') }}" name="form" method="post">
        {{ csrf_field() }}
      <div class="row">
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Name')}} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="text" name="nome_empresa" value="{{ $empresa->nome }}">
        </div>

        <div class="col-md-6">
          <label class="font14">{{ trans('site.General_Email')}} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="email" name="email_empresa" value="{{ $empresa->email }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <label class="font14">{{ trans('site.NIF')}} <span class="tx-coral">*</span></label>
          <input type="text" name="nif" maxlength="9" class="ip margin-bottom20" @if($empresa->nif == 0) value="" @else value="{{ $empresa->nif }}" @endif>
        </div>
        <div class="col-md-4">
          <label class="font14">{{ trans('site.CAE')}} <span class="tx-coral">*</span></label>
          <input type="text" name="cae_empresa" class="ip margin-bottom20" @if($empresa->cae == 0) value="" @else value="{{ $empresa->cae }}" @endif>
        </div>
        <div class="col-md-4">
          <label class="font14">{{ trans('site.Contact') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="text" name="telefone_empresa" @if($empresa->telefone == 0) value="" @else value="{{ $empresa->telefone }}" @endif>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <label class="font14">{{ trans('site.Number_Points_txt')}} <span class="tx-coral">*</span></label>
          <input type="text" name="num_pontos_venda" class="ip margin-bottom20" value="{{ $empresa->pontos_venda }}">
        </div>
        <div class="col-md-4">
          <label class="font14">{{ trans('site.Sales_Amount_txt')}} <span class="tx-coral">*</span></label>
          <input type="text" name="num_vendas" class="ip margin-bottom20" value="{{ $empresa->volume_venda }}">
        </div>
        <div class="col-md-4">
          <label class="font14">{{ trans('site.EbitdaOfLastYear')}} <span class="tx-coral">*</span></label>
          <input type="text" name="ebitda" class="ip margin-bottom20" value="{{ $empresa->ebitda }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Street') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="text" name="morada" @if($moradas) value="{{ $moradas->morada }}" @else  @endif>
        </div>

        <div class="col-md-6">
          <label class="font14">{{ trans('site.Street') }} - {{ trans('site.Line') }} 2 ({{ trans('site.optional') }})</label>
          <input class="ip margin-bottom20" type="text" name="morada_opc" @if($moradas) value="{{ $moradas->morada_opc }}" @else @endif>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Postal_Code') }} <span class="tx-coral">*</span></label>
          <input id="code" class="ip margin-bottom20" type="text" name="codigo_postal" @if($moradas) value="{{ $moradas->codigo_postal }}" @else @endif>
        </div>

        <div class="col-md-6 margin-bottom20">
          <label class="font14">{{ trans('site.Country')}} <span class="tx-coral">*</span></label>
          <select id="pais" class="select2" style="width: 100%;" name="pais">
            <option value="" selected></option>
            @foreach ($paises as $pais)
              <option value="{{ $pais->nome }}">{{ $pais->nome }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label class="font14">{{ trans('site.City')}} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="text" name="cidade" @if($moradas) value="{{ $moradas->cidade }}" @else @endif>
        </div>

        <div class="col-md-6">
          <label class="font14">Fax</label>
          <input class="ip margin-bottom20" type="text" name="fax" @if($moradas) @if($moradas->fax == 0) value="" @else value="{{ $moradas->fax }}" @endif @else @endif>
        </div>
      </div>

      
      <label class="font14">{{ trans('site.IES_txt')}} <span class="tx-coral">*</span></label>
      <div class="quest-resposta div-100">
        <label class="a-dotted-white" id="uploads_ies">@ if($empresa->ies) { { $empresa->ies }} @ else { { trans('site.No_document') }} @ endif</label>
      </div>
      
      <label id="ies_bt" for="selecao-view-IES" @ if($empresa->ies) class="lb-40 bt-azul float-right margin-left10" @ else class="lb-40 bt-cinza float-right margin-left10" @ endif>
        <a id="ies" @ if($empresa->ies) href="/empresa/ies/{ { $empresa->ies }}" @ endif download><i class="fas fa-download"></i></a>
      </label>

      <label for="arquivo-ies" class="lb-40 bt-azul float-right"><i class="fas fa-upload" aria-hidden="true"></i></label>
      <input id="arquivo-ies" type="file" name="resposta_IES" accept="" onchange="lerFicheiros(this,'uploads_ies');">

      
      <label class="font14 margin-top10">{{ trans('site.Certificate_Company_txt')}} <span class="tx-coral">*</span></label>
      <div class="quest-resposta div-100">
        <label class="a-dotted-white" id="uploads_img">@if($empresa->certidao ){{ $empresa->certidao }}@else {{ trans('site.No_document') }} @endif</label>
      </div>
      
      <label id="certidao_bt" for="selecao-view-certidao" @if($empresa->certidao) class="lb-40 bt-azul float-right margin-left10" @else class="lb-40 bt-cinza float-right margin-left10" @endif>
        <a id="certidao" @if($empresa->certidao) href="/empresa/certidao/{{ $empresa->certidao }}" @endif download><i class="fas fa-download"></i></a>
      </label>

      <label for="arquivo-certidao" class="lb-40 bt-azul float-right"><i class="fas fa-upload" aria-hidden="true"></i></label>
      <input id="arquivo-certidao" type="file" name="resposta_certidao" accept="" onchange="lerFicheiros(this,'uploads_img');">
      
      

      <div class="tx-azul-claro margin-top20"><h5>{{ trans('site.Legal_Representatives') }}</h5></div>

      <div class="row">
        <div class="col-md-12">
          <label class="font14">{{ trans('site.Name') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="text" name="nome_responsavel" @if($comerciante_resp) value="{{ $comerciante_resp->nome }}" @endif>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Email') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="email" name="email_responsavel" @if($comerciante_resp) value="{{ $comerciante_resp->email }}" @endif>
        </div>
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Contact') }} </label>
          <input class="ip margin-bottom20" type="text" name="telefone_responsavel" >
        </div>
      </div>

      
      <label class="font14">{{ trans('site.Declaration_of_Powers') }} <span class="tx-coral">*</span></label>
      <div class="quest-resposta div-100">
        <label class="a-dotted-white" id="uploads">{{ trans('site.No_document') }}</label>
      </div>
      
      <label id="doc_bt" for="selecao-view-doc" class="lb-40 bt-azul float-right margin-left10">
        <a id="doc_resp" href="/comerciante/doc_responsabilidade/" download><i class="fas fa-download"></i></a>
      </label>

      <label for="arquivo-doc" class="lb-40 bt-azul float-right"><i class="fas fa-upload" aria-hidden="true"></i></label>
      <input id="arquivo-doc" type="file" name="doc_responsabilidade" accept="" onchange="lerFicheiros(this,'uploads');">

      
      <p class="margin-top20 font14">{{ trans('site.Validation_file_txt') }} <a href="/empresa/minuta/Minuta.docx" style="text-decoration: underline;" class="tx-azul-claro" download> {{ trans('site.Download_Minuta') }}</a></p>


      
      <div class="margin-top20 tx-azul-claro"><h5>{{ trans('site.Contact_person') }}</h5></div> 
      <div class="row">
        <div class="col-md-12">
          <label class="font14">{{ trans('site.Name') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom20" type="text" name="nome_gerente" value="">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Email') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom10" type="email" name="email_gerente" value="">
        </div>
        <div class="col-md-6">
          <label class="font14">{{ trans('site.Contact') }} <span class="tx-coral">*</span></label>
          <input class="ip margin-bottom10" type="text" name="telefone_gerente" >
        </div>
      </div>

      <div class="margin-top20 tx-azul-claro"><h5>{{ trans('site.Additional_information') }}</h5></div> 
      <textarea class="tx margin-top5" name="obs"></textarea>

      <label class="margin-top10 tx-azul-claro"><span class="tx-coral">*</span> {{ trans('site.Required_fields') }}</label>
      <div class="height-20"></div>
      <div id="botoes">
        <input id="tipo" type="hidden" name="funcao" value="guardar">
        <button class="bt bt-azul float-right" type="submit" onclick="submeterForm('submeter');"><i class="fas fa-check"></i> {{ trans('site.to_submit') }}</button>
        <button class="bt bt-border-azul float-right" type="submit" onclick="submeterForm('guardar');" style="margin-right: 10px;"><i class="fas fa-save"></i> {{ trans('site.Save') }}</button>
        <label class="width-10 height-40 float-right"></label>
      </div>
      <div id="loading" class="loading"><i class="fas fa-sync fa-spin"></i> {{ trans('site.A_enviar')}}</div>
      <div class="clearfix"></div>
      <div class="height-10"></div>
      <label id="labelSucesso" class="av-100 alert-success display-none" role="alert"><span id="spanSucesso"></span>{{ trans('site.savedSuccessfully') }} <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelSucesso_submit" class="av-100 alert-success display-none" role="alert"><span id="spanSucesso"></span>{{ trans('site.Successfully_submitted') }} <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros" class="av-100 alert-danger display-none" role="alert"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      </form>
    </div>-->
  </div> 

  <!-- Modal Delete Photo-->
  <div class="modal fade" id="myModalDeletePhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <input type="hidden" name="id_modal_photo" id="id_modal_photo">
        <div class="modal-header">
          <button type="button" class="close premio-icon-modal" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body area-user-modal-delete">
          <h5 class="tx-azul" id="exampleModalLongTitle">{!! trans('site.DeletePhoto') !!}</h5>
          <button type="button" class="bt bt-vermelho margin-top30" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i> {{ trans('site.Cancel') }}</button>
          <button type="button" class="bt bt-azul margin-top30" data-dismiss="modal" onclick="apagarFoto();"><i class="fas fa-check"></i> {{ trans('site.Delete') }}</button>
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
<script type="text/javascript"> $('.select2').select2(); </script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script>
  $(document).ready(function () { 
    var $campo = $("#code");
    $campo.mask('0000-000', {reverse: true});
  });

  function lerFicheiros(input,id) {
    $('#'+id).html('');
    var quantidade = input.files.length;
    var nome = input.value;
    if(quantidade==1){$('#'+id).html(nome);}
    else{$('#'+id).html(quantidade+' {{ trans('profile.selectedFiles') }}');}
  };
</script>
<script>
  function submeterForm(funcao){
    $('#tipo').val(funcao);
    //$('#form-registoComercial').submit();
  }

  function apagarFoto(){
    var id = $('#id_modal_photo').val();

    $.ajax({
      type: "POST",
      url: '{{ route('photoDelete') }}',
      data: {id:id},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if('sucesso'){
        $('#avatarAccount').css("background-image","url({{ asset('/img/site/default.svg') }})");
      }else{
        $("#spanErroAvatar").html(resposta);
        $("#labelErrosAvatar").show();
      }
    });
  }
</script>
<script type="text/javascript">
  $('#form-registoComercial').on('submit',function(e) {
    $("#labelSucesso").hide();
    $("#labelErros").hide();
    $('#botoes').hide();
    $('#loading').show();
    
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
        //$('#id').val(resp.id);
        
        $("#labelSucesso").show();
        if(resp.ies != null){
          document.getElementById("ies").href = "/empresa/ies/"+resp.ies;
          $("#uploads_ies").html(resp.ies);
          $("#arquivo-ies").val('');
          $("#ies_bt").removeClass('bt-cinza');
          $("#ies_bt").addClass('bt-azul');
        }

        if(resp.certidao != null){
          document.getElementById("certidao").href = "/empresa/certidao/"+resp.certidao;
          $("#uploads_img").html(resp.certidao);
          $("#arquivo-certidao").val('');
          $("#certidao_bt").removeClass('bt-cinza');
          $("#certidao_bt").addClass('bt-azul');
        }
        
        if(resp.doc_resp != null){
          document.getElementById("doc_resp").href = "/comerciante/doc_responsabilidade/"+resp.doc_resp;
          $("#uploads").html(resp.doc_resp);
          $("#arquivo-doc").val('');
          $("#doc_bt").removeClass('bt-cinza');
          $("#doc_bt").addClass('bt-azul');
        }

        if (resp.funcao == 'submeter'){ 
          $("#labelSucesso_submit").show();
          $("#labelSucesso").hide();
          setTimeout(window.location="{{ route('pageWait') }}",500);
        }
      }
      else if(resposta){
        $("#spanErro").html(resposta);
        $("#labelErros").show();
      }
      $('#loading').hide();
      $('#botoes').show();
    });
  });

  $('#form-logotipo').on('submit',function(e) {
    $("#labelErrosAvatar").hide();
    $('#loadingAvatar').show();
    $('#botoesAvatar').hide();
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
      $('#selecao-logotipo').val('');
      try{ resp=$.parseJSON(resposta); }
      catch (e){
          if(resposta){ $("#spanErroAvatar").html(resposta); }
          else{ $("#spanErroAvatar").html('ERROR'); }
          $("#labelErrosAvatar").show();
          $('#loadingAvatar').hide();
          $('#botoesAvatar').show();
          return;
      }
      if(resp.estado=='sucesso'){
        $('#avatarAccount').css("background-image","url(/empresa/logotipo/"+resp.logotipo+")");
        $('#loadingAvatar').hide();
        $('#botoesAvatar').show();
      }
    });
  });
</script>
@stop