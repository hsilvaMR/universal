@extends('site_v2/layouts/default')
@section('content')

<div class="mod-product @if($array_produto['tipo'] == 'gourmet_alho') bg-yellow @elseif($array_produto['tipo'] == 'gourmet_azeitona') bg-lilac @else bg-yellow @endif">
	<div class="container mod-product-bottom">
        <div class="row">
          <div class="col-md-4 offset-md-1 tx-right">
            <img class="product-section1-img"  src="/site_v2/img/site/{{ $array_produto['section1_img'] }}">
          </div>
          <div class="offset-md-1 col-md-5">
            <h1 class="slide-prod-tit">{!! $array_produto['section1_tit'] !!}</h1>
            <h3 class="slide-prod-txt">{!! $array_produto['section1_txt'] !!}</h3>
            <button id="bt-range" class="bt-blue">{!! $array_produto['section1_bt'] !!}</button>
          </div>
        </div>
    </div>
    <div class="mod-frame-product"></div>
</div>

<div id="section2" class="mod-padding120 bg-gray">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 offset-lg-2">
				<div class="product-section2-img">
					<img  src="/site_v2/img/site/{{ $array_produto['section2_img'] }}">
				</div>
			</div>
			<div class="col-lg-5 offset-lg-1">
				<div class="product-section2-txt">
					<h1 class="tx-navy">{!! $array_produto['section2_tit'] !!}</h1>
					<p>{!! $array_produto['section2_txt'] !!}</p>

					{!! $array_produto['section2_li'] !!}

					<button id="bt-ing" class="bt-blue bt-margin">{{ trans('site_v2.INGREDIENTS') }}</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="section3" class="bg-white">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5">
				<div class="mod-padding120 product-section3-txt">
					<h1 class="tx-navy">{!! $array_produto['section3_tit'] !!}</h1>
					<p>{!! $array_produto['section3_txt'] !!}</p>

					<div dir="rtl" class="product-section3-li">{!! $array_produto['section3_li'] !!}</div>

					<button id="bt-nutri" class="bt-blue">{{ trans('site_v2.NUTRITIONAL_DECLARATION') }}</button>
				</div>
			</div>
			<div class="col-md-6 offset-md-1">
				<div class="product-section3-fd-img" style="background:url('/site_v2/img/site/{{ $array_produto['section3_fd'] }}') 100% 50%;">
					<div class="col-md-5">
						<div class="product-section3-div-img">
							<img class="product-section3-img" src="/site_v2/img/site/{{ $array_produto['section3_img'] }}">
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>

<div id="section4" class="bg-gray">
	<div class="mod-padding120">
		<div class="container">
			<div class="row">
				<div class="col-md-10 offset-md-1">
					<h2>{{ trans('site_v2.Nutritional_Declaration') }}</h2>

					<div class="product-table">
						<table class="table">
							
							<tbody>  
								
								<tr class="bg-blue tx-white">
									<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
									<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
								</tr>

								<tr class="table-bordered">
								    <td class="table-border"><b class="tx-jet">{{ trans('site_v2.Energy') }}</b> (Kj / Kcal)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['energia'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border-right"><b class="tx-jet">{{ trans('site_v2.Lipids') }}</b> (g)</td>
								    <td class="table-border-right tx-right product-sm">{!! $array_produto['lipidos'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border">...{{ trans('site_v2.of_which_saturated') }} (g)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['lipidos_sat'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border-right"><b class="tx-jet">{{ trans('site_v2.Carbohydrates') }}</b> (g)</td>
								    <td class="table-border-right tx-right product-sm">{!! $array_produto['hidratos_carbono'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border">...{{ trans('site_v2.of_which_sugars') }} (g)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['hidratos_carbono_acucares'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border"><b class="tx-jet">{{ trans('site_v2.Proteins') }}</b> (g)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['proteinas'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border-right"><b class="tx-jet">{{ trans('site_v2.Salt') }}</b> (g)</td>
								    <td class="table-border-right tx-right product-sm">{!! $array_produto['sal'] !!}</td>
								</tr>

								@if($array_produto['fibras'] != '')
									<tr class="table-bordered">
									    <td class="table-border-right"><b class="tx-jet">{{ trans('site_v2.Fibers') }}</b> (g)</td>
									    <td class="table-border-right tx-right product-sm">{!! $array_produto['fibras'] !!}</td>
									</tr>
								@endif

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('css')
@stop

@section('javascript')
	<script>
		$('.header-span').css('color','#333');
		//$('.header-span a').css('color','#333');
		$('.header-submenu a').css('color','#333');
		$('.header-area-span').css('color','#333');
	</script>

	<script>
		$("#bt-range").click(function() {
		    $('html,body').animate({
		        scrollTop: $("#section2").offset().top},
		        'slow');
		});

		$("#bt-ing").click(function() {
		    $('html,body').animate({
		        scrollTop: $("#section3").offset().top},
		        'slow');
		});

		$("#bt-nutri").click(function() {
		    $('html,body').animate({
		        scrollTop: $("#section4").offset().top},
		        'slow');
		});
	</script>
@stop