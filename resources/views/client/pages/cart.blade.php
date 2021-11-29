@extends('client/layouts/default-menu')
@section('content')


<section class="min-height data-mod">
  @if(isset($aviso))@include('client/includes/modalEmail')@endif
  
  <div class="container">
    <div class="bg-white">

      <div class="data-border-form">
        <label>{{ trans('site_v2.Shopping_Cart') }}</label>
      </div>

      @if($carrinho_utilizador > 0)
        <div id="table_product">
          <div class="cart overflowX">
            <table class="table table-striped table-datos">
              <thead>
                <tr class="table-thead font16">
                  <th class="cart-table"></th>
                  <th class="cart-table" scope="col">{{ trans('site_v2.Article') }}</th>
                  
                  <th scope="col" class="tx-right cart-table">{{ trans('site_v2.Amount') }}</th>
                  <th scope="col" class="tx-right cart-table">{{ trans('site_v2.Points') }}</th>
                  <th scope="col" class="tx-right cart-table">{{ trans('site_v2.Value') }}</th>
                </tr>
              </thead>
              <tbody>
                @php $valor = 0; @endphp
                @foreach($car as $car_uti)
                  @php
                    $valor_qtd = $car_uti->quantidade * $car_uti->valor_cliente;
                    $valor = $valor + $valor_qtd;
                  @endphp

                  <tr class="cart-height-line" id="premio_{{ $car_uti->id_linha }}">
                    <td scope="row">
                      <i class="fas fa-times cart-icon-delete" data-toggle="modal" data-target="#modalDelete_{{ $car_uti->id }}"></i>
                    </td>
                    <td class="cart-width-td" scope="row">
                      <div class="cart-div-img"><img src="{{ $car_uti->img }}"></div>
                    
                      <div @if($car_uti->variante != '') class="cart-desc-variante" @else class="cart-desc" @endif>
                        <span class="cart-font-14 margin-left20">{{ $car_uti->nome_pt }}</span>
                        @if($car_uti->variante != '')
                          <br><span class="cart-font-14 margin-left20">{{ $car_uti->variante }}</span>
                        @endif
                        <br>
                        <span class="cart-font-12 margin-left20">{{ $car_uti->valor_cliente }} {{ trans('site_v2.Points') }}</span>
                      </div>
                    </td>
                    <td scope="row" class="cart-td cart-width-td">
                      <div class="cart-div-input">
                        <div class="cursor-pointer float-left" id="decrease" onclick="decreaseValue({{ $car_uti->quantidade }},{{ $car_uti->valor_cliente }},{{ $car_uti->id_linha }},{{ $car_uti->id_carrinho }},'decrementar',{{ $valor}})" value="Decrease Value">-</div>
                        <input class="cart-input" type="number" id="quantidade{{ $car_uti->id_linha }}" value="{{ $car_uti->quantidade }}"  onchange="updateCar({{ $car_uti->valor_cliente }},{{ $car_uti->id_linha }},{{ $car_uti->id_carrinho }},'update',{{ $valor}});"> <!--readonly-->
                        <div class="cursor-pointer float-right" id="increase" onclick="increaseValue({{ $car_uti->quantidade }},{{ $car_uti->valor_cliente }},{{ $car_uti->id_linha }},{{ $car_uti->id_carrinho }},'incrementar',{{ $valor}})" value="Increase Value">+</div>
                      </div>
                    </td>
                    @if(($car_uti->quantidade*$car_uti->valor_cliente) > ($car_uti->pontos_utilizados))
                      <td class="cart-td cart-width-td"> <span id="premio_ponto{{ $car_uti->id_linha }}" class="tx-coral">{{ $car_uti->pontos_utilizados }}</span> / <span id="valor_total_unidade{{ $car_uti->id_linha }}">{{ $valor_qtd }}</span></td>
                    @else
                      <td class="cart-td cart-width-td"> <span id="premio_ponto{{ $car_uti->id_linha }}">{{ $car_uti->pontos_utilizados }}</span> / <span id="valor_total_unidade{{ $car_uti->id_linha }}">{{ $valor_qtd }}</span></td>
                    @endif
                    
                    <td class="cart-td cart-width-td">0,00€</td>
                  </tr>

                  <!-- Modal -->
                  <div class="modal fade" id="modalDelete_{{ $car_uti->id_premio }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close areaReservada-icon-modal" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body text-center padding50">
                          <input id="count_car" type="hidden" name="count_car" value="{{ $carrinho_utilizador }}">
                          <h3>{!! trans('site_v2.DeletePremium_txt') !!}</h3>
                          
                          <button type="button" class="bt-white tx-coral margin-top30" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i> {{ trans('site_v2.Cancel') }}</button>
                          <button type="button" class="bt bt-blue margin-top30 margin-left20" data-dismiss="modal" aria-label="Close" onclick="deletePremium({{ $car_uti->id_linha }},{{ $car_uti->id }});"><i class="fas fa-check"></i> {{ trans('site_v2.Remove') }}</button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach

                <tr id="points_tr" class="cart-height-line display-none">
                  <td style="width:50px !important;"></td>
                  <td class="cart-width-td">
                    <div class="cart-div-cupao float-left">
                      <img src="site_v2/img/cart/carrinho-pontos.png">
                    </div>
                    <div class="cart-desc-prod">
                      <span class="margin-left20">{{ trans('site_v2.Points') }} x <span id="pontos_falta">{!! $pontos_falta !!}</span></span>
                      <br>
                      <span class="margin-left20 cart-font-12">{{ trans('site_v2.Missing_Points') }}</span>
                    </div>
                  </td>
                  <td class="cart-td cart-width-td">1</td>
                  <td class="cart-td cart-width-td" id="points_td">{!! $pontos_falta !!}</td>
                  <td class="cart-td cart-width-td" id="valor_euro_td">{!! $valor_euro !!}€</td>
                </tr>
                
              
                <tr class="cart-height-line">
                  <td style="width:50px;"></td>
                  <td class="cart-width-td"><div class="cart-div-cupao"><img src="site_v2/img/cart/carrinho-portes.png">
                  <label class="margin-left20">{{ trans('site_v2.Send') }}</label></div></td>
                  <td class="cart-td cart-width-td">1</td>
                  <td class="cart-td cart-width-td">0</td>
                  <td class="cart-td cart-width-td">0,00€</td>
                </tr>
                <tr class="cart-height-line">
                  <td style="width:50px;"></td>
                  <td class="cart-width-td"></td>
                  <td class="cart-width-td"></td>
                  <td class="cart-txt cart-width-td" id="total_pontos">{{ trans('site_v2.Total_of_points') }} <span class="cart-desc-bold" id="points">{{ $valor}}</span></td>
                  <td class="cart-txt cart-width-td">{{ trans('site_v2.Total_Value') }} <span class="cart-desc-bold" id="valor_euro">{!! $valor_euro !!}€</span></td>
                </tr>
                <tr class="cart-height-line">
                  <td style="width:50px;"></td>
                  <td class="cart-width-td"></td>
                  <td class="cart-width-td"></td>
                  <td class="tx-right cart-width-td">{{ trans('site_v2.Remaining_points') }} <span class="cart-rest-points" id="pontos_restantes">{{ $pontos_restantes }}</span></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div id="botoes" class="cart-bt">
            <a href="{{ route('areaReservedV2') }}"><button class="bt bt-gray-clear"><i class="fas fa-angle-left"></i> {{ trans('site_v2.Continue_to_Site') }}</button></a>
            <button class="bt-blue cart-bt-continue" onclick="next_Page();">{{ trans('site_v2.Billing_Delivery_Details') }} <i class="fas fa-angle-right"></i></button>
          </div>    
          <div class="height20"></div>
        </div>
      @else
        <div class="cart-status">
          <img height="75" src="site_v2/img/cart/carrinho-vazio.png">
          <p class="cart-status-txt">{!! trans('site_v2.Cart_clean_desc') !!} <a href="{{ route('buyPointsV2') }}" class="tx-navy">{{ trans('site_v2.Consult_page') }}</a></p>

          <a href="{{ route('areaReservedV2') }}"><button class="bt bt-gray-clear">{{ trans('site_v2.Continue_for_Site') }}</button></a>
        </div>
      @endif

      <div class="cart-status display-none" id="cart_empty">
        <img height="75" src="site_v2/img/cart/carrinho-vazio.png">
        <p class="cart-status-txt">{!! trans('site_v2.Cart_clean_desc') !!} <a href="" class="tx-navy">{{ trans('site_v2.Consult_page') }}</a></p>

        <a href="{{ route('areaReservedV2') }}"><button class="bt bt-gray-clear">{{ trans('site_v2.Continue_for_Site') }}</button></a>
      </div>
    </div>
  </div>
