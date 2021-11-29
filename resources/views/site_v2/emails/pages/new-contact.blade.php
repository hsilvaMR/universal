@extends('site_v2/emails/layouts/default')

@section('content')
	@include('site_v2/emails/includes/header')

	<table style="padding-top:20px;" border="0" cellpadding="0" width="100%">
	   	<tr>
		    <td width="100%" style="color:#58595b;font-size:14px;line-height:20px;text-align: justify;">
		    	{!! trans('email.O_Cliente') !!} {{ $dados['nome'] }} {!! trans('email.InfoEmail') !!}
		    	<br><br>{!! trans('email.Nome') !!}: {{ $dados['nome'] }}
		    	@if(isset($dados['email']))<br><br>{!! trans('email.Email') !!}: <a style="color:#00AE39;">{{ $dados['email'] }}</a>@endif
	        	@if(isset($dados['mensagem']))<br><br>{!! trans('email.Mensagem') !!}: {{ $dados['mensagem'] }}@endif
			</td>
		</tr>
	</table>

	@include('site_v2/emails/includes/footer')	
@stop