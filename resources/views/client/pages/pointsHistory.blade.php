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
            <label>{{ trans('site_v2.Points_History') }}</label>
          </div>
  
          <div class="history-points">

            <label class="table-label" >{{ trans('site_v2.Total_of_points') }} : <span id="span_point_history" class="tx-navy">@if($utilizadores->pontos < 0) 0 @else {{ $utilizadores->pontos }} @endif</span></label>

            <table class="table table-striped table-datos">
              <thead>
                <tr class="table-thead">
                  <th scope="col">{{ trans('site_v2.Codes') }}</th>
                  <th scope="col">{{ trans('site_v2.Points') }}</th>
                  <th scope="col">{{ trans('site_v2.Validate') }}</th>
                  <th scope="col">{{ trans('site_v2.State') }}</th>
                </tr>
              </thead>
              <tbody id="novos_rotulos">
                @foreach($primeiros_dez as $value)
                  @php $valor_rotulo = $value->valor - $value->valor_final;  @endphp
                  <tr id="hideAll_{{ $value->id }}">
                    <td scope="row">{{ $value->codigo }}</td>
                    <td scope="row">{{ $valor_rotulo }}/{{ $value->valor }}</td>
                    <td>{{ date('Y-m-d', $value->data_user) }}</td>
                    <td>@if($value->estado == 'disponivel') <i class="fas fa-circle tx-lightgreen margin-right10"></i> {{ trans('site_v2.available') }} @else <i class="fas fa-circle tx-coral margin-right10"></i> {{ trans('site_v2.unavailable') }} @endif</td>
                  </tr>
                @endforeach
              
                @foreach($rotulos_utilizador as $value)
                  @php $valor_rotulo = $value->valor - $value->valor_final;  @endphp
                  <tr id="showAll_{{ $value->id }}" class="display-none">
                    <td scope="row">{{ $value->codigo }}</td>
                    <td scope="row">{{ $valor_rotulo }}/{{ $value->valor }}</td>
                    <td>{{ date('Y-m-d', $value->data_user) }}</td>
                    <td>
                      @if($value->estado == 'disponivel')
                        <i class="fas fa-circle tx-lightgreen margin-right10"></i> {{ trans('site_v2.available') }}
                      @else
                        <i class="fas fa-circle tx-coral margin-right10"></i> {{ trans('site_v2.unavailable') }}
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            
            @if(count($rotulos_utilizador) > 10)
              <div class="table-view">
                <a id="search_plus" onclick="openRotulosInvalidos();"><span class="tx-navy">{{ trans('site_v2.view_more') }} <i class="fas fa-angle-down"></i></span></a>
                <a id="search_minus" class="display-none" onclick="closeRotulosInvalidos();"><span class="tx-navy">{{ trans('site_v2.see_less') }} <i class="fas fa-angle-up"></i></span></a>
              </div>
            @endif
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
    function openRotulosInvalidos(){

      @foreach($rotulos_utilizador as $value)
        $('#showAll_'+{!! $value->id !!}).show();
        $('#hideAll_'+{!! $value->id !!}).hide();
      @endforeach

      
      $('#search_plus').hide();
      $('#search_minus').show();
    };

    function closeRotulosInvalidos(){

      @foreach($rotulos_utilizador as $value)
        $('#hideAll_'+{!! $value->id !!}).show();
        $('#showAll_'+{!! $value->id !!}).hide();
      @endforeach
        
      $('#search_plus').show();
      $('#search_minus').hide();
    };
  </script>
@stop