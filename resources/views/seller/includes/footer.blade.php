<footer style="background-color:#fff;">

	<div class="container-fluid">
		<div class="footer">
			<i id="angle-open" class="fas fa-angle-down footer-icon-condicoes" onclick="openFooterXS();"></i>
			<i id="angle-close" class="fas fa-angle-up footer-icon-condicoes display-none" onclick="openColseXS();"></i>
			<!--icons redes sociais-->
			<a href="https://www.facebook.com/queijouniversal" target="_blank"><i class="fab fa-facebook-square footer-icon-face"></i></a>
			<a href="https://www.instagram.com/universal_queijo/" target="_blank"><i class="fab fa-instagram footer-icon-insta"></i></a>


			<!--menu footer-->
			<a href="{{ route('contactsPageV2') }}"><span class="footer-menu margin-left45">{{ trans('seller.Contacts') }}</span></a>
			<a href="{{ route('termsPageV2') }}"><span class="footer-menu">{{ trans('seller.Terms_conditions') }}</span></a>
			<a href="{{ route('innovationPageV2') }}"><span class="footer-menu">{{ trans('seller.Incentives') }}</span></a>

			@if($separador == 'page_formSeller')
				<a href="{{ route('formRegisterClientPageV2') }}"><span class="footer-menu">{{ trans('seller.Client') }}</span></a>
			@else
				<a href="{{ route('formRegisterSellerPageV2') }}"><span class="footer-menu">{{ trans('seller.Traders') }}</span></a>
			@endif

			<!--Change the language
			<div class="footer-lang">
				<span id="lang" class="display-none">
					<a href="{ { route('languageGetV2','pt') }}">PT</a> | 
					<a href="{ { route('languageGetV2','en') }}">EN</a> | 
					<a href="{ { route('languageGetV2','es') }}">ES</a> | 
					<a href="{ { route('languageGetV2','fr') }}">FR</a> <i class="fas fa-angle-left" onclick="hideLang();"></i>
				</span>
				<span id="lang-present" onclick="showLang();">{ { trans('seller.site_language') }} <i class="fas fa-angle-left"></i></span>
				<span class="footer-angle" onclick="goup();"><i class="fas fa-angle-up"></i></span>
			</div>-->
		</div>

		<div id="condicoes" class="condicoes">
			<a href="{{ route('contactsPageV2') }}"><p>{{ trans('seller.Contacts') }}</p></a>
			<a href="{{ route('termsPageV2') }}"><p>{{ trans('seller.Terms_conditions') }}</p></a>
			<a href="{{ route('innovationPageV2') }}"><p>{{ trans('seller.Incentives') }}</p></a>
			@if($separador == 'page_formSeller')
				<a href="{{ route('formRegisterClientPageV2') }}"><p>{{ trans('seller.Client') }}</p></a>
			@else
				<a href="{{ route('formRegisterSellerPageV2') }}"><p>{{ trans('seller.Traders') }}</p></a>
			@endif
        </div>
	</div>

</footer>