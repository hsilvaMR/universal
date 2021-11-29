@extends('backoffice/layouts/default')

@section('content')
  <?php $arrayCrumbs = [ trans('backoffice.Dashboard') => route('dashboardPageB') ]; ?>
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">
  	{{ trans('backoffice.Dashboard') }}
  	<div class="page-informacao" data-toggle="modal" data-target="#myModalInformation"><i class="fas fa-info"></i></div>
  </div>

  <div class="row">
    <div class="col-sm-6 col-lg-3">
	  <div class="modulo-azul">
	  	<div class="modulo-azul-top">{{ $num1 }}<i class="fas fa-shopping-cart"></i></div>
	  	<a href="{{ route('usersPageB') }}">
	  	  <div class="modulo-azul-bottom">{{ trans('backoffice.Users') }}<i class="fas fa-arrow-circle-right"></i></div>
	  	</a>
	  </div>
    </div>    
    <div class="col-sm-6 col-lg-3">
	  <div class="modulo-azul">
	  	<div class="modulo-azul-top">{{ $num2 }}<i class="fas fa-briefcase"></i></div>
	  	<a href="{{ route('companiesPageB') }}">
	  	  <div class="modulo-azul-bottom">{{ trans('backoffice.Companies') }}<i class="fas fa-arrow-circle-right"></i></div>
	  	</a>
	  </div>
    </div>
    <div class="col-sm-6 col-lg-3">
	  <div class="modulo-azul">
	  	<div class="modulo-azul-top">{{ $num3 }}<i class="fas fa-users"></i></div>
	  	<a href="{{ route('sellersPageB') }}">
	  	  <div class="modulo-azul-bottom">{{ trans('backoffice.Sellers') }}<i class="fas fa-arrow-circle-right"></i></div>
	  	</a>
	  </div>
    </div>
    <div class="col-sm-6 col-lg-3">
	  <div class="modulo-azul">
	  	<div class="modulo-azul-top">{{ $num4 }}<i class="fas fa-envelope"></i></div>
	  	<a href="{{ route('ordersAllPageB') }}">
	  	  <div class="modulo-azul-bottom">{{ trans('backoffice.Orders') }}<i class="fas fa-arrow-circle-right"></i></div>
	  	</a>
	  </div>
    </div>
  </div>

  <div class="row">
  	<div class="col-lg-6">
  		<div class="modulo-dashboard">
			<div class="modulo-head">
				{{ trans('backoffice.latestOrders') }}
				<a href="{{ route('ordersAllPageB') }}">{{ trans('backoffice.goToPage') }} <i class="fas fa-arrow-circle-right"></i></a>
			</div>
			<table class="modulo-body">
				@foreach($lista1 as $list)
				    <tr>
				      <td>
				        <font class="tx-azul">{{ date('Y-m-d',$list->data) }}</font>
					    {{ $list->nome }}
					    <font class="tx-verde">{{ number_format($list->total, 2, ',', ' ').' €' }}</font>
				      </td>
				    </tr>
				@endforeach
				@if(empty($lista1)) <tr><td><font class="tx-cinza">{{ trans('backoffice.noRecords') }}</td></tr></font> @endif
			</table>
	  	</div>
  	</div>
  	<div class="col-lg-6">
  		<div class="modulo-dashboard">
			<div class="modulo-head">
				{{ trans('backoffice.latestCompanies') }}
				<a href="{{ route('companiesPageB') }}">{{ trans('backoffice.goToPage') }} <i class="fas fa-arrow-circle-right"></i></a>
			</div>
			<table class="modulo-body">
				@foreach($lista2 as $list)
				    <tr>
				      <td>
				        <font class="tx-azul">{{ date('Y-m-d',$list->data) }}</font>
					    {{ $list->nome }}
					    <font class="tx-verde">{{ $list->email }}</font>
				      </td>
				    </tr>
				@endforeach
				@if(empty($lista2)) <tr><td><font class="tx-cinza">{{ trans('backoffice.noRecords') }}</td></tr></font> @endif			  
			</table>
	  	</div>
  	</div>
  </div>

  <div class="row">
  	<div class="col-lg-6">
  		<div class="modulo-dashboard">
			<div class="modulo-head">
				{{ trans('backoffice.latestSellers') }}
				<a href="{{ route('sellersPageB') }}">{{ trans('backoffice.goToPage') }} <i class="fas fa-arrow-circle-right"></i></a>
			</div>
			<table class="modulo-body">
				@foreach($lista3 as $list)
				    <tr>
				      <td>
				        <font class="tx-azul">{{ date('Y-m-d',$list->data) }}</font>
					    {{ $list->nome }}
					    <font class="tx-verde">{{ $list->empresa }}</font>
				      </td>
				    </tr>
				@endforeach
				@if(empty($lista3)) <tr><td><font class="tx-cinza">{{ trans('backoffice.noRecords') }}</td></tr></font> @endif
			</table>
	  	</div>
  	</div>
  	<div class="col-lg-6">
  		<div class="modulo-dashboard">
			<div class="modulo-head">
				{{ trans('backoffice.latestUsers') }}
				<a href="{{ route('usersPageB') }}">{{ trans('backoffice.goToPage') }} <i class="fas fa-arrow-circle-right"></i></a>
			</div>
			<table class="modulo-body">
			  	@foreach($lista4 as $list)
				    <tr>
				      <td>
				        <font class="tx-azul">{{ date('Y-m-d',$list->data) }}</font>
					    {{ $list->nome.' '.$list->apelido }}
					    <font class="tx-verde">{{ $list->email }}</font>
				      </td>
				    </tr>
				@endforeach
				@if(empty($lista4)) <tr><td><font class="tx-cinza">{{ trans('backoffice.noRecords') }}</td></tr></font> @endif
			</table>
	  	</div>
  	</div>
  </div>

  
  <!-- Modal Information -->
  <div class="modal fade" id="myModalInformation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title">{{ trans('backoffice.dashboardTt') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.dashboardTx') !!}</div>
        <div class="modal-footer"><button type="button" class="bt bt-azul" data-dismiss="modal"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</button></div>
      </div>
    </div>
  </div>
@stop

@section('css')
@stop

@section('javascript')
@stop