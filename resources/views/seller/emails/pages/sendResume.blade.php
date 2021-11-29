@extends('seller/emails/layouts/default')

@section('content')
	@include('seller/emails/includes/header')
	
	<table style="padding-top:40px;background-color:#fff;padding:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%" style="color:#333;font-size:16px;line-height:20px;">
		    	<img height="75" src="{{ asset('img/emails/bell.png') }}">
		        <p style="margin-top:20px;">O utilizador <b>{{ $dados['nome_comerciante'] }}</b> com o <b>#{{ $dados['id_comerciante'] }}</b> da empresa <b>{{ $dados['nome_empresa'] }}</b>, com o <b>#{{ $dados['id_empresa'] }}</b> realizou uma encomenda. Em anexo segue o resumo da encomenda.</p>
			</td>
		</tr>
	</table>
	
	<table style="padding:20px 60px 0px 60px;text-align:center;color:#666;font-size:12px;" border="0" cellpadding="0" width="100%">
		<tr>
			<td align="center" width="100%">{{ trans('site_v2.add_email_contacts_txt') }}: 
			<span style="font-size: 12px; line-height: 21px;"><a href="mailto:no-reply@universal.com.pt" style="color: #1974d8;" title="no-reply@universal.com.pt">no-reply@universal.com.pt</a></span></td>
		</tr>
	</table>

	<table style="padding-top:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%">
				<img height="25" src="{{ asset('img/emails/slogan.png') }}">
			</td>
		</tr>
	</table>

	<table style="padding-top:20px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%">
				<a href="www.universal.com.pt" style="text-align:center;color:#1974d8;font-size:12px;">universal.com.pt</a>
			</td>
		</tr>
	</table>

	<table style="padding-top:10px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%">
				<a href="https://www.facebook.com/queijouniversal" target="_blank"><img height="20" width="20" src="{{ asset('img/emails/fb.png') }}"></a>&ensp;<a href="https://www.instagram.com/universal_queijo" target="_blank"><img height="20" width="20" src="{{ asset('img/emails/instagram.png') }}"></a>
			</td>
		</tr>
	</table>
@stop