</section>
@stop

@section('css')
@stop

@section('javascript')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

  <script>
    $('.header-client-site').css('background-color','#fff');
    $('.header-span').css('color','#333');
    $('.header-span a').css('color','#333');
    $('.header-submenu').css('background-image','linear-gradient(180deg, #eee, #fbfbfb)');
    $('.header-submenu-angle').css('border-bottom','15px solid rgba(0,0,0,0.06)');
    $('.header-submenu a').css('color','#333');
  </script>

  <script>
    if({!! $pontos_falta !!} > 0){
      console.log({!! $pontos_falta !!});
      $("#points_tr").show();
    }
    else{
      console.log(2);
      $("#points_tr").hide();
    }
  </script>

  <script>
    function deletePremium(id,id_premio){
      var valor_utilizado = $('#premio_ponto'+id).html();
      var quantidade = $('#quantidade'+id).val();
      //var cont_cart = $('#cont_cart').html();

      $.ajax({
        type: "POST",
        url: '{{ route('deletePremiumPostV2') }}',
        data: { id:id,valor_utilizado:valor_utilizado,id_premio:id_premio,quantidade:quantidade},
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            if(resposta){ $("#spanErro").html(resposta); }
            else{ $("#spanErro").html('ERROR'); }
            $("#labelErro").show();
            $('#loading').hide();
            $('#botoes').show();
            return;
        }

        if(resp.estado=='sucesso'){
          $('#modalDelete_'+id_premio).hide();
          $('#premio_'+id).css('display','none');
          
          $('#cont_cart_client').html(resp.count_final);
          $('#points_td').html(resp.pontos_falta);
          $('#pontos_falta').html(resp.pontos_falta);
          $('#valor_euro').html(resp.valor_euro+'€');
          $('#valor_euro_td').html(resp.valor_euro+'€');
          $('#points').html(resp.total_pontos);
          $("#pontos_restantes").html(resp.pontos_restantes);


          resp.ids_product.forEach(function(element) {
            $("#premio_ponto"+element.id_prod).html(element.pontos_uti);

            if (element.cor_prod=='coral') {
              $("#premio_ponto"+element.id_prod).removeClass('tx-jet');
              $("#premio_ponto"+element.id_prod).addClass('tx-coral');
            }
            else{
              $("#premio_ponto"+element.id_prod).removeClass('tx-coral');
              $("#premio_ponto"+element.id_prod).addClass('tx-jet');
            } 
          });

          if (resp.pontos_falta == 0) {
            $('#points_tr').hide();
          }

          if (resp.qtd_delete == 0) {
            $('#cart_empty').show();
            $('#table_product').hide();
          }
        }
      });
    }
  </script>

  <script>
    function updateQtd(quantidade,valor,id_linha,id_carrinho,tipo,pontos_falta){

      var qtd = $('#quantidade'+id_linha).val();

      $.ajax({
        type: "POST",
        url: '{{ route('updateQtdPostV2') }}',
        data: {valor:valor,qtd:qtd,id_linha:id_linha,id_carrinho:id_carrinho,tipo:tipo,pontos_falta:pontos_falta},
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            if(resposta){ $("#spanErro").html(resposta); }
            else{ $("#spanErro").html('ERROR'); }
            $("#labelErro").show();
            $('#loading').hide();
            $('#botoes').show();
            return;
        }

        if(resp.estado=='sucesso'){
          
          $("#valor_euro").html(resp.valor_euro +'€');
          $("#points").html(resp.total_pontos );
          $('#pontos_falta').html(resp.pontos_falta_td);
          if (resp.valor_euro < 0) {
            resp.valor_euro = 0;
          }

          $("#valor_euro_td").html(resp.valor_euro +'€');

          if (resp.pontos_falta_td > 0) {
            $("#points_tr").show();
            $("#points_td").html(resp.pontos_falta_td);
          }
          else{ $("#points_tr").hide(); }
          
          /*if (resp.user_pontos < 0) { resp.user_pontos = 0; }*/

          //$("#points_header").html(resp.user_pontos);
          $("#cont_cart").html(resp.qtd_car);
          $("#cont_cart_client").html(resp.qtd_car);
          
          //$("#premio_ponto"+resp.id_linha).html(resp.pontos_utilizados);
          $("#premio_ponto"+resp.id_linha).html(resp.pontos_utilizado);
          $("#valor_total_unidade"+resp.id_linha).html(resp.valor_total_unidade);
          $("#pontos_restantes").html(resp.pontos_restantes);

          //quantidade
          $("#quantidade"+resp.id_linha).val(resp.quantidade);

          if (resp.quantidade==0) {
            $('#premio_'+id_linha).hide();
          }

          if (resp.qtd_car==0) {
            $('#cart_empty').show();
            $('#table_product').hide();
          }

          resp.ids_product.forEach(function(element) {
            $("#premio_ponto"+element.id_prod).html(element.pontos_uti);

            if (element.cor_prod=='coral') {
              $("#premio_ponto"+element.id_prod).removeClass('tx-jet');
              $("#premio_ponto"+element.id_prod).addClass('tx-coral');
            }
            else{
              $("#premio_ponto"+element.id_prod).removeClass('tx-coral');
              $("#premio_ponto"+element.id_prod).addClass('tx-jet');
            } 
          });


          if (resp.cor=='coral') {
            $("#premio_ponto"+resp.id_linha).removeClass('tx-jet');
            $("#premio_ponto"+resp.id_linha).addClass('tx-coral');
          }
          else{
            $("#premio_ponto"+resp.id_linha).removeClass('tx-coral');
            $("#premio_ponto"+resp.id_linha).addClass('tx-jet');
          
          }    
        }
        else{
          $('#labelErro').show();
        }
      });
    }
  </script>

  <script>
    function updateCar(valor,id_linha,id_carrinho,tipo,pontos_falta){
      var qtd = $('#quantidade'+id_linha).val();
      updateQtd(qtd,valor,id_linha,id_carrinho,tipo,pontos_falta);
    }
  </script>

  <script>
    function increaseValue(quantidade,valor,id_linha,id_carrinho,tipo,pontos_falta) {
      var value = parseInt(document.getElementById('quantidade'+id_linha).value, 10);
      value = isNaN(value) ? 0 : value;
      value++;
      document.getElementById('quantidade'+id_linha).value = value;

      updateQtd(quantidade,valor,id_linha,id_carrinho,tipo,pontos_falta);
    }

    function decreaseValue(quantidade,valor,id_linha,id_carrinho,tipo,pontos_falta) {
      var value = parseInt(document.getElementById('quantidade'+id_linha).value, 10);
      value = isNaN(value) ? 0 : value;
      value < 1 ? value = 1 : '';
      value--;
      document.getElementById('quantidade'+id_linha).value = value;

      updateQtd(quantidade,valor,id_linha,id_carrinho,tipo,pontos_falta);
    }
  </script>

  <script>
    function next_Page() {
      //if ({!! Cookie::get('cookie_user_cart') !!} != 0) {
        window.location="{{ route('cartBillingPageV2') }}";
      //}
    }
  </script>
@stop