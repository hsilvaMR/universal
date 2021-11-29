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
          <h3>{{ trans('seller.Orders') }}</h3>
        </div>
        <div class="mod-area">
          @if($company->estado == 'em_aprovacao')
            <div class="alert alert-danger margin-bottom40" role="alert">
              {{ trans('seller.Account_approval_not_possible_orders_txt') }}
            </div>
          @elseif(count($fatura_vencida) >= 1)
            <div class="alert alert-danger margin-bottom40" role="alert">
              {{ trans('seller.Overdue_Invoice_not_possible_orders_txt') }}
            </div>
          @elseif(($company->estado == 'aprovado') && ($morada_armazem_count == 0) && ($seller->tipo != 'comerciante'))
            <div class="alert av-amarelo margin-bottom40" role="alert">
              {!! trans('seller.No_store_addresses_txt') !!}
            </div>
          @elseif(($company->estado == 'aprovado') && ($morada_armazem_count == 0) && ($seller->tipo == 'comerciante'))
            <div class="alert av-amarelo margin-bottom40" role="alert">
              Necessita de ter endereços de compras para realizar encomendas. Informe o seu Administrador para a inserção de endereços.
            </div>
          @endif
            @if(count($encomenda) == 0)
              <div class="orders-no">
                <img height="75" src="img/icones/no-orders.png">
                <p>{{ trans('seller.No_orders') }}</p>
                @if(($company->estado != 'em_aprovacao') && ($morada_armazem_count >=1) && ($seller->tipo != 'gestor'))
                  <a href="{{ route('ordersNewV2') }}" >
                    <button class="bt bt-blue">{{ trans('seller.Start_orders') }}</button>
                  </a>
                @endif
              </div>
            @else
              @if(($company->estado != 'em_aprovacao') && ($morada_armazem_count >=1) && ($seller->tipo != 'gestor'))
                <a href="{{ route('ordersNewV2') }}">
                  <button class="bt bt-blue"><i class="fas fa-plus"></i> {{ trans('seller.New_Order') }}</button>
                </a>
              @endif
              <div class=" margin-top30">
                <div class="modulo-table">
                  <div class="modulo-scroll">
                    <table class="modulo-body" id="sortable">
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
                        @foreach($encomenda as $val)
                          <tr id="enc_{{ $val->id_enc }}">
                            <input type="hidden" class="ordem_atual" name="ordem" value="">
                            <td class="tx-bold">{{ $val->id_enc }}</td>
                            <td>{!! date('Y-m-d',$val->data) !!}</td>
                            <td>
                              @if($val->estado_enc == 'em_processamento')
                                <i class="fas fa-circle tx-orange-esc margin-right5"></i> {{ trans('seller.processing') }} 
                              @elseif($val->estado_enc == 'registada') 
                                <i class="fas fa-circle tx-blue margin-right5"></i> {{ trans('seller.resgisted') }} 
                              @elseif($val->estado_enc == 'concluida') 
                                <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.completed') }}
                              @elseif($val->estado_enc == 'expedida')
                                <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.spent') }}
                              @elseif($val->estado_enc == 'expedida_parcialmente')
                                <i class="fas fa-circle tx-lightgreen margin-right5"></i> {{ trans('seller.partially_dispatched') }}
                              @elseif($val->estado_enc == 'backoffice')
                                <i class="fas fa-circle tx-amarelo-claro margin-right5"></i> {{ trans('seller.processing') }}
                              @else 
                                <i class="fas fa-circle tx-red margin-right5"></i> {{ trans('seller.overdue_invoice') }}
                              @endif
                            </td>
                            <td class="dashboard-table-file">
                              <a @if(file_exists(base_path('public_html/doc/orders/'.$val->documento))) href="/doc/orders/{{ $val->documento }}" @else href="{{ route('ordersPdfV2',[$val->id_enc,$company->id]) }}" @endif class="tx-navy" download=""><i class="far fa-file-pdf"></i> {{ $val->documento }}</a>
                            </td>
                            <td class="dashboard-table-value">{{ $val->total }} €</td>
                            <td><i class="far fa-user"></i> {{ $val->nome }}</td>

                            @if($val->estado_enc == 'registada')
                              <td class="dashboard-table-cancel">
                                <span onclick="$('#id_modal').val({{ $val->id_enc }});" data-toggle="modal" data-target="#myModalCancel"><i class="fas fa-times"></i> {{ trans('seller.cancel') }}</span>
                              </td>
                            @else
                              <td class="dashboard-table-cancel-inactive">
                                <i class="fas fa-times"></i> {{ trans('seller.cancel') }}
                              </td>
                            @endif
                            <td class="dashboard-table-details">
                              <a class="tx-navy" href="{{ route('ordersDetailsV2', $val->id_enc) }}"><i class="far fa-eye"></i> {{ trans('seller.details') }}</a>
                            </td>
                          </tr> 
                        @endforeach                                           
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
           
          @endif
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
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 6 ] }],
      lengthMenu: [[10,20,-1], [10,20,'{{ trans('seller.All') }}']],
      order: [[ 2, "DESC" ]],
    });

    $('#sortable_filter input').attr('placeholder', ' {{ trans('seller.Search') }}');
    $('.paginate_button i').addClass('fas');
    $('#sortable_info').hide();
  });
</script>
@stop