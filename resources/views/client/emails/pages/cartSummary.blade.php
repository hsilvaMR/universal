@extends('site_v2/emails/layouts/default')

@section('content')
	@include('site_v2/emails/includes/header')

	<table style="padding-top:40px;background-color:#fff;padding:40px;color:#333;font-size:16px;line-height:20px;" border="0" cellspacing="0" cellpadding="0" width="100%">
	   <tr>
		    <td width="10%" >
		    	<img height="50" align="left" src="{{ asset('site_v2/img/emails/success.png') }}">
				&emsp;
	        	<span>{{ trans('site_v2.Your_order') }} <span style="font-weight:bold;">#{{ $dados['id_car'] }}</span> {{ trans('site_v2.was_successful') }}.
	        	<br>&emsp;{{ trans('site_v2.The_details_your_request') }}:</span>
			</td>
		</tr>
	</table>
	
	<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;padding-bottom:10px;padding:40px;" valign="top" width="100%">

		<table bgcolor="#FFFFFF" cellpadding="10" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;" valign="top" width="100%">
			
			<tr style="font-family:'Roboto',serif;font-weight:400;font-size:16px;">
				<td style="border-top: 1px solid #eeeeee;">{{ trans('site_v2.Article') }}</td>
				<td style="border-top: 1px solid #eeeeee;text-align:right;">{{ trans('site_v2.Amount') }}</td>
				<td style="border-top: 1px solid #eeeeee;text-align:right;">{{ trans('site_v2.Points') }}</td>
				<td style="border-top: 1px solid #eeeeee;text-align:right;">{{ trans('site_v2.Value') }}</td>
			</tr>

			@foreach($dados['car'] as $val)
				<tr style="font-family:'Roboto',serif;font-weight:400;font-size:14px;">
					<td style="border-top: 1px solid #eeeeee;">{{ $val->produto }}<br> {{ $val->variante }}</td>
					<td style="text-align:right;border-top: 1px solid #eeeeee;">{{ $val->quantidade }}</td>

					@if(($val->quantidade*$val->valor_cliente) > ($val->pontos_utilizados))
	                  <td style="text-align:right;border-top: 1px solid #eeeeee;"> {{ $val->pontos_utilizados }} </td>
	                @else
	                  <td style="text-align:right;border-top: 1px solid #eeeeee;"> {{ $val->pontos_utilizados }}</td>
	                @endif

					<td style="text-align:right;border-top: 1px solid #eeeeee;">0.00€</td>
				</tr>
			@endforeach

			@if($dados['pontos_falta'])
				<tr style="font-family:'Roboto',serif;font-weight:400;font-size:14px;">
					<td style="border-top: 1px solid #eeeeee;">{{ trans('site_v2.Points') }} x <span>{{ $dados['pontos_falta'] }}</span></td>
					<td style="text-align:right;border-top: 1px solid #eeeeee;">1</td>
					<td style="text-align:right;border-top: 1px solid #eeeeee;">{{ $dados['pontos_falta'] }}</td>
					<td style="text-align:right;border-top: 1px solid #eeeeee;">{{ $dados['valor_euro'] }}€</td>
				</tr>
			@endif

			@php $valor = 0; $qtd=0; @endphp
            @foreach($dados['car'] as $car_uti)
              @php
                $valor_qtd = $car_uti->quantidade * $car_uti->valor_cliente;
                $valor = $valor + $valor_qtd;
                $qtd = $qtd + $car_uti->quantidade;
              @endphp
            @endforeach
            @if($dados['pontos_falta']) @php $qtd = $qtd+1; @endphp @else @php $qtd = $qtd; @endphp @endif
			<tr style="font-family:'Roboto',serif;font-weight:400;font-size:14px;font-weight:bold;margin-bottom:40px;">
				<td style="border-top: 1px solid #eeeeee;">Total</td>
				<td style="text-align:right;border-top: 1px solid #eeeeee;">{{ $qtd }}</td>
				<td style="text-align:right;border-top: 1px solid #eeeeee;">{{ $valor }}</td>
				<td style="text-align:right;border-top: 1px solid #eeeeee;">{{ $dados['valor_euro'] }}€</td>
			</tr>

	    </table>

	    <table bgcolor="#FFFFFF" cellpadding="10" cellspacing="5" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;" valign="top" height="50px" width="100%">
	    </table>

	    <table bgcolor="#FFFFFF" cellpadding="10" cellspacing="5" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;" valign="top" width="100%">
	    	<tr>
				<td style="border-top: 1px solid #eeeeee;border-bottom: 1px solid #eeeeee;">{{ trans('site_v2.Billing_Address') }}</td>
				<td style="border-top: 1px solid #eeeeee;border-bottom: 1px solid #eeeeee;">{{ trans('site_v2.Delivery_Address') }}</td>
			</tr>

			<tr>
				<td>
					<span style="font-weight:bold;">{{ trans('site_v2.email') }}:</span> @if(empty($dados['email_fact'])) - @else <span><a href="mailto:{{ $dados['email_fact'] }}" style="color: #1974d8;" title="{{ $dados['email_fact'] }}">{{ $dados['email_fact'] }}</a></span> @endif
				</td>
				<td>
					<span style="font-weight:bold;">{{ trans('site_v2.email') }}:</span> @if(empty($dados['email_entrega'])) - @else <span><a href="mailto:{{ $dados['email_entrega'] }}" style="color: #1974d8;" title="{{ $dados['email_entrega'] }}">{{ $dados['email_entrega'] }}</a></span> @endif
				</td>
			</tr>

			<tr>
				<td><span style="font-weight:bold;">{{ trans('site_v2.phone') }}:</span> @if(empty($dados['contacto_fact'])) - @else {{ $dados['contacto_fact'] }} @endif</td>
				<td><span style="font-weight:bold;">{{ trans('site_v2.phone') }}:</span> @if(empty($dados['contacto_entrega'])) - @else {{ $dados['contacto_entrega'] }} @endif</td>
			</tr>

			<tr>
				<td>
					<span style="font-weight:bold;">{{ trans('site_v2.VAT') }}:</span> @if(empty($dados['nif_fact'])) - @else {{ $dados['nif_fact'] }} @endif
				</td>
				<td></td>
			</tr>

			<tr>
				<td><span style="font-weight:bold;">{{ trans('site_v2.adress') }}:</span> @if(empty($dados['morada_fact'])) - @else {{ $dados['morada_fact'] }} @endif </td>
				<td><span style="font-weight:bold;">{{ trans('site_v2.adress') }}:</span> @if(empty($dados['morada_entrega'])) - @else {{ $dados['morada_entrega'] }} @endif</td>
			</tr>

			<tr>
				<td>@if(empty($dados['morada_opc_fact'])) - @else {{ $dados['morada_opc_fact'] }} @endif </td>
				<td>@if(empty($dados['morada_opc_entrega'])) - @else {{ $dados['morada_opc_entrega'] }} @endif</td>
			</tr>

			<tr>
				<td><span style="font-weight:bold;">{{ trans('site_v2.postal_code') }}:</span> @if(empty($dados['code_post_fact'])) - @else {{ $dados['code_post_fact'] }} @endif</td>
				<td><span style="font-weight:bold;">{{ trans('site_v2.postal_code') }}:</span> @if(empty($dados['code_post_entrega'])) - @else {{ $dados['code_post_entrega'] }} @endif</td>
			</tr>

			<tr>
				<td><span style="font-weight:bold;">{{ trans('site_v2.city') }}:</span> @if(empty($dados['cidade_fact'])) - @else {{ $dados['cidade_fact'] }} @endif</td>
				<td><span style="font-weight:bold;">{{ trans('site_v2.city') }}:</span> @if(empty($dados['cidade_entrega'])) - @else {{ $dados['cidade_entrega'] }} @endif</td>
			</tr>

			<tr style="margin-bottom:40px;">
				<td><span style="font-weight:bold;">{{ trans('site_v2.country') }}:</span> @if(empty($dados['pais_fact'])) - @else {{ $dados['pais_fact'] }} @endif</td>
				<td><span style="font-weight:bold;">{{ trans('site_v2.country') }}:</span> @if(empty($dados['pais_entrega'])) - @else {{ $dados['pais_entrega'] }} @endif</td>
			</tr>
		</table>

		<table bgcolor="#FFFFFF" cellpadding="10" cellspacing="5" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;" valign="top" height="30px" width="100%">
	    </table>
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
				<a href="https://www.facebook.com/queijouniversal"><img height="20" src="{{ asset('site_v2/img/emails/fb.png') }}"></a>&ensp;<a href="https://www.instagram.com/universal_queijo"><img height="20" src="{{ asset('site_v2/img/emails/instagram.png') }}"></a>
			</td>
		</tr>
	</table>


@stop