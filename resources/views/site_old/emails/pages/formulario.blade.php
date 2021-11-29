@extends('site_v2/emails/layouts/default')

@section('content')
	@include('site_v2/emails/includes/header')

	<table style="padding-top:20px;" border="0" cellpadding="0" width="100%">
	   	<tr>
		    <td width="100%" style="color:#58595b;font-size:14px;line-height:20px;text-align: justify;">
		    	{!! trans('email.Info_Formulario') !!}

	        	@foreach($dados as $val)
	      			@if($val['tipo']=='checkbox')
	    
	        			@php $array=json_decode($val['resposta']); @endphp
	        			<br><br>{!! $val['pergunta'] !!} : @if(isset($array)) @foreach($array as $value) {{ $value }} / @endforeach @endif
	        		@elseif($val['tipo']=='file')
	        			<br><br>{!! $val['pergunta'] !!} : http://www.melomcool.pt{!! nl2br($val['resposta']) !!}	
	        		@else
        				<br><br>{!! $val['pergunta'] !!} : {!! nl2br($val['resposta']) !!}		
        			@endif   
	        	@endforeach
	     	
			</td>
		</tr>
	</table>

	@include('site_v2/emails/includes/footer')	
@stop