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
          $('#span_point').html('');
          $('#span_point').append(resp.totalPoints);
          $('#span_point_history').html('');
          $('#span_point_history').append(resp.totalPoints);
          $('#novos_rotulos').prepend(resp.novos_rotulos);
          
          
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
      url: '{{ route('cancelEmailPostV2') }}',
      data: { },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
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
      url: '{{ route('resendEmailPostV2') }}',
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
  function goup() {
    $('html, body').animate({ scrollTop: 0 }, "slow");
    return false;
  }
</script>
