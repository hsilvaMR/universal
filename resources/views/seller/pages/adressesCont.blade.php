@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3">
          @include('seller/includes/menu')
      </div>
      <div class="col-lg-9">

        <div class="mod-tit">
          <h3>{{ trans('seller.Accounting_Address') }}</h3>
        </div>
        <div class="mod-area">
          @if($morada_cont)
            <div  id="morada_{{ $morada_cont->id }}" >
              <input id="{{ $morada_cont->id }}_id_morada" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->id }}">
              <input id="{{ $morada_cont->id }}_morada" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->morada }}">
              <input id="{{ $morada_cont->id }}_morada_opc" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->morada_opc }}">
              <input id="{{ $morada_cont->id }}_codigo_postal" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->codigo_postal }}">
              <input id="{{ $morada_cont->id }}_cidade" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->cidade }}">
              <input id="{{ $morada_cont->id }}_pais" class="{{ $morada_cont->id }}_update up_select" type="hidden" name="" value="{{ $morada_cont->pais }}">
              <input id="{{ $morada_cont->id }}_contacto_empresa" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->telefone }}">
              <input id="{{ $morada_cont->id }}_fax" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->fax }}">
              <input id="{{ $morada_cont->id }}_nome_gerente" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->nome_gerente }}">
              <input id="{{ $morada_cont->id }}_cargo_gerente" class="{{ $morada_cont->id }}_update up_select" type="hidden" name="" value="{{ $morada_cont->cargo_gerente }}">
              <input id="{{ $morada_cont->id }}_email_gerente" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->email_gerente }}">
              <input id="{{ $morada_cont->id }}_telefone_gerente" class="{{ $morada_cont->id }}_update" type="hidden" name="" value="{{ $morada_cont->telefone_gerente }}">
            </div>
          @endif

          <div class="row">
            <div class="col-md-6">
              <div class="mod-area-col">
                <span>{{ trans('seller.Adres') }}</span>
              </div>

              <div id="div_adress" class="mod-area-col-div">
                <span class="tx-bold">{{ trans('seller.adress') }}:</span><span>@if($morada_cont && $morada_cont->morada){{ $morada_cont->morada }}@else-@endif</span><br>
                @if($morada_cont && $morada_cont->morada_opc)<span>{{ $morada_cont->morada_opc }}</span><br>@endif
                <span class="tx-bold">{{ trans('seller.postal_code') }}:</span><span>@if($morada_cont && $morada_cont->codigo_postal){{ $morada_cont->codigo_postal }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.city') }}:</span><span>@if($morada_cont && $morada_cont->cidade){{ $morada_cont->cidade }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.country') }}:</span><span>@if($morada_cont && $morada_cont->pais){{ $morada_cont->pais }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.phone_call') }}:</span><span>@if($morada_cont && $morada_cont->telefone){{ $morada_cont->telefone }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.fax') }}:</span><span>@if($morada_cont && $morada_cont->fax){{ $morada_cont->fax }}@else-@endif</span>
              </div>
              <button class="bt bt-gray margin-bottom20" onclick="edit('adress',@if($morada_cont){{ $morada_cont->id }}@endif);">
                <i class="fas fa-pencil-alt"></i> {{ trans('seller.Edit') }}
              </button>
            </div>

            <div class="col-md-6">
              <div class="mod-area-col">
                <span>{{ trans('seller.Responsible') }}</span>
              </div>

              <div id="div_gerente" class="mod-area-col-div">
                <span class="tx-bold">{{ trans('seller.name') }}:</span><span>@if($morada_cont && $morada_cont->nome_gerente){{ $morada_cont->nome_gerente }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.office') }}:</span><span>@if($morada_cont && $morada_cont->cargo_gerente){{ $morada_cont->cargo_gerente }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.e-mail') }}:</span><span>@if($morada_cont && $morada_cont->email_gerente){{ $morada_cont->email_gerente }}@else-@endif</span><br>
                <span class="tx-bold">{{ trans('seller.phone_call') }}:</span><span>@if($morada_cont && $morada_cont->telefone_gerente){{ $morada_cont->telefone_gerente }}@else-@endif</span><br>
              </div>
              <button class="bt bt-gray" onclick="edit('resp',@if($morada_cont){{ $morada_cont->id }}@endif);">
                <i class="fas fa-pencil-alt"></i> {{ trans('seller.Edit') }}
              </button>
            </div>
          </div>
        </div> 

        <form id="form-editAdress" class="bg-white" enctype="multipart/form-data" action="{{ route('addAdressPost') }}" name="form" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="tipo" value="morada_contabilidade">
          <input id="id_morada_adress" type="hidden" name="id_morada" @if($morada_cont) value="{{ $morada_cont->id }}" @endif>
          

          <div id="editAdress" class="display-none">
            
            <div class="mod-tit mod-border-top">
              <h3>{{ trans('seller.Edit_Address') }}</h3>
            </div>
            <div class="mod-area">
              <label>{{ trans('seller.Adress') }}</label>
              <input id="morada" class="ip bg-gray-dark margin-bottom10" type="text" name="morada">
              <input id="morada_opc" class="ip bg-gray-dark" type="text" name="morada_opc" value="">

              <div class="row">
                <div class="col-md-3">
                  <label>{{ trans('seller.Postal_Code') }}</label>
                  <input id="codigo_postal" class="ip bg-gray-dark" name="codigo_postal" value="">
                </div>
                <div class="col-md-4">
                  <label>{{ trans('seller.City') }}</label>
                  <input id="cidade" class="ip bg-gray-dark" type="text" name="cidade" value="">
                </div>
                <div class="col-md-5">
                  <label>{{ trans('seller.Country') }}</label>
                  <div class="select-wrapper">
                    <select id="pais" name="pais" class="bg-gray-dark">
                      <option selected disabled="true">{{ trans('seller.Country') }}</option>
                      @foreach ($paises as $pais)
                        <option value="{{ $pais->nome }}">{{ $pais->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.Phone_call') }}</label>
                  <input id="contacto_empresa" class="ip bg-gray-dark" type="text" name="contacto_empresa" value="">
                </div>
                <div class="col-md-6">
                  <label>{{ trans('seller.Fax') }}</label>
                  <input id="fax" class="ip bg-gray-dark" type="text" name="fax_empresa" value="">
                </div>
              </div>

              <div class="tx-right margin-top10">
                <span class="bt margin-right10 tx-gray" onclick="cancelAdress();"><i class="fas fa-times"></i> {{ trans('seller.Cancel') }}</span>
                <button class="bt-blue"><i class="fas fa-check"></i> {{ trans('seller.Save') }}</button>
              </div>

              <label id="labelSucesso" class="av-100 alert-success display-none" role="alert">
                <span id="spanSucesso">{{ trans('seller.savedSuccessfully') }}</span>
                <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
              </label>
              <label id="labelErros" class="av-100 alert-danger display-none" role="alert">
                <span id="spanErro"></span> <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
              </label>
            </div>
          </div>
        </form>

        <form id="form-editResp" enctype="multipart/form-data" action="{{ route('addRespPost') }}" name="form" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="tipo" value="morada_contabilidade">
          <input id="id_morada_resp" type="hidden" name="id_morada" @if($morada_cont) value="{{ $morada_cont->id }}" @endif>

          <div id="editResp" class="display-none">
            
            <div class="mod-tit mod-border-top">
              <h3>{{ trans('seller.Edit_Responsible') }}</h3>
            </div>
            <div class="mod-area">
              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.Name') }}</label>
                  <input id="nome_gerente" class="ip bg-gray-dark" type="text" name="nome_gerente">
                </div>
                <div class="col-md-6">
                  <label>{{ trans('seller.Office') }}</label>
                  <input id="cargo_gerente" class="ip bg-gray-dark" type="text" name="cargo_gerente" value="">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('seller.E-mail') }}</label>
                  <input id="email_gerente" class="ip bg-gray-dark" type="text" name="email_gerente" value="">
                </div>
                <div class="col-md-6">
                  <label>{{ trans('seller.Phone_call') }}</label>
                  <input id="telefone_gerente" class="ip bg-gray-dark" type="text" name="telefone_gerente" value="">
                </div>
              </div>

              <div class="tx-right margin-top10">
                <span class="bt margin-right10 tx-gray" onclick="cancelResp();"><i class="fas fa-times"></i> {{ trans('seller.Cancel') }}</span>
                <button class="bt-blue"><i class="fas fa-check"></i> {{ trans('seller.Save') }}</button>
              </div>

              <label id="labelSucessoResp" class="av-100 alert-success display-none" role="alert">
                <span id="spanSucesso">{{ trans('seller.savedSuccessfully') }}</span>
                <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
              </label>
              <label id="labelErrosResp" class="av-100 alert-danger display-none" role="alert">
                <span id="spanErroResp"></span> <i class="fa fa-times" aria-hidden="true" onclick="$(this).parent().hide();"></i>
              </label>
            </div>
          </div>          
        </form>
      </div>
    </div>
  </div>
