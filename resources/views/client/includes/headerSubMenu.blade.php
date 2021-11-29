
<div class="container header-client">
	<div class="row">
		<div class="col-lg-6">
			<div class="header-client-desc">
				<img id="img-menu" class="header-client-img" @if(Cookie::get('cookie_user_photo')) src="/img/clientes/{{ Cookie::get('cookie_user_photo') }}" @else src="{{ asset('/img/clientes/default.svg') }}" @endif>
				<span id="name_user">{{ $nome }}</span><br><span class="header-client-pontos-tx">{{ trans('site_v2.Your_points') }}: <span id="span_point" class="font-bold tx-navy">@if(Cookie::get('cookie_user_points') < 0) 0 @else {{ Cookie::get('cookie_user_points') }}@endif</span></span>
			</div>
		</div>
		<div class="col-lg-6">
			<form id="form-insertCodes" action="{{ route('insertCodesPostV2') }}" name="form" method="post">
				{{ csrf_field() }}

				<div class="header-client-pontos">
					<label>{{ trans('site_v2.Accumulate_Points') }}</label>
					<input id="insert-code" class="ip header-client-ip" maxlength="7" type="text" name="codes" placeholder="000 000"><button class="bt-50 bt-codes" type="submit"><i class="fas fa-plus"></i></button>	
				</div>
			</form>
		</div>

		<div style="padding:0px 15px;width:100%;text-align:right;">
			<label id="labelSucesso" class="av-100 alert-success display-none margin-bottom40" role="alert"><span id="spanSucesso">{{ trans('site_v2.Successfully_Introduced') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
	        <label id="labelErros" class="av-100 alert-danger display-none margin-bottom40" role="alert"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
        </div>
	</div>
</div>
