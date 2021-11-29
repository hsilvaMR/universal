<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;

class Giveaways extends Controller
{
  private $dados=[];
  /*private $lang;
  public function __construct()
  {
    //$this->lang=Session::get('locale');
    $this->middleware(function ($request, $next) {
          $this->lang = json_decode(Cookie::get('admin_cookie'))->lingua;
          return $next($request);
      });
  }*/
  
  /************/
  /*  AWARDS  */
  /************/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.giveawaysTitulo');
    $this->dados['separador']="webGiveaways";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('passatempos')
                  ->select('*','titulo_'.$lang.' AS titulo', 'premio_'.$lang.' AS premio')
                  ->orderBy('id','DESC')
                  ->get();
    $array =[];
    foreach ($query as $valor){
      $imagem = '<img src="'.asset('site_v2/img/passatempos/default.svg').'" class="table-img">';
      if($valor->imagem && file_exists(base_path('public_html'.$valor->imagem))){
        $imagem = '<a href="'.asset($valor->imagem).'" target="_blank"><img src="'.asset($valor->imagem).'" class="table-img"></a>';
      }

      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->estado){
        case "ativo": $estado = '<span class="tag tag-verde">'.trans('backoffice.Active').'</span>'; break;
        case "desativo": $estado = '<span class="tag tag-cinza">'.trans('backoffice.Inactive').'</span>'; break;
        case "ppqueijinho": $estado = '<span class="tag tag-ouro">'.trans('backoffice.QfC').'</span>'; break;
        default: $estado = '<span class="tag tag-vermelho">'.$valor->estado.'</span>';
      }

      $array[] = [
        'id' => $valor->id,
        'imagem' => $imagem,
        'titulo' => $valor->titulo,
        'premio' => $valor->premio,
        'inicio' => $valor->data_inicio ? date('Y-m-d',$valor->data_inicio) : '',
        'fim' => $valor->data_fim ? date('Y-m-d',$valor->data_fim) : '',
        'estado' => $estado
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/giveaways-all', $this->dados);
  }

