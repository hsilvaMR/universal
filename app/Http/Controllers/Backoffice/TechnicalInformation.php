<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class TechnicalInformation extends Controller
{
  private $dados=[];
  
  /***************************/
  /*  TECHNICALINFORMATIONS  */
  /***************************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.technicalInfoTitulo');
    $this->dados['separador']="setTechnical";
    $this->dados['funcao']="all";

    $query = \DB::table('informacoes_tecnicas')->orderBy('tipo','produto')->get();
    $array =[];
    foreach ($query as $valor){
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado) {
        case 'novo': $estado = '<span class="tag tag-verde nowrap">'.trans('backoffice.New').'</span>'; break;
        case 'actualizado': $estado = '<span class="tag tag-turquesa nowrap">'.trans('backoffice.Updated').'</span>'; break;
        case 'ativo': $estado = '<span class="tag tag-azul nowrap">'.trans('backoffice.Active').'</span>'; break;
        default: $estado = '<span class="tag tag-cinza nowrap">'.$valor->estado.'</span>'; break;
      }
      switch ($valor->tipo) {
        case 'produto': $tipo = '<span class="tag tag-laranja nowrap">'.trans('backoffice.Product').'</span>'; break;
        case 'documento': $tipo = '<span class="tag tag-amarelo nowrap">'.trans('backoffice.Document').'</span>'; break;
        default: $tipo = '<span class="tag tag-cinza nowrap">'.$valor->tipo.'</span>'; break;
      }

      $array[] = [
        'id' => $valor->id,
        'descricao' => $valor->descricao,
        'ficheiro' => $valor->ficheiro ? '<a href="public_html/doc/informations/'.$valor->ficheiro.'" taget="_blank" download>'.$valor->ficheiro.'</a>' : '',
        'tipo' => $tipo,
        'estado' => $estado,
        'online' => $valor->online
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/technical-info-all', $this->dados);
  }

  public function delete(Request $request){
    $id=trim($request->id);
    $linha = \DB::table('informacoes_tecnicas')->where('id',$id)->first();
    if(isset($linha->id)){
      if($linha->ficheiro && file_exists(base_path('public_html/doc/informations/'.$linha->ficheiro))){ \File::delete('../public_html/doc/informations/'.$linha->ficheiro); }

      \DB::table('informacoes_tecnicas')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }
  public function new(){
    $this->dados['headTitulo']=trans('backoffice.technicalInfoTitulo');
    $this->dados['separador']="setTechnical";
    $this->dados['funcao']="new";

    return view('backoffice/pages/technical-info-new', $this->dados);
  }

  public function edit($id){
    $this->dados['headTitulo']=trans('backoffice.technicalInfoTitulo');
    $this->dados['separador']="setTechnical";
    $this->dados['funcao']="edit";

    $this->dados['obj'] = \DB::table('informacoes_tecnicas')->where('id',$id)->first();
    return view('backoffice/pages/technical-info-new', $this->dados);
  }

  public function form(Request $request){
    $id=trim($request->id);
    $descricao=trim($request->descricao);
    $tipo = trim($request->tipo) ? trim($request->tipo) : 'produto';
    $estado = trim($request->estado) ? trim($request->estado) : 'novo';
    $online = (isset($request->online)) ? 1 : 0;

    $ficheiro_antigo=trim($request->ficheiro_antigo);
    $ficheiro=$request->file('ficheiro');


    if($id){
      \DB::table('informacoes_tecnicas')
        ->where('id',$id)
        ->update(['descricao' => $descricao,
                  'tipo' => $tipo,
                  'estado' => $estado,
                  'online' => $online ]);
      
      if(empty($ficheiro_antigo) || count($ficheiro)){
        $linha = \DB::table('informacoes_tecnicas')->where('id',$id)->first();
        if($linha->ficheiro){
          if(file_exists(base_path('public_html/doc/informations/'.$linha->ficheiro))){ \File::delete('../public_html/doc/informations/'.$linha->ficheiro); }
          \DB::table('informacoes_tecnicas')->where('id',$linha->id)->update(['ficheiro'=>'']);
        }
      }
    }else{
      $id=\DB::table('informacoes_tecnicas')
              ->insertGetId(['descricao' => $descricao,
                             'tipo' => $tipo,
                             'estado' => $estado,
                             'online' => $online,
                             'data'=>\Carbon\Carbon::now()->timestamp ]);
    }

    $novoNome=$ficheiro_antigo;
    if(count($ficheiro)){
      $pasta = base_path('public_html/doc/informations/');
      $antigoNome='';
      
      $arquivo_tmp = $ficheiro->getPathName(); // caminho
      $arquivo_name = $ficheiro->getClientOriginalName(); // nome do ficheiro
      $extensao = strtolower($ficheiro->getClientOriginalExtension());     
      $novoNome = str_replace('.'.$extensao , '_'.str_random(3).'.'.$extensao, $arquivo_name);

      if(@move_uploaded_file($arquivo_tmp, $pasta.$novoNome)){ \DB::table('informacoes_tecnicas')->where('id',$id)->update([ 'ficheiro'=>$novoNome ]); }
    }
    
    $resposta = [
        'estado' => 'sucesso',
        'mensagem' => '',
        'id' => $id,
        'ficheiro' => $novoNome ];
    return json_encode($resposta,true);

  }
}