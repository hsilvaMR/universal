<div class="menu-config" id="menu_config">

	<input id="count_notificacoes" type="hidden" value="{{ Cookie::get('cookie_not_ative') }}">
	
	<div class="menu-config-h3">
		<h3>{{ trans('seller.Notifications') }} <i class="fas fa-times" onclick="$('.menu-config').hide();$('#img_bell').show();"></i></h3> 
	</div>


	<div id="div-loading" style="text-align:center;">
		<i class="fas fa-spinner" style="font-size:50px;margin:20px 0px;"></i>
		<p>Em carregamento ...</p>
	</div>


	<div class="menu-1 display-none">

		<div class="menu-config-filtro">
			<span id="span_filterRead" onclick="filterUnRead();">{{ trans('seller.Filter_for_unread_txt') }}</span>
			<span id="span_markAll" class="float-right" onclick="markAll();">{{ trans('seller.Mark_all_read_txt') }}</span>
		</div>

		<div id="notificacoes"></div>
	</div>


	<div id="noti_empty" class="display-none">
		<div class="menu-config-filtro">
			<span onclick="showAll();">{{ trans('seller.See_All') }}</span>
		</div>

		<div class="menu-config-empty">
			<img src="/img/icones/ok.svg">
			<p class="menu-config-tit-empty">{{ trans('seller.Noti_tit_empty') }}</p>
			<p class="tx-jet">{!! trans('seller.Noti_txt_empty') !!}</p>
		</div>
	</div>

</div>

<script>
	function markAll(){
		$.ajax({
	      type: "POST",
	      url: '{{ route('markAllNotiPost') }}',
	      data: {},
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
	      	$('.menu-1').hide();
	      	$('#noti_empty').show();
		    $('#img_bell_sub').css('display','inline');
		    $('.header-notification-number').css('display','none');
		    $('#count_notificacoes').val(0);
		   
	      }
	  	});
	}

	function showAll(){
		$(".menu-1").hide();
		$('#span_markAll').hide();
		$('#noti_empty').hide();
		$('#span_filterRead').show();
	    $('#div-loading').show();
	    $("#notificacoes").empty();


	    $.ajax({
	      type: "POST",
	      url: '{{ route('notificationsV2') }}',
	      data: { },
	      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) { 
	      setTimeout(function() {$('.menu-1').show();}, 999);
	      setTimeout(function() {$('#div-loading').hide();}, 999);
	      setTimeout(function() {$('#notificacoes').append(resposta);}, 1000);
	    });
	}

	function filterUnRead(){
		$(".menu-1").hide();
	    $('#div-loading').show();
	    $("#notificacoes").empty();


	    $.ajax({
	    	type: "POST",
	      	url: '{{ route('filterUnReadPost') }}',
	      	data: { },
	      	headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
	    })
	    .done(function(resposta) {
	  		console.log({!!Cookie::get('cookie_not_ative')!!});
	      
	    	setTimeout(function() {$('#div-loading').hide();}, 999);
	    	if($('#count_notificacoes').val() == 0){
	    		setTimeout(function() {$('#span_filterRead').hide();}, 1000);
	    		setTimeout(function() {$('#span_markAll').hide();}, 1000);
	    		setTimeout(function() {$('#noti_empty').show();}, 1000);
			}else{
				setTimeout(function() {$('.menu-1').show();}, 999);
	    		setTimeout(function() {$('#notificacoes').append(resposta);}, 1000);
			}
	    });
	}

	function changeVisto(id,url){
		$.ajax({
	      type: "POST",
	      url: '{{ route('markNotiPost') }}',
	      data: {id:id},
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
	      	console.log('www.universal.com.pt'+url);
		    $('#not_'+id).css('background-color','#fff');
		    $('#header-notification-number').html('');
		    $('#header-notification-number').html(resp.not_count);
		    window.location = url;
	      }
	  	});
	}
</script>