
@if(($separador == 'page_premium' || $separador == 'page_premium_info' ) && (\Cookie::get('cookie_user_id') || \Cookie::get('cookie_comerc_id')))
	<header>
	<div class="header-client-site bg-white">
		<div class="container">
			<a href="{{ asset('') }}"><img class="header-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}" alt="logotipo-universal"></a>

			<div class="header-span">
				@if(Cookie::get('cookie_user_id'))
					<a href="{{ route('areaReservedV2') }}" class="header-span-right">
						<span class="header-menu">
							{{ trans('site_v2.Your_points') }}: 
							<span id="points_header" class="tx-navy font-bold" style="margin-right:10px;">@if(Cookie::get('cookie_user_points') < 0) 0 @else {{ Cookie::get('cookie_user_points') }} @endif @if(Cookie::get('cookie_company_points')) {{ Cookie::get('cookie_company_points') }} @endif
							</span> 
							<button class="bt-50 bt-add"><i class="fas fa-plus"></i></button>
						</span>
					</a>
				
					<a href="{{ route('areaReservedV2') }}" class="header-span-right">
						<span class="header-menu"><button class="bt bt-add">{{ trans('site_v2.EXCHANGE_POINTS') }}</button></span>
					</a>
				@endif

				<a @if(Cookie::get('cookie_user_id')) href="{{ route('areaReservedDataV2') }}" @else href="{{ route('dashboardV2') }}" @endif>
					<img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_user_photo')) src="/img/clientes/{{ Cookie::get('cookie_user_photo') }}" @elseif(Cookie::get('cookie_comerc_photo')) src="/img/comerciantes/{{ Cookie::get('cookie_comerc_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif>
					<span class="header-area-name">{{ trans('site_v2.Hello') }}, <span id="cookie_user_name">@if(Cookie::get('cookie_user_name')) {{ Cookie::get('cookie_user_name') }} @else {{ Cookie::get('cookie_comerc_name') }} @endif </span> <i class="fas fa-angle-down"></i></span>
					
				</a>
				@if(Cookie::get('cookie_user_id'))
					<a href="{{ route('cartPageV2') }}" style="float:right;width:80px;">
						<div class="header-img-car">
							<sup id="cont_cart_client" class="header-icon-car">@if($separador == 'page_cart_sucess') 0 @elseif(isset($carrinho_utilizador)) {{ $carrinho_utilizador }} @else {{ Cookie::get('cookie_user_cart') }} @endif</sup>
						</div>
					</a>
				@endif
			</div>
		</div>
	</div>
	
	<div id="menu-products" class="header-submenu">
		<a href="{{ route('productPageV2','cheese') }}"><span class="header-menu">{{ trans('site_v2.Cheese') }}</span></a>
		<a href="{{ route('productPageV2','butter') }}"><span class="header-menu">{{ trans('site_v2.Butter') }}</span></a>
	</div>


	<div id="menu-premium" class="header-submenu">
		<a href="{{ route('codesPageV2') }}"><span class="header-menu">{{ trans('site_v2.Codes') }}</span></a>
		<a href="{{ route('pastimePageV2') }}"><span class="header-menu">{{ trans('site_v2.Game') }}</span></a>
	</div>


	<div class="header-xs-client">
		<div class="container">
			<a href="{{ route('homePageV2') }}"><img class="header-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}" alt="logotipo-universal"></a>
			<i class="fas fa-bars cursor-pointer" onclick="showHeaderXS();"></i>
		</div>
	</div>

	<div id="submenu-xs" class="submenu-xs">
		<i class="fa fa-times submenu-xs-close" aria-hidden="true" onclick="hideMenuXS();"></i>

		<a href="{{ route('areaReservedDataV2') }}">
			<div class="submenu-xs-li"> 
				<img id="img-menu" class="header-client-img float-none" @if(Cookie::get('cookie_user_photo')) src="/img/clientes/{{ Cookie::get('cookie_user_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif> {{ Cookie::get('cookie_user_name') }}
			</div>
		</a>

		<a href="{{ route('areaReservedV2') }}">
			<div class="submenu-xs-li">
				{{ trans('site_v2.Your_points') }}: <span class="font-bold tx-navy">@if(Cookie::get('cookie_user_points') < 0) 0 @else {{ Cookie::get('cookie_user_points') }} @endif</span> 
				<a href="{{ route('areaReservedV2') }}"><button class="bt-50 bt-add"><i class="fas fa-plus"></i></button></a>
			</div>

		</a>


		<a href="{{ route('cartPageV2') }}">
			<div class="submenu-xs-li"><span style="margin-right:20px;"> {{ trans('site_v2.Cart') }} </span> 
				<div class="header-img-car">
					<sup id="cont_cart" class="header-icon-car" style="margin-left:5px;">{{ Cookie::get('cookie_user_cart') }}</sup>
				</div> 
			</div>
		</a>
		<a href="{{ route('areaReservedV2') }}"><button class="bt-blue submenu-bt">{{ trans('site_v2.EXCHANGE_POINTS') }}</button></a>
	</div>
	</header>
