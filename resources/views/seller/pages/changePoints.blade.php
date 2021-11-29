@extends('seller/layouts/default')
@section('content')

<div class="container-fluid mod-seller">
    
  @include('seller/includes/headerSubMenu')
  @include('seller/includes/menuSettings')
  @include('seller/includes/menuNotifications')

  <div class="row">
    <div class="col-lg-3">
        @include('seller/includes/menu')
    </div>
    <div class="col-lg-9">
      <div class="mod-tit">
          <h3>{{ trans('seller.Awards') }}</h3>
      </div>
        
      <div class="mod-area" id="demo">
        <div class="container">
          <div class="row">
            <div class="col-md-10 offset-md-1">  
              <div class="container-list">
                <div class="row">
                  @foreach($premium as $val)
                    <div class="col-lg-3 col-sm-6">
                      <div class="premium-div" style="background-image:url({{ $val['img'] }});background-position:center;background-size: contain; background-repeat:no-repeat;">
                        <!--<img src="{ { $val['img'] }}">-->

                        
                      </div>

                      <div class="premium-div-desc">
                        <div style="min-height:110px;">
                          <span>{{ $val['name'] }}</span>
                          <p class="tx-navy">{{ $val['value'] }} {{ trans('site_v2.Points') }}</p>
                        </div>
                        <a href="{{ route('premiumInfoV2',$val['id']) }}"><button class="bt bt-blue">{{ trans('site_v2.TO_EXCHANGE') }}</button></a>
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
  </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/css/swiper.min.css">
@stop

@section('javascript')
  <!-- Swiper -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.6/js/swiper.min.js"></script>
  <!--Pagination-->
  <script src="{{ asset('site_v2/js/pagination.js') }}"></script>

  <script>
    $('.swiper-slide-shadow-left').css('border-radius','50%');
    $('.swiper-slide-shadow-right').css('border-radius','50%');
    $('.header-xs i').css('color','#fff');
  </script>
@stop