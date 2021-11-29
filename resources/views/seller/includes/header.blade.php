<header>
	<div class="container-fluid">
		<a href="{{ route('homePageV2') }}"><img class="header-img" src="{{ asset('site_v2/img/site/universal-logo.png') }}" alt="logotipo-universal"></a>

		<div class="header-div">
			<span class="header-notification">
				<a href="{{ route('personalDataV2') }}">
					<img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_comerc_photo')) src="/img/comerciantes/{{ Cookie::get('cookie_comerc_photo') }}" @else src="{{ asset('/img/comerciantes/default.svg') }}" @endif>
					<span>{{ trans('seller.Hello') }}, <span id="cookie_comerc_name"> {{ Cookie::get('cookie_comerc_name') }} </span> <i class="fas fa-angle-down"></i></span>
				</a>
			</span>

			<span class="margin-right15">
				<a onclick="showConfig();">
					<img style="padding:5px;" src="/img/icones/config.svg">
				</a>
			</span>
			

			<a onclick="showNotification();">
				@if(Cookie::get('cookie_not_ative') == 0)
					<img id="img_bell" src="/img/icones/bell.svg">
				@else
					<a onclick="showNotification();" class="header-notification-number"><span id="header-notification-number">{{ Cookie::get('cookie_not_ative') }}</span>+</a>
					<a onclick="showNotification();"><img id="img_bell_sub" style="display:none;" src="/img/icones/bell.svg"></a>
				@endif
			</a>
		</div>

		<div class="header-xs">
			<i class="fas fa-bars cursor-pointer" onclick="showHeaderXS();"></i>
		</div>

		<div id="submenu-xs" class="submenu-xs">
			<i class="fa fa-times submenu-xs-close" aria-hidden="true" onclick="hideMenuXS();"></i>

			<a href="">
				<div class="submenu-xs-li"> 
					<span class="header-notification">
						<img id="img-header" class="header-img-photo" @if(Cookie::get('cookie_comerc_photo')) src="/img/comerciantes/{{ Cookie::get('cookie_comerc_photo') }}" @else src="{{ asset('/img/comerciantes/default.svg') }}" @endif>
						<span>{{ trans('seller.Hello') }}, <span id="cookie_comerc_name"> {{ Cookie::get('cookie_comerc_name') }} </span> <i class="fas fa-angle-down"></i></span>
					</span>
				</div>
			</a>

			<a onclick="$('.menu-config-xs').toggle()">
				<div class="submenu-xs-li">
					<img src="/img/icones/config.svg">
				</div>
			</a>


			<div class="menu-config-xs">
				
				<div class="menu-notification">
					<span class="font16 tx-navy">{{ trans('seller.Orders') }}</span>
					<br>
					<span class="tx-jet">{{ trans('seller.Track_status_orders_txt') }}</span>
				</div>
				<div class="float-right">
					<label class="switch" >
						<input type="checkbox" id="orders">
						<span class="slider round"></span>
					</label>
				</div>
				
	
				
				<div class="menu-notification">
					<span class="font16"><span class="tx-bold tx-navy">{{ trans('seller.Users') }}:</span> {{ trans('seller.Order') }}</span>
					<br>
					<span class="tx-jet">{{ trans('seller.Receive_email_summary_txt') }}</span>
				</div>
				<div class="float-right">
					<label class="switch" >
						<input type="checkbox" id="orders">
						<span class="slider round"></span>
					</label>
				</div>
				
				
				<div class="menu-notification">
					<span class="font16"><span class="tx-bold tx-navy">{{ trans('seller.Users') }}:</span> {{ trans('seller.Receipt') }}</span>
						<br>
					<span class="tx-jet">{{ trans('seller.Receive_email_receipt_txt') }}</span>
				</div>
				<div class="float-right">
					<label class="switch" >
						<input type="checkbox" id="orders">
						<span class="slider round"></span>
					</label>
				</div>
				
			</div>


			<a onclick="$('.menu-notification-xs').toggle()">
				<div class="submenu-xs-li">
					<img src="/img/icones/bell.svg">
				</div>
			</a>

			<div class="menu-notification-xs">

				<img src="/img/icones/ship.svg">
								
				<div class="menu-notification-xs-desc">
					<span class="tx-navy">Encomenda A00082-Braga</span>
					
					<br>
					<span class="tx-jet">Encomenda parcialmente expedida</span>
					<br>
					<span class="menu-config-line-date">2019-05-30</span>
				</div>	
				
			</div>
		</div>
	</div>
</header>