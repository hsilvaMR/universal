@extends('backoffice/layouts/default')

@section('content')
<?php $arrayCrumbs = [ trans('backoffice.comuniBradCromb') => route('mainPageComun') ]; ?>
@include('backoffice/includes/crumbs')

<div class="page-titulo">{{ trans('backoffice.comuniBradCromb') }}</div>
<a href="{{ route('comunAdd') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i>
    {{ trans('backoffice.commuBtnAdd') }}</a>
{{-- table  --}}
<div class="modulo-table">
    <div class="modulo-scroll">
        <table class="modulo-body" id="sortable">
            <thead>
                <tr>
                    <th class="display-none"></th>
                    <th>#</th>
                    <th>{{ trans('backoffice.communiFile') }}</th>
                    <th>{{ trans('backoffice.communNome') }}</th>
                    <th>{{ trans('backoffice.comunDesc') }}</th>
                    <th>{{ trans('backoffice.communUpdate') }}</th>
                    <th>{{ trans('backoffice.commuLink') }}</th>
                    <th class="text-center">{{ trans('backoffice.commuAction') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($array as $val)
                <tr id="linha_{{ $val['id'] }}">
                    <td class="display-none"></td>
                    <td>{!! $val['id'] !!}</td>
                    <td>{!! $val['ficheiro'] !!}</td>
                    <td>{!! $val['nome'] !!}</td>
                    <td>{!! $val['descr'] !!}</td>
                    <td>{!! $val['update'] !!}</td>
                    <td>{!! $val['link'] !!}</td>
                    {{-- action edit | delete | download | share --}}
                    <td class="table-opcao">
                        <a href="{{ route('companiesEditPageB',['id'=>$val['id']]) }}" class="table-opcao">
                            <i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}
                        </a>&ensp;
                        <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal"
                            data-target="#myModalDelete">
                            <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                        </span>&ensp;
                        {{-- download --}}
                        <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal"
                            data-target="#myModalDelete">
                            <i class="fas fa-file-download"></i>&nbsp;{{trans('backoffice.Delete')}}
                        </span>&ensp;
                        {{-- share link  --}}
                        <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal"
                            data-target="#myModalDelete">
                            <i class="fas fa-cloud-download-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
                        </span>
                    </td>
                </tr>
                @endforeach
                @if(empty($array)) <tr>
                    <td colspan="10">{{ trans('backoffice.noRecords') }}</td>
                </tr> @endif
            </tbody>
        </table>
    </div>
</div>

@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script>
    //<!-- APAGAR EMPRESA -->
  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('companiesDeleteB') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      //console.log(resposta);
      if(resposta=='sucesso'){
        //$('#linha_'+id).slideUp();
        $('#linha_'+id).remove();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{
        $.notific8(resposta, {color:'ruby'});
      }
    });
  }

  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,2,8 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop