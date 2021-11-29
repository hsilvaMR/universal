@extends('client/layouts/default')
@section('content')
<section class="min-height">
    
  <div class="codes-bg">
    <div class="codes-bg-img codes-padding" style="background-image:url('/site_v2/img/site/code-hero-back.png');">
      <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <div class="client-div">
              <form id="form-insertCodes" action="{{ route('insertCodesPostV2') }}" name="form" method="post">
                {{ csrf_field() }}
                <h1>{{ trans('site_v2.Hello') }} {{ $utilizadores->nome }},</h1>
                <p>{{ trans('site_v2.Your_current_balance') }} <span id="points_user" class="font-bold">@if(Cookie::get('cookie_user_points') < 0) 0 @else {{ Cookie::get('cookie_user_points') }} @endif</span> {{ trans('site_v2.points') }}.</p>
                <p class="font18 margin-bottom40">{{ trans('site_v2.have_codes_txt') }}</p>

                <input id="code-premios" class="ip header-client-ip" maxlength="7" type="text" name="codes" placeholder="000 000">
                <button class="bt-50 bt-codes" type="submit"><i class="fas fa-plus"></i></button>  

                <a href="{{ route('areaReservedDataV2') }}"><p class="client-txt">{{ trans('site_v2.change_account_data') }}</p></a>

                <label id="labelSucesso" class="av-100 alert-success display-none" role="alert"><span id="spanSucesso">{{ trans('site_v2.Successfully_Introduced') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
                <label id="labelErros" class="av-100 alert-danger display-none" role="alert"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="universal-frame"></div>

  @include('site_v2/includes/all-premium')

  <div class="bg-gradient-blue">
    <div class="premium-banner" style="background: url('/site_v2/img/site/code-hero-back.png')no-repeat center;">
      <div class="container">
        <h1>{!! $conteudos['sect_premium_banner_tit'] !!}</h1>
        <a href="{{ route('buyPointsV2') }}"><button class="bt-gray tx-transform">{!! $conteudos['sect_premium_banner_bt'] !!}</button></a>
      </div>
    </div>
  </div>
</section>
@stop

@section('css')
@stop

@section('javascript')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
  <script src="{{ asset('site_v2/js/pagination.js') }}"></script>
  <script>
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-client-site').css('background-color','#fff');
  </script>

  <script>
    $(document).ready(function () { 
      var $campo = $("#code-premios");
      $campo.mask('AAA AAA', {reverse: true});
    });
  </script>

  <script>
    /*
    $('#form-insertCodes').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
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
            $('#botoes').show();
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
        $('#botoes').show();
      });
    });*/
  </script>
@stop