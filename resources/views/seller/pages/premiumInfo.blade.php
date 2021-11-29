@extends('seller/layouts/default')
@section('content')

<div class="container-fluid mod-seller">
    
  @include('seller/includes/headerSubMenu')
  @include('seller/includes/menuSettings')
  @include('seller/includes/menuNotifications')

  <div class="row">
    <div class="col-lg-3">
        @include('seller/includes/menu')
    </div>
    <div class="col-lg-9">
      <div class="mod-tit">
        <h3>Adquirir Prémio</h3>
      </div>
        
      <div class="mod-area">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="premium-info-img" style="background-image:url({{ $info_premium->img }});"></div>
            </div>

            <div class="col-md-6">
              <div class="premium-info-desc">
                <h1>{{ $info_premium->name }}</h1>
                <div class="margin-bottom10">
                  <label>{{ $info_premium->desc }}</label><br>
                  <label>{{ $info_premium->valor_empresa }} {{ trans('site_v2.points') }}</label>
                </div>
                
                <form id="form-premium" action="{{ route('addPremiumCompanyPost') }}" name="form" method="post">
                  {{ csrf_field() }}

                  <input type="hidden" name="id_premio" value="{{ $info_premium->id }}">
                  <input type="hidden" name="valor_premio" value="{{ $info_premium->valor_empresa }}">
                  <input type="hidden" name="id_empresa" value="{{ Cookie::get('cookie_comerc_id_empresa') }}">
                  <input type="hidden" name="id_comerciante" value="{{ Cookie::get('cookie_comerc_id') }}">

                  <label class="label_select">
                    <select name="quantidade">
                      <option value="quantidade" id="qtd" selected disabled="true">{{ trans('seller.Amount') }}</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                  </label>
                  <br>
                            
                  @if($variantes)
                    <label class="label_select">
                      <select name="variante">
                        <option value="variante" selected disabled="true">{{ $name_var->var_name }}</option>
                        @foreach($variantes as $val)
                          <option value="{{ $val['valor'] }}">{{ $val['valor'] }}</option>
                        @endforeach
                      </select>
                    </label>
                  @endif
                  <br>
                  <br>

                  @if($info_premium->valor_empresa != 0)<button class="bt-blue">{{ trans('seller.CHANGE') }}</button>@endif
          
                  <div class="width100 height60">
                    <label id="labelSucesso" class="av-100 alert-success display-none float-right" role="alert">
                      <span id="spanSucesso">{{ trans('site_v2.Successfully_Added') }}</span> 
                      <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
                    </label>
                    <label id="labelErros" class="av-100 alert-danger display-none float-right" role="alert">
                      <span id="spanErro"></span> 
                      <i class="fas fa-times" onclick="$(this).parent().hide();"></i>
                    </label>
                  </div>
                  <div class="height20"></div>
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
  </div>
</div>
@stop

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@stop

@section('javascript')

<script>
  $('#form-premium').on('submit',function(e) {
    $("#labelSucesso").hide();
    $("#labelErros").hide();

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

      if(resposta == 'sucesso'){ 
        $("#labelSucesso").show();
        $('#form-premium').get(0).reset();
      }
      else{
        $("#spanErro").html(resposta);
        $("#labelErros").show();
      }
    });
  });
</script>
@stop