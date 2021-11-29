@extends('seller/layouts/default')
@section('content')

  <div class="container-fluid mod-seller">
    @include('seller/includes/headerSubMenu')
    @include('seller/includes/menuSettings')
    @include('seller/includes/menuNotifications')

    <div class="row">
      <div class="col-lg-3"> @include('seller/includes/menu') </div>
      <div class="col-lg-9">
        <div class="mod-tit"><h3>{{ trans('seller.Technical_Information_tit') }}</h3></div>

        <div class="mod-area">
          <span>{{ trans('seller.Technical_promotional_txt') }}</span>

          <div class="modulo-table information-table">
            <div class="modulo-scroll">
              <table class="modulo-body" id="sortable" >
                <thead>
                  <tr>
                    <th>{{ trans('seller.Document') }}</th>
                    <th>{{ trans('seller.Date') }}</th>
                    <th>{{ trans('seller.Filename') }}</th>
                    <th class="background-none"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($informacoes_tecnicas as $val)
                    @if($val->tipo == 'documento')
                      <tr id="tr_line_1">
                        <td class="information-table-td">{{ $val->descricao }} 
                          @if($val->estado == 'actualizado') 
                            <span class="information-satus-update">{{ trans('seller.updated') }}</span> 
                          @elseif($val->estado == 'novo') 
                            <span class="information-satus-new">{{ trans('seller.new') }}</span> 
                          @endif
                        </td>
                        <td>{{ date('Y-m-d',$val->data) }}</td>
                        <td class="tx-navy tx-underline">@if($val->ficheiro) <i class="far fa-file-pdf"></i> {{ $val->ficheiro }} @endif</td>
                        <td class="tx-navy cursor-pointer"><a class="tx-navy" href="\doc\informations\{{ $val->ficheiro }}" download><i class="fas fa-cloud-download-alt"></i>&nbsp; {{ trans('seller.transfer') }}</a></td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
       
          <div class="modulo-table information-table">
            <div class="modulo-scroll">
              <table class="modulo-body" id="sortable_2" >
                <thead>
                  <tr id="tr_line_2">
                    <th>{{ trans('seller.Product') }}</th>
                    <th>{{ trans('seller.Date') }}</th>
                    <th>{{ trans('seller.Filename') }}</th>
                    <th class="background-none"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($informacoes_tecnicas as $val)
                    @if($val->tipo == 'produto')
                      <tr>
                        <td class="information-table-td">{{ $val->descricao }} 
                          @if($val->estado == 'actualizado') 
                            <span class="information-satus-update">{{ trans('seller.updated') }}</span> 
                          @elseif($val->estado == 'novo') 
                            <span class="information-satus-new">{{ trans('seller.new') }}</span> 
                          @endif
                        </td>
                        <td>{{ date('Y-m-d',$val->data) }} </td>
                        <td class="tx-navy tx-underline">@if($val->ficheiro) <i class="far fa-file-pdf"></i> {{ $val->ficheiro }} @endif</td>
                        <td class="tx-navy cursor-pointer"><i class="fas fa-cloud-download-alt"></i> {{ trans('seller.transfer') }}</td>
                      </tr>
                    @endif
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
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 3 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
      order: [ [ $('#tr_line_1').index(),  'novo' ] ]
    });

    $('#sortable_2').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ -1, 3 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('seller.All') }}']],
    });

    $('#sortable_length').hide(); 
    $('#sortable_filter').hide();
    $('#sortable_info').hide();
    $('#sortable_paginate').hide();
    $('#sortable_2_paginate').hide();
    $('#sortable_2_info').hide();
    $('#sortable_2_filter').hide();
    $('#sortable_2_length').hide();
  });
</script>
@stop