
<div class="container-fluid" style="padding: 75px 0px 40px 0px;">
	<div class="row">
		<div class="col-lg-3">

			<a href="{{ route('companyDataV2') }}"><img class="data-img" id="logo_submenu" @if(empty(Cookie::get('cookie_company_photo'))) src="/img/empresas/company.svg" @else src="/img/empresas/{{ Cookie::get('cookie_company_photo') }}" @endif style="float:left;margin-right:20px;"></a>
			
			<div class="header-client-desc">
				<span id="company_name">{{ Cookie::get('cookie_company_name') }}</span>
				<br>
				<span class="header-client-name">{{ trans('seller.number_of_client') }}: {{ Cookie::get('cookie_comerc_id') }}</span>
			</div>
			
		</div>
		<div class="col-lg-9">
			<div class="header-client-pontos display-none">
				<label>Precisa de ajuda? <span>Consulte a nossa página de suporte</span>.</label>
			</div>
			<div>
			@if($company->estado == 'pendente')
	          	<label class="av-100 av-amarelo">
	          		{{ trans('seller.Company_pending_txt') }}
	          	</label>
	        @endif

	        @if($company->estado == 'reprovado')
	          	<label class="av-100 alert-danger">
	          		<label>{{ trans('seller.Disapproved_Company_Data_txt') }}</label>
	          		<br>
	          		{{ $company->nota }}
	          	</label>
	        @endif

	        @if($company->estado == 'em_aprovacao')
	          	<label class="av-100 av-amarelo">
	          		{!! trans('seller.Company_approval_txt') !!}
	          	</label>
	        @endif

	        @if($separador == 'Dados_Pessoais' || $separador == 'Dados_Empresa')
		        @if($seller->aprovacao == 'reprovado')
		          	<label class="av-100 alert-danger">
		          		<label>{{ trans('seller.Disapproved_personal_data_txt') }}</label>
		          		<br>
		          		{{ $seller->nota }}
		          	</label>
		        @endif
	        @endif

	        @if(isset($seller->email_alteracao) && ($seller->email_alteracao!=''))
	        	@include('seller/includes/modalEmail')
	        @endif
        </div>
		</div>
	</div>
</div>
