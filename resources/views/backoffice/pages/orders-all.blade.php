@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.allOrders') => route('ordersAllPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.allOrders') }}</div>

  <a class="abt bt-verde modulo-botoes" data-toggle="modal" data-target="#myModalOrder"><i class="fas fa-plus"></i> {{ trans('backoffice.AddOrder') }}</a>
  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Company') }}</th>
            <th>{{ trans('backoffice.Seller') }}</th>
            <th>{{ trans('backoffice.Document') }}</th>
            <th>{{ trans('backoffice.Invoice') }}</th>
            <th>{{ trans('backoffice.Receipt') }}</th>
            <th>{{ trans('backoffice.SubTotal') }}</th>
            <th>{{ trans('backoffice.Total') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($array as $val)
            <tr id="linha_{{ $val['id'] }}">
              <td class="display-none"></td>
              <td>{!! $val['id'] !!}</td>
              <td>{!! $val['empresa'] !!}</td>
              <td>{!! $val['comerciante'] !!}</td>
              <td>{!! $val['documento'] !!}</td>
              <td>
                <a href="/doc/orders/{!! $val['fatura'] !!}" target="_blank">{!! $val['fatura'] !!}</a><br>
                @if(isset($val['data_fatura']) && ($val['data_fatura'] != 0)){!! date('Y-m-d',$val['data_fatura']) !!}@endif
              </td>
              <td>
                <a href="/doc/orders/{!! $val['recibo'] !!}" target="_blank">{!! $val['recibo'] !!}</a><br>
                @if(isset($val['data_recibo']) && ($val['data_recibo'] != 0)){!! date('Y-m-d',$val['data_recibo']) !!}@endif
              </td>
              <td>{!! $val['subtotal'] !!} €</td>
              <td>{!! $val['total'] !!} €</td>
              <td>{!! $val['data'] !!}</td>
              <td>{!! $val['estado'] !!}</td>
              <td class="table-opcao">
                <a class="table-opcao" href="{{ route('ordersWarehouseAllPageB',['id'=>$val['id']]) }}"><i class="far fa-eye"></i>&nbsp;{{ trans('backoffice.Details') }}</a>&ensp;
                <a href="{{ route('ordersEditTotalPageB',['id'=>$val['id']]) }}" class="table-opcao">
                  <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                </a>&ensp;
                @if($val['tipo_fatura'] == 'fat_unificada')<a href="{{ route('invoiceOrderPageB',['id'=>$val['id_encomenda']]) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;Editar fatura/recibo</a>&ensp;@endif
                <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
                  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                </span>
              </td>
            </tr>
          @endforeach
       
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Order -->
  <div class="modal fade" id="myModalOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{!! trans('backoffice.AddOrder') !!}</h4></div>
        <div class="modal-body">
          <label class="label_select">
            <select id="adress" style="border:1px solid #000;padding:0px 10px;" onchange="location = this.value;">
              <option selected disabled value="0">{{ trans('backoffice.SelectedCompany') }}</option>
              @foreach($companys as $value)
                <option value="{!! route('ordersNewPageB',$value->id) !!}">{{ $value->nome }}</option>
              @endforeach
            </select>
          </label>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalDelete">
    <input id="id_modal" type="hidden" name="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel2">{!! trans('backoffice.DeleteOrder') !!}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteOrder_txt') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarLinha();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>

  
@stop

@section('css')
<!-- PAGINAR -->
<link href="{{ asset('backoffice/vendor/datatables/jquery.dataTables.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script>
  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('orderDeleteFormB') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $('#linha_'+id).slideUp();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{ $.notific8(resposta, {color:'ruby'}); }
    });

  }
</script>

<script>
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,11 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop