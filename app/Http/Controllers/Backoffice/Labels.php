<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Labels extends Controller
{
  private $dados=[];
  
  /************/
  /*  LABELS  */
  /************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.labelsTitulo');
    $this->dados['separador']="setLabels";
    $this->dados['funcao']="all";
    return view('backoffice/pages/labels-all', $this->dados);
  }
  public function indexList(Request $request){
    $columns = array(
      0 =>'id', 
      1 => 'codigo',
      2 => 'serie',
      3 => 'valor',
      4 => 'estado',
      5 => 'data'
    );
    // getting total number records without any search
    $totalData = \DB::table('rotulos')->select('id')->count();
    $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
    //$orderPrefix = ($columns[$request['order'][0]['column']]=='utilizadores') ? '' : 'rotulos.';

    if( !empty($request['search']['value']) ) {
      // if there is a search parameter
      $query = \DB::table('rotulos')
                ->select(\DB::raw('SQL_CALC_FOUND_ROWS *'))
                ->orWhere('id', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('codigo', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('serie', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('valor', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('estado', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('data', 'LIKE', '%'.$request['search']['value'].'%')
                ->orderBy($columns[$request['order'][0]['column']],$request['order'][0]['dir'])
                ->offset($request['start'])
                ->limit($request['length'])
                ->get();

      $totalFiltered  = \DB::select(\DB::raw('SELECT FOUND_ROWS() AS total'))[0]->total;
      //$totalFiltered  = $totalFiltered->total;

      /*$totalFiltered = \DB::table('rotulos')
                ->orWhere('id', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('cargo_pt', 'LIKE', '%'.$request['search']['value'].'%')
                ->orWhere('cargo_en', 'LIKE', '%'.$request['search']['value'].'%')
                ->count();*/
      
    }else{
      $query = \DB::table('rotulos')
                ->orderBy($columns[$request['order'][0]['column']],$request['order'][0]['dir'])
                ->offset($request['start'])
                ->limit($request['length'])
                ->get();
    }
    $data = array();
    foreach ($query as $valor) {
      //$utilizadores = \DB::table('trabalhos')->select('id')->where('id_cargo',$valor->id)->count();
      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "disponivel": $estado = '<span class="tag tag-verde">'.trans('backoffice.Available').'</span>'; break;
        case "indisponivel": $estado = '<span class="tag tag-vermelho">'.trans('backoffice.Unavailable').'</span>'; break;
        default: $estado = '<span class="tag tag-cinza">'.$valor->estado.'</span>';
      }

      $opcao = '<a href="'.route('labelsEditPageB',['id'=>$valor->id]).'" class="table-opcao"><i class="fas fa-pencil-alt"></i>&nbsp;'.trans('backoffice.Edit').'</a>&ensp;
                      <span id="del'.$valor->id.'" class="table-opcao" onclick="$(\'#id_modal\').val('.$valor->id.');" data-toggle="modal" data-target="#myModalDelete">
                        <i class="fas fa-trash-alt"></i>&nbsp;'.trans('backoffice.Delete').'
                      </span>';

      $nestedData=array(); 
      $nestedData[] = $valor->id;
      $nestedData[] = $valor->codigo;
      $nestedData[] = $valor->serie;
      $nestedData[] = $valor->valor;
      $nestedData[] = $estado;
      $nestedData[] = $valor->data ? date('d/m/Y H:i:s',$valor->data) : '';
      $nestedData[] = $opcao;

      $data[] = $nestedData;
    }

    $json_data = array(
          "draw"            => intval( $request['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
          "recordsTotal"    => intval( $totalData ),  // total number of records
          "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
          "data"            => $data   // total data array
          );
    return json_encode($json_data);
  }

  public function newLabel(){
    $this->dados['headTitulo']=trans('backoffice.labelTitulo');
    $this->dados['separador']="setLabels";
    $this->dados['funcao']="new";

    return view('backoffice/pages/labels-new', $this->dados);
  }
  public function editLabel($id){
    $this->dados['headTitulo']=trans('backoffice.labelTitulo');
    $this->dados['separador']="setLabels";
    $this->dados['funcao']="edit";

    $this->dados['obj'] = \DB::table('rotulos')->where('id',$id)->first();    
    return view('backoffice/pages/labels-new', $this->dados);
  }
  public function formLabel(Request $request){
    $id=trim($request->id);
    $codigo=strip_tags(trim($request->codigo)) ? strtoupper(strip_tags(trim($request->codigo))) : $this->geraCodigo(6);
    $codigo_inv=strrev($codigo);
    $serie=trim($request->serie) ? trim($request->serie) : 'BO';
    $valor=intval(trim($request->valor)) ? intval(trim($request->valor)) : \DB::table('produtos')->max('pontos');
    $estado=trim($request->estado) ? trim($request->estado) : 'disponivel';

    if($id){
      if($codigo!='BACKOFFICE'){
        while(\DB::table('rotulos')->select('id')->where('id', '!=', $id)->where('codigo', 'LIKE BINARY', $codigo)->count()){
          $codigo = $this->geraCodigo(6);
          $codigo_inv=strrev($codigo);
        }
      }
      \DB::table('rotulos')
        ->where('id',$id)
        ->update(['codigo'=>$codigo,
                  'codigo_inv'=>$codigo_inv,
                  'serie'=>$serie,
                  'valor'=>$valor,
                  'estado'=>$estado ]);
    }else{
      if($codigo!='BACKOFFICE'){
        while(\DB::table('rotulos')->select('id')->where('codigo', 'LIKE BINARY', $codigo)->count()){
          $codigo = $this->geraCodigo(6);
          $codigo_inv=strrev($codigo);
        }
      }
      $id=\DB::table('rotulos')
              ->insertGetId([
                'codigo'=>$codigo,
                'codigo_inv'=>$codigo_inv,
                'serie'=>$serie,
                'valor'=>$valor,
                'estado'=>$estado,
                'data'=>\Carbon\Carbon::now()->timestamp
      ]);
      $id_inv = $this->inverterId($id);
      \DB::table('rotulos')->where('id',$id)->update([ 'id_inv'=>$id_inv ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }

  public function generatePage(){
    $this->dados['headTitulo']=trans('backoffice.labelsTitulo');
    $this->dados['separador']="setLabels";
    $this->dados['funcao']="generate";

    return view('backoffice/pages/labels-generate', $this->dados);
  }
  public function generateForm(Request $request){
    $quantidade=intval(trim($request->quantidade)) ? intval(trim($request->quantidade)) : 1;
    $serie=trim($request->serie) ? trim($request->serie) : 'BO_'.strtoupper(str_random(3));
    //$valor=intval(trim($request->valor)) ? intval(trim($request->valor)) : 0;
    //$estado=trim($request->estado) ? trim($request->estado) : 'disponivel';
    $valor=\DB::table('produtos')->max('pontos');


    for($i=1; $i<=$quantidade; $i++){
      do{
        $codigo = $this->geraCodigo(6);
        $codigo_inv=strrev($codigo);
      }while(\DB::table('rotulos')->select('id')->where('codigo', 'LIKE BINARY', $codigo)->count());

      $id=\DB::table('rotulos')
              ->insertGetId([
                'codigo'=>$codigo,
                'codigo_inv'=>$codigo_inv,
                'serie'=>$serie,
                'valor'=>$valor,
                'estado'=>'disponivel',
                'data'=>\Carbon\Carbon::now()->timestamp
      ]);
      $id_inv = $this->inverterId($id);
      \DB::table('rotulos')->where('id',$id)->update([ 'id_inv'=>$id_inv ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }

  public function exportPage(){
    $this->dados['headTitulo']=trans('backoffice.labelsTitulo');
    $this->dados['separador']="setLabels";
    $this->dados['funcao']="export";

    $this->dados['series']=\DB::table('rotulos')->select('serie')->groupBy('serie')->get();
    return view('backoffice/pages/labels-export', $this->dados);
  }
  public function exportForm(Request $request){
    $primeiro = strtoupper(trim($request->primeiro));
    $ultimo = strtoupper(trim($request->ultimo));
    $series=$request->series;

    if($primeiro || $ultimo || $series){
      require_once(base_path('public_html/vendor/phpexcel/Classes/PHPExcel.php'));
      require_once(base_path('public_html/vendor/phpexcel/Classes/PHPExcel/IOFactory.php'));
      $nomeficheiro = date("Y-m-d").'_'.trans('backoffice.labelsTit');
      //trans('backoffice.labelsTitPag')

      $output = '
        <table>
          <tr>
            <th>'.trans('backoffice.fieldId').'</th>
            <th>'.trans('backoffice.fieldLabel').'</th>
            <th>'.trans('backoffice.fieldInvertedid').'</th>
            <th>'.trans('backoffice.fieldInvertedlabel').'</th>
            <th>'.trans('backoffice.fieldSerie').'</th>
          </tr>';

      $queryLabels=\DB::table('rotulos');
      if($primeiro){ $queryLabels=$queryLabels->where('id', '>=', $primeiro); }
      if($ultimo){ $queryLabels=$queryLabels->where('id', '<=', $ultimo); }
      if(is_array($series)){ $queryLabels=$queryLabels->whereIn('serie',$series); }
      $queryLabels=$queryLabels->get();
      //$queryLabels=\DB::table('rotulos')->whereIn('id',[1,2,3,4])->get();    
      foreach($queryLabels as $val){   
        $output .='
          <tr>
            <td>'.$val->id.'</td>
            <td>'.$val->codigo.'</td>
            <td>'.$val->id_inv.'</td>
            <td>'.$val->codigo_inv.'</td>
            <td>'.$val->serie.'</td>
          </tr>';
      }
      $output .='</table>';

      header("Content-Type: application/xls");
      header("Content-Disposition: attachment; filename=".$nomeficheiro.".xls");
      header("Pragma: no-cache");
      header("Expires: 0");

      exit($output);
    }
    return;
  }

  public function identifyPage(){
    $this->dados['headTitulo']=trans('backoffice.labelsTitulo');
    $this->dados['separador']="setLabels";
    $this->dados['funcao']="identify";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['products']=\DB::table('produtos')->select('id','nome_'.$lang.' AS nome')->get();
    return view('backoffice/pages/labels-identify', $this->dados);
  }
  public function identifyForm(Request $request){
    $primeiro = strtoupper(trim($request->primeiro));
    $ultimo = strtoupper(trim($request->ultimo));
    $id_produto=$request->produto;

    if(empty($primeiro) || empty($ultimo)){ return trans('backoffice.firstLastLabelError'); }
    if(empty($id_produto)){ return trans('backoffice.selectProductError'); }
    
    $primeiroQuery=\DB::table('rotulos')->select('id')->where('id', $primeiro)->orWhere('codigo', 'LIKE BINARY', $primeiro)->first();
    if(empty($primeiroQuery)){ return trans('backoffice.firstLabelError'); }  
    $ultimoQuery=\DB::table('rotulos')->select('id')->where('id', $ultimo)->orWhere('codigo', 'LIKE BINARY', $ultimo)->first();
    if(empty($ultimoQuery)){ return trans('backoffice.lastLabelError'); }
    if($ultimoQuery->id>$primeiroQuery->id){ $id_primeiro=$primeiroQuery->id; $id_ultimo=$ultimoQuery->id; }
    else{ $id_primeiro=$ultimoQuery->id; $id_ultimo=$primeiroQuery->id; }

    $produtoQuery=\DB::table('produtos')->select('id', 'pontos')->where('id', $id_produto)->first();
    if(empty($produtoQuery)){ return trans('backoffice.productLabelError'); }

    \DB::table('rotulos')->where('id','>=',$id_primeiro)->where('id','<=',$id_ultimo)->update(['id_produto'=>$produtoQuery->id,'valor'=>$produtoQuery->pontos]);

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id_produto ];
    return json_encode($resposta,true);
  }

  private function geraCodigo($tamanho){
    //$simbolos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvxz';
    $simbolos = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $simbolos;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++)
    {
      $rand = mt_rand(1, $len);
      $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
  }

  private function inverterId($id){
    switch(strlen($id)){
      case '1': $id_inv=strrev($id.'00000000000'); break;
      case '2': $id_inv=strrev($id.'0000000000'); break;
      case '3': $id_inv=strrev($id.'000000000'); break;
      case '4': $id_inv=strrev($id.'00000000'); break;
      case '5': $id_inv=strrev($id.'0000000'); break;
      case '6': $id_inv=strrev($id.'000000'); break;
      case '7': $id_inv=strrev($id.'00000'); break;
      case '8': $id_inv=strrev($id.'0000'); break;
      case '9': $id_inv=strrev($id.'000'); break;
      case '10':$id_inv=strrev($id.'00'); break;
      case '11':$id_inv=strrev($id.'0'); break;      
      default:  $id_inv=strrev($id); break;
    }
    return $id_inv;
  }
}