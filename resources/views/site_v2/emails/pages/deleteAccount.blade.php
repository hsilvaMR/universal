@extends('site_v2/emails/layouts/default')

@section('content')
	@include('site_v2/emails/includes/header')

	<table style="padding-top:40px;background-color:#fff;padding:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%" style="color:#333;font-size:16px;line-height:20px;">

		    	<img height="75" src="{{ asset('site_v2/img/emails/account.png') }}">
		        <p style="margin-top:20px;">{!! trans('site_v2.Delete_Account_txt') !!}</p>
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
@stop