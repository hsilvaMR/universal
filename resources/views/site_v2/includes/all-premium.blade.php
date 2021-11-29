<div class="mod-padding120 bg-gray">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<h1 class="tx-center tx-navy">{!! $conteudos['sect_premium_slide_tit'] !!}</h1>

				<div class="margin-bottom20">
				  	<div class="container-list"><!--style="display:flex;flex-wrap:wrap;justify-content:flex-start;width:960px;"-->
				  		<div class="row">
				  			@foreach($premium as $val)
								<div class="col-lg-3 col-sm-6">
									<div class="premium-center">
										<div class="premium-div" style="background-image:url({{ $val['img'] }});">
											<!--<img src="{ { $val['img'] }}">-->
											
										</div>
										<div class="premium-div-desc">
											<span>{{ $val['name'] }}</span>
											<p class="tx-navy">{{ $val['value'] }} {{ trans('site_v2.Points') }}</p>
											<a href="{{ route('infoPremiumPageV2',$val['id']) }}"><button class="bt bt-blue">{{ trans('site_v2.TO_EXCHANGE') }}</button></a>
										</div>
									</div>
								</div>
							@endforeach
						</div>
				  	</div>
				</div>
			</div>
		</div>
	</div>
</div>