@else
	<header @if($separador == 'page_login' || $separador == 'page_formClient' || $separador == 'page_formSeller') style="height:100px;" @endif>
		@if($separador == 'home') @include('site_v2/includes/banner') @endif
	<div class="header">
		<div class="container">
			<a href="{{ route('homePageV2') }}"><img class="header-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}" alt="logotipo-universal"></a>

			<div @if($separador != 'page_client_buy_points' && $separador != 'page_codes') class="header-span" @else class="header-span-buy" @endif>
				<a href="{{ route('universalPageV2') }}" class="header-span-right"><span class="header-menu">{{ trans('site_v2.The_Universal') }}</span></a>
				<span class="header-menu header-span-right" onclick="openMenu('product');">{{ trans('site_v2.Products') }}<span id="angle-product" class="header-submenu-angle"></span></span>
				<span class="header-menu header-span-right" onclick="openMenu('premium');">{{ trans('site_v2.Premium') }}<span id="angle-premium" class="header-submenu-angle"></span></span>
				

				@if(\Cookie::get('cookie_user_id') || \Cookie::get('cookie_comerc_id'))
					<a @if(\Cookie::get('cookie_user_id')) href="{{ route('areaReservedDataV2') }}" @else href="{{ route('dashboardV2') }}" @endif class="header-menu" style="padding-right:0px;">
						@if(\Cookie::get('cookie_user_id'))
							<img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_user_photo')) src="/img/clientes/{{ Cookie::get('cookie_user_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif>
						@else
							<img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_comerc_photo')) src="/img/comerciantes/{{ Cookie::get('cookie_comerc_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif>
						@endif
						<span>{{ trans('site_v2.Hello') }},  @if(\Cookie::get('cookie_user_id')) {{ Cookie::get('cookie_user_name') }} @else {{ Cookie::get('cookie_comerc_name') }} @endif
					</span></a>
				@else
					<a href="{{ route('loginPageV2') }}" class="header-span-bt"><button class="bt-blue">{{ trans('site_v2.LOGIN') }}</button></a>
				@endif
			</div>
		</div>
	</div>
	
	<div id="menu-products" class="header-submenu">
		<a href="{{ route('productPageV2','cheese') }}"><label class="header-menu-tipo">Queijo Bola</label></a>
		<a href="{{ route('productPageV2','prato') }}"><label class="header-menu-tipo">Queijo Prato</label></a>
		<a href="{{ route('productPageV2','extra_curado') }}"><label class="header-menu-tipo">Queijo Extra curado</label></a>
		<a href="{{ route('productPageV2','gourmet_azeitona') }}"><label class="header-menu-tipo">Queijo Azeitona</label></a>
		<a href="{{ route('productPageV2','gourmet_alho') }}"><label class="header-menu-tipo">Queijo Alho & Orégãos</label></a>
		<a href="{{ route('productPageV2','sem_sal') }}"><label class="header-menu-tipo">Queijo Sem sal</label></a>
		<a href="{{ route('productPageV2','butter') }}"><label class="header-menu-tipo">{{ trans('site_v2.Butter') }}</label></a>
	</div>


	<div id="menu-premium" class="header-submenu">
		<a href="{{ route('codesPageV2') }}"><span class="header-menu-tipo">{{ trans('site_v2.Codes') }}</span></a>
		<a href="{{ route('pastimePageV2') }}"><span class="header-menu-tipo">{{ trans('site_v2.Game') }}</span></a>
	</div>


	<div id="header-site" class="header-xs">
		<div class="container">
			<a href="{{ route('homePageV2') }}"><img class="header-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}" alt="Logotipo Universal"></a>
			<i class="fas fa-bars cursor-pointer" onclick="showHeaderXS();"></i>
		</div>
	</div>

	<div id="submenu-xs" class="submenu-xs">
		<i class="fa fa-times submenu-xs-close" aria-hidden="true" onclick="hideMenuXS();"></i>
		<a href="{{ route('universalPageV2') }}"><div class="submenu-xs-li">{{ trans('site_v2.The_Universal') }}</div></a>
		<div onclick="showSubMenuXS('products');" class="submenu-xs-li cursor-pointer">{{ trans('site_v2.Products') }}</div>
		<div id="header-prod" style="display:none;">
			<a href="{{ route('productPageV2','cheese') }}"><span class="submenu-xs-li bg-gray">{{ trans('site_v2.Cheese') }} Bola</span></a>
			<a href="{{ route('productPageV2','prato') }}"><span class="submenu-xs-li bg-gray">{{ trans('site_v2.Cheese') }} Prato</span></a>
			<a href="{{ route('productPageV2','extra_curado') }}"><span class="submenu-xs-li bg-gray">{{ trans('site_v2.Cheese') }} Extra curado</span></a>
			<a href="{{ route('productPageV2','gourmet_azeitona') }}"><span class="submenu-xs-li bg-gray">{{ trans('site_v2.Cheese') }} Azeitona</span></a>
			<a href="{{ route('productPageV2','gourmet_alho') }}"><span class="submenu-xs-li bg-gray">{{ trans('site_v2.Cheese') }} Alho & Orégãos</span></a>
			<a href="{{ route('productPageV2','sem_sal') }}"><span class="submenu-xs-li bg-gray">{{ trans('site_v2.Cheese') }} Sem sal</span></a>
			<a href="{{ route('productPageV2','butter') }}"><div class="submenu-xs-li bg-gray">{{ trans('site_v2.Butter') }}</div></a>
		</div>
		
		<!--@ if(\Cookie::get('job'))@ endif-->
		<div onclick="showSubMenuXS('premium');" class="submenu-xs-li cursor-pointer">{{ trans('site_v2.Premium') }}</div>

		<div id="header-premium" style="display:none;">
			<a href="{{ route('codesPageV2') }}"><div class="submenu-xs-li bg-gray">{{ trans('site_v2.Codes') }}</div></a>
			<a href="{{ route('pastimePageV2') }}"><div class="submenu-xs-li bg-gray">{{ trans('site_v2.Game') }}</div></a>
		</div>

		@if(\Cookie::get('cookie_user_id') || \Cookie::get('cookie_comerc_id'))
			<a href="{{ route('areaReservedDataV2') }}"><button class="bt-blue submenu-bt">{{ trans('site_v2.Hello') }}, {{ Cookie::get('cookie_user_name') }}</button></a>
		@else
			<a href="{{ route('loginPageV2') }}"><button class="bt-blue submenu-bt">{{ trans('site_v2.LOGIN') }}</button></a>
		@endif
	</div>
	</header>
@endif