  public function apagar(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('passatempos')->where('id',$id)->first();
    if(isset($linha->id)){
      if($linha->imagem && file_exists(base_path('public_html'.$linha->imagem))){ \File::delete('../public_html'.$linha->imagem); }
      if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }

      \DB::table('passatempos')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function newPage(){
    $this->dados['headTitulo']=trans('backoffice.giveawayTitulo');
    $this->dados['separador']="webGiveaways";
    $this->dados['funcao']="new";
    return view('backoffice/pages/giveaways-new', $this->dados);
  }

  public function editPage($id){
    $this->dados['headTitulo']=trans('backoffice.giveawayTitulo');
    $this->dados['separador']="webGiveaways";
    $this->dados['funcao']="edit";
    $this->dados['obj'] = \DB::table('passatempos')->where('id',$id)->first();    
    return view('backoffice/pages/giveaways-new', $this->dados);
  }

  public function form(Request $request)
  {
    $id=trim($request->id);
    $titulo_pt=trim($request->titulo_pt);
    $titulo_en=trim($request->titulo_en);
    $titulo_es=trim($request->titulo_es);
    $titulo_fr=trim($request->titulo_fr);

    $premio_pt=trim($request->premio_pt);
    $premio_en=trim($request->premio_en);
    $premio_es=trim($request->premio_es);
    $premio_fr=trim($request->premio_fr);

    $regulamento_pt=trim($request->regulamento_pt);
    $regulamento_en=trim($request->regulamento_en);
    $regulamento_es=trim($request->regulamento_es);
    $regulamento_fr=trim($request->regulamento_fr);

    $link_fb=trim($request->link_fb);
    $link_insta=trim($request->link_insta);
    $estado=trim($request->estado) ? trim($request->estado) : 'desativo';
    $vencedor=trim($request->vencedor);
    $tipo_vencedor=trim($request->tipo_vencedor);
    if($tipo_vencedor=='muitos'){ $vencedor=intval($vencedor); }
    //$data_validade = trim($request->data_validade) ? strtotime(trim($request->data_validade)) : \Carbon\Carbon::now()->timestamp; //->toDateTimeString();
    $data_inicio = trim($request->data_inicio) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_inicio).' 00:00:00')->timestamp : \Carbon\Carbon::now()->timestamp;
    $data_fim = trim($request->data_fim) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_fim).' 23:59:59')->timestamp : \Carbon\Carbon::now()->timestamp;
    if($data_inicio>$data_fim){ $data_inicio ^= $data_fim ^= $data_inicio ^= $data_fim; }

    $img_antiga=trim($request->img_antiga);
    $ficheiro=$request->file('ficheiro');
    $img_antiga_quadrada=trim($request->img_antiga_quadrada);
    $ficheiro_quadrada=$request->file('ficheiro_quadrada');

    if($id){
      \DB::table('passatempos')
        ->where('id',$id)
        ->update(['titulo_pt'=>$titulo_pt,
                  'titulo_en'=>$titulo_en,
                  'titulo_es'=>$titulo_es,
                  'titulo_fr'=>$titulo_fr,
                  'premio_pt'=>$premio_pt,
                  'premio_en'=>$premio_en,
                  'premio_es'=>$premio_es,
                  'premio_fr'=>$premio_fr,
                  'regulamento_pt'=>$regulamento_pt,
                  'regulamento_en'=>$regulamento_en,
                  'regulamento_es'=>$regulamento_es,
                  'regulamento_fr'=>$regulamento_fr,
                  'link_fb'=>$link_fb,
                  'link_insta'=>$link_insta,
                  'vencedor'=>$vencedor,
                  'tipo_vencedor'=>$tipo_vencedor,
                  'data_inicio'=>$data_inicio,
                  'data_fim'=>$data_fim,
                  'estado'=>$estado ]);

      if(empty($img_antiga) || count($ficheiro) || empty($img_antiga_quadrada) || count($ficheiro_quadrada)){
        $linha = \DB::table('passatempos')->where('id',$id)->first();
        if($linha->imagem && (empty($img_antiga) || count($ficheiro))){
          if(file_exists(base_path('public_html'.$linha->imagem))){ \File::delete('../public_html'.$linha->imagem); }
          \DB::table('passatempos')->where('id',$linha->id)->update(['imagem'=>'']);
        }
        if($linha->img && (empty($img_antiga_quadrada) || count($ficheiro_quadrada))){
          if(file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }
          \DB::table('passatempos')->where('id',$linha->id)->update(['img'=>'']);
        }
      }
    }else{

      $id=\DB::table('passatempos')
              ->insertGetId(['titulo_pt'=>$titulo_pt,
                             'titulo_en'=>$titulo_en,
                             'titulo_es'=>$titulo_es,
                             'titulo_fr'=>$titulo_fr,
                             'premio_pt'=>$premio_pt,
                             'premio_en'=>$premio_en,
                             'premio_es'=>$premio_es,
                             'premio_fr'=>$premio_fr,
                             'regulamento_pt'=>$regulamento_pt,
                             'regulamento_en'=>$regulamento_en,
                             'regulamento_es'=>$regulamento_es,
                             'regulamento_fr'=>$regulamento_fr,
                             'link_fb'=>$link_fb,
                             'link_insta'=>$link_insta,
      			                 'vencedor'=>$vencedor,
      			                 'tipo_vencedor'=>$tipo_vencedor,
      			                 'data_inicio'=>$data_inicio,
      			                 'data_fim'=>$data_fim,
                             'estado'=>$estado,
                             'data'=>\Carbon\Carbon::now()->timestamp ]);
    }

    $novoNome=$img_antiga;
    if(count($ficheiro)){
      $pasta = base_path('public_html/site_v2/img/passatempos/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      
      $novoNome = 'giveaways'.$id.'-'.$cache.'.'.$extensao;
      $width = 1600; $height = 1600;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      $novoNome='/site_v2/img/passatempos/'.$novoNome;
      \DB::table('passatempos')->where('id',$id)->update([ 'imagem'=>$novoNome ]);
    }
    $novoNomeQuadrada=$img_antiga_quadrada;
    if(count($ficheiro_quadrada)){
      $pasta = base_path('public_html/site_v2/img/passatempos/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro_quadrada->getClientOriginalExtension());
      
      $novoNomeQuadrada = 'giveaways'.$id.'-square-'.$cache.'.'.$extensao;
      $width = 600; $height = 600;
      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro_quadrada,$antigoNome,$novoNomeQuadrada,$pasta,$width,$height);
      $novoNomeQuadrada='/site_v2/img/passatempos/'.$novoNomeQuadrada;
      \DB::table('passatempos')->where('id',$id)->update([ 'img'=>$novoNomeQuadrada ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id,
        'imagem_quadrada' => $novoNomeQuadrada,
        'imagem' => $novoNome ];
    return json_encode($resposta,true);
  }
}