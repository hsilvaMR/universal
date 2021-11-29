@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3"> @include('seller/includes/menu') </div>
      <div class="col-lg-9">
        <div class="mod-tit">
          <h3>{{ trans('seller.Points_History') }}</h3>
        </div>
        
        <div class="mod-area" style="padding-top: 10px;">
          <h3>{{ trans('seller.Points_total') }}: <span class="tx-navy">{{ $total_pontos }}</span></h3>

          <div class="modulo-table information-table margin-top50">
            <div class="modulo-scroll">
              <table class="modulo-body" id="sortable">
                <thead>
                  <tr>
                    <th>{{ trans('seller.Order_Reference') }}</th>
                    <th>{{ trans('seller.Points') }}</th>
                    <th>{{ trans('seller.Validate') }}</th>
                    <th>{{ trans('seller.Status') }}</th>
                  </tr>
                </thead>
                <tbody class="modulo-body">
                  @foreach($pontos as $val)
                    <tr>
                      <td class="tx-bold">{{ $val->id_enc }}</td>
                      <td>{{ $val->pontos }}</td>
                      <td>{{ date('Y-m-d',$val->validade) }}</td>
                      <td>
                        @if($val->estado_pontos == 'indisponivel') 
                          <i class="fas fa-circle tx-red margin-right5"></i> {{ trans('seller.unavailable') }} 
                        @else 
                          <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.available') }} 
                        @endif
                      </td>
                    </tr>
                  @endforeach
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
<!-- PAGINAR -->
<link href="{{ asset('/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<!-- ORDENAR -->
<script src="{{ asset('/vendor/sortable/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>


<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']], 
    });
    $('#sortable_filter').html('<a href="{{ route('changePointsV2') }}"><button class="bt bt-blue" style="margin-bottom:30px;"><i class="fas fa-exchange-alt"></i> {{ trans('seller.Change_Points') }}</button></a>');
    $('.paginate_button i').addClass('fas');
    $('.sorting').css('background-image','url()');
    $('.sorting_asc').css('background-image','url()');

    if({!! $count_pontos !!} == 0){
      $('#sortable_paginate').hide();
    }
    
  });
</script>
@stop