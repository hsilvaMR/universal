@extends('backoffice/layouts/default')

@section('content')
	<?php $arrayCrumbs = [ $headTitulo => route('certificationsIdPageB',$id) ]; ?>
  	@include('backoffice/includes/crumbs')

  	<div class="page-titulo">{{ $headTitulo }}</div>
  
  	<div class="modulo-table">
  		<div class="modulo-scroll">
  	  		<table class="modulo-body" id="sortable">
  	    		<thead>
  	      			<tr>
          				<th class="display-none"></th>
			  			<th>{{ trans('backoffice.Process') }}</th>
  		  			</tr>
  				</thead>
  				<tbody>
  		  			@foreach($process as $val)
  		  				<tr id="linha_{{ $val->id }}">
  		    				<td class="display-none"></td>
          					<td><a href="{{ route('certificationsProcessPageB',$val->id) }}">{{ $val->referencia }} - {{ $val->nome }}</a></td>
  						</tr>
  		  			@endforeach
  		  			@if(empty($process)) <tr><td colspan="2">{{ trans('backoffice.noRecords') }}</td></tr> @endif
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
<script type="text/javascript">
  //<!-- PAGINAR -->
  $(document).ready(function(){
    $('#sortable').dataTable({
      aoColumnDefs: [{ "bSortable": false, "aTargets": [ 0 ] }],
      lengthMenu: [[20,50,-1], [20,50,'{{ trans('backoffice.All') }}']],
    });
  });
</script>
@stop