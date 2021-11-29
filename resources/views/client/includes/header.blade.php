<header>
	<div class="header-client-site">
		<div class="container">
			<a href="{{ asset('') }}"><img class="header-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}" alt="logotipo-universal"></a>


			<div class="header-span">
				@if(Cookie::get('cookie_user_id'))
					<a href="{{ route('areaReservedV2') }}" class="header-span-right">
					<span class="header-menu">
						{{ trans('site_v2.Your_points') }}: 
						<span id="points_header" class="tx-navy font-bold" style="margin-right:10px;">@if(Cookie::get('cookie_user_points') < 0) 0 @else {{ Cookie::get('cookie_user_points') }} @endif
						</span> 
						<button class="bt-50 bt-add"><i class="fas fa-plus"></i></button>
					</span>
				</a>
				<a href="{{ route('areaReservedV2') }}" class="header-span-right"><span class="header-menu"><button class="bt bt-add">{{ trans('site_v2.EXCHANGE_POINTS') }}</button></span></a>
					<a href="{{ route('areaReservedDataV2') }}">
						<span class="header-menu-cart"><img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_user_photo')) src="/img/clientes/{{ Cookie::get('cookie_user_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif>
							<span class="header-area-name">{{ trans('site_v2.Hello') }}, <span id="cookie_user_name"> {{ Cookie::get('cookie_user_name') }} </span> <i class="fas fa-angle-down"></i></span>
						</span>
					</a>
					<a href="{{ route('cartPageV2') }}" style="float:right;width:80px;">
						<div class="header-img-car">
							<sup id="cont_cart_client" class="header-icon-car">@if($separador == 'page_cart_sucess') 0 @elseif(isset($carrinho_utilizador)) {{ $carrinho_utilizador }} @else {{ Cookie::get('cookie_user_cart') }}@endif</sup>
						</div>
					</a>
				@elseif(Cookie::get('cookie_comerc_id'))
					<img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_comerc_photo')) src="/img/comerciantes/{{ Cookie::get('cookie_comerc_photo') }}" @else src="{{ asset('/img/comerciantes/default.svg') }}" @endif>
					<span class="header-area-name tx-navy">{{ trans('site_v2.Hello') }}, {{ Cookie::get('cookie_comerc_name') }}</span>
				@else
					<a href="{{ route('loginPageV2') }}">
						<img id="img-header" class="header-img-photo" src="{{ asset('/img/clientes/default.svg') }}">
						<span class="header-area-name tx-navy"><a href="{{ route('loginPageV2') }}">{{ trans('site_v2.SignIn') }}</a></span>
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
				<img id="img-menu-xs" class="header-client-img float-none" @if(Cookie::get('cookie_user_photo')) src="/img/clientes/{{ Cookie::get('cookie_user_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif> <span id="cookie_user_name_xs">{{ Cookie::get('cookie_user_name') }}</span>
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
					<sup id="cont_cart_client" class="header-icon-car" style="margin-left:5px;">@if($separador == 'page_cart_sucess') 0 @else {{ Cookie::get('cookie_user_cart') }} @endif</sup>
				</div> 
			</div>
		</a>
		<a href="{{ route('areaReservedV2') }}"><button class="bt-blue submenu-bt">{{ trans('site_v2.EXCHANGE_POINTS') }}</button></a>
	</div>
</header>