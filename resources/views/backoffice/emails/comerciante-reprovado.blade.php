@extends('backoffice/emails/layouts/default')

@section('content')
	@include('backoffice/emails/includes/header')
	<!-- ICON -->
	<table style="padding-top:50px;" border="0" cellpadding="0" width="100%">
	   <tr>
		  <td align="center" width="100%">
		    <img src="{{  asset('backoffice/img/emails/disapproved.png') }}" alt="Disapproved" height="90">
		  </td>
		</tr>
	</table>
	<!-- TEXTO -->
	<table style="padding-top:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%" style="color:#58595b;font-size:14px;line-height:20px;">
		        {!! trans('backoffice.DisapprovedAccountTx') !!}
			</td>
		</tr>
	</table>
	<!-- BOTAO -->
	<table style="padding-top:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		  	<td align="center" width="100%">
			    <table style="font-size:14px;padding:8px 13px;color:#ffffff;background-color:#0084B6;">
					<tr><td><a href="{{ route('loginPageV2') }}" target="_blank" style="color:#ffffff;text-decoration:none;background:#0084B6;">{!! trans('backoffice.DisapprovedAccountBt') !!}</a></td></tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- DOESN'T WORK -->
	<table style="padding-top:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		  	<td align="center" width="100%" style="color:#58595b;font-size:11px;line-height:15px;">
		    	{!! trans('backoffice.doesntWorkTx') !!} <a href="{{ route('loginPageV2') }}" style="text-decoration:none;color:#2fb385;" target="_blank">{{ route('loginPageV2') }}</a>
			</td>
		</tr>
	</table>
@stop