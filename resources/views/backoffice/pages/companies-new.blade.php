@extends('backoffice/layouts/default')

@section('content')
  @if($funcao=='new')<?php $arrayCrumbs = [ trans('backoffice.allCompanies') => route('companiesPageB'), trans('backoffice.newCompany') => route('companiesNewPageB') ]; ?>@else
  <?php $arrayCrumbs = [ trans('backoffice.allCompanies') => route('companiesPageB'), trans('backoffice.editCompany') => route('companiesEditPageB',['id'=>$dados['id']]) ]; ?>@endif
  @include('backoffice/includes/crumbs')

  <div class="page-titulo">@if($funcao=='new'){{ trans('backoffice.newCompany') }}@else{{ trans('backoffice.editCompany') }}@endif</div>
  <form id="companiesUploadLogoFormB" method="POST" enctype="multipart/form-data" action="{{ route('companiesUploadLogoFormB') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_empresa" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
    <div class="row text-center">
      <div class="col-lg-12">
        <div id="photoProfile" class="conta-logotipo" @if(isset($dados['logotipo']) && $dados['logotipo']) style="background-image:url(/img/empresas/{{ $dados['logotipo'] }});" @endif></div>
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
    <div id="TAB6" class="tab" onClick="mudarTab(6);"><span class="visible-xs">{{ trans('backoffice.U') }}</span><span class="hidden-xs">{{ trans('backoffice.Sellers') }}</span></div>
    <div id="TAB2" class="tab" onClick="mudarTab(2);"><span class="visible-xs">{{ trans('backoffice.P') }}</span><span class="hidden-xs">{{ trans('backoffice.Products') }}</span></div>
    <div id="TAB3" class="tab" onClick="mudarTab(3);"><span class="visible-xs">{{ trans('backoffice.A') }}</span><span class="hidden-xs">{{ trans('backoffice.Addresses') }}</span></div>
    <div id="TAB4" class="tab" onClick="mudarTab(4);"><span class="visible-xs">{{ trans('backoffice.P') }}</span><span class="hidden-xs">{{ trans('backoffice.People') }}</span></div>
    <div id="TAB5" class="tab" onClick="mudarTab(5);"><span class="visible-xs">{{ trans('backoffice.Aw') }}</span><span class="hidden-xs">{{ trans('backoffice.Awards') }}</span></div>
  </div>

  <div id="INF1">
    <form id="companiesInfoAccountFormB" method="POST" enctype="multipart/form-data" action="{{ route('companiesInfoAccountFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_empresa" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="row">
        <div class="col-lg-8">
          <label class="lb">{{ trans('backoffice.Name') }}</label>
          <input class="ip" type="text" name="nome" value="@if(isset($dados['nome'])){{ $dados['nome'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Points') }}</label>
          <input class="ip" type="text" name="pontos" value="@if(isset($dados['pontos'])){{ $dados['pontos'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Email') }}</label>
          <input class="ip" type="text" name="email" value="@if(isset($dados['email'])){{ $dados['email'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Changeemail') }}</label>
          <input class="ip" type="text" name="email_alteracao" value="@if(isset($dados['email_alteracao'])){{ $dados['email_alteracao'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Token') }}</label>
          <input class="ip" type="text" name="token_alteracao" value="@if(isset($dados['token_alteracao'])){{ $dados['token_alteracao'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Vat') }}</label>
          <input class="ip" type="text" name="nif" value="@if(isset($dados['nif']) && $dados['nif']){{ $dados['nif'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Cae') }}</label>
          <input class="ip" type="text" name="cae" value="@if(isset($dados['cae']) && $dados['cae']){{ $dados['cae'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Phone') }}</label>
          <input class="ip" type="text" name="telefone" value="@if(isset($dados['telefone'])){{ $dados['telefone'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Sellingpoints') }}</label>
          <input class="ip" type="text" name="pontos_venda" value="@if(isset($dados['pontos_venda'])){{ $dados['pontos_venda'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Salesamount') }}</label>
          <input class="ip" type="text" name="volume_venda" value="@if(isset($dados['volume_venda'])){{ $dados['volume_venda'] }}@endif">
        </div>
        <div class="col-lg-4">
          <label class="lb">{{ trans('backoffice.Ebitda') }}</label>
          <input class="ip" type="text" name="ebitda" value="@if(isset($dados['ebitda'])){{ $dados['ebitda'] }}@endif">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Permanentcertificate') }}</label>
          <input type="hidden" id="certidao_antiga" name="certidao_antiga" value="@if(isset($dados['certidao'])){{ $dados['certidao'] }}@endif">
          <div>
            <div class="div-50">
              <div class="div-50" id="certidao">
                @if(isset($dados['certidao']) && $dados['certidao'])<a href="/doc/companies/{{ $dados['certidao'] }}" target="_blank" class="a-dotted-white" id="certidao_upload" download>{{ $dados['certidao'] }}</a>@else<label class="a-dotted-white" id="certidao_upload">&nbsp;</label>@endif
              </div>
              <label for="selecao-certidao" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
              <input id="selecao-certidao" type="file" name="certidao" onchange="lerFicheiros(this,'certidao_upload');">
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('certidao');"><i class="fa fa-trash-alt"></i></label>          
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.IesTx') }}</label>
          <input type="hidden" id="ies1_antiga" name="ies1_antiga" value="@if(isset($ies[0]->ies)){{ $ies[0]->ies }}@endif">
          <div>
            <div class="div-50">
              <div class="row">
                <div class="col-lg-4">
                  <input class="ip" type="text" name="ies1_ano" value="@if(isset($ies[0]->ano)){{ $ies[0]->ano }}@endif">
                </div>
                <div class="col-lg-8">
                  <div class="div-50" id="ies1">
                    @if(isset($ies[0]->ies) && $ies[0]->ies)<a href="/doc/companies/{{ $ies[0]->ies }}" target="_blank" class="a-dotted-white" id="ies1_upload" download>{{ $ies[0]->ies }}</a>@else<label class="a-dotted-white" id="ies1_upload">&nbsp;</label>@endif
                  </div>
                  <label for="selecao-ies1" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
                  <input id="selecao-ies1" type="file" name="ies1" onchange="lerFicheiros(this,'ies1_upload');">
                </div>
              </div>
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('ies1');"><i class="fa fa-trash-alt"></i></label>          
          </div>
          <input type="hidden" id="ies2_antiga" name="ies2_antiga" value="@if(isset($ies[1]->ies)){{ $ies[1]->ies }}@endif">
          <div>
            <div class="div-50">
              <div class="row">
                <div class="col-lg-4">
                  <input class="ip" type="text" name="ies2_ano" value="@if(isset($ies[1]->ano)){{ $ies[1]->ano }}@endif">
                </div>
                <div class="col-lg-8">
                  <div class="div-50" id="ies2">
                    @if(isset($ies[1]->ies) && $ies[1]->ies)<a href="/doc/companies/{{ $ies[1]->ies }}" target="_blank" class="a-dotted-white" id="ies2_upload" download>{{ $ies[1]->ies }}</a>@else<label class="a-dotted-white" id="ies2_upload">&nbsp;</label>@endif
                  </div>
                  <label for="selecao-ies2" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
                  <input id="selecao-ies2" type="file" name="ies2" onchange="lerFicheiros(this,'ies2_upload');">
                </div>
              </div>
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('ies2');"><i class="fa fa-trash-alt"></i></label>          
          </div>
          <input type="hidden" id="ies3_antiga" name="ies3_antiga" value="@if(isset($ies[2]->ies)){{ $ies[2]->ies }}@endif">
          <div>
            <div class="div-50">
              <div class="row">
                <div class="col-lg-4">
                  <input class="ip" type="text" name="ies3_ano" value="@if(isset($ies[2]->ano)){{ $ies[2]->ano }}@endif">
                </div>
                <div class="col-lg-8">
                  <div class="div-50" id="ies3">
                    @if(isset($ies[2]->ies) && $ies[2]->ies)<a href="/doc/companies/{{ $ies[2]->ies }}" target="_blank" class="a-dotted-white" id="ies3_upload" download>{{ $ies[2]->ies }}</a>@else<label class="a-dotted-white" id="ies3_upload">&nbsp;</label>@endif
                  </div>
                  <label for="selecao-ies3" class="lb-40 bt-azul float-right"><i class="fas fa-upload"></i></label>
                  <input id="selecao-ies3" type="file" name="ies3" onchange="lerFicheiros(this,'ies3_upload');">
                </div>
              </div>
            </div>
            <label class="lb-40 bt-azul float-right" onclick="limparFicheiros('ies3');"><i class="fa fa-trash-alt"></i></label>          
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Paymentterm') }}</label>
          <select class="select2" name="prazo_pag">
            <option value="30" @if(isset($dados['prazo_pag']) && $dados['prazo_pag']=='30') selected @endif>{{ trans('backoffice.30days') }}</option>
            <option value="60" @if(isset($dados['prazo_pag']) && $dados['prazo_pag']=='60') selected @endif>{{ trans('backoffice.60days') }}</option>
          </select>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Invoicetype') }}</label>
          <select class="select2" name="tipo_fatura">
            <option value="unificada" @if(isset($dados['tipo_fatura']) && $dados['tipo_fatura']=='unificada') selected @endif>{{ trans('backoffice.UnifiedInvoiceTx') }}</option>
            <option value="separada" @if(isset($dados['tipo_fatura']) && $dados['tipo_fatura']=='separada') selected @endif>{{ trans('backoffice.SeparateInvoiceTx') }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Comments') }}</label>
          <textarea class="tx" name="obs" rows="3">@if(isset($dados['obs'])){{ $dados['obs'] }}@endif</textarea>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.dateOfRegistration') }}</label>
          <input class="ip" type="text" name="data" value="@if(isset($dados['data'])){{ date('Y-m-d H:i',$dados["data"]) }}@endif" disabled>
        </div>
        <div class="col-lg-6">
          <label class="lb">{{ trans('backoffice.Status') }}</label>
          <input type="hidden" name="estado_antigo" value="@if(isset($dados['estado'])){{ $dados['estado'] }}@endif">
          <select class="select2" name="estado">
            <option value="pendente" @if(isset($dados['estado']) && $dados['estado']=='em_aprovacao') selected @endif>{{ trans('backoffice.Pending') }}</option>
            <option value="em_aprovacao" @if(isset($dados['estado']) && $dados['estado']=='em_aprovacao') selected @endif>{{ trans('backoffice.OnApproval') }}</option>
            <option value="aprovado" @if(isset($dados['estado']) && $dados['estado']=='aprovado') selected @endif>{{ trans('backoffice.Approved') }}</option>
            <option value="reprovado" @if(isset($dados['estado']) && $dados['estado']=='reprovado') selected @endif>{{ trans('backoffice.Disapproved') }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.NoteCompTx') }}</label>
          <textarea class="tx" name="nota" rows="3">@if(isset($dados['nota'])){{ $dados['nota'] }}@endif</textarea>
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
    <form id="companiesProductsFormB" method="POST" enctype="multipart/form-data" action="{{ route('companiesProductsFormB') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id_empresa" value="@if(isset($dados['id'])){{ $dados['id'] }}@endif">
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Price') }}</label>
          <select class="select2" name="precos">
            <option value="valor" @if(isset($dados['precos']) && $dados['precos']=='manual') selected @endif>{{ trans('backoffice.FinalValue') }}</option>
            <option value="percentagem" @if(isset($dados['precos']) && $dados['precos']=='percentagem') selected @endif>{{ trans('backoffice.Percentage') }}</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <label class="lb">{{ trans('backoffice.Products') }}</label>
          @foreach($produtos as $val)
            <div class="row">
              <div class="col-lg-6"><label class="a-dotted-white">{{ $val['nome'] }}</label></div>
              <div class="col-lg-3"><label class="a-dotted-white">{{ $val['preco'] }}</label></div>
              <div class="col-lg-3"><input class="ip" type="text" name="preco{{ $val['id'] }}" value="{{ $val['valor'] }}"></div>
            </div>
          @endforeach
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
    <div class="modulo-table">
      <div class="modulo-scroll">
        <table class="modulo-body" id="sortable">
          <thead>
            <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Address') }}</th>
            <th>{{ trans('backoffice.Responsible') }}</th>
            <th>{{ trans('backoffice.Active') }}</th>
            <th>{{ trans('backoffice.Type') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($enderecos as $val)
          <tr id="linha_address{{ $val['id'] }}">
            <td class="display-none"></td>
            <td>{!! $val['id'] !!}</td>
            <td>{!! $val['morada'] !!}</td>
            <td>{!! $val['responsavel'] !!}</td>
            <td>{!! $val['estado'] !!}</td>
            <td>{!! $val['tipo'] !!}</td>
          </tr>
          @endforeach
          @if(empty($enderecos)) <tr><td colspan="6">{{ trans('backoffice.noRecords') }}</td></tr> @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="INF4" class="display-none">
    <div class="modulo-table">
      <div class="modulo-scroll">
        <table class="modulo-body" id="sortable">
          <thead>
            <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Name') }}</th>
            <th>{{ trans('backoffice.Contact') }}</th>
            <th>{{ trans('backoffice.Comments') }}</th>
            <th>{{ trans('backoffice.Type') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pessoas as $val)
          <tr id="linha_people{{ $val['id'] }}">
            <td class="display-none"></td>
            <td>{!! $val['id'] !!}</td>
            <td>{!! $val['nome'] !!}</td>
            <td>{!! $val['email'] !!}</td>
            <td>{!! $val['obs'] !!}</td>
            <td>{!! $val['tipo'] !!}</td>
          </tr>
          @endforeach
          @if(empty($pessoas)) <tr><td colspan="6">{{ trans('backoffice.noRecords') }}</td></tr> @endif
          </tbody>
        </table>
      </div>
    </div>
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
            <th>{{ trans('backoffice.Variant') }}</th>
            <th>{{ trans('backoffice.Quantity') }}</th>
            <th>{{ trans('backoffice.Points') }}</th>
            <th>{{ trans('backoffice.Date') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($premios as $val)
          <tr id="linha_awards{{ $val['id'] }}">
            <td class="display-none"></td>
            <td>{!! $val['id'] !!}</td>
            <td>{!! $val['nome'] !!}</td>
            <td>{!! $val['variante'] !!}</td>
            <td>{!! $val['quantidade'] !!}</td>
            <td>{!! $val['pontos'] !!}</td>
            <td>{!! $val['data'] !!}</td>
            <td>{!! $val['estado'] !!}</td>
          </tr>
          @endforeach
          @if(empty($premios)) <tr><td colspan="8">{{ trans('backoffice.noRecords') }}</td></tr> @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="INF6" class="display-none">
    <div class="modulo-table">
      <div class="modulo-scroll">
        <table class="modulo-body" id="sortable">
          <thead>
            <tr>
            <th class="display-none"></th>
            <th>#</th>
            <th>{{ trans('backoffice.Image') }}</th>
            <th>{{ trans('backoffice.Name') }}</th>
            <th>{{ trans('backoffice.Email') }}</th>
            <th>{{ trans('backoffice.lastAcess') }}</th>
            <th>{{ trans('backoffice.Type') }}</th>
            <th>{{ trans('backoffice.Status') }}</th>
            <th>{{ trans('backoffice.Option') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($utilizadores as $val)
          <tr id="linha_seller{{ $val['id'] }}">
            <td class="display-none"></td>
            <td>{!! $val['id'] !!}</td>
            <td>{!! $val['avatar'] !!}</td>
            <td>{!! $val['nome'] !!}</td>
            <td>{!! $val['email'] !!}</td>
            <td>{!! $val['ultimo'] !!}</td>
            <td>{!! $val['tipo'] !!}</td>
            <td>{!! $val['estado'] !!}</td>
            <td class="table-opcao">
              <a href="{{ route('sellersEditPageB',['id'=>$val['id']]) }}" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;{{trans('backoffice.Edit')}}</a>&ensp;
              <a href="{{ route('sellersLoginB',['id'=>$val['id']]) }}" class="table-opcao" target="_blank"><i class="fas fa-sign-in-alt"></i>&nbsp;{{trans('backoffice.logIn')}}</a>&ensp;
              <span class="table-opcao" onclick="$('#id_modal_seller').val({{ $val['id'] }});" data-toggle="modal" data-target="#myModalDeleteSeller">
                <i class="fas fa-trash-alt"></i>&nbsp;{{trans('backoffice.Delete')}}
              </span>
            </td>
          </tr>
          @endforeach
          @if(empty($utilizadores)) <tr><td colspan="10">{{ trans('backoffice.noRecords') }}</td></tr> @endif
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
        <div class="modal-body">{!! trans('backoffice.DeleteLogotipo') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarFoto();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Delete Seller -->
  <div class="modal fade" id="myModalDeleteSeller" tabindex="-1" role="dialog" aria-labelledby="myModalDeleteSeller">
    <input type="hidden" name="id_modal_seller" id="id_modal_seller">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header"><h4 class="modal-title">{{ trans('backoffice.Delete') }}</h4></div>
        <div class="modal-body">{!! trans('backoffice.DeleteUser') !!}</div>
        <div class="modal-footer">
          <button type="button" class="bt bt-cinza" data-dismiss="modal"><i class="fas fa-times"></i> {{ trans('backoffice.Cancel') }}</button>&nbsp;
          <button type="button" class="bt bt-vermelho" data-dismiss="modal" onclick="apagarComerciante();"><i class="fas fa-trash-alt"></i> {{ trans('backoffice.Delete') }}</button>
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
          <a href="{{ route('companiesPageB') }}" class="abt bt-cinza"><i class="fas fa-arrow-left"></i> {{ trans('backoffice.Back') }}</a>&nbsp;
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
    function lerFicheiros(input,id) {
      var quantidade = input.files.length;
      var nome = input.value;
      if(quantidade==1){$('#'+id).html(nome);}
      else{$('#'+id).html(quantidade+' {{ trans('backoffice.selectedFiles') }}');}
    }
    function limparFicheiros(id) {
      $('#selecao-'+id).val('');
      $('#'+id+'_upload').html('&nbsp;');
      $('#'+id+'_antiga').val('');
      $('#'+id).html('<label class="a-dotted-white" id="'+id+'_upload">&nbsp;</label>');
    }

    $('#companiesUploadLogoFormB').on('submit',function(e) {
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
          $('#photoProfile').css("background-image","url(/img/empresas/"+resp.foto+")");
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
        url: '{{ route('companiesDeleteLogoFormB') }}',
        data: { id,id },
        headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
      })
      .done(function(resposta) {
        if(resposta=='sucesso'){
          $('#photoProfile').css('background-image','url({{ asset('/img/empresas/company.svg') }})');
        }else{
          $("#spanErroImg").html(resposta);
          $("#labelErrosImg").show();
        }
      });
    }


    $('#companiesInfoAccountFormB').on('submit',function(e) {
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
      .done(function(resposta) {
        //$('#myModalSave').modal('show');
        //console.log(resposta);
        try{ resp=$.parseJSON(resposta); }
        catch (e){            
            if(resposta){
              $("#spanErro").html(resposta);
              $("#labelErros").show();
            }
            $('#loading').hide();
            $('#botoes').show();
            return;
        }
        if(resp.estado=='sucesso'){
          //$('#id').val(resp.id);
          //limparFicheiros('certidao');
          //limparFicheiros('ies1');
          //limparFicheiros('ies2');
          //limparFicheiros('ies3');
          if(resp.reload){ $('#myModalSave').modal('show'); }
          $('#loading').hide();
          $('#botoes').show();
          $("#labelSucesso").show();
        }else if(resp.estado=='erro'){
          $("#spanErro").html(resp.mensagem);
          $("#labelErros").show();
          $('#loading').hide();
          $('#botoes').show();
        }
      });
    });

    $('#companiesProductsFormB').on('submit',function(e) {
      $("#labelSucesso2").hide();
      $("#labelErros2").hide();
      $('#loading2').show();
      $('#botoes2').hide();
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
        //$('#myModalSave').modal('show');
        //console.log(resposta);
        //alert(resposta);
        if(resposta=='sucesso'){
          $("#labelSucesso2").show();
        }else{
          $("#spanErro2").html(resposta);
          $("#labelErros2").show();
        }
        $('#loading2').hide();
        $('#botoes2').show();
      });
    });
    $('#sellersCompanyFormB').on('submit',function(e) {
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
        //alert(resposta);
        if(resposta=='sucesso'){
          $("#loading3").hide();
          $("#botoes3").show();
          $("#labelSucesso3").show();
        }
      });
    });

  function apagarComerciante(){
    var id = $('#id_modal_seller').val();
    $.ajax({
      type: "POST",
      url: '{{ route('sellersDeleteB') }}',
      data: { id:id },
      headers:{ 'X-CSRF-Token':'{!! csrf_token() !!}' }
    })
    .done(function(resposta) {
      if(resposta=='sucesso'){
        $('#linha_seller'+id).slideUp();
        $.notific8('{{ trans('backoffice.savedSuccessfully') }}');
      }else{ $.notific8(resposta, {color:'ruby'}); }
    });
  }
  </script>
@stop