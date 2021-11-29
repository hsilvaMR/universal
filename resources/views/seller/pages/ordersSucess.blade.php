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
          <h3>{{ trans('seller.New_Order') }}</h3>
        </div>
        <div class="mod-area tx-center">
          <img height="75" src="/img/icones/success.png">
          <div class="orders-sucess">
            <p>{{ trans('seller.Your_order_registered_txt') }} <span class="tx-bold">{{ $id }}</span>.</p>
            <p>{{ trans('seller.Track_your_status_txt') }} <a class="tx-navy cursor-pointer" href="{{ route('ordersV2') }}">{{ trans('seller.orders') }}</a></p>
          </div>
          <a href="{{ route('ordersV2') }}"><button class="bt bt-blue"><i class="fas fa-angle-left"></i> {{ trans('seller.Orders') }}</button></a>         
        </div>  
      </div>
    </div>                  
  </div>
@stop

@section('css')
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>
  //setTimeout(function() {window.location.href = '{{ route('ordersPdfV2',[$id,$company->id]) }}';}, 1000);

  var tempo = 0;
  @foreach($enc_armz as $val)
    tempo=tempo + 1000;
    setTimeout(function() {window.location.href = '{{ route('ordersAdressPdfV2',[$id,$val->id_morada,$company->id]) }}';}, tempo);
  @endforeach
</script>
@stop