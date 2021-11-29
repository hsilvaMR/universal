<div class="menu-lateral">
  @if(Cookie::get('cookie_comerc_type') != 'comerciante')
    <a href="{{ route('dashboardV2') }}" @if($separador == 'Dashboard') class="tx-navy" @endif>
      <div class="menu-border">{{ trans('seller.Home_Panel') }}</div>
    </a>
  @endif
  
  <a href="{{ route('personalDataV2') }}"  @if($separador == 'Dados_Pessoais') class="tx-navy" @endif>
    <div class="menu-border">{{ trans('seller.Personal_Data') }}</div>
  </a>

  @if(Cookie::get('cookie_comerc_type') != 'comerciante')
    <a href="javascript:;" class="menuClick">
      <div class="menu-border">
        {{ trans('seller.Company') }} <i class="fas fa-angle-down"></i>
      </div>
    </a>

  
    <div class="@if(($separador == 'Dados_Empresa') || ($separador == 'Dados_Representante') || ($separador == 'Dados_Pessoa_Contacto')) display-block @else display-none @endif menuOpen">
      <a href="{{ route('companyDataV2') }}" @if($separador == 'Dados_Empresa') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Company_Data') }}</div>
      </a>
      <a href="{{ route('legalRV2') }}" @if($separador == 'Dados_Representante') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Legal_Representative') }}</div>
      </a>
      <a href="{{ route('contactPersonV2') }}" @if($separador == 'Dados_Pessoa_Contacto') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Contact_Person') }}</div>
      </a>
    </div>
  

    <a href="{{ route('usersV2') }}" @if($separador == 'Users') class="tx-navy" @endif>
      <div class="menu-border">{{ trans('seller.Users') }}</div>
    </a>


    <a href="javascript:;" class="menuClick">
      <div class="menu-border">
        {{ trans('seller.Adresses') }} <i class="fas fa-angle-down"></i>
      </div>
    </a>

    <div class=" @if(($separador == 'Endereco_sede') || ($separador == 'Endereco_cont') || ($separador == 'Endereco_purchase')) display-block @else display-none @endif menuOpen">
      <a href="{{ route('adressOfficeV2') }}" @if($separador == 'Endereco_sede') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Office_Address') }}</div>
      </a>
      <a href="{{ route('adressContV2') }}" @if($separador == 'Endereco_cont') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Accounting_Address') }}</div>
      </a>
      <a href="{{ route('addressPurchaseV2') }}" @if($separador == 'Endereco_purchase') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Purchase_Addresses') }}</div>
      </a>
    </div>
  @endif

  <a href="{{ route('ordersV2') }}" @if($separador == 'Orders') class="tx-navy" @endif>
    <div class="menu-border">{{ trans('seller.Orders') }}</div>
  </a>

  @if(Cookie::get('cookie_comerc_type') != 'comerciante')
    <a href="javascript:;" class="menuClick">
      <div class="menu-border">
        {{ trans('seller.Points') }} <i class="fas fa-angle-down"></i>
      </div>
    </a>

    <div class=" @if(($separador == 'Points') || ($separador == 'premiumHistory') || ($separador == 'changePoints'))  display-block @else display-none @endif menuOpen">
      <a href="{{ route('changePointsV2') }}" @if($separador == 'changePoints') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Awards') }}</div>
      </a>
      <a href="{{ route('pointsHistoryV2') }}" @if($separador == 'Points') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Points_History') }}</div>
      </a>
      <a href="{{ route('premiumHistoryV2') }}" @if($separador == 'premiumHistory') class="tx-navy" @endif>
        <div class="menu-submenu">{{ trans('seller.Premium_History') }}</div>
      </a>
    </div>
  @endif

  <a href="{{ route('technicalInformationV2') }}" @if($separador == 'Information') class="tx-navy" @endif>
    <div class="menu-border">{{ trans('seller.Technical_Information') }}</div>
  </a>

  <a href="{{ route('supportV2') }}" @if($separador == 'Support') class="tx-navy" @endif>
    <div class="menu-border">{{ trans('seller.Support') }}</div>
  </a>

  <a href="{{ route('logoutPost') }}">
    <div class="menu-border-last"><i class="fas fa-sign-out-alt"></i>{{ trans('seller.Logout') }}</div>
  </a>
</div>
