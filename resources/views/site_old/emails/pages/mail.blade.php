@extends('site_old/emails/layouts/default')

@section('content')
	@include('site_old/emails/includes/header')

	<table style="padding-top:20px;" border="0" cellpadding="0" width="100%">
	   	<tr>
		    <td width="100%" style="color:#58595b;font-size:14px;line-height:20px;text-align: justify;">
		    	
		    	<br><br>{!! trans('site_old.Name') !!}: {{ $dados['nome'] }}<br><br>
		    	{!! trans('site_old.Email') !!}: {{ $dados['email'] }}<br><br>
	        	{!! trans('site_old.Message') !!}: {{ $dados['mensagem'] }}

			</td>
		</tr>
	</table>

	@include('site_old/emails/includes/footer')	
@stop