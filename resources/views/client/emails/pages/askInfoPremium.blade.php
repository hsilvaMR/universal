@extends('client/emails/layouts/default')

@section('content')
	@include('client/emails/includes/header')


	<table style="padding-top:40px;background-color:#fff;padding:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%" style="color:#333;font-size:16px;line-height:20px;">

		    	<img height="75" src="{{ asset('site_v2/img/emails/bell.svg') }}">
		        <p style="margin-top:20px;">{{ trans('site_v2.The_customer_ID') }}{{ $dados['nome_cliente'] }}, {{ trans('site_v2.Request_information_premium_txt') }} {{ $dados['nome_premio'] }}, {{ trans('site_v2.with_id') }}{{ $dados['id_pedido'] }} {{ trans('site_v2.made_date') }} <span style="color: #FE4A49;">{{ date('Y-m-d',$dados['data_pedido']) }}</span></p>
			</td>
		</tr>
	</table>

	<table style="padding-top:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%">
				<img height="25" src="{{ asset('site_v2/img/emails/slogan.png') }}">
			</td>
		</tr>
	</table>

	<table style="padding-top:20px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%">
				<a href="http://www.universal.com.pt" target="_blank" style="text-align:center;color:#1974d8;font-size:12px;">universal.com.pt</a>
			</td>
		</tr>
	</table>

	<table style="padding-top:10px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%">
				<a href="https://www.facebook.com/queijouniversal" target="_blank"><img height="20" width="20" src="{{ asset('site_v2/img/emails/fb.png') }}"></a>&ensp;<a href="https://www.instagram.com/universal_queijo" target="_blank"><img height="20" width="20" src="{{ asset('site_v2/img/emails/instagram.png') }}"></a>
			</td>
		</tr>
	</table>
	@include('client/emails/includes/footer')	
@stop