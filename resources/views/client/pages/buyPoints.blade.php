@extends('client/layouts/default-menu')
@section('content')
<section class="min-height">
  
  <div class="codes-bg" style="height:170px;"></div>
  <div class="universal-frame"></div>

  <div style="background-color:#fbfbfb;padding-top: 120px;">
    <h1 class="tx-navy tx-center" style="font-family:'Roboto Slab',serif;font-weight:100;">{{ trans('site_v2.buy_points_tit') }}</h1>

    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <span style="margin:40px 0px;font-size:18px;color:#333;font-family:'Roboto',serif;font-weight:300;">
            {{ trans('site_v2.buy_points_txt') }}
          </span>
        </div>
      </div>

      <div style="padding-bottom:120px;">
        <div class="cart overflowX">
          <table class="table table-striped table-datos" style="margin:0px;">
            <thead>
              <tr class="table-thead font16" style="background-color:#fbfbfb;border-bottom: 1px solid #eee;border-top: 1px solid #eee;">
                
                <th class="cart-table" scope="col" style="padding-left:30px!important;">{{ trans('site_v2.Article') }}</th>
                
                <th scope="col" class="tx-right cart-table">{{ trans('site_v2.Amount') }}</th>
                <th scope="col" class="tx-right cart-table">{{ trans('site_v2.Points') }}</th>
                <th scope="col" class="tx-right cart-table">{{ trans('site_v2.Value') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr class="cart-height-line">
                
                <td class="cart-width-td" scope="row" style="white-space:nowrap;">

                  <div class="row">

                    <div class="col-lg-5">
                      <i class="fas fa-times cart-icon-delete"></i>

                      <div class="cart-div-img" style="float:none;margin-left:25px;margin-top:10px;"><img src="site_v2/img/premios/bola.png"></div>

                    </div>
                    <div class="col-lg-7">
                      <div class="margin-top20" style="margin-left:10px;margin-bottom:20px;"><span style="font-size: 14px;">{{ trans('site_v2.BeachBall') }}</span><br><span style="font-size:12px;">{{ trans('site_v2.BeautifulBall_txt') }}</span></div>
                    </div>                    
                  </div>
                </td>
                
                <td scope="row" class="cart-td cart-width-td">1</td>
                <td class="cart-td cart-width-td tx-red">30</td>
                <td class="cart-td cart-width-td">0,00€</td>
              </tr>

              <tr class="cart-height-line" style="white-space:nowrap;">
                <td class="cart-width-td">
                  <div class="row">
                    <div class="col-lg-5">
                      <div class="cart-div-cupao" style="float:left;line-height:90px;"><img style="margin-left:25px;" src="site_v2/img/cart/carrinho-pontos.png"></div>
                    </div>
                    <div class="col-lg-7">
                      <div class="margin-top20" style="margin-left:10px;margin-bottom:20px;"><span>{{ trans('site_v2.Points') }} x 20
                      </span><br><span>{{ trans('site_v2.Missing_Points') }}</span></div>
                    </div>
                  </div>
                </td>
                <td class="cart-td cart-width-td">1</td>
                <td class="cart-td cart-width-td">20</td>
                <td class="cart-td cart-width-td">10,00€</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="bg-gradient-blue">
  <div class="premium-banner">
    <div class="container">
      <h1>{{ trans('site_v2.DiscoverAllOffers') }}</h1>
      <a href="{{ route('areaReservedV2') }}"><button class="bt-gray tx-transform">{{ trans('site_v2.View_All_Awards') }}</button></a>
    </div>
  </div>
</div>
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');
  </script>
@stop