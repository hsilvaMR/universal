@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  @include('client/includes/headerSubMenu')

  <div class="container">
    <div class="row">
      <div class="col-lg-3">@include('client/includes/menu')</div>
      <div class="col-lg-9">
        <div class="bg-white">
          <div class="data-border-form">
            <label>{{ trans('site_v2.Delivery_Address') }}</label>
          </div>
          <form id="updateAdressD" method="POST" enctype="multipart/form-data" action="{{ route('updateAdressDPostV2') }}">
            {{ csrf_field() }}
            <div class="data-form">
          
              <label>{{ trans('site_v2.Name') }}</label>
              <input class="ip data-ip" type="text" name="name" @if(isset($morada_entrega->nome)) value="{{ $morada_entrega->nome }}" @endif>
      
              <div class="row">
                <div class="col-md-6">
                  <label>{{ trans('site_v2.E-mail') }}</label>
                  <input class="ip data-ip" type="email" name="email" @if(isset($morada_entrega->email)) value="{{ $morada_entrega->email }}" @endif>
                </div>
                <div class="col-md-6">
                  <label>{{ trans('site_v2.Phone_call') }}</label>
                  <input class="ip data-ip" type="text" name="contacto" placeholder="{{ trans('site_v2.optional') }}" @if(isset($morada_entrega->telefone)) value="{{ $morada_entrega->telefone }}" @endif>
                </div>
              </div>

              <label>{{ trans('site_v2.Address') }}</label>
              <input class="ip data-ip" type="text" name="morada" @if(isset($morada_entrega->morada)) value="{{ $morada_entrega->morada }}" @endif>
              <input class="ip data-ip" type="text" name="morada_opc" placeholder="{{ trans('site_v2.optional') }}" @if(isset($morada_entrega->morada_opc)) value="{{ $morada_entrega->morada_opc }}" @endif>

              <div class="row">
                <div class="col-md-3">
                  <label>{{ trans('site_v2.Postal_Code') }}</label>
                  <input id="code-postal" class="ip data-ip" type="text" name="codigoPostal" @if(isset($morada_entrega->codigo_postal)) value="{{ $morada_entrega->codigo_postal }}" @endif>
                </div>
                <div class="col-md-4">
                  <label>{{ trans('site_v2.City') }}</label>
                  <input class="ip data-ip" type="text" name="cidade" @if(isset($morada_entrega->cidade)) value="{{ $morada_entrega->cidade }}" @endif>
                </div>
                <div class="col-md-5">
                  <label>{{ trans('site_v2.Country') }}</label>
                  <input class="ip data-ip" type="text" name="pais" value="Portugal - Continental" disabled>
                </div> 
              </div>

              <div class="height10"></div>

              <div class="tx-right">
                <a href="{{ route('areaReservedAdressV2') }}" class="bt bt-white"><i class="fas fa-times"></i> {{ trans('site_v2.Cancel') }}</a>
                <button class="bt-blue" type="submit"><i class="fas fa-check"></i> {{ trans('site_v2.Save') }}</button>
                <div class="height20"></div>
              </div>
              <div id="botoes" class="display-none">
                <label id="labelSucessoAdress" class="av-100 alert-success display-none float-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.Send_successfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
                <label id="labelErrosAdress" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErroAdress"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
                <div class="height20"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="bg-gradient-blue">
  <div class="premium-banner" style="background: url('/site_v2/img/site/code-hero-back.png')no-repeat center;">
    <div class="container">
      <h1>{{ trans('site_v2.Insufficient_points') }}</h1>
      <button class="bt-gray tx-transform">{{ trans('site_v2.BUY_POINTS') }}</button>
    </div>
  </div>
</div>
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
      var $campo = $("#code-postal");
      $campo.mask('AAAA - AAA', {reverse: true});
    });
  </script>

  <script>
    $('#updateAdressD').on('submit',function(e) {
      $("#labelSucessoAdress").hide();
      $("#labelErrosAdress").hide();
      $('#loadingAdress').show();
      $('#botoes').show();
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
            if(resposta){ $("#spanErroAdress").html(resposta); }
            else{ $("#spanErroAdress").html('ERROR'); }
            $("#labelErrosAdress").show();
            $('#loadingAdress').hide();
            return;
        }
        if(resp.estado=='sucesso'){ /*$("#labelSucessoAdress").show();*/ 
          window.location="{{ route('areaReservedAdressV2') }}";
        }
        else{
          $("#spanErroAdress").html(resposta);
          $("#labelErrosAdress").show();
        }
        $('#loadingAdress').hide();
      });
    });
  </script>
@stop