@stop

@section('css')
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>
  $('#form-editAdress').on('submit',function(e) {
    $("#labelSucesso").hide();
    $("#labelErros").hide();

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
        if(resposta){ $("#spanErro").html(resposta); }
        else{ $("#spanErro").html('ERROR'); }
        $("#labelErros").show();
        return;
      }
      if(resp.estado == 'sucesso'){
        $('#morada_'+resp.id_morada).append(resp.conteudo);

        $('#div_adress').html('');
        $('#div_adress').append(resp.conteudo_adress);
        $('#id_morada_adress').val(resp.id_morada);
        $('#id_morada_resp').val(resp.id_morada);

        $('#labelSucesso').show();
        $("#labelErros").hide();
        $('#editAdress').hide();
        $('#editResp').hide();
        $('#labelSucesso').hide();
      }
    });
  });
</script>

<script>
  $('#form-editResp').on('submit',function(e) {
    $("#labelSucessoResp").hide();
    $("#labelErrosResp").hide();

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
        if(resposta){ $("#spanErroResp").html(resposta); }
        else{ $("#spanErroResp").html('ERROR'); }
        $("#labelErrosResp").show();
        return;
      }
      if(resp.estado == 'sucesso'){

        $('#morada_'+resp.id_morada).append(resp.conteudo);

        $('#div_gerente').html('');
        $('#div_gerente').append(resp.conteudo_resp);
        $('#id_morada_resp').val(resp.id_morada);
        $('#id_morada_adress').val(resp.id_morada);

        $('#labelSucessoResp').show();
        $("#labelErrosResp").hide();
        $('#editAdress').hide();
        $('#editResp').hide();
        $('#labelSucessoResp').hide();
      }
    });
  });
