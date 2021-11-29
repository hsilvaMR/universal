@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  @include('client/includes/headerSubMenu')

  <div class="container">
    <div class="row">
        <div class="col-lg-3">
            @include('client/includes/menu')
        </div>
      <div class="col-lg-9">
        <div class="bg-white">
          <div class="data-border-form">
            <label>{{ trans('site_v2.Adresses') }}</label>
          </div>
          <div class="bg-white data-margin">
            <div class="row">
              
              <div class="col-md-6">
                <div class="adress-border-tit">{{ trans('site_v2.Billing_Address') }}</div>

                <div class="adress-div">
                  <label class="adress-label">{{ trans('site_v2.name') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->nome) && $morada_faturacao->nome != '') {{ $morada_faturacao->nome }} @else - @endif 
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.email') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->email) && $morada_faturacao->email != '') {{ $morada_faturacao->email }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.phone') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->telefone) && $morada_faturacao->telefone != '') {{ $morada_faturacao->telefone }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.VAT') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->nif) && $morada_faturacao->nif != 0) {{ $morada_faturacao->nif }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.adress') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->morada) && $morada_faturacao->morada != '') {{ $morada_faturacao->morada }} @else - @endif
                  </label><br>

                  @if(isset($morada_faturacao->morada_opc) && $morada_faturacao->morada_opc != '')<label> {{ $morada_faturacao->morada_opc }} </label><br>@endif

                  <label class="adress-label">{{ trans('site_v2.postal_code') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->code_fat) && $morada_faturacao->code_fat != '') {{ $morada_faturacao->code_fat }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.city') }}:</label>
                  <label>
                    @if(isset($morada_faturacao->cidade) && $morada_faturacao->cidade != '') {{ $morada_faturacao->cidade }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.country') }}:</label>
                  <label>@if(isset($morada_faturacao->pais))  {{ $morada_faturacao->pais }} @else - @endif</label><br>
                </div>

                <a href="{{ route('editAdressBillingV2') }}"><button class="bt adress-bt-edit"><i class="fas fa-pencil-alt"></i> {{ trans('site_v2.Edit') }}</button></a>
              </div>

              <div class="col-md-6">
                <div class="adress-border-tit">{{ trans('site_v2.Delivery_Address') }}</div>

                <div class="adress-div">
                  <label class="adress-label">{{ trans('site_v2.name') }}:</label>
                  <label>
                    @if(isset($morada_entrega->nome) && $morada_entrega->nome != '') {{ $morada_entrega->nome }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.email') }}:</label>
                  <label>
                    @if(isset($morada_entrega->email) && $morada_entrega->email != '') {{ $morada_entrega->email }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.phone') }}:</label>
                  <label>
                    @if(isset($morada_entrega->telefone) && $morada_entrega->telefone != 0) {{ $morada_entrega->telefone }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.adress') }}:</label>
                  <label>
                    @if(isset($morada_entrega->morada) && $morada_entrega->morada != '') {{ $morada_entrega->morada }} @else - @endif
                  </label><br>

                  @if(isset($morada_entrega->morada_opc) && $morada_entrega->morada_opc != '')<label> {{ $morada_entrega->morada_opc }}</label><br>@endif

                  <label class="adress-label">{{ trans('site_v2.postal_code') }}:</label>
                  <label>
                    @if(isset($morada_entrega->codigo_postal) && $morada_entrega->codigo_postal !='') {{ $morada_entrega->codigo_postal }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.city') }}:</label>
                  <label>
                    @if(isset($morada_entrega->cidade) && $morada_entrega->cidade != '') {{ $morada_entrega->cidade }} @else - @endif
                  </label><br>

                  <label class="adress-label">{{ trans('site_v2.country') }}:</label>
                  <label>@if(isset($morada_entrega->pais)) {{ $morada_entrega->pais }} @else - @endif</label><br>
                </div>

                <a href="{{ route('editAdressDeliveryV2') }}"><button class="bt adress-bt-edit"><i class="fas fa-pencil-alt"></i> {{ trans('site_v2.Edit') }}</button></a>
              </div>
            </div>
          </div>
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
      var $campo = $("#code-premios");
      $campo.mask('AAA AAA', {reverse: true});
    });
  </script>
@stop