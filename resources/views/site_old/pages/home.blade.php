@extends('site_old/layouts/default')


@section('content')
    <section class="main">
        <section class="slider">
            <div class="wrap">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <!-- upload/slide1.jpg upload/slide2.jpg upload/slide3.jpg img/slider-01.jpg img/slider-03.jpg -->
                        <div class="swiper-slide">
                            <img src="site_old/img/slide/slider-02.jpg" height="360" alt="Queijo Universal">
                        </div>
                        <div class="swiper-slide">
                            <img src="site_old/img/slide/slider-04.jpg" height="360" alt="Queijo Universal">
                        </div>
                        <div class="swiper-slide">
                            <img src="site_old/img/slide/slide.jpg" height="360" alt="Queijo Universal">
                        </div>                        
                        <div class="swiper-slide">
                            <img src="site_old/img/slide/slider-05.jpg" height="360" alt="Queijo Universal">
                        </div>
                        <div class="swiper-slide">
                            <img src="site_old/img/slide/slider-06.jpg" height="360" alt="Queijo Universal">
                        </div>
                        
                    </div>
                    <div class="swiper-pagination-wrapper">
                        <div class="swiper-pagination"></div>
                    </div><!-- /swiper-pagination-wrapper -->
                </div>
            </div><!-- wrap -->
        </section><!-- slider -->
        <section class="features">
            <div class="wrap">
                <div class="features-columns clearfix">

                    <div class="features-column">{!! trans('site_old.About_Us_Tit') !!} {!! trans('site_old.About_Us_Txt') !!}</div><!-- features-column -->
                    <div class="features-column">{!! trans('site_old.At_the_beginning_Tit') !!} {!! trans('site_old.At_the_beginning_Txt') !!}</div>
                    <div class="features-column">{!! trans('site_old.We_universalize_Tit') !!} {!! trans('site_old.We_universalize_Txt') !!}</div>
                

                </div>

                <div class="separator"></div>
            </div><!-- wrap -->
        </section><!-- features -->
        <section class="textblock">
            <div class="wrap">
                <img src="site_old/img/site/queijo.jpg" width="320" height="280" alt=""><!-- upload/image.jpg -->
                <div class="text-column">
               
                    <h2>{{ trans('site_old.the_Cheese_Tit') }}</h2>
                    <p>{{ trans('site_old.the_Cheese_Txt') }}</p>
                    {!! trans('site_old.the_Cheese_Top') !!}

                </div><!-- text-column -->
                <div class="separator"></div>
            </div><!-- wrap -->
        </section><!-- textblock -->
        <section class="textblock textblock-last">
            <div class="wrap">
                <img src="site_old/img/site/manteiga.jpg" width="320" height="280" class="align-right" alt=""><!-- upload/image2.jpg -->
                <div class="text-column">

                    <h2>{{ trans('site_old.the_Butter_Tit') }}</h2>
                    <p>{{ trans('site_old.the_Butter_Txt') }}</p>
                    {!! trans('site_old.the_Butter_Top') !!}

                </div><!-- text-column -->
            </div><!-- wrap -->
        </section><!-- textblock -->
        
        <section class="twitter">
            <div class="wrap">
                <!--<img src="/img/site/universal.png" height="142" alt="Logotipo Universal">-->
                <img src="site_old/img/icons/logo-queijo.png" height="146" alt="Logotipo Queijo Universal">
            </div><!-- wrap -->
        </section><!-- twitter -->
        <section class="newsletter">
            <div class="wrap">
                <div class="newsletter-title">{{ trans('site_old.Contacts') }}</div>
                <div class="newsletter-wrapper">
                    <div class="newsletter-form clearfix">

                        <form id="form-contacto" class="form-inline" action="{{ route('FormContactoPost') }}" name="form" method="post">
                            {{ csrf_field() }}
                            <input name="cf_name" type="text" class="txtbox" placeholder="{{ trans('site_old.name') }}" required><br>
                            <input name="cf_email" type="email" class="txtbox" placeholder="{{ trans('site_old.email') }}" required><br>
                            <textarea name="cf_message" class="txtbox" placeholder="{{ trans('site_old.message') }}" required></textarea><br>

                            <div class="float-right" style="margin-top: 20px;">
                                <p id="formGreen"></p>
                                <p id="formRed"></p>
                            </div>
                            <br><input type="submit" name="Submit" value="{{ trans('site_old.send') }}">
                        </form>

                    </div><!-- newsletter-form -->
                </div><!-- newsletter-wrapper -->
                    <p class="newsletter-note">{!! trans('site_old.contact_txt') !!}</p><br>
                    {!! trans('site_old.adress_txt') !!}
            </div><!-- wrap -->
        </section><!-- newsletter -->

        
    </section>
@stop

@section('css')
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.1.6/css/swiper.css">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.1.6/css/swiper.min.css">
@stop

@section('javascript')
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.1.6/js/swiper.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.1.6/js/swiper.min.js"></script>
<script src="site_old/js/jquery.js"></script>
<script src="site_old/js/library.js"></script>
<script src="site_old/js/script.js"></script>
<script src="site_old/js/retina.js"></script>
<script src="site_old/js/javatm.js"></script>
<script type="text/javascript">
    function stopRKey(evt) {
      var evt = (evt) ? evt : ((event) ? event : null);
      var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
      if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
    }
    document.onkeypress = stopRKey;


    /*FUNCTION FORMULARIO*/
    $('#form-contacto').on('submit',function(e) {
        var form = $(this);
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: new FormData(this),
            contentType: false,
            processData: false,
            cache: false,
            headers:{'X-CSRF-Token':'{!! csrf_token() !!}'}
        })
       .done(function(resposta) {
         try{ resp=$.parseJSON(resposta); }
            catch (e){
                if(resposta){
                    $('#formRed').css('color','red');
                    $('#formRed').html(resposta);
                }
                return;
            }
            if(resp.estado =='sucesso'){
                $('#formRed').hide();
                $('#formGreen').css('color','green');
                $('#formGreen').html(resp.mensagem);

                document.getElementById("form-contacto").reset();
            }
        });
    });

</script>
@stop