</script>

<script>
  function edit(valor,id){

    if (valor == 'adress') {
      $('#editAdress').show();
      $('html,body').animate({scrollTop: $("#editAdress").offset().top},'slow');
      $('#editResp').hide();
    }
    else{
      $('#editResp').show();
      $('html,body').animate({scrollTop: $("#editResp").offset().top},'slow');
      $('#editAdress').hide();
    }
    
    if (id) {
      $('.'+id+'_update').each(function(){
        if($(this).hasClass('up_check')){
          var valor=$(this).val();
          var destino=$(this).attr('id');
          destino = destino.replace(id+'_','');
          if(!valor){
            $('#'+destino).attr('checked',false);
          }else{ $('#'+destino).attr('checked',true); }
        }
        if($(this).hasClass('up_select')){
          var valor=$(this).val();
          var destino=$(this).attr('id');
          destino = destino.replace(id+'_','');
          $('#'+destino).val(valor).trigger('change');
        }else{
          var valor=$(this).val();
          var destino=$(this).attr('id');
          destino = destino.replace(id+'_','');
          $('#'+destino).val(valor);
          }
      });
    }
  }
</script>

<script>
  function cancelAdress(){
    $('#editAdress').hide();
    document.getElementById("form-editAdress").reset();
  }

  function cancelResp(){
    $('#editResp').hide();
    document.getElementById("form-editResp").reset();
  }
</script>
@stop