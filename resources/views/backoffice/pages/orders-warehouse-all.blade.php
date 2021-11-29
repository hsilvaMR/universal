@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.Warehouse_Orders') => route('ordersAllPageB'), trans('backoffice.newUser') => route('usersNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB'), trans('backoffice.Warehouse_Orders') => route('ordersWarehouseAllPageB',['id'=>$id]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.Warehouse_Orders') }}</div>

  <!--<a href="{ { route('usersNewPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> { { trans('backoffice.addNew') }}</a>-->
  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Document') }}</th>
            <th>{{ trans('backoffice.StartProcessing') }}</th>
            <th>{{ trans('backoffice.Proforma') }}</th>
            <th>{{ trans('backoffice.Guide') }}</th>
            <th>{{ trans('backoffice.Expedition') }}</th>
            <th>{{ trans('backoffice.Invoice') }}</th>
            <th>{{ trans('backoffice.Proof') }}</th>
            <th>{{ trans('backoffice.Receipt') }}</th>
            <th>{{ trans('backoffice.SubTotal') }}</th>
            <th>{{ trans('backoffice.Total') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($array as $val)
            <tr id="linha_{{ $val['id'] }}">
              <td class="display-none"></td>
              <td>{!! $val['id'] !!}</td>
              <td>
                {!! $val['doc_parcial'] !!}<br> 
                @if(isset($val['data_encomenda'])){!! date('Y-m-d',$val['data_encomenda']) !!}@endif
              </td>
              <td>@if(isset($val['data_inicio_process']) && ($val['data_inicio_process'] != 0)){!! date('Y-m-d',$val['data_inicio_process']) !!}@endif</td>
              <td>
                <a href="/doc/orders/{!! $val['doc_proforma'] !!}" target="_blank">{!! $val['doc_proforma'] !!}</a><br>
                @if(isset($val['data_proforma']) && ($val['data_proforma'] != 0)){!! date('Y-m-d',$val['data_proforma']) !!}@endif
              </td>
              <td>
                <a href="/doc/orders/{!! $val['doc_guia'] !!}" target="_blank">{!! $val['doc_guia'] !!}</a> <br> 
                @if(isset($val['data_guia']) && ($val['data_guia'] != 0)){!! date('Y-m-d',$val['data_guia']) !!}@endif
              </td>
              <td>@if(isset($val['data_expedicao']) && ($val['data_expedicao'] != 0)){!! date('Y-m-d',$val['data_expedicao']) !!}@endif</td>
              <td>
                <a href="/doc/orders/{!! $val['doc_fatura'] !!}" target="_blank">{!! $val['doc_fatura'] !!}</a> <br>
                @if(isset($val['data_fatura']) && ($val['data_fatura'] != 0)){!! date('Y-m-d',$val['data_fatura']) !!}@endif
              </td>
              <td>
                <a href="/doc/orders/{!! $val['doc_comprovativo'] !!}" target="_blank">{!! $val['doc_comprovativo'] !!}</a> <br> 
                @if(isset($val['data_comprovativo']) && ($val['data_comprovativo'] != 0)){!! date('Y-m-d',$val['data_comprovativo']) !!}@endif
              </td>
              <td>
                <a href="/doc/orders/{!! $val['doc_recibo'] !!}" target="_blank">{!! $val['doc_recibo'] !!}</a> <br>
                @if(isset($val['data_recibo']) && ($val['data_recibo'] != 0)){!! date('Y-m-d',$val['data_recibo']) !!}@endif
              </td>
              <td>{!! $val['subtotal'] !!} €</td>
              <td>{!! $val['total'] !!} €</td>
              <td>{!! $val['estado'] !!}</td>
              <td class="table-opcao">
                <a href="{{ route('ordersEditPageB',['id'=>$val['id']]) }}" class="table-opcao">
                  <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                </a>&ensp;
                <!--<a href="" class="table-opcao">
                  <i class="fas fa-plus"></i>&nbsp;{ {trans('backoffice.AddProduct')}}
                </a>&ensp;-->
                <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
                  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                </span>
              </td>
            </tr>
          @endforeach
          @if(empty($array)) <tr><td colspan="10">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>

  
@stop

@section('css')
<!-- PAGINAR -->
<link href="{{ asset('backoffice/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,13 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop