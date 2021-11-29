<div class="menu-config" id="menu_notification">
	
	<div class="menu-config-h3">
		<h3>{{ trans('seller.Settings') }} <i class="fas fa-times" onclick="$('.menu-config').hide();$('#img_bell').show();"></i></h3> 
	</div>

	<div class="menu-inicial padding20">
		<?php
			$count_recibo = 0;
	        $count_enc = 0;
	        $count_email = 0;
		?>
		@foreach($configuracoes as $config)

			@if ($config->tag == 'recibo_uti') 
                <?php $count_recibo = $count_recibo + 1; $valor_recibo = $config->valor; $tag_recibo = $config->tag; $id_recibo = $config->id;?>
            @endif

            @if ($config->tag == 'enc_uti')
                <?php $count_enc = $count_enc + 1; $valor_enc = $config->valor; $tag_enc = $config->tag; $id_enc = $config->id; ?>
            @endif

            @if ($config->tag == 'enc_email')
               <?php $count_email = $count_email + 1; $valor_email = $config->valor; $tag_email = $config->tag; $id_email = $config->id ?>
            @endif
        @endforeach
			
		
		@if((Cookie::get('cookie_comerc_type') == 'admin') || (Cookie::get('cookie_comerc_type') == 'gestor'))

			<div class="menu-notification">
				<span class="font16"><span class="tx-bold">{{ trans('seller.Users') }}:</span> {{ trans('seller.Receipt') }}</span>
				<br>
				<span class="tx-jet">{{ trans('seller.Receive_email_receipt_txt') }}</span>
			</div>
			<div class="float-right">
				<label class="switch" >
					@if($count_recibo == 0)
						<input type="checkbox" id="recibo_uti" value="0" onclick="ChangeConfig('recibo_uti',0);">
					@else
						<input type="checkbox" id="recibo_uti" @if($valor_recibo == 1 && ($tag_recibo == 'recibo_uti')) checked @endif value="{{ $valor_recibo}}" onclick="ChangeConfig('recibo_uti',@if($tag_recibo == 'recibo_uti') {{ $id_recibo }} @else 0 @endif);">
					@endif
					<span class="slider round"></span>
				</label>
			</div>
			
			<div class="menu-notification">
				<span class="font16"><span class="tx-bold">{{ trans('seller.Users') }}:</span> {{ trans('seller.Order') }}</span>
				<br>
				<span class="tx-jet">{{ trans('seller.Receive_email_summary_txt') }}</span>
			</div>
			<div class="float-right">
				<label class="switch">
					@if($count_enc == 0)
						<input type="checkbox" id="enc_uti" value="0" onclick="ChangeConfig('enc_uti',0);">
					@else
						<input type="checkbox" id="enc_uti" @if($valor_enc == 1 && ($tag_enc == 'enc_uti')) checked @endif value="{{ $valor_enc }}" onclick="ChangeConfig('enc_uti',@if($tag_enc == 'enc_uti') {{ $id_enc }} @else 0 @endif);">
					@endif
					<span class="slider round"></span>
				</label>
			</div>
		@endif

		@if((Cookie::get('cookie_comerc_type') != 'gestor'))
			<div class="menu-notification">
				<span class="font16">{{ trans('seller.Orders') }}</span>
				<br>
				<span class="tx-jet">{{ trans('seller.Track_status_orders_txt') }}</span>
			</div>
			<div class="float-right">
				<label class="switch">
					@if($count_email == 0)
						<input type="checkbox" id="enc_email" value="0" onclick="ChangeConfig('enc_email',0);">
					@else
						<input type="checkbox" id="enc_email" @if(($valor_email) == 1 && ($tag_email == 'enc_email')) checked value="{{ $valor_email }}"@else value="0" @endif onclick="ChangeConfig('enc_email',@if($tag_email == 'enc_email') {{ $id_email }} @else 0 @endif);">
					@endif
					<span class="slider round"></span>
				</label>
			</div>
		@endif

	</div>
</div>

<script>
	function ChangeConfig(tag,id){

		var enc = $('#enc_email').val();
		var order_user = $('#enc_uti').val();
		var orders_receipt = $('#recibo_uti').val();

		console.log(enc);
		

		$.ajax({
	      type: "POST",
	      url: '{{ route('changeConfigPost') }}',
	      data: {tag:tag,id:id,enc:enc,order_user:order_user,orders_receipt:orders_receipt},
	      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	    	console.log(resposta);
	      try{ resp=$.parseJSON(resposta); }
	      catch (e){
	          if(resposta){}
	          else{}
	          return;
	      }
	      if (resp.estado == 'sucesso') {
	      	console.log('#'+resp.tag);
	      	$('#'+resp.tag).attr('onclick','ChangeConfig(\''+resp.tag+'\','+resp.id_line+')');
	      }
	  	});
	}


</script>