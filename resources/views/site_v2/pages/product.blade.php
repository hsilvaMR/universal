@extends('site_v2/layouts/default')
@section('content')

<div class="mod-product 
	@if($array_produto['tipo'] == 'cheese') bg-yellow 
	@elseif($array_produto['tipo'] == 'butter') bg-lilac 
	@elseif($array_produto['tipo'] == 'gourmet_alho') bg-pastel
	@elseif($array_produto['tipo'] == 'gourmet_azeitona') bg-green-baby
	@elseif($array_produto['tipo'] == 'sem_sal') bg-blue-baby 
	@elseif($array_produto['tipo'] == 'extra_curado') bg-terracota 
	@elseif($array_produto['tipo'] == 'prato') bg-alface
	@else bg-lilac @endif">
	<div class="container mod-product-bottom">
        <div class="row">
          <div class="col-md-4 offset-md-1">
            @if($array_produto['section1_img'] != '')
            	<img class="product-section1-img"  src="/site_v2/img/site/{{ $array_produto['section1_img'] }}">
            @endif
          </div>
          <div class="offset-md-1 col-md-5">
            <h1 @if($array_produto['tipo'] == 'extra_curado') style="color:#fff;" @endif class="slide-prod-tit">{!! $array_produto['section1_tit'] !!}</h1>
            <h3 class="slide-prod-txt">{!! $array_produto['section1_txt'] !!}</h3>
            <button id="bt-range" class="bt-blue">{!! $array_produto['section1_bt'] !!}</button>
          </div>
        </div>
    </div>
    <div class="mod-frame-product"></div>
</div>


<div id="section2" class="@if(($array_produto['tipo'] != 'sem_sal') && ($array_produto['tipo'] != 'gourmet_alho') && ($array_produto['tipo'] != 'gourmet_azeitona')) mod-padding120 @else mod-padding60 @endif bg-gray">
	@if(($array_produto['tipo'] != 'sem_sal') && ($array_produto['tipo'] != 'gourmet_alho') && ($array_produto['tipo'] != 'gourmet_azeitona'))
		<div class="container">
			<div class="row">
				<div class="col-lg-3 offset-lg-2">
					<div class="product-section2-img">
						@if($array_produto['section2_img'] != '')
							<img  src="/site_v2/img/site/{{ $array_produto['section2_img'] }}">
						@endif
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
	@endif
</div>


