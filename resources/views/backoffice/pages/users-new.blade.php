@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allUsers') => route('usersPageB'), trans('backoffice.newUser') => route('usersNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allUsers') => route('usersPageB'), trans('backoffice.editUser') => route('usersEditPageB',['id'=>$dados['id']]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newUser') }}@else{{ trans('backoffice.editUser') }}@endif</div>
  <form id="usersUploadPhotoFormB" method="POST" enctype="multipart/form-data" action="{{ route('usersUploadPhotoFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
    <div class="row text-center">
      <div class="col-lg-12">
        <div id="photoProfile" class="conta-avatar" @if(isset($dados['foto']) && $dados['foto']) style="background-image:url(/img/clientes/{{ $dados['foto'] }});" @endif></div>
        <div class="clearfix"></div>
        <div id="botoesImg">
          <div class="conta-col-esq">
            <label for="selecao-arquivo" class="lb-40 bt-azul"><i class="fas fa-upload"></i></label>
            <input id="selecao-arquivo" type="file" accept="image/*" name="ficheiro" onchange="$(this).submit();">
          </div>
          <div class="conta-col-dir">
            <button class="bt-40 bt-azul" type="button" data-toggle="modal" data-target="#myModalDelete"><i class="fas fa-trash-alt"></i></button>
          </div>
        </div>
        <div id="loadingImg" class="loading-center"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
        <div class="clearfix height-10"></div>
        <label id="labelErrosImg" class="av-100 av-vermelho display-none"><span id="spanErroImg"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      </div>
    </div>    
  </form>

  <div class="tabs">
    <div id="TAB1" class="tab tab-active" onClick="mudarTab(1);"><span class="visible-xs">{{ trans('backoffice.AI') }}</span><span class="hidden-xs">{{ trans('backoffice.AccountInformation') }}</span></div>
    <div id="TAB2" class="tab" onClick="mudarTab(2);"><span class="visible-xs">{{ trans('backoffice.PI') }}</span><span class="hidden-xs">{{ trans('backoffice.PersonalInformation') }}</span></div>
    <div id="TAB3" class="tab" onClick="mudarTab(3);"><span class="visible-xs">{{ trans('backoffice.Ad') }}</span><span class="hidden-xs">{{ trans('backoffice.Addresses') }}</span></div>
    <div id="TAB4" class="tab" onClick="mudarTab(4);"><span class="visible-xs">{{ trans('backoffice.Po') }}</span><span class="hidden-xs">{{ trans('backoffice.Points') }}</span></div>
    <div id="TAB5" class="tab" onClick="mudarTab(5);"><span class="visible-xs">{{ trans('backoffice.Aw') }}</span><span class="hidden-xs">{{ trans('backoffice.Awards') }}</span></div>
  </div>

  <div id="INF1">
    <form id="usersInfoAccountFormB" method="POST" action="{{ route('usersInfoAccountFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="name" value="@if(isset($dados['nome'])){{ $dados['nome'] }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Token') }}</label>
          <input class="ip" type="text" name="token" value="@if(isset($dados['token'])){{ $dados['token'] }}@endif" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Points') }}</label>
          <input class="ip" type="text" id="pontos" name="pontos" value="@if(isset($dados['pontos'])){{ $dados["pontos"] }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Lang') }}</label>
          <select class="select2" name="lingua" disabled>
            <option value="en" @if(isset($dados['lingua']) && $dados['lingua']=='en') selected @endif>{{ trans('backoffice.English') }}</option>
            <option value="pt" @if(isset($dados['lingua']) && $dados['lingua']=='pt') selected @endif>{{ trans('backoffice.Portuguese') }}</option>
            <option value="es" @if(isset($dados['lingua']) && $dados['lingua']=='es') selected @endif>{{ trans('backoffice.Spanish') }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Lastacess') }}</label>
          <input class="ip" type="text" name="ultimo_acesso" value="@if(isset($dados['ultimo_acesso'])){{ date('Y-m-d H:i',$dados["ultimo_acesso"]) }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.dateOfRegistration') }}</label>
          <input class="ip" type="text" name="data" value="@if(isset($dados['data'])){{ date('Y-m-d H:i',$dados["data"]) }}@endif" disabled>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Status') }}</label>
          <select class="select2" name="estado">
            <option value="ativo" @if(isset($dados['estado']) && $dados['estado']=='ativo') selected @endif>{{ trans('backoffice.Active') }}</option>
            <option value="pendente" @if(isset($dados['estado']) && $dados['estado']=='pendente') selected @endif>{{ trans('backoffice.Pending') }}</option>
          </select>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Additional') }}</label>
          <div class="clearfix height-10"></div>
          <input type="checkbox" name="newsletter" id="newsletter" value="1" @if(isset($dados['newsletter']) && $dados['newsletter']) checked @endif>
          <label for="newsletter"><span></span>{{ trans('backoffice.Newsletter') }}</label>
        </div>
      </div>
      <div class="clearfix height-20"></div>
      <div id="botoes">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      </div>
      <div id="loading" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso" class="av-100 av-verde display-none"><span id="spanSucesso">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros" class="av-100 av-vermelho display-none"><span id="spanErro"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>


  <div id="INF2" class="display-none">
    <form id="usersInfoPersonalFormB" method="POST" action="{{ route('usersInfoPersonalFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="nome" value="@if(isset($dados['nome'])){{ $dados['nome'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Surname') }}</label>
          <input class="ip" type="text" name="apelido" value="@if(isset($dados['apelido'])){{ $dados['apelido'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Phone') }}</label>
          <input class="ip" type="text" name="telefone" value="@if(isset($dados['telefone'])){{ $dados['telefone'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Email') }}</label>
          <input class="ip" type="text" name="email" value="@if(isset($dados['email'])){{ $dados['email'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Changeemail') }}</label>
          <input class="ip" type="text" name="email_alteracao" value="@if(isset($dados['email_alteracao'])){{ $dados['email_alteracao'] }}@endif">
        </div>
      </div>
      <div class="clearfix height-20"></div>
      <div id="botoes2">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      </div>
      <div id="loading2" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso2" class="av-100 av-verde display-none"><span id="spanSucesso2">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros2" class="av-100 av-vermelho display-none"><span id="spanErro2"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>

  <div id="INF3" class="display-none">
    <form id="usersAddressesFormB" method="POST" action="{{ route('usersAddressesFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">

      <label class="av-100 av-cinza">{{ trans('backoffice.BillingAddress') }}</label>
      <input type="hidden" id="id_faturacao" name="id_faturacao" value="@if(isset($enderecos['id_faturacao'])){{ $enderecos['id_faturacao'] }}@endif">
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="nome_fact" value="@if(isset($enderecos['nome_fact'])){{ $enderecos['nome_fact'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Email') }}</label>
          <input class="ip" type="text" name="email_fact" value="@if(isset($enderecos['email_fact'])){{ $enderecos['email_fact'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Phone') }}</label>
          <input class="ip" type="text" name="telefone_fact" value="@if(isset($enderecos['telefone_fact'])){{ $enderecos['telefone_fact'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.VAT') }}</label>
          <input class="ip" type="text" name="nif_fact" value="@if(isset($enderecos['nif_fact'])){{ $enderecos['nif_fact'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Address(line1)') }}</label>
          <input class="ip" type="text" name="morada_fact" value="@if(isset($enderecos['morada_fact'])){{ $enderecos['morada_fact'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Address(line2)') }}</label>
          <input class="ip" type="text" name="morada_opc_fact" value="@if(isset($enderecos['morada_opc_fact'])){{ $enderecos['morada_opc_fact'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Zipcode') }}</label>
          <input class="ip" type="text" name="codigo_postal_fact" value="@if(isset($enderecos['codigo_postal_fact'])){{ $enderecos['codigo_postal_fact'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.City') }}</label>
          <input class="ip" type="text" name="cidade_fact" value="@if(isset($enderecos['cidade_fact'])){{ $enderecos['cidade_fact'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Country') }}</label>
          <input class="ip" type="text" name="pais_fact" value="@if(isset($enderecos['pais_fact'])){{ $enderecos['pais_fact'] }}@endif">
        </div>
      </div>

      <div class="clearfix height-20"></div>
      <label class="av-100 av-cinza">{{ trans('backoffice.DeliveryAddress') }}</label>
      <input type="hidden" id="id_entrega" name="id_entrega" value="@if(isset($enderecos['id_entrega'])){{ $enderecos['id_entrega'] }}@endif">
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="nome_entrega" value="@if(isset($enderecos['nome_entrega'])){{ $enderecos['nome_entrega'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Email') }}</label>
          <input class="ip" type="text" name="email_entrega" value="@if(isset($enderecos['email_entrega'])){{ $enderecos['email_entrega'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Phone') }}</label>
          <input class="ip" type="text" name="telefone_entrega" value="@if(isset($enderecos['telefone_entrega'])){{ $enderecos['telefone_entrega'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Address(line1)') }}</label>
          <input class="ip" type="text" name="morada_entrega" value="@if(isset($enderecos['morada_entrega'])){{ $enderecos['morada_entrega'] }}@endif">
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Address(line2)') }}</label>
          <input class="ip" type="text" name="morada_opc_entrega" value="@if(isset($enderecos['morada_opc_entrega'])){{ $enderecos['morada_opc_entrega'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Zipcode') }}</label>
          <input class="ip" type="text" name="codigo_postal_entrega" value="@if(isset($enderecos['codigo_postal_entrega'])){{ $enderecos['codigo_postal_entrega'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.City') }}</label>
          <input class="ip" type="text" name="cidade_entrega" value="@if(isset($enderecos['cidade_entrega'])){{ $enderecos['cidade_entrega'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Country') }}</label>
          <input class="ip" type="text" name="pais_entrega" value="@if(isset($enderecos['pais_entrega'])){{ $enderecos['pais_entrega'] }}@endif">
        </div>
      </div>
      <div class="clearfix height-20"></div>
      <div id="botoes3">
        <button class="bt bt-verde float-right" type="submit"><i class="fas fa-check"></i> {{ trans('backoffice.Save') }}</button>
      </div>
      <div id="loading3" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso3" class="av-100 av-verde display-none"><span id="spanSucesso3">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros3" class="av-100 av-vermelho display-none"><span id="spanErro3"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>

  <div id="INF4" class="display-none">
    <div class="modulo-table">
      <div class="modulo-scroll">
        <table class="modulo-body" id="sortable">
          <thead>
            <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Code') }}</th>
            <th>{{ trans('backoffice.Points') }}</th>
            <th>{{ trans('backoffice.Validity') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rotulos as $val)
          <tr id="linha_points{{ $val['id'] }}">
            <td class="display-none"></td>
            <td>{!! $val['id'] !!}</td>
            <td>{!! $val['codigo'] !!}</td>
            <td>{!! $val['pontos'] !!}</td>
            <td>{!! $val['validade'] !!}</td>
            <td>{!! $val['estado'] !!}</td>
            <td class="table-opcao">
              <span class="table-opcao" onclick="$('#id_modal_points').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDeletepoints">
                <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
              </span>
            </td>
          </tr>
          @endforeach
          @if(empty($rotulos)) <tr><td colspan="7">{{ trans('backoffice.noRecords') }}</td></tr> @endif
          </tbody>
        </table>
      </div>
    </div>

    <form id="usersPointsFormB" method="POST" action="{{ route('usersPointsFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_user" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="clearfix height-20"></div>
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Quantity') }}</label>
          <input class="ip" type="text" name="quantidade" value="">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Code') }}</label>
          <input class="ip" type="text" name="codigo" value="">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Validity') }}</label>
          <input class="ip datePicker" type="text" name="validade" value="">
        </div>
      </div>
      <div class="clearfix height-20"></div>
      <div id="botoes4">
        <button class="bt bt-azul float-right" type="submit"><i class="fas fa-plus"></i> {{ trans('backoffice.Add') }}</button>
      </div>
      <div id="loading4" class="loading"><i class="fas fa-circle-o-notch fa-spin"></i> {{ trans('backoffice.SavingR') }}</div>
      <div class="clearfix"></div>
      <div class="height-20"></div>
      <label id="labelSucesso4" class="av-100 av-verde display-none"><span id="spanSucesso4">{{ trans('backoffice.savedSuccessfully') }}</span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
      <label id="labelErros4" class="av-100 av-vermelho display-none"><span id="spanErro4"></span> <i class="fas fa-times" onclick="$(this).parent().hide();"></i></label>
    </form>
  </div>

  <div id="INF5" class="display-none">
    <div class="modulo-table">
      <div class="modulo-scroll">
        <table class="modulo-body" id="sortable">
          <thead>
            <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Name') }}</th>
            <th>{{ trans('backoffice.Points') }}</th>
            <th>{{ trans('backoffice.Value') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($carrinho as $val)
          <tr id="linha_cart{{ $val['id'] }}">
            <td class="display-none"></td>
            <td>{!! $val['id'] !!}</td>
            <td>{!! $val['nome'] !!}</td>
            <td>{!! $val['pontos'] !!}</td>
            <td>{!! $val['valor'] !!}</td>
            <td>{!! $val['data'] !!}</td>
            <td>{!! $val['estado'] !!}</td>
            <td class="table-opcao">
              <span class="table-opcao" onclick="$(this).parent().parent().next().slideToggle();"><i class="fas fa-trophy"></i>&nbsp;{{trans('backoffice.Awards')}}</span>
            </td>
          </tr>
          <tr class="display-none">
            <td colspan="8">
              <table class="user-awards-table">
                <thead>
                  <tr>
                    <th class="display-none"></th>
                    <th>#</th>
                    <th>{{ trans('backoffice.Name') }}</th>
                    <th>{{ trans('backoffice.Variant') }}</th>
                    <th>{{ trans('backoffice.Quantity') }}</th>
                    <th>{{ trans('backoffice.Points') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($val['produtos'] as $vl)
                  <tr id="linha_cart{{ $val['id'] }}_prod{{ $vl['id'] }}">
                    <td class="display-none"></td>
                    <td>{!! $vl['id'] !!}</td>
                    <td>{!! $vl['nome'] !!}</td>
                    <td>{!! $vl['variante'] !!}</td>
                    <td>{!! $vl['quantidade'] !!}</td>
                    <td>{!! $vl['pontos'] !!}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>
          @endforeach
          @if(empty($carrinho)) <tr><td colspan="8">{{ trans('backoffice.noRecords') }}</td></tr> @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>



  <!-- Modal Delete Avatar -->
  <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteAvatar') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarFoto();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Delete Points-->
  <div class="modal fade" id="myModalDeletepoints" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
    <input type="hidden" name="id_modal_points" id="id_modal_points">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabel3">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteLine') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarPontos();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Save -->
  <div class="modal fade" id="myModalSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabelS">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title" id="myModalLabelS">{{ trans('backoffice.Saved') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.savedSuccessfully') !!}</div>
        <div class="modal-footer">
          <a href="{{ route('usersPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
          <a href="javascript:;" class="abt bt-verde" onclick="location.reload();"><i class="fas fa-check"></i> {{ trans('backoffice.Ok') }}</a>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.css') }}">
@stop

@section('javascript')
  <script type="text/javascript">
  function mudarTab(numero) {
    var tabs = $('.tabs').find('.tab').length;
    for (var i=tabs; i>0; i--) {
      if(i==numero){
        $("#TAB"+i).addClass("tab-active");
        $("#INF"+i).css("display","block");
      }
      else{
        $("#TAB"+i).removeClass("tab-active");
        $("#INF"+i).css("display","none");
      }
    }
  }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/{{json_decode(Cookie::get('admin_cookie'))->lingua}}.js"></script>
  <script type="text/javascript">$('.select2').select2({'language':'{{json_decode(Cookie::get('admin_cookie'))->lingua}}'});</script>

  <script type="text/javascript" src="{{ asset('backoffice/vendor/datepicker/bootstrap-datepicker.js') }}"></script>
  <script type="text/javascript">
    $('.datePicker').datepicker({
      format:'dd-mm-yyyy',
      //viewMode:2,
      days: {!! trans('backoffice.days') !!},
      daysShort: {!! trans('backoffice.daysShort') !!},
      daysMin: {!! trans('backoffice.daysMin') !!},
      months: {!! trans('backoffice.months') !!},
      monthsShort: {!! trans('backoffice.monthsShort') !!}
    });
  </script>
  <script type="text/javascript">
    $('#usersUploadPhotoFormB').on('submit',function(e) {
      $("#labelErrosImg").hide();
      $('#loadingImg').show();
      $('#botoesImg').hide();
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
      .done(function(resposta) {
        //console.log(resposta);
        $('#selecao-arquivo').val('');
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            $("#spanErroImg").html(resposta);
            $("#labelErrosImg").show();
            return;
        }
        if(resp.estado=='sucesso'){
          $('#photoProfile').css("background-image","url(/img/clientes/"+resp.foto+")");
          $('#loadingImg').hide();
          $('#botoesImg').show();
        }else if(resp.estado=='erro'){
          $("#spanErroImg").html(resp.erro);
          $("#labelErrosImg").show();
        }else{
          $("#spanErroImg").html(resposta);
          $("#labelErrosImg").show();
        }
      });
    });

    function apagarFoto(){
      var id = {{ $dados['id'] }};
      $.ajax({
        type: "POST",
        url: '{{ route('usersDeletePhotoFormB') }}',
        data: { id,id },
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        if(resposta=='sucesso'){
          $('#photoProfile').css('background-image','url({{ asset('/backoffice/img/icons/user-default-1.svg') }})');
        }else{
          $("#spanErroImg").html(resposta);
          $("#labelErrosImg").show();
        }
      });
    }

    $('#usersInfoAccountFormB').on('submit',function(e) {
      $("#labelSucesso").hide();
      $("#labelErros").hide();
      $('#loading').show();
      $('#botoes').hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta) {
        if(resposta){
          $("#spanErro").html(resposta);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
        else{
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }
      });
    });
    $('#usersInfoPersonalFormB').on('submit',function(e) {
      $("#labelSucesso2").hide();
      $("#labelErros2").hide();
      $('#loading2').show();
      $('#botoes2').hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta) {
        if(resposta){
          $("#spanErro2").html(resposta);
          $("#labelErros2").show();
          $("#loading2").hide();
          $("#botoes2").show();
        }
        else{
          $("#loading2").hide();
          $("#botoes2").show();
          $("#labelSucesso2").show();
        }
      });
    });
    $('#usersAddressesFormB').on('submit',function(e) {
      $("#labelSucesso3").hide();
      $("#labelErros3").hide();
      $("#loading3").show();
      $("#botoes3").hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta){
        try{ resp=$.parseJSON(resposta); }
        catch (e){
            $("#spanErro3").html(resposta);
            $("#labelErros3").show();
            $("#loading3").hide();
            $("#botoes3").show();
            return;
        }
        if(resp.estado=='sucesso'){
          $("#id_faturacao").val(resp.id_faturacao);
          $("#id_entrega").val(resp.id_entrega);
          $("#loading3").hide();
          $("#botoes3").show();
          $("#labelSucesso3").show();
        }
      });
    });
    function apagarPontos(){
      var id_user = {{ $dados['id'] }};
      var id_points = $('#id_modal_points').val();
      $.ajax({
        type: "POST",
        url: '{{ route('usersDeletePointsFormB') }}',
        data: { id_user:id_user, id_points:id_points },
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta){
        //console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){            
            if(resposta){ $.notific8(resposta, {color:'ruby'}); }
            else{ $.notific8('ERROR', {color:'ruby'}); }
            return;
        }
        if(resp.estado=='sucesso'){
          //$('#myModalSave').modal('show');
          $('#linha_points'+id_points).slideUp();
          $('#pontos').val(resp.quantidade);
          $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
        }
      });
    }
    $('#usersPointsFormB').on('submit',function(e) {
      $("#labelSucesso4").hide();
      $("#labelErros4").hide();
      $('#loading4').show();
      $('#botoes4').hide();
      var form = $(this);
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
      })
      .done(function(resposta){
        //console.log(resposta);
        if(resposta=='sucesso'){
          $('#myModalSave').modal('show');
        }else if(resposta){
          $("#spanErro4").html(resposta);
          $("#labelErros4").show();
        }
        $('#loading4').hide();
        $('#botoes4').show();
      });
    });
  </script>
@stop