<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;
use Mail;

class Companies extends Controller
{
  private $dados=[];

  /**************
  *  COMPANIES  *
  **************/
  public function companies(){
    $this->dados['headTitulo']=trans('backoffice.companiesTitulo');
    $this->dados['separador']="bizCompanies";
    $this->dados['funcao']="all";

    $query = \DB::table('empresas')->orderBy('id','DESC')->get(); 
    $array =[];
    foreach ($query as $valor){
      $avatar = '<img src="'.asset('/img/empresas/company.svg').'" class="table-img-circle">';
      if($valor->logotipo && file_exists(base_path('public_html/img/empresas/'.$valor->logotipo))){
        $avatar = '<img src="'.asset('/img/empresas/'.$valor->logotipo).'" class="table-img-circle">';
      }

      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "pendente": $estado = '<span class="tag tag-turquesa">'.trans('backoffice.Pending').'</span>'; break;
        case "em_aprovacao": $estado = '<span class="tag tag-amarelo">'.trans('backoffice.OnApproval').'</span>'; break;
        case "aprovado": $estado = '<span class="tag tag-verde">'.trans('backoffice.Approved').'</span>'; break;
        case "reprovado": $estado = '<span class="tag tag-vermelho">'.trans('backoffice.Disapproved').'</span>'; break;
        default: $estado = '<span class="tag tag-roxo">'.$valor->estado.'</span>';
      }

      //abrir dominio do email
      $emailPiece = explode("@", $valor->email);
      if(!in_array($emailPiece[1], ['gmail.com','hotmail.com','outlook.com','outlook.pt','yahoo.com','msn.com','aol.com','live.com','mail.com'])){
        $email = '<a href="http://'.$emailPiece[1].'" target="_blank">'.$valor->email.'</a>';
      }else{ $email = $valor->email; }

      $array[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'email' => $email,
        'nif' => $valor->nif ? $valor->nif : '',
        'logotipo' => $avatar,
        'registo' => $valor->data ? date('d/m/Y H:i:s',$valor->data) : '',
        'pontos' => $valor->pontos,
        'estado' => $estado
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/companies-all', $this->dados);
  }

  public function companiesDelete(Request $request){
    $id=trim($request->id);
    $linha = \DB::table('empresas')->where('id', $id)->first();
    if(isset($linha->id) && $linha->id){
      //Apagar Logotipo
      if($linha->logotipo && file_exists(base_path('public_html/img/empresas/'.$linha->logotipo))){
        \File::delete('../public_html/img/empresas/'.$linha->logotipo);
        //\File::deleteDirectory('../public_html/img/empresas/'.$linha->logotipo);
      }
      //Apagar documentos (certidao, ies, validação comerciantes)
      if($linha->certidao && file_exists(base_path('public_html/doc/companies/'.$linha->certidao))){
        \File::delete('../public_html/doc/companies/'.$linha->certidao);
      }
      if($linha->ies){
        $obj = json_decode($linha->ies);
        foreach($obj as $value){
          if($value->ies && file_exists(base_path('public_html/doc/companies/'.$value->ies))){
            \File::delete('../public_html/doc/companies/'.$value->ies);
          }
        }
      }
      //Apagar tickets
      $tikQuery = \DB::table('tickets')->where('id_empresa',$id)->get(); 
      foreach ($tikQuery as $valor){
        $msgQuery = \DB::table('tickets_msg')->where('id_ticket',$valor->id)->get(); 
        foreach ($msgQuery as $val){
          if($val->ficheiros){
            $obj = json_decode($val->ficheiros);
            foreach($obj as $file){
              if($file->ficheiro && file_exists(base_path('public_html/doc/support/'.$file->ficheiro))){
                \File::delete('../public_html/doc/support/'.$file->ficheiro);
              }
            }
          }
        }
      }
      //Manter as encomendas
      \DB::table('empresas')->where('id',$id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }


  /************
  *  COMPANY  *
  ************/
  public function companiesNew(){
    $this->dados['headTitulo']=trans('backoffice.sellerTitulo');
    $this->dados['separador']="bizCompanies";
    $this->dados['funcao']="new";
    
    return redirect()->route('companiesPageB');
    //return view('backoffice/pages/companies-new', $this->dados);
  }

  public function companiesEdit($id){
    $this->dados['headTitulo']=trans('backoffice.sellerTitulo');
    $this->dados['separador']="bizCompanies";
    $this->dados['funcao']="edit";
    $lang=json_decode(Cookie::get('admin_cookie'))->lingua;

    //DATA
    $this->dados['dados']=(array) \DB::table('empresas')->where('id',$id)->first();
    $this->dados['ies']= json_decode($this->dados['dados']['ies']);

    //PRODUCTS
    $produtosQuery=\DB::table('produtos')->select('*','nome_'.$lang.' AS nome')->get();
    $produtos =[];
    foreach ($produtosQuery as $valor){
      $valQuery = \DB::table('produtos_empresa')->select('valor')->where('id_produto', $valor->id)->where('id_empresa', $id)->first();

      $produtos[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'preco' => number_format($valor->preco_unitario, 2, '.', '').' €',
        'valor' => isset($valQuery->valor) ? $valQuery->valor : ''
      ];
    }
    $this->dados['produtos'] = $produtos;

    //ADDRESSES
    $enderecosQuery=\DB::table('moradas')->where('id_empresa', $id)->orderBy('tipo', 'DESC')->get();
    $enderecos =[];
    foreach ($enderecosQuery as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "ativo": $estado = '<span class="tag tag-verde">'.trans('backoffice.Active').'</span>'; break;
        case "inativo": $estado = '<span class="tag tag-cinza">'.trans('backoffice.Inactive').'</span>'; break;
        default: $estado = '<span class="tag tag-roxo">'.$valor->estado.'</span>';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tipo){
        case "morada_sede": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Headoffice').'</span>'; break;
        case "morada_contabilidade": $tipo = '<span class="tag tag-laranja">'.trans('backoffice.Accounting').'</span>'; break;
        case "morada_armazem": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Shopping').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }

      $morada=$valor->nome_personalizado.'<br>'.$valor->morada.'<br>'.$valor->morada_opc.'<br>'.$valor->codigo_postal.' '.$valor->cidade.'<br>'.$valor->pais.'<br>'.$valor->telefone.' - '.$valor->fax;
      $responsavel=$valor->nome_gerente.'<br>'.$valor->cargo_gerente.'<br>'.$valor->email_gerente.'<br>'.$valor->telefone_gerente;
      
      $enderecos[] = [
        'id' => $valor->id,
        'morada' => $morada,
        'responsavel' => $responsavel,
        'tipo' => $tipo,
        'estado' => $estado
      ];
    }
    $this->dados['enderecos'] = $enderecos;

    //PEOPLE
    $pessoasQuery=\DB::table('empresas_responsaveis')->where('id_empresa', $id)->orderBy('tipo', 'DESC')->get();
    $pessoas =[];
    foreach ($pessoasQuery as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tipo){
        case "resp_legal": $tipo = '<span class="tag tag-laranja">'.trans('backoffice.Representative').'</span>'; break;
        case "person_contact": $tipo = '<span class="tag tag-verde">'.trans('backoffice.Contact').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }

      $doc='';
      if($valor->doc_validacao){ $doc='<a href="doc/companies/'.$valor->doc_validacao.'" target="_blank" download>'.$valor->doc_validacao.'</a><br>'; }

      $pessoas[] = [
        'id' => $valor->id,
        'nome' => $valor->nome.'<br>'.$valor->cargo,
        'email' => $valor->email.'<br>'.$valor->contacto,
        'doc' => $doc,
        'data' => $valor->data ? date('d/m/Y H:i',$valor->data) : '',
        'obs' => $doc.$valor->obs,
        'tipo' => $tipo
      ];
    }
    $this->dados['pessoas'] = $pessoas;

    //USERS
    $utilizadoresQuery = \DB::table('comerciantes')->where('id_empresa', $id)->get();
    $utilizadores =[];
    foreach ($utilizadoresQuery as $valor){

      $avatar = '<img src="'.asset('/img/comerciantes/default.svg').'" class="table-img-circle">';
      if($valor->foto && file_exists(base_path('public_html/img/comerciantes/'.$valor->foto))){
        $avatar = '<img src="'.asset('/img/comerciantes/'.$valor->foto).'" class="table-img-circle">';
      }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tipo){
        case "gestor": $tipo = '<span class="tag tag-roxo">'.trans('backoffice.Manager').'</span>'; break;
        case "comerciante": $tipo = '<span class="tag tag-turquesa">'.trans('backoffice.Seller').'</span>'; break;
        case "admin": $tipo = '<span class="tag tag-azul">'.trans('backoffice.Administrator').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza">'.$valor->tipo.'</span>';
      }
      if($valor->aprovacao=='em_aprovacao'){ $tipo .= ' <span class="tag tag-amarelo"><i class="fas fa-exclamation-triangle"></i></span>'; }
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "ativo": $estado = '<span class="tag tag-verde">'.trans('backoffice.Active').'</span>'; break;
        case "pendente":
          $estado = '<span class="tag tag-amarelo cursor-pointer" onclick="$(\'#erroEnvio'.$valor->id.'\').toggle();">'.trans('backoffice.Pending').'</span>
          <div id="erroEnvio'.$valor->id.'" class="display-none">
            <span class="tag tag-turquesa cursor-pointer nowrap" onclick="$(\'#id_modalRE\').val('.$valor->id.');" data-toggle="modal" data-target="#myModalResendEmail">'.trans('backoffice.ResendEmail').'</span>
          </div>';
          break;
        case "delete": $estado = '<span class="tag tag-cinza">'.trans('backoffice.Deleted').'</span>'; break;
        default: $estado = '<span class="tag tag-roxo">'.$valor->estado.'</span>';
      }

      $utilizadores[] = [
        'id' => $valor->id,
        'token' => $valor->token,
        'nome' => $valor->nome,
        'email' => $valor->email,
        'avatar' => $avatar,
        'ultimo' => $valor->ultimo_acesso ? date('d/m/Y H:i:s',$valor->ultimo_acesso) : date('d/m/Y H:i:s',$valor->data_registo),
        'tipo' => $tipo,
        'estado' => $estado
      ];
    }
    $this->dados['utilizadores'] = $utilizadores;

    //AWARDS
    $premiosQuery=\DB::table('premios_empresa')->where('id_empresa',$id)->get();
    $premios =[];
    foreach ($premiosQuery as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "atual":
          $data=$valor->data;
          $estado='<span class="tag tag-amarelo">'.trans('backoffice.Current').'</span>';
          break;
        case "processamento":
          $data=$valor->data_pedido;
          $estado='<span class="tag tag-laranja">'.trans('backoffice.Processing').'</span>';
          break;
        case "enviado":
          $data=$valor->data_envio;
          $estado='<span class="tag tag-verde">'.trans('backoffice.Sent').'</span>';
          break;
        case "concluido":
          $data=$valor->data_conclusao;
          $estado='<span class="tag tag-azul">'.trans('backoffice.Concluded').'</span>';
          break;
        default:
          $data='';
          $estado='<span class="tag tag-cinza">'.$valor->estado.'</span>';
      }

      $premios[] = [
        'id' => $valor->id,
        'nome' => $valor->nome,
        'variante' => $valor->variante,
        'quantidade' => $valor->quantidade,
        'pontos' => $valor->pontos,
        'data' => $data ? date('d/m/Y H:i',$data) : '',
        'estado' => $estado
      ];
    }
    $this->dados['premios'] = $premios;