<div id="section3" class="bg-white">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-5">
				<div class="mod-padding120 product-section3-txt">
					<h1 class="tx-navy">{!! $array_produto['section3_tit'] !!}</h1>
					<div><p>{!! $array_produto['section3_txt'] !!}</p></div>
					<div dir="rtl" class="product-section3-li">{!! $array_produto['section3_li'] !!}</div>

					<button id="bt-nutri" class="bt-blue">{{ trans('site_v2.NUTRITIONAL_DECLARATION') }}</button>
				</div>
			</div>
			<div class="col-md-6 offset-md-1">
				<div class="product-section3-fd-img" style="background:url('/site_v2/img/site/{{ $array_produto['section3_fd'] }}') 100% 50%;">
					<div class="col-md-5">
						<div class="product-section3-div-img">
							@if($array_produto['section3_img'] != '')
								<img class="product-section3-img" src="/site_v2/img/site/{{ $array_produto['section3_img'] }}">
							@endif
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
								@if($array_produto['tipo'] == 'cheese')
									<tr class="bg-blue tx-white">
										<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
										<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
										<th class="tx-right table-header product-md">{{ trans('site_v2.by_small_ball') }} c/700g</th>
										<th class="tx-right table-header product-lg">{{ trans('site_v2.by_ball') }} c/1,5kg</th>
									</tr>
								@elseif($array_produto['tipo'] == 'butter')
									<tr class="bg-blue tx-white">
										<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
										<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
										<th class="tx-right table-header product-md">{{ trans('site_v2.per_pack_of') }} 10g</th>
										<th class="tx-right table-header product-lg">{{ trans('site_v2.per_pack_of') }} 250g</th>
									</tr>
								@elseif($array_produto['tipo'] == 'extra_curado')
									<tr class="bg-blue tx-white">
										<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
										<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
										<th class="tx-right table-header product-md">{{ trans('site_v2.per') }} 300g</th>
										<th class="tx-right table-header product-lg">{{ trans('site_v2.by_ball') }} c/1,5kg</th>
									</tr>
								@elseif($array_produto['tipo'] == 'prato')
									<tr class="bg-blue tx-white">
										<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
										<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
										<th class="tx-right table-header product-md">{{ trans('site_v2.per') }} 700g</th>
										<th class="tx-right table-header product-lg">{{ trans('site_v2.per') }} 1,45kg</th>
									</tr>
								@elseif($array_produto['tipo'] == 'sem_sal')
									<tr class="bg-blue tx-white">
										<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
										<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
										<th class="tx-right table-header product-lg">{{ trans('site_v2.by_ball') }} c/650g</th>
									</tr>
								@elseif($array_produto['tipo'] == 'gourmet_alho' || $array_produto['tipo'] == 'gourmet_azeitona')
									<tr class="bg-blue tx-white">
										<th class="table-header">{{ trans('site_v2.Nutritional_Declaration') }}</th>
										<th class="tx-right table-header product-sm">{{ trans('site_v2.per') }} 100g</th>
										<th class="tx-right table-header product-lg">{{ trans('site_v2.by_ball') }} c/400g</th>
									</tr>

								@endif

								<tr class="table-bordered">
								    <td class="table-border"><b class="tx-jet">{{ trans('site_v2.Energy') }}</b> (Kj / Kcal)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['energ_min'] !!}</td>
								    @if($array_produto['energ_inter'] != '')
								    	<td class="table-border tx-right product-md">{!! $array_produto['energ_inter'] !!}</td>
								    @endif
								    <td class="table-border-bottom tx-right product-lg">{!! $array_produto['energ_max'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border-right"><b class="tx-jet">{{ trans('site_v2.Lipids') }}</b> (g)</td>
								    <td class="table-border-right tx-right product-sm">{!! $array_produto['lipidos_min'] !!}</td>
								    @if($array_produto['lipidos_inter'] != '')
								    	<td class="table-border-right tx-right product-md">{!! $array_produto['lipidos_inter'] !!}</td>
								    @endif
								    <td class="table-border-padding tx-right product-lg">{!! $array_produto['lipidos_max'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border">...{{ trans('site_v2.of_which_saturated') }} (g)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['lipidos_sat_min'] !!}</td>
								    @if($array_produto['lipidos_sat_inter'] != '')
								    	<td class="table-border tx-right product-md">{!! $array_produto['lipidos_sat_inter'] !!}</td>
								    @endif
								    <td class="table-border-bottom tx-right product-lg">{!! $array_produto['lipidos_sat_max'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border-right"><b class="tx-jet">{{ trans('site_v2.Carbohydrates') }}</b> (g)</td>
								    <td class="table-border-right tx-right product-sm">{!! $array_produto['hidratos_min'] !!}</td>
								    @if($array_produto['hidratos_inter'] != '')
								    	<td class="table-border-right tx-right product-md">{!! $array_produto['hidratos_inter'] !!}</td>
								    @endif
								    <td class="table-border-padding tx-right product-lg">{!! $array_produto['hidratos_max'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border">...{{ trans('site_v2.of_which_sugars') }} (g)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['hidratos_acu_min'] !!}</td>
								    @if($array_produto['hidratos_acu_inter'] != '')
								    	<td class="table-border tx-right product-md">{!! $array_produto['hidratos_acu_inter'] !!}</td>
								    @endif
								    <td class="table-border-bottom tx-right product-lg">{!! $array_produto['hidratos_acu_max'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="table-border"><b class="tx-jet">{{ trans('site_v2.Proteins') }}</b> (g)</td>
								    <td class="table-border tx-right product-sm">{!! $array_produto['proteinas_min'] !!}</td>
								    @if($array_produto['proteinas_inter'] != '')
								    	<td class="table-border tx-right product-md">{!! $array_produto['proteinas_inter'] !!}</td>
								    @endif
								    <td class="table-border-bottom tx-right product-lg">{!! $array_produto['proteinas_max'] !!}</td>
								</tr>
								<tr class="table-bordered">
								    <td class="@if($array_produto['tipo'] == 'gourmet_alho' || $array_produto['tipo'] == 'gourmet_azeitona') table-border @else table-border-right @endif"><b class="tx-jet">{{ trans('site_v2.Salt') }}</b> (g)</td>
								    <td class="@if($array_produto['tipo'] == 'gourmet_alho' || $array_produto['tipo'] == 'gourmet_azeitona') table-border @else table-border-right @endif tx-right product-sm">{!! $array_produto['sal_min'] !!}</td>
								    @if($array_produto['sal_inter'] != '')
								    	<td class="@if($array_produto['tipo'] == 'gourmet_alho' || $array_produto['tipo'] == 'gourmet_azeitona') table-border @else table-border-right @endif tx-right product-md">{!! $array_produto['sal_inter'] !!}</td>
								    @endif
								    <td class=" @if($array_produto['tipo'] == 'gourmet_alho' || $array_produto['tipo'] == 'gourmet_azeitona') table-border @else table-border-padding @endif tx-right product-lg">{!! $array_produto['sal_max'] !!}</td>
								</tr>
								@if($array_produto['tipo'] == 'gourmet_alho' || $array_produto['tipo'] == 'gourmet_azeitona')
									<tr class="table-bordered">
									    <td class="table-border-right"><b class="tx-jet">Fibra</b> (g)</td>
									    <td class="table-border-right tx-right product-sm">{!! $array_produto['fibras_min'] !!}</td>
									    <td class="table-border-padding tx-right product-lg">{!! $array_produto['fibras_max'] !!}</td>
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
		@if($array_produto['tipo'] != 'extra_curado') 
			$('.header-span').css('color','#333'); 
			$('.header-submenu a').css('color','#333'); 
			$('.header-area-span').css('color','#333');
		@endif
		@if($array_produto['tipo'] == 'extra_curado') 
			$('.header-span').css('color','#fff'); 
			$('.header-span a').css('color','#fff'); 
			$('.header-submenu a').css('color','#fff'); 
			$('.header-area-span').css('color','#fff');
			
		@endif
		//$('.header-span a').css('color','#333');
		
		
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