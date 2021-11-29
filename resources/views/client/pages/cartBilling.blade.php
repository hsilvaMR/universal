@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  
  <div class="container">
    <div class="bg-white">
      <div class="data-border-form">
        <label>{{ trans('site_v2.Billing_Data') }}</label>
      </div>
      <form id="form_billing" method="POST" enctype="multipart/form-data" action="{{ route('addBillingPostV2') }}">
        {{ csrf_field() }}
        <div class="data-form">
          <div class="row">
            <div class="col-md-12">
              <label>{{ trans('site_v2.Name') }} *</label>
              <input class="ip data-ip" type="text" name="name" @if(isset($dados_fatura->nome_fact)) value="{{ $dados_fatura->nome_fact }}" @elseif(isset($dados_faturacao->nome)) value="{{ $dados_faturacao->nome }}" @else value="" @endif>
            </div>
          </div>
          <div class="row">
            <input type="hidden" name="id" value="{{ $id_carrinho->id }}">
            <div class="col-md-6">
              <label>{{ trans('site_v2.E-mail') }} *</label>
              <input class="ip data-ip" type="email" name="email" @if(isset($dados_fatura->email_fact)) value="{{ $dados_fatura->email_fact }}" @elseif(isset($dados_faturacao->email)) value="{{ $dados_faturacao->email }}" @else value="" @endif>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('site_v2.Phone_call') }}</label>
                  <input class="ip data-ip" type="text" name="contacto" placeholder="{{ trans('site_v2.optional') }}" @if(isset($dados_fatura->contacto_fact)) value="{{ $dados_fatura->contacto_fact }}" @elseif(isset($dados_faturacao->telefone)) value="{{ $dados_faturacao->telefone }}" @else value="" @endif>
                </div>
                <div class="col-md-6">
                  <label>{{ trans('site_v2.VAT') }}</label>
                  <input class="ip data-ip" type="text" name="nif" placeholder="{{ trans('site_v2.optional') }}" @if(isset($dados_fatura->nif_fact) && $dados_fatura->nif_fact!=0) value="{{ $dados_fatura->nif_fact }}" @elseif(isset($dados_faturacao->nif) && $dados_faturacao->nif!=0) value="{{ $dados_faturacao->nif }}" @else value="" @endif>
                </div>              
              </div>
            </div>
          </div>

          <label>{{ trans('site_v2.Address') }} *</label>
          <input class="ip data-ip" type="text" name="morada" @if(isset($dados_fatura->morada_fact)) value="{{ $dados_fatura->morada_fact }}" @elseif(isset($dados_faturacao->morada)) value="{{ $dados_faturacao->morada }}" @else value="" @endif>
          <input class="ip data-ip" type="text" name="adress_opc" placeholder="{{ trans('site_v2.optional') }}" @if(isset($dados_fatura->morada_opc_fact)) value="{{ $dados_fatura->morada_opc_fact }}" @else value="" @endif>

          <div class="row">
            <div class="col-md-4">
              <label>{{ trans('site_v2.Postal_Code') }} *</label>
              <input class="ip data-ip" type="text" name="codigo_postal" @if(isset($dados_fatura->code_post_fact)) value="{{ $dados_fatura->code_post_fact }}" @elseif(isset($dados_faturacao->codigo_postal)) value="{{ $dados_faturacao->codigo_postal }}" @else value="" @endif>
            </div>
            <div class="col-md-4">
              <label>{{ trans('site_v2.City') }} *</label>
              <input class="ip data-ip" type="text" name="cidade" @if(isset($dados_fatura->cidade_fact)) value="{{ $dados_fatura->cidade_fact }}" @elseif(isset($dados_faturacao->cidade)) value="{{ $dados_faturacao->cidade }}"  @else value="" @endif>
            </div>
            <div class="col-md-4">
              <label>{{ trans('site_v2.Country') }}</label>
              <input class="ip data-ip" type="text" name="pais" value="Portugal - Continental" disabled>
            </div>
          </div>
          <input type="checkbox" id="new_adress" name="new_adress" value="0">
          <label for="new_adress" class="data-terms margin-bottom20">
            <span class="data-ip"></span>{{ trans('site_v2.Use_different_shipping_txt') }}
          </label>
        </div>

        <div id="data_adress" class="display-none">
          <div class="data-border">
            <label>{{ trans('site_v2.Delivery_Address') }}</label>
          </div>

          <div class="data-form">
            <div class="row">
              <div class="col-md-12">
                <label>{{ trans('site_v2.Name') }} *</label>
                <input class="ip data-ip" type="text" name="name_alternativo" @if(isset($dados_fatura->nome_entrega)) value="{{ $dados_fatura->nome_entrega }}" @elseif(isset($dados_entrega->nome)) value="{{ $dados_entrega->nome }}" @else value="" @endif>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label>{{ trans('site_v2.E-mail') }} *</label>
                <input class="ip data-ip" type="email" name="email_alternativo" @if(isset($dados_fatura->email_entrega)) value="{{ $dados_fatura->email_entrega }}" @elseif(isset($dados_entrega->email)) value="{{ $dados_entrega->email }}"  @else value="" @endif>
              </div>
              <div class="col-md-6">
                <label>{{ trans('site_v2.Phone_call') }}</label>
                <input class="ip data-ip" type="text" name="contacto_alternativo" placeholder="{{ trans('site_v2.optional') }}" @if(isset($dados_fatura->contacto_entrega)) value="{{ $dados_fatura->contacto_entrega }}" @elseif(isset($dados_entrega->telefone)) value="{{ $dados_entrega->telefone }}" @else value="" @endif>
              </div>
            </div>

            <label>{{ trans('site_v2.Address') }} *</label>
            <input class="ip data-ip" type="text" name="morada_alternativo" @if(isset($dados_fatura->morada_entrega)) value="{{ $dados_fatura->morada_entrega }}" @elseif(isset($dados_entrega->morada)) value="{{ $dados_entrega->morada }}" @else value="" @endif>
            <input class="ip data-ip" type="text" name="adress_opc_alternativo" placeholder="{{ trans('site_v2.optional') }}" @if(isset($dados_fatura->morada_opc_entrega)) value="{{ $dados_fatura->morada_opc_entrega }}" @else value="" @endif>

            <div class="row">
              <div class="col-md-4">
                <label>{{ trans('site_v2.Postal_Code') }} *</label>
                <input class="ip data-ip" type="text" name="cod_postal_alternativo" @if(isset($dados_fatura->code_post_entrega)) value="{{ $dados_fatura->code_post_entrega }}" @elseif(isset($dados_entrega->codigo_postal)) value="{{ $dados_entrega->codigo_postal }}"  @else value="" @endif>
              </div>
              <div class="col-md-4">
                <label>{{ trans('site_v2.City') }} *</label>
                <input class="ip data-ip" type="text" name="cidade_alternativo" @if(isset($dados_fatura->cidade_entrega)) value="{{ $dados_fatura->cidade_entrega }}" @elseif(isset($dados_entrega->cidade)) value="{{ $dados_entrega->cidade }}" @else value="" @endif>
              </div>
              <div class="col-md-4">
                <label>{{ trans('site_v2.Country') }}</label>
                <input class="ip data-ip" type="text" name="pais" value="Portugal - Continental" disabled>
              </div>
            </div>
            
          </div>
        </div>

        <div class="height20"></div>
        <div id="notes" class="data-notes">
          <label>{{ trans('site_v2.Notes') }}</label>
          <textarea class="tx data-ip" name="nota">@if(isset($dados_fatura->nota)) {{ $dados_fatura->nota }} @endif</textarea>
        </div>

        <div class="height20"></div>
        <div class="cart-bt">
          <a href="{{ route('cartPageV2') }}"><label class="bt bt-gray-clear" style="line-height:40px;"><i class="fas fa-angle-left"></i> {{ trans('site_v2.Previous') }}</label></a>
          <button class="bt-blue cart-bt-continue" type="submit">{{ trans('site_v2.Order_Summary') }} <i class="fas fa-angle-right"></i></button>

          <div id="loadingData" class="loading display-none"><i class="fas fa-sync fa-spin"></i> {{ trans('site_v2.A_enviar')}}</div>
          <label id="labelSucessoData" class="av-100 alert-success display-none float-none" role="alert"><span id="spanSucessoData">{{ trans('site_v2.Send_successfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
          <label id="labelErrosData" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErroData"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
          <div class="height20"></div>
        </div>    
      </form>
    </div>
  </div>
</section>
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');
  </script>

  <script>
    $(document).ready(function () { 
      var $campo = $("#code-premios");
      $campo.mask('AAA AAA', {reverse: true});
    });
  </script>

  <script>
    $("#new_adress").on('change', function() {
      if ($(this).is(':checked')) { $('#data_adress').show(); $(this).attr('value', '1'); } 
      else { $('#data_adress').hide(); $(this).attr('value', '0'); }
    });
  </script>

  <script>
    $('#form_billing').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErrosData").hide();
      $('#loadingData').hide();

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

        try{ resp=$.parseJSON(resposta); }
        catch (e){
            if(resposta){ $("#spanErroData").html(resposta); }
            else{ $("#spanErroData").html('ERROR'); }
            $("#labelErrosData").show();
            $('#loadingData').hide();

            return;
        }
        if(resp.estado=='sucesso'){
          
          /*$("#labelSucessoData").show();*/
          window.location="{{ route('cartSummaryPageV2') }}";
        }
        else{
          $("#spanErroData").html(resposta);
          $("#labelErrosData").show();

        }
        $('#loadingData').hide();

      });
    });
  </script>
@stop