    return view('backoffice/pages/companies-new', $this->dados);
  }

  public function uploadLogo(Request $request){
    $id_empresa=trim($request->id_empresa);
    $ficheiro=$request->file('ficheiro');

    $novoNome='';
    if(count($ficheiro)){
      $linha = \DB::table('empresas')->where('id',$id_empresa)->first();
      $antigoNome=$linha->logotipo;
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      $novoNome = 'logo_'.$linha->id.'_'.$cache.'.'.$extensao;

      $pasta = base_path('public_html/img/empresas/');
      $width = 300; $height = 300;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      \DB::table('empresas')->where('id',$id_empresa)->update([ 'logotipo'=>$novoNome ]);
    }

    $resposta = [
          'estado' => 'sucesso',
          'foto' => $novoNome,
          'erro' => ''
    ];
    return json_encode($resposta,true);
  }

  public function deleteLogo(Request $request){
    $id_empresa=trim($request->id);
    $linha = \DB::table('empresas')->where('id',$id_empresa)->first();

    if(isset($linha->id))
    {
      if($linha->logotipo && file_exists(base_path('public_html/img/empresas/'.$linha->logotipo))){
        \File::delete('../public_html/img/empresas/'.$linha->logotipo);
      }
      //if($linha['avatar'] && file_exists(base_path().'/public_html/img/empresas/'.$ficha->logotipo)){ \File::delete('../public_html/img/empresas/'.$ficha->logotipo); }
      \DB::table('empresas')->where('id',$id_empresa)->update([ 'logotipo'=>'' ]);
    }
    return 'sucesso';
  }

  public function companiesAccountForm(Request $request){
    $id_empresa=trim($request->id_empresa);
    $nome=trim($request->nome);
    $pontos=intval($request->pontos);
    $email=trim($request->email);
    $email_alteracao=trim($request->email_alteracao);
    $token_alteracao=trim($request->token_alteracao);
    $nif=intval($request->nif);
    $cae=intval($request->cae);
    $telefone=trim($request->telefone);
    $pontos_venda=trim($request->pontos_venda);
    $volume_venda=trim($request->volume_venda);
    $ebitda=trim($request->ebitda);
    $prazo_pag=intval($request->prazo_pag);
    $tipo_fatura=trim($request->tipo_fatura);
    $obs=trim($request->obs);
    $estado_antigo = trim($request->estado_antigo);
    $estado = trim($request->estado);
    $nota=trim($request->nota);

    $certidao_antiga=trim($request->certidao_antiga);
    $certidao=$request->file('certidao');
    $ies1_antiga=trim($request->ies1_antiga);
    $ies1_ano=intval($request->ies1_ano);
    $ies1=$request->file('ies1');
    $ies2_antiga=trim($request->ies2_antiga);
    $ies2_ano=intval($request->ies2_ano);
    $ies2=$request->file('ies2');
    $ies3_antiga=trim($request->ies3_antiga);
    $ies3_ano=intval($request->ies3_ano);
    $ies3=$request->file('ies3');


    \DB::table('empresas')
        ->where('id',$id_empresa)
        ->update([ 'nome'=>$nome,
                   'pontos'=>$pontos,
                   'email'=>$email,
                   'email_alteracao'=>$email_alteracao,
                   'token_alteracao'=>$token_alteracao,
                   'nif'=>$nif,
                   'cae'=>$cae,
                   'telefone'=>$telefone,
                   'pontos_venda'=>$pontos_venda,
                   'volume_venda'=>$volume_venda,
                   'ebitda'=>$ebitda,
                   'prazo_pag'=>$prazo_pag,
                   'tipo_fatura'=>$tipo_fatura,
                   'obs'=>$obs,
                   'nota'=>$nota,
                   'estado'=>$estado ]);

    if(empty($certidao_antiga) || count($certidao)){
      $linha = \DB::table('empresas')->where('id',$id_empresa)->first();
      if($linha->certidao){
        if(file_exists(base_path('public_html/doc/companies/'.$linha->certidao))){ \File::delete('../public_html/doc/companies/'.$linha->certidao); }
        \DB::table('empresas')->where('id',$linha->id)->update(['certidao'=>'']);
      }
    }

    $relaod='';
    //carregar a certidao
    if(count($certidao)){
      $pasta = base_path('public_html/doc/companies/');      
      $arquivo_tmp = $certidao->getPathName(); // caminho
      //$arquivo_name = $certidao->getClientOriginalName(); // nome do ficheiro
      $extensao = strtolower($certidao->getClientOriginalExtension());
      $novoNome = 'company_'.$id_empresa.'_certidao_'.str_random(3).'.'.$extensao;

      if(@move_uploaded_file($arquivo_tmp, $pasta.$novoNome)){ \DB::table('empresas')->where('id',$id_empresa)->update([ 'certidao'=>$novoNome ]); }
      $relaod='sim';
    }

    $arrayIES=[];
    $ies1Q=$ies2Q=$ies3Q='';
    if(empty($ies1_antiga) || count($ies1) || empty($ies2_antiga) || count($ies2) ||empty($ies3_antiga) || count($ies3)){
      //apagar ies
      $linha = \DB::table('empresas')->where('id',$id_empresa)->first();
      if($linha->ies){
        $obj = json_decode($linha->ies);
        if($obj){
          $i=1;
          foreach($obj as $value){
            switch ($i) {
              case '1':
                if(empty($ies1_antiga) || count($ies1)){
                  if(file_exists(base_path('public_html/doc/companies/'.$value->ies))){ \File::delete('../public_html/doc/companies/'.$value->ies); }
                }else{$ies1Q=$value->ies;}
                break;
              case '2':
                if(empty($ies2_antiga) || count($ies2)){
                  if(file_exists(base_path('public_html/doc/companies/'.$value->ies))){ \File::delete('../public_html/doc/companies/'.$value->ies); }
                }else{$ies2Q=$value->ies;}
                break;
              case '3':
                if(empty($ies3_antiga) || count($ies3)){
                  if(file_exists(base_path('public_html/doc/companies/'.$value->ies))){ \File::delete('../public_html/doc/companies/'.$value->ies); }
                }else{$ies3Q=$value->ies;}
                break;
            }
            $i++;
          }
        }
      }

      if(count($ies1)){
        $pasta = base_path('public_html/doc/companies/');        
        $arquivo_tmp = $ies1->getPathName(); // caminho
        //$arquivo_name = $ies1->getClientOriginalName(); // nome do ficheiro
        $extensao = strtolower($ies1->getClientOriginalExtension());
        $novoNome = 'company_'.$id_empresa.'_ies_'.$ies1_ano.'_'.str_random(3).'.'.$extensao;

        if(@move_uploaded_file($arquivo_tmp, $pasta.$novoNome)){ $arrayIES[] = [ 'ano' => $ies1_ano, 'ies' => $novoNome ]; }      
      }else{ $arrayIES[] = [ 'ano'=>$ies1_ano, 'ies'=>$ies1Q ]; }

      if(count($ies2)){
        $pasta = base_path('public_html/doc/companies/');        
        $arquivo_tmp = $ies2->getPathName(); // caminho
        //$arquivo_name = $ies2->getClientOriginalName(); // nome do ficheiro
        $extensao = strtolower($ies2->getClientOriginalExtension());
        $novoNome = 'company_'.$id_empresa.'_ies_'.$ies2_ano.'_'.str_random(3).'.'.$extensao;

        if(@move_uploaded_file($arquivo_tmp, $pasta.$novoNome)){ $arrayIES[] = [ 'ano' => $ies2_ano, 'ies' => $novoNome ]; }      
      }else{ $arrayIES[] = [ 'ano'=>$ies2_ano, 'ies'=>$ies2Q ]; }

      if(count($ies3)){
        $pasta = base_path('public_html/doc/companies/');        
        $arquivo_tmp = $ies3->getPathName(); // caminho
        //$arquivo_name = $ies3->getClientOriginalName(); // nome do ficheiro
        $extensao = strtolower($ies3->getClientOriginalExtension());
        $novoNome = 'company_'.$id_empresa.'_ies_'.$ies3_ano.'_'.str_random(3).'.'.$extensao;

        if(@move_uploaded_file($arquivo_tmp, $pasta.$novoNome)){ $arrayIES[] = [ 'ano' => $ies3_ano, 'ies' => $novoNome ]; }      
      }else{ $arrayIES[] = [ 'ano'=>$ies3_ano, 'ies'=>$ies3Q ]; }
      
      \DB::table('empresas')->where('id',$id_empresa)->update([ 'ies'=>json_encode($arrayIES) ]);

      $relaod='sim';
    }

    if(($estado_antigo!='aprovado' && $estado=='aprovado') || ($estado_antigo!='reprovado' && $estado=='reprovado')){
      $sellersQuery=\DB::table('comerciantes')
                  ->select('nome','email')
                  ->where('id_empresa', $id_empresa)
                  ->where('tipo', '!=', 'comerciante')
                  ->where(function($query){
                      $query->where('tipo', 'gestor')
                            ->orWhere('aprovacao','aprovado');
                  })
                  ->get();
      foreach ($sellersQuery as $valor){
        //app()->setLocale($lingua);
        if($estado=='aprovado'){
          Mail::send('backoffice.emails.empresa-aprovada', [], function($message) use ($valor){
              $message->from(config('backoffice.noreply')['mail'], config('backoffice.noreply')['nome']);
              $message->subject(trans('backoffice.subjectApprovedCompany'));
              $message->to($valor->email, $valor->nome);
              $message->replyTo($valor->email, $valor->nome);
          });
        }else{
          Mail::send('backoffice.emails.empresa-reprovada', [], function($message) use ($valor){
              $message->from(config('backoffice.noreply')['mail'], config('backoffice.noreply')['nome']);
              $message->subject(trans('backoffice.subjectDisapprovedCompany'));
              $message->to($valor->email, $valor->nome);
              $message->replyTo($valor->email, $valor->nome);
          });
        }
      }
      $relaod='sim';
    }
    
    $resposta = [
        'estado' => 'sucesso',
        'mensagem' => '',
        'reload' => $relaod ];
    return json_encode($resposta,true);
  }

  public function companiesProductsForm(Request $request){
    $id_empresa=trim($request->id_empresa);
    $precos=trim($request->precos);

    \DB::table('empresas')->where('id',$id_empresa)->update([ 'precos'=>$precos ]);

    \DB::table('produtos_empresa')->where('id_empresa',$id_empresa)->delete();
    $produtosQuery=\DB::table('produtos')->select('id')->get();
    foreach ($produtosQuery as $valor){
      $aux='preco'.$valor->id;
      $preco=$request->$aux;
      $preco = str_replace("," , "." , $preco);
      $preco = floatval($preco);

      \DB::table('produtos_empresa')->insert(['id_produto'=>$valor->id, 'id_empresa'=>$id_empresa, 'valor'=>$preco ]);
    }

    \DB::table('encomenda')->where('id_empresa',$id_empresa)->where('estado','inicio')->delete();
    return 'sucesso';
  }
}