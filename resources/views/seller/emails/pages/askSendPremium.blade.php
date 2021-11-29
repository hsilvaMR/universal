@extends('seller/emails/layouts/default')

@section('content')
	@include('seller/emails/includes/header')

	<table style="padding-top:20px;" border="0" cellpadding="0" width="100%">
	   	<tr>
		    <td width="100%" style="color:#58595b;font-size:14px;line-height:20px;text-align: justify;">
		    	
		    	<br><br>
		    	{{ trans('seller.The_customer_ID') }} {{ $dados['nome_cliente'] }}, {{ trans('seller.Request_information_premium_txt') }} {{ $dados['nome_premio'] }}, {{ trans('seller.with_id') }} {{ $dados['id_pedido'] }} {{ trans('seller.made_date') }} <span style="color: #FE4A49;">{{ date('Y-m-d',$dados['data_pedido']) }}</span>.
			</td>
		</tr>
	</table>

	@include('seller/emails/includes/footer')	
@stop
