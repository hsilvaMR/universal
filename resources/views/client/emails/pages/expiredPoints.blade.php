@extends('site_v2/emails/layouts/default')

@section('content')
	@include('site_v2/emails/includes/header')

	<table style="padding-top:40px;background-color:#fff;padding:40px;" border="0" cellpadding="0" width="100%">
	   <tr>
		    <td align="center" width="100%" style="color:#333;font-size:16px;line-height:20px;">

		    	<img height="75" src="{{ asset('site_v2/img/emails/warning.png') }}">
		        <p style="margin-top:20px;">{!! trans('site_v2.Points_expiring_account_txt') !!}</p>
			</td>
		</tr>
	</table>

	<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; width: 100%;padding-bottom:20px;" valign="top" width="100%">

		<tr style="vertical-align: top;" valign="top">
			<td style="word-break: break-word; vertical-align: top; border-collapse: collapse;" valign="top">
			<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#FFFFFF"><![endif]-->

			<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 10px; padding-right: 10px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:31.5pt; width:150pt; v-text-anchor:middle;" arcsize="48%" stroke="false" fillcolor="#1974d8"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px"><![endif]-->
			<div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#1974d8;border-radius:20px;-webkit-border-radius:20px;-moz-border-radius:20px;width:auto;border-top:1px solid #1974d8;border-right:1px solid #1974d8;border-bottom:1px solid #1974d8;border-left:1px solid #1974d8;padding-top:5px;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span style="font-size: 16px; line-height: 32px;"><a href="{{ route('premiumPageV2') }}" target="_blank" style="color:#ffffff;text-decoration:none;border-radius:20px;border-radius: 20em;">{{ trans('site_v2.View_All_Awards') }}</a></span></span></div>
			<!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->

			<!--[if (!mso)&(!IE)]><!-->

			<!--<![endif]-->


			<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
			<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->


			<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
			</td>
		</tr>
	</table>

	<table style="background-color:#fff;color:#1974d8;font-size:16px;" border="0" cellpadding="0" width="100%">
		<tr>
			<td align="center" width="100%">
				{!! trans('site_v2.Missin_points_txt') !!}
			</td>
		</tr>
	</table>

	<table style="padding:20px 180px 0px 180px;text-align:center;color:#666;font-size:12px;" border="0" cellpadding="0" width="100%">
		<tr>
			<td align="center" width="100%" >{{ trans('site_v2.add_email_contacts_txt') }}: <a style="color:#1974d8 !important;text-decoration:none !important;">no-replay@universal.com.pt</a></td>
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
				<a href="https://www.facebook.com/queijouniversal"><img height="20" src="{{ asset('site_v2/img/emails/fb.png') }}"></a>&ensp;<a href="https://www.instagram.com/universal_queijo"><img height="20" src="{{ asset('site_v2/img/emails/instagram.png') }}"></a>
			</td>
		</tr>
	</table>


@stop