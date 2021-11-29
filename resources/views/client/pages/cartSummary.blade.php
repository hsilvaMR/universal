@extends('client/layouts/default-menu')
@section('content')
<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  
  <div class="container">
    <div class="bg-white">
      <div class="data-border-form">
        <label>{{ trans('site_v2.Summary') }}</label>
      </div>
 
      <div class="cart overflowX">
        <table class="table table-striped table-summary">
          <thead>
            <tr class="table-thead">
              <th scope="col">{{ trans('site_v2.Article') }}</th>
              <th scope="col" class="tx-right">{{ trans('site_v2.Amount') }}</th>
              <th scope="col" class="tx-right">{{ trans('site_v2.Points') }}</th>
              <th scope="col" class="tx-right">{{ trans('site_v2.Value') }}</th>
            </tr>
          </thead>
          <tbody>
            @php $valor = 0; @endphp
            @foreach($car as $car_uti)
              @php
                $valor_qtd = $car_uti->quantidade * $car_uti->valor_cliente;
                $valor = $valor + $valor_qtd;
              @endphp

              <tr>
                <td>{{ $car_uti->nome_pt }} @if(isset($car_uti->variante)) - {{ $car_uti->variante }}@endif</td>
                <td class="tx-right">{{ $car_uti->quantidade }}</td>

                @if(($car_uti->quantidade*$car_uti->valor_cliente) > ($car_uti->pontos_utilizados))
                  <td class="tx-right"> <span id="premio_ponto{{ $car_uti->id_linha }}" class="tx-coral">{{ $car_uti->pontos_utilizados }}</span> / {{ $valor_qtd }} </td>
                @else
                  <td class="tx-right"><span id="premio_ponto{{ $car_uti->id_linha }}">{{ $car_uti->pontos_utilizados }}</span> / {{ $valor_qtd }}</td>
                @endif
          
                <td class="tx-right">0.00€</td>
              </tr>
            @endforeach

            <tr>
              <td>{{ trans('site_v2.Points') }} x <span id="pontos_falta">{!! $pontos_falta !!}</span></td>
              <td class="tx-right">1</td>
              <td class="tx-right" id="points_td">{!! $pontos_falta !!}</td>
              <td class="tx-right" id="valor_euro_td">{!! $valor_euro !!}€</td>
            </tr>

            <tr>
              <td>{{ trans('site_v2.Send') }}</td>
              <td class="tx-right">1</td>
              <td class="tx-right">0</td>
              <td class="tx-right">0.00€</td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td class="tx-right" id="total_pontos">{{ trans('site_v2.Total_of_points') }} <span class="summary-points" id="points">{{ $valor}}</span></td>
              <td class="tx-right">{{ trans('site_v2.Total_Value') }} <span class="summary-points" id="valor_euro">{!! $valor_euro !!}€</span></td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td class="tx-right">{{ trans('site_v2.Remaining_points') }} <span class="margin-left20" id="pontos_restantes"> {{ $pontos_restantes }}</span></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>

      <input id="ip_stripe" type="hidden" name="ip_stripe" value="{{ $valor_stripe }}">
      <div class="summary-datos">
        <div class="row">
          <div class="col-md-6"> 
            <div class="adress-border-tit">{{ trans('site_v2.Billing_Address') }}</div>

            <label class="adress-label">{{ trans('site_v2.name') }}:</label>
            <label>
              @if($carrinho_user->nome_fact) {{ $carrinho_user->nome_fact }} @else - @endif 
            </label><br>

            <label class="adress-label">{{ trans('site_v2.email') }}:</label>
            <label>
              @if($carrinho_user->email_fact) {{ $carrinho_user->email_fact }}  @else - @endif 
            </label><br>

            @if($carrinho_user->contacto_fact)
              <label class="adress-label">{{ trans('site_v2.phone') }}:</label>
              <label>{{ $carrinho_user->contacto_fact }}</label><br>
            @endif 

            @if($carrinho_user->nif_fact)
              <label class="adress-label">{{ trans('site_v2.VAT') }}:</label>{{ $carrinho_user->nif_fact }}<label></label><br>
            @endif 

            <label class="adress-label">{{ trans('site_v2.adress') }}:</label>
            <label>
              @if($carrinho_user->morada_fact) {{ $carrinho_user->morada_fact }}  @else - @endif 
            </label><br>

            @if($carrinho_user->morada_opc_fact)<label>{{ $carrinho_user->morada_opc_fact }}</label><br>@endif 

            <label class="adress-label">{{ trans('site_v2.postal_code') }}:</label>
            <label>
              @if($carrinho_user->code_post_fact) {{ $carrinho_user->code_post_fact }}  @else - @endif 
            </label><br>

            <label class="adress-label">{{ trans('site_v2.city') }}:</label>
            <label>
              @if($carrinho_user->cidade_fact) {{ $carrinho_user->cidade_fact }}  @else - @endif 
            </label><br>

            <label class="adress-label">{{ trans('site_v2.country') }}:</label>
            <label> {{ $carrinho_user->pais_fact }} </label>
          </div>
          <div class="col-md-6"> 
            <div class="adress-border-tit">{{ trans('site_v2.Delivery_Address') }}</div>

            <label class="adress-label">{{ trans('site_v2.name') }}:</label>
            <label>
              @if($carrinho_user->nome_entrega) {{ $carrinho_user->nome_entrega }}  @else - @endif 
            </label><br>

            <label class="adress-label">{{ trans('site_v2.email') }}:</label>
            <label>
              @if($carrinho_user->email_entrega) {{ $carrinho_user->email_entrega }}  @else - @endif 
            </label><br>

            @if($carrinho_user->contacto_entrega)
              <label class="adress-label">{{ trans('site_v2.phone') }}:</label>
              <label>{{ $carrinho_user->contacto_entrega }}</label><br>
            @endif 

            <label class="adress-label">{{ trans('site_v2.adress') }}:</label>
            <label>
              @if($carrinho_user->morada_entrega) {{ $carrinho_user->morada_entrega }}  @else - @endif 
            </label><br>

            @if($carrinho_user->morada_opc_entrega)<label>{{ $carrinho_user->morada_opc_entrega }}</label><br>@endif

            <label class="adress-label">{{ trans('site_v2.postal_code') }}:</label>
            <label>
              @if($carrinho_user->code_post_entrega) {{ $carrinho_user->code_post_entrega }}  @else - @endif 
            </label><br>

            <label class="adress-label">{{ trans('site_v2.city') }}:</label>
            <label>
              @if($carrinho_user->cidade_entrega) {{ $carrinho_user->cidade_entrega }}  @else - @endif 
            </label><br>

            <label class="adress-label">{{ trans('site_v2.country') }}:</label>
            <label>{{ $carrinho_user->pais_entrega }}</label>
          </div>
        </div>

        @if($carrinho_user->nota)
          <div class="adress-border-tit margin-top40">{{ trans('site_v2.Notes') }}</div>
          <label>{{ $carrinho_user->nota }}</label>
        @endif

        <div class="summary-terms">
          <input type="checkbox" id="terms" name="terms" value="0">
          <label for="terms" class="data-terms margin-bottom20">
            <span class="data-ip"></span><a href="{{ route('termsPageV2') }}">{!! trans('site_v2.terms_txt') !!}</a>
          </label>
        </div>
        
        
        <a href="{{ route('cartBillingPageV2') }}"><button class="bt bg-gray tx-navy"><i class="fas fa-angle-left"></i> {{ trans('site_v2.Previous') }}</button></a>

        @if($valor_euro>0)
          <button class="bt bg-green float-right" id="customButton"><i class="fas fa-check"></i> {{ trans('site_v2.Pay_by_credit_card') }}</button>
        @else
          <button class="bt bg-green float-right" onclick="sucess();"><i class="fas fa-check"></i> {{ trans('site_v2.Checkout') }}</button>
        @endif
        <div class="height20"></div>

        <label id="labelErrosData" class="av-100 alert-danger display-none float-none" role="alert"><span id="spanErroData"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
        <div class="height20"></div>
      </div>
    </div>
  </div>
</section>
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
  <script src="https://checkout.stripe.com/checkout.js"></script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');
  </script>

  <script>
    $("#terms").on('change', function() {
      if ($(this).is(':checked')) { $(this).attr('value', '1'); } 
      else { $(this).attr('value', '0'); }
    });
  </script>

  <script>
    function sucess(){
      console.log({!! $pontos_restantes !!});
      if ($("#terms").val() == 1) {
        $('#points_header').html('');
        $('#points_header').append({!! $pontos_restantes !!});
        window.location="{{ route('cartSucessPageV2') }}";
      }else{
        $('#labelErrosData').show();
        $('#labelErrosData').html('{{ trans('site_v2.Agree_terms_txt') }}');
      }
    }
  </script>

  <script>
    var handler = StripeCheckout.configure({
      key: 'pk_live_EYnIhRdJx3Xc67YdtoPwBiP800mmDAUdpE',
      image: 'img/site/img-stripe.svg',
      locale: 'auto',
      token: function(token) {
        // You can access the token ID with `token.id`.
        // Get the token ID to your server-side code for use.

        window.location="{{ route('cartSucessPageV2') }}";
        $('#points_header').html('');
        $('#points_header').append({!! $pontos_restantes !!});
      }
    });
 
    document.getElementById('customButton').addEventListener('click', function(e) {

      if ($("#terms").val() == 1) {

        $val_stripe = $('#ip_stripe').val();

        if ($val_stripe > 0) {
          // Open Checkout with further options:
          handler.open({
            name: 'Universal',
            description: '{!! $utilizadores->email !!}',
            currency: 'EUR',
            amount: $val_stripe,
            email:'{!! $carrinho_user->email_fact !!}' 
          });
          e.preventDefault();
        }
        else{
          window.location="{{ route('cartSucessPageV2') }}";
        }
        
        $('#labelErrosData').hide();
      }
      else{
        $('#labelErrosData').show();
        $('#labelErrosData').html('{{ trans('site_v2.Agree_terms_txt') }}');
      }
    });

    // Close Checkout on page navigation:
    window.addEventListener('popstate', function() {
      handler.close();
    });
  </script>

@stop