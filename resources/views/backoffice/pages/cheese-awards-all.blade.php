@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.allAwards') => route('cheeseAwardsAllPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">{{ trans('backoffice.allAwards') }}</div>

  <a href="{{ route('cheeseAwardsNewPageB') }}" class="abt bt-verde modulo-botoes"><i class="fas fa-plus"></i> {{ trans('backoffice.addNew') }}</a>
  
  <div class="modulo-table">
  	<div class="modulo-scroll">
  	  <table class="modulo-body" id="sortable">
  	    <thead>
  	      <tr>
          <th class="display-none"></th>
  			  <th>#</th>
  			  <th>{{ trans('backoffice.Image') }}</th>
  			  <th>{{ trans('backoffice.Award') }}</th>
          <th>{{ trans('backoffice.publicationDate') }}</th>
          <th>{{ trans('backoffice.Tag') }}</th>
          <th>{{ trans('backoffice.Online') }}</th>
  			  <th>{{ trans('backoffice.Option') }}</th>
  		  </tr>
  		</thead>
  		<tbody>
  		  @foreach($array as $art)
  		  <tr id="linha_{{ $art['id'] }}">
  		    <td class="display-none"></td>
          <td>{{ $art['id'] }}</td>
  			  <td>{!! $art['imagem'] !!}</td>
          <td>{{ $art['premio'] }}</td>
          <td>{{ $art['publicacao'] }}</td>
          <td>{!! $art['tag'] !!}</td>
          <td class="check-small">
            <input type="checkbox" name="online" id="check3{{ $art['id'] }}" value="1" onchange="updateOnOffTM({{ $art['id'] }});" @if($art['online']) checked @endif>
            <label for="check3{{ $art['id'] }}"><span></span></label>
          </td>
  			  <td class="table-opcao">
  				<a href="{{ route('cheeseAwardsEditPageB',['id'=>$art['id']]) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}</a>&ensp;
  				<span class="table-opcao" onclick="$('#id_modal').val({{ $art['id'] }});" data-toggle="modal" data-target="#myModalDelete">
  				  <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
  				</span>
  			  </td>
  			</tr>
  		  @endforeach
  		  @if(empty($array)) <tr><td colspan="7">{{ trans('backoffice.noRecords') }}</td></tr> @endif
  	  	</tbody>
  	  </table>
  	</div>
  </div>

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
  function updateOnOffTM(id){
    $.ajax({
      type: "POST",
      url: '{{ route('updateOnOffTMB') }}',
      data: { tabela:'ppq_premios', id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{
        $.notific8(resposta, {color:'ruby'});
      }
    });
  }
  function apagarLinha(){
    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('cheeseAwardsAllApagarB') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $('#linha_'+id).slideUp();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }
    });
  }
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0,2,6,7 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop