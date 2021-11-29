@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.Notifications') => route('notificationsPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.Notifications') }}</div>

  <a href="{{ route('notificationsNewPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.addNew') }}</a>
  
  @if($arrayNew)
  <div class="modulo-table-new">
    <div class="modulo-scroll">
      <table class="modulo-body">
        <thead>
          <tr>
          <th class="display-none"></th>
          <th>#</th>
          <th>{{ trans('backoffice.Notification') }}</th>
          <th>{{ trans('backoffice.Type') }}</th>
          <th>{{ trans('backoffice.Date') }}</th>        
          @if(json_decode(\Cookie::get('admin_cookie'))->tipo=='admin')
          <th>{{ trans('backoffice.Agent') }}</th>
          <th>{{ trans('backoffice.Option') }}</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach($arrayNew as $val)
        <tr id="linha_{{ $val['id'] }}">
          <td class="display-none"></td>
          <td>{{ $val['id'] }}</td>
          <td>{!! $val['mensagem'] !!}</td>
          <td>{!! $val['tipo'] !!}</td>
          <td>{{ $val['data'] }}</td>
          @if(json_decode(\Cookie::get('admin_cookie'))->tipo=='admin')
          <td>{!! $val['agente'] !!}</td>
          <td class="table-opcao">
            <a href="{{ route('notificationsEditPageB',['id'=>$val['id']]) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}</a>&ensp;
            <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
              <i class="far fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
            </span>
          </td>
          @endif
        </tr>
        @endforeach
        @if(empty($arrayNew)) <tr><td colspan="10">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>
  @endif

  @if(empty($arrayNew) || $array)
  <div class="modulo-table">
    <div class="modulo-scroll">
      <table class="modulo-body" id="sortable">
        <thead>
          <tr>
          <th class="display-none"></th>
          <th>#</th>
          <th>{{ trans('backoffice.Notification') }}</th>
          <th>{{ trans('backoffice.Type') }}</th>
          <th>{{ trans('backoffice.Date') }}</th>        
          @if(json_decode(\Cookie::get('admin_cookie'))->tipo=='admin')
          <th>{{ trans('backoffice.User') }}</th>
          <th>{{ trans('backoffice.Option') }}</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach($array as $val)
        <tr id="linha_{{ $val['id'] }}" @if(!$val['visto']) class="fd-amarelo" @endif>
          <td class="display-none"></td>
          <td>{{ $val['id'] }}</td>
          <td>{!! $val['mensagem'] !!}</td>
          <td>{!! $val['tipo'] !!}</td>
          <td>{{ $val['data'] }}</td>
          @if(json_decode(\Cookie::get('admin_cookie'))->tipo=='admin')
          <td>{!! $val['agente'] !!}</td>
          <td class="table-opcao">
            <a href="{{ route('notificationsEditPageB',['id'=>$val['id']]) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}</a>&ensp;
            <span class="table-opcao" onclick="$('#id_modal').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDelete">
              <i class="far fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
            </span>
          </td>
          @endif
        </tr>
        @endforeach
        @if(empty($array)) <tr><td colspan="10">{{ trans('backoffice.noRecords') }}</td></tr> @endif
        </tbody>
      </table>
    </div>
  </div>
  @endif

  <!-- Modal Delete -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" name="id_modal" id="id_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteLine') !!}</div>
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
@stop

@section('javascript')
<!-- PAGINAR -->
<script src="{{ asset('backoffice/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('deleteLineTMB') }}',
      data: { tabela:'admin_not', id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $('#linha_'+id).slideUp();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{
        $.notific8(resposta, {color:'ruby'});
      }
    });
  }
  //<!-- PAGINAR -->
  @if(isset($array) && $array)
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0, 2, @if(json_decode(\Cookie::get('admin_cookie'))->tipo=='admin') 6 @endif ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
  @endif
</script>
@stop