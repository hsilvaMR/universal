<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Laravel -->
<script src="{{ asset('site_v2/js/app.js') }}"></script>

<!-- Javascript -->
<!--<script src="{ { asset('site_v2/js/main.js') }}"></script>-->

<script>
  
  function showHeaderXS(){
    setTimeout(function(){$("#submenu-xs").show();}, 300);
    $('body').css('overflow','hidden');
    $('.header-xs-client').animate({right: "280"}, 300);
    $('#submenu-xs').animate({right: "0"}, 300);
    $('body').animate({right: "280"}, 300);
  }
  
  function hideMenuXS(){
    $('#submenu-xs').hide();
    $('body').css('overflow','visible');
    $('.header-xs-client').animate({right: "0"}, 300);
    $('body').animate({right: "0"}, 300);
  }

  function showNotification(){
    //$('#menu_config').show();
    $('#menu_notification').hide();
    
    $(".menu-1").hide();
    $('#noti_empty').hide();
    $('#div-loading').show();
    $("#notificacoes").empty();


    $.ajax({
      type: "POST",
      url: '{{ route('notificationsV2') }}',
      data: { },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
  
      setTimeout(function() {$('#div-loading').hide();}, 999);

      if($('#count_notificacoes').val() == 0){
        setTimeout(function() {$('#noti_empty').show();}, 1000);
      }else{
        setTimeout(function() {$('.menu-1').show();}, 999);
        setTimeout(function() {$('#notificacoes').append(resposta);}, 1000);
      }
    });
    
 
    var x = document.getElementById("menu_config");
    var displaySetting = x.style.display;
    
    if (x.style.display === "none") {
      x.style.display = "block";
    }
    else if(displaySetting == ''){
      x.style.display = "block";
    }
    else {
      x.style.display = "none";
    }
  }

  function showConfig(){
    //$('#menu_notification').show();
    $('#menu_config').hide();

    var x = document.getElementById("menu_notification");
    var displaySetting = x.style.display;

    if (x.style.display === "none") {
      x.style.display = "block";
    }
    else if(displaySetting == ''){
      x.style.display = "block";
    }
    else {
      x.style.display = "none";
    }
    
  }
  

</script>

<script>
  $("#enc_email").on('change', function() {
    if ($(this).is(':checked')) { $('#enc_email').show(); $(this).attr('value', '1'); } 
    else { $('#enc_email').hide(); $(this).attr('value', '0'); }
  });

  $("#enc_uti").on('change', function() {
    if ($(this).is(':checked')) { $('#enc_uti').show(); $(this).attr('value', '1'); } 
    else { $('#enc_uti').hide(); $(this).attr('value', '0'); }
  });

  $("#recibo_uti").on('change', function() {
    if ($(this).is(':checked')) { $('#recibo_uti').show(); $(this).attr('value', '1'); } 
    else { $('#recibo_uti').hide(); $(this).attr('value', '0'); }
  });
</script>


<script>
  
  $(".menuClick").click(function(){
    var submenu=$(this).next();
    $(".menuOpen").not(submenu).slideUp();
    submenu.slideToggle();

    var seta=$(this).find('.fa-angle-down');
    $(".fa-angle-down").not(seta).removeClass('rodar180');
    seta.toggleClass('rodar180');
  });

</script>


<script>
	$(document).ready(function () { 
	  var $campo = $("#insert-code");
	  $campo.mask('AAA AAA', {reverse: true});
	});
</script>

<script>
    $('#form-insertCodes').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: new FormData(this),
        contentType: false,
        processData: false,
        cache: false
      })
      .done(function(resposta){
        console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            if(resposta){ $("#spanErro").html(resposta); }
            else{ $("#spanErro").html('ERROR'); }
            $("#labelErros").show();
            $('#loading').hide();
            return;
        }
        if(resp.estado=='sucesso'){
          $("#labelSucesso").show();
          //setTimeout(function(){location.reload();},1000);
          $('#points_user').html('');
          $('#points_user').append(resp.totalPoints);
          $('#points_header').html('');
          $('#points_header').append(resp.totalPoints);
          
          document.getElementById("form-insertCodes").reset();
        }else if(resposta){
          $("#spanErro").html(resposta);
          $("#labelErros").show();
        }
        $('#loading').hide();
      });
    });
</script>

<script>
  function cancelValitionEmail(){

    $.ajax({
      type: "POST",
      url: '{{ route('cancelEmailSellerPostV2') }}',
      data: { },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      try{ resp=$.parseJSON(resposta); }
      catch (e){}
      if(resp.estado == 'sucesso'){
        setTimeout(function() { $('.ModalEmailAlternativo').hide() }, 1000);
      }
    });
  }

  function resendValitionEmail(){
    $.ajax({
        type: "POST",
        url: '{{ route('resendEmailSellerPostV2') }}',
        data: { },
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        try{ resp=$.parseJSON(resposta); }
        catch (e){}

        if(resp.estado == 'sucesso'){
          $('.resendEmail').html('Reenviado com sucesso.');
        }
      });
  }
</script>

<script>
  function cancelOrder(){

    var id = $('#id_modal').val();
    $.ajax({
      type: "POST",
      url: '{{ route('cancelOrderPost') }}',
      data: {id:id},
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      console.log(resposta);
      try{ resp=$.parseJSON(resposta); }
        catch (e){}

        if(resp.estado == 'sucesso'){
          
          $('#enc_'+id).hide();

          if (resp.total_enc == 1) {
            $('#ultimas_enc').html('<tr class="bg-gray"><td valign="top" colspan="8" class="dataTables_empty">{{ trans('seller.Empty_data_table_txt') }}</td></tr>');
          }

          if (resp.estado_enc == 'fatura_vencida') { $('#enc_vencida').html(resp.total_estado);}
          if(resp.estado_enc == 'registada'){ $('#enc_registada').html(resp.total_estado);}
          if(resp.estado_enc == 'em_processamento'){ $('#enc_proc').html(resp.total_estado);}
          if(resp.estado_enc == 'expedida_parcialmente'){ $('#enc_exp_parcial').html(resp.total_estado);}
          if(resp.estado_enc == 'expedida'){ $('#enc_exp').html(resp.total_estado);}
          if(resp.estado_enc == 'concluida'){ $('#enc_concluida').html(resp.total_estado);}

          for(value in resp.enc_array){
            $('#enc_armz_'+resp.enc_array[value].id_morada).html(resp.enc_array[value].count_enc);
          }
        }
    });
  }
</script>

<script>
  function goup() {
    $('html, body').animate({ scrollTop: 0 }, "slow");
    return false;
  }
</script>
