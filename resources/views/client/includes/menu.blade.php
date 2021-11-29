<div class="header-area-menu">
  <div class="header-area-div">
    <a href="{{ route('areaReservedDataV2') }}" @if($separador == 'page_client_data') class="tx-navy" @endif > {{ trans('site_v2.Personal_Data') }}</a>
  </div>
  <div class="header-area-div">
    <a href="{{ route('areaReservedAdressV2') }}" @if($separador == 'page_client_adress' || $separador == 'page_client_adressBilling' || $separador == 'page_client_adressDelivery') class="tx-navy" @endif>{{ trans('site_v2.Adresses') }}</a>
  </div>
  <div class="header-area-div">
    <a href="{{ route('historyPointsV2') }}" @if($separador == 'page_client_point') class="tx-navy" @endif>{{ trans('site_v2.Points_History') }}</a>
  </div>
  <div class="header-area-div">
    <a href="{{ route('historyPremiumV2') }}" @if($separador == 'page_client_premium') class="tx-navy" @endif>{{ trans('site_v2.Premium_History') }}</a>
  </div>
  <div class="header-area-div">
    <a href="{{ route('showRegulationV2') }}" @if($separador == 'page_client_regulation') class="tx-navy" @endif>{{ trans('site_v2.Regulation') }}</a>
  </div>
  <div class="header-area-last">
    <a href="{{ route('logoutPost') }}"><i class="fas fa-sign-out-alt"></i> {{ trans('site_v2.Logout') }}</a>
  </div>
</div>