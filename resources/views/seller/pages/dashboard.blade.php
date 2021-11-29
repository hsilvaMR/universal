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
          <div class="row">
            <div class="col-md-4">
              <div class="dashboard-col-resume">
                <img src="img/icones/orders.png">
                <p>{{ trans('seller.Orders') }}</p>
                <div class="dashboard-col-desc">
                  <span class="tx-bold">{{ $count_enc_concluida }}</span> {{ trans('seller.completeds') }}<br>
                  <span class="tx-bold">{{ $count_enc_proc }}</span> {{ trans('seller.processing') }}<br>
                  <i class="fas fa-exclamation-triangle"></i> <span class="tx-bold">{{ $valor_divida }} €</span> {{ trans('seller.in_debt') }}
                </div>
                <a href="{{ route('ordersV2') }}"><button><i class="fas fa-plus"></i></button></a>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="dashboard-col-resume">
                <img src="img/icones/addresses.png">
                <p>{{ trans('seller.Purchase_Addresses') }}</p>
                <div class="dashboard-col-desc">
                  <span class="tx-bold">{{ $end_compras_count }}</span> {{ trans('seller.adresses') }}
                </div>
                <a href="{{ route('addressPurchaseV2') }}"><button><i class="fas fa-plus"></i></button></a>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="dashboard-col-resume">
                <img src="img/icones/users.png">
                <p>{{ trans('seller.Users') }}</p>
                <div class="dashboard-col-desc">
                  <span class="tx-bold">{{ $user_admin }}</span> {{ trans('seller.admins') }}<br>
                  <span class="tx-bold">{{ $user_gestor }}</span> {{ trans('seller.managers') }}<br>
                  <span class="tx-bold">{{ $user_comerciante }}</span> {{ trans('seller.commercial') }}
                </div>
                <a href="{{ route('usersV2') }}"><button><i class="fas fa-plus"></i></button></a>
              </div>
            </div>
          </div>

          <div class="dashboard-table">
            <div class="dashboard-table-tit">
              <h3>{{ trans('seller.Latest_Orders') }}</h3>
            </div>
            <div class="modulo-table modulo-padding">
              <div class="modulo-scroll">
                <table class="modulo-body" id="sortable" >
                  <thead>
                    <tr>
                      <th>{{ trans('seller.Reference') }}</th>
                      <th>{{ trans('seller.Date') }}</th>
                      <th>{{ trans('seller.Status') }}</th>
                      <th>{{ trans('seller.Last_document') }}</th>
                      <th>{{ trans('seller.Value') }}</th>
                      <th>{{ trans('seller.User') }}</th>
                      <th class="background-none"></th>
                      <th class="background-none"></th>
                    </tr>
                  </thead>
                  <tbody id="ultimas_enc">
                    @foreach($ultimas_enc as $ultimas)
                      <tr id="enc_{{ $ultimas->id_encomenda }}">
                        <input type="hidden" class="ordem_atual" name="ordem" value="">
                        <td class="tx-bold">{{ $ultimas->id_encomenda }}</td>
                        <td>{{ date('Y-m-d',$ultimas->data) }}</td>
                        @if($ultimas->estado_enc == 'fatura_vencida')
                          <td class="tx-orange"><i class="fas fa-circle margin-right5"></i> {{ trans('seller.overdue_invoice') }}</td>
                        @elseif($ultimas->estado_enc == 'registada')
                          <td><i class="fas fa-circle dashboard-invoice-resgisted"></i> {{ trans('seller.resgisted') }}</td>
                        @elseif($ultimas->estado_enc == 'em_processamento')
                          <td><i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.processing') }}</td>
                        @elseif($ultimas->estado_enc == 'expedida_parcialmente')
                          <td><i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.partially_dispatched') }}</td>
                        @elseif($ultimas->estado_enc == 'expedida')
                          <td><i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.spent') }}</td>
                        @else
                          <td><i class="fas fa-circle dashboard-invoice-completed"></i> {{ trans('seller.completed') }}</td>
                        @endif

                        <td class="dashboard-table-file">
                          <a class="tx-navy" @if(file_exists(base_path('public_html/doc/orders/'.$ultimas->documento))) href="/doc/orders/{{ $ultimas->documento }}" @else href="{{ route('ordersPdfV2',[$ultimas->id_encomenda,$company->id]) }}" @endif ><i class="far fa-file-pdf"></i> {{ $ultimas->documento }}</a>
                        </td>
                        <td class="dashboard-table-value">{{ $ultimas->total }} €</td>
                        <td><i class="far fa-user"></i> {{ $ultimas->nome }}</td>
                        @if($ultimas->estado_enc == 'concluida')
                          <td class="tx-gray"><i class="fas fa-times"></i> {{ trans('seller.cancel') }}</td>
                        @else
                          <td class="dashboard-table-cancel">
                            <span onclick="$('#id_modal').val({{ $ultimas->id_encomenda }});" data-toggle="modal" data-target="#myModalCancel">
                            <i class="fas fa-times"></i> {{ trans('seller.cancel') }}</span>
                          </td>
                        @endif
                        <td class="dashboard-table-details">
                          <a href="{{ route('ordersDetailsV2',$ultimas->id_encomenda) }}" class="tx-navy">
                            <i class="far fa-eye"></i> {{ trans('seller.details') }}
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="dashboard-table-button">
              <a href="{{ route('ordersV2') }}">
                <button class="bt-gray margin-bottom10 margin-right10"><i class="fas fa-bars"></i> {{ trans('seller.See_All')}}</button>
              </a>
              <a href="{{ route('ordersNewV2') }}"><button class="bt-blue"><i class="fas fa-plus"></i> {{ trans('seller.New_Order') }}</button></a>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="dashboard-col-add">
                <div class="dashboard-table-tit">
                  <h3>{{ trans('seller.Users') }}</h3>
                </div>

                <div class="modulo-table modulo-padding">
                  <div class="modulo-scroll">
                    <table class="modulo-body" id="sortable_user">
                      <thead>
                        <tr>
                          <th>{{ trans('seller.Name') }}</th>
                          <th class="tx-right">{{ trans('seller.Orders') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($users as $user)
                          <tr>
                            <td>
                              @if($user->foto) <img class="dashboard-col-add-1" src="img/comerciantes/{{ $user->foto }}">
                              @else <img class="dashboard-col-add-1" src="img/comerciantes/default.svg">
                              @endif
                              {{ $user->nome }} @if($user->id == Cookie::get('cookie_comerc_id'))({{ trans('seller.I') }})@endif
                            </td>
                            @foreach($user_array as $array)
                              @if($user->id == $array['id'])
                                <td class="dashboard-col-add-2">{{ $array['count_enc'] }}</td>
                              @endif
                            @endforeach
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="dashboard-col-add-button">
                  <a href="{{ route('usersV2') }}"><button class="bt-gray margin-right10 margin-bottom10"><i class="fas fa-bars"></i> {{ trans('seller.See_All')}}</button></a>
                  <a href="{{ route('usersV2') }}"><button class="bt-blue"><i class="fas fa-plus"></i> {{ trans('seller.Add') }}</button></a>
                </div>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="dashboard-col-add">
                <div class="dashboard-table-tit">
                  <h3>{{ trans('seller.Purchase_Addresses') }}</h3>
                </div>
                <div class="modulo-table modulo-padding">
                  <div class="modulo-scroll">
                    <table class="modulo-body" id="sortable_adress">
                      <thead>
                        <tr>
                          <th>{{ trans('seller.Adresses') }}</th>
                          <th class="tx-right">{{ trans('seller.Orders') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($end_compras as $end)
                          <tr>
                            <td>{{ $end->nome_personalizado }}</td>
                            @foreach($end_array as $array)
                              @if($end->id == $array['id'])
                                <td id="enc_armz_{{ $array['id'] }}" class="dashboard-col-add-2">{{ $array['count_enc'] }}</td>
                              @endif
                            @endforeach
                          </tr>
                        @endforeach
                        @if($end_compras_count == 0)
                          <tr>
                            <td>Sem dados disponíveis.</td>
                            <td></td>
                          </tr>
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="dashboard-col-add-button">
                  <a href="{{ route('addressPurchaseV2') }}">
                    <button class="bt-gray margin-right10 margin-bottom10"><i class="fas fa-bars"></i> {{ trans('seller.See_All')}}</button>
                  </a>
                  <a href="{{ route('addressPurchaseV2') }}">
                    <button class="bt-blue"><i class="fas fa-plus"></i> {{ trans('seller.Add') }}</button>
                  </a>
                </div>
              </div>
            </div> 

            <div class="col-md-4">
              <div class="dashboard-col-add">
                <div class="dashboard-table-tit">
                  <h3>{{ trans('seller.Order_Status') }}</h3>
                </div>

                <div class="modulo-table modulo-padding">
                  <div class="modulo-scroll">
                    <table class="modulo-body" id="sortable">
                      <thead>
                        <tr>
                          <th>{{ trans('seller.Status') }}</th>
                          <th class="tx-right">{{ trans('seller.Orders') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="tx-orange"><i class="fas fa-circle margin-right5"></i> {{ trans('seller.overdue_invoice') }}</td>
                          <td id="enc_vencida" class="dashboard-col-add-2">{{ $count_enc_vencida }}</td>
                        </tr>
                        <tr>
                          <td><i class="fas fa-circle dashboard-invoice-resgisted"></i> {{ trans('seller.resgisted') }}</td>
                          <td id="enc_registada" class="dashboard-col-add-2">{{ $count_enc_registada }}</td>
                        </tr>
                        <tr>
                          <td><i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.processing') }}</td>
                          <td id="enc_proc" class="dashboard-col-add-2">{{ $count_enc_proc }}</td>
                        </tr>
                        <tr>
                          <td><i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.partially_dispatched') }}</td>
                          <td id="enc_exp_parcial" class="dashboard-col-add-2">{{ $count_enc_exp_parcial }}</td>
                        </tr>
                        <tr>
                          <td><i class="fas fa-circle dashboard-invoice-spent"></i> {{ trans('seller.spent') }}</td>
                          <td id="enc_exp" class="dashboard-col-add-2">{{ $count_enc_exp }}</td>
                        </tr>
                        <tr>
                          <td><i class="fas fa-circle dashboard-invoice-completed"></i> {{ trans('seller.completed') }}</td>
                          <td id="enc_concluida" class="dashboard-col-add-2">{{ $count_enc_concluida }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="dashboard-col-add-button-end">
                  <a href="{{ route('ordersV2') }}"><button class="bt-gray margin-right10 margin-bottom10"><i class="fas fa-bars"></i> {{ trans('seller.See_All')}}</button></a>
                  <a href="{{ route('ordersNewV2') }}"><button class="bt-blue"><i class="fas fa-plus"></i> {{ trans('seller.Add') }}</button></a>
                </div>
              </div>
            </div>           
          </div>

          <!-- Modal Delete-->
          <div class="modal fade" id="myModalCancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <input type="hidden" name="id_modal" id="id_modal">
                <div class="modal-header">
                  <h3>{{ trans('seller.Cancel_Order') }}</h3>
                  <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body mod-area">
                  <label>{{ trans('seller.Cancel_Order_txt') }}</label>
                  <div>
                    <!--<button class="bt-transparent tx-gray" data-dismiss="modal"><i class="fas fa-times"></i>{ { trans('seller.Cancel') }}</button>-->
                    <button class="bt-blue" data-dismiss="modal" onclick="cancelOrder();"><i class="fas fa-check"></i>{{ trans('seller.Cancel') }}</button>
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
<!-- PAGINAR -->
<link href="{{ asset('/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){

    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 6 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
      order: [[ 2, "desc" ]],
    });
    

    $('#sortable_length').hide();
    $('#sortable_filter').hide();
    $('#sortable_info').hide();
    $('#sortable_paginate').hide();

  });
</script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){

    $('#sortable_user').dataTable({
      order: [[ 0, "asc" ]],
    });
    

    $('#sortable_user_length').hide();
    $('#sortable_user_filter').hide();
    $('#sortable_user_info').hide();
    $('#sortable_user_paginate').hide();

  });
</script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){

    $('#sortable_adress').dataTable({
      order: [[ 1, "desc" ]],
    });
    

    $('#sortable_adress_length').hide();
    $('#sortable_adress_filter').hide();
    $('#sortable_adress_info').hide();
    $('#sortable_adress_paginate').hide();

  });
</script>
@stop