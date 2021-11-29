@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3"> @include('seller/includes/menu') </div>
      <div class="col-lg-9">
  
        <div class="row">
          <div class="col-md-6">
            <div class="div-support margin-bottom20">
              <div class="div-support-desc"><i class="fas fa-ticket-alt"></i> <div class="div-support-tit">{{ trans('seller.Ticket') }}</div></div>
              <h3 class="div-support-txt">{{ trans('seller.Ticket_txt') }}</h3>
              <a href="{{ route('newTicketV2')}}"><button class="bt bt-blue">{{ trans('seller.Creat_Ticket') }}</button></a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="div-support margin-bottom10">
              <div class="div-support-desc"><i class="fas fa-phone"></i> <div class="div-support-tit">{{ trans('seller.Telephone') }}</div></div>
              <h3 class="div-support-txt">{{ $msg_ticket->valor }}</h3>
            </div>
          </div>
        </div>

        <div class="mod-tit margin-top40"><h3>{{ trans('seller.Support') }}</h3></div>

        <div class="mod-area">
          <table class="modulo-body" id="sortable" >
            <thead>
              <tr>
                <th>{{ trans('seller.Subject') }}</th>
                <th>{{ trans('seller.Date') }}</th>
                <th>{{ trans('seller.Status') }}</th>
              </tr>
            </thead>
            <tbody id="linha_tbody">
              @foreach($tickets as $ticket)
                <tr>
                  <td class="cursor-pointer">
                    <a href="{{ route('msgTicketV2',$ticket['id'])}}">
                      {{ $ticket['assunto'] }}<br>
                      <span class="tx-navy">#{{ $ticket['id'] }}</span>
                    </a>
                  </td>
                  <td>{{ date('Y-m-d',$ticket['data']) }}</td>
                  <td>{!! $ticket['estado'] !!}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 2 ], "ordering": false }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
      order: [[ 0, "asc" ]],
    });

    //$('#sortable_length').hide(); 
    $('#sortable_filter input').attr('placeholder', ' {{ trans('seller.Search') }}');
    $('#sortable_length').hide();
    $('#sortable_info').hide();
    $('#sortable_paginate').hide();
  });
</script>
@stop