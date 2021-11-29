@extends('backoffice/emails/layouts/default')

@section('content')
	@include('backoffice/emails/includes/header')


	<table style="padding-top:40px;background-color:#fff;padding:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%" style="color:#333;font-size:16px;line-height:20px;">
		    	<img height="75" src="{{ asset('backoffice/img/emails/file.png') }}">
		        <p style="margin-top:20px;">
		        	{{ trans('backoffice.TheVersion') }} <b>#{{ $dados['id_versoes'] }}</b> {{ trans('backoffice.TheVersion_txt') }}
		        </p>
			</td>
		</tr>
	</table>


	<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;padding-bottom:10px;" valign="top" width="100%">

		<tr style="vertical-align: top;" valign="top" align="center">
			<td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">	
			
				<!--[if (!mso)&(!IE)]><!-->
				<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:10px; padding-bottom:10px; padding-right: 0px; padding-left: 0px;">
				<!--<![endif]-->
				<div align="center" class="button-container" style="padding-top:0px;padding-right:30px;padding-bottom:30px;padding-left:30px;width:200px;">
				<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 0px; padding-right: 30px; padding-bottom: 30px; padding-left: 30px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ route('loginPageB') }}" style="height:31.5pt; width:150pt; v-text-anchor:middle;" arcsize="96%" stroke="false" fillcolor="#1974d8"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:'Trebuchet MS', Tahoma, sans-serif; font-size:16px"><![endif]-->
				<a href="{{ route('loginPageB') }}" target="_blank"><div style="text-decoration:none;display:block;color:#ffffff;background-color:#1974d8;border-radius:40px;-webkit-border-radius:40px;-moz-border-radius:40px;width:100%; width:calc(100% - 2px);border-top:1px solid #1974d8;border-right:1px solid #1974d8;border-bottom:1px solid #1974d8;border-left:1px solid #1974d8;padding-top:5px;padding-bottom:5px;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;">
					<span style="padding-left:5px;padding-right:5px;font-size:16px;display:inline-block;">
					<span style="font-size: 16px; line-height: 32px;color:#ffffff;">{!! trans('backoffice.beginSession') !!}</span>
					</span>
				</div></a>
				<!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
				</div>
				</div>
		
			</td>
		</tr>
	</table>
	

	<table style="padding:40px 180px 0px 180px;text-align:center;color:#666;font-size:12px;" border="0" cellpadding="0" width="100%">
	   <tr>
		  	<td align="center" width="100%" style="line-height:15px;">
		    	{!! trans('site_v2.doesntWork_tx') !!} <a href="{{ route('loginPageB') }}" style="text-decoration:none;color:#1974d8;" target="_blank">{{ route('loginPageB') }}</a>
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
				<img height="25" src="{{ asset('site_v2/img/emails/slogan.png') }}">
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
				<a href="https://www.facebook.com/queijouniversal" target="_blank"><img height="20" width="20" src="{{ asset('site_v2/img/emails/fb.png') }}"></a>&ensp;<a href="https://www.instagram.com/universal_queijo" target="_blank"><img height="20" width="20" src="{{ asset('site_v2/img/emails/instagram.png') }}"></a>
			</td>
		</tr>
	</table>

@stop