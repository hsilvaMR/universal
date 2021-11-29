<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class WebSlide extends Controller
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
  
  /***********/
  /*  SLIDE  */
  /***********/
  public function index(){
    $this->dados['headTitulo']=trans('backoffice.slidesTitulo');
    $this->dados['separador']="webSlide";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('conteudos_slide')
                  ->select('*','titulo_'.$lang.' AS titulo','texto_'.$lang.' AS texto')
                  ->orderBy('id','DESC')
                  ->get();
    $array =[];
    foreach ($query as $valor){
      $imagem = '<img src="'.asset('site_v2/img/slide/mini-default.jpg').'" class="table-img">';
      if($valor->img && file_exists(base_path('public_html'.$valor->img))){
        $imagem = '<a href="'.asset($valor->img).'" target="_blank"><img src="'.asset($valor->img).'" class="table-img"></a>';
      }

      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tipo) {
        case 'img_texto': $tipo = '<span class="tag tag-azul nowrap">'.trans('backoffice.tipoSlideImgTexto').'</span>'; break;
        case 'img': $tipo = '<span class="tag tag-verde nowrap">'.trans('backoffice.tipoSlideImg').'</span>'; break;
        default: $tipo = '<span class="tag tag-vermelho nowrap">'.$valor->tipo.'</span>'; break;
      }

      $array[] = [
        'id' => $valor->id,
        'imagem' => $imagem,
        'titulo' => $valor->titulo,
        'texto' => $valor->texto,
        'tipo' => $tipo,
        'online' => $valor->online
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/web-slide-all', $this->dados);
  }

  public function apagar(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('conteudos_slide')->where('id',$id)->first();
    if(isset($linha->id)){
      if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }
      if($linha->img_xs && file_exists(base_path('public_html'.$linha->img_xs))){ \File::delete('../public_html'.$linha->img_xs); }

      \DB::table('conteudos_slide')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function newPage(){
    $this->dados['headTitulo']=trans('backoffice.slideTitulo');
    $this->dados['separador']="webSlide";
    $this->dados['funcao']="new";

    $count = \DB::table('conteudos_slide')->count();   

    $array_ordem = [];
    for ($i=1; $i <= $count+1 ; $i++) { 
      $array_ordem[] = [
        'valor' => $i
      ];
    }
    $this->dados['colors']=\DB::table('conteudos_slide_cor')->get();

    $this->dados['array_ordem'] = $array_ordem;
    return view('backoffice/pages/web-slide-new', $this->dados);
  }

  public function editPage($id){
    $this->dados['headTitulo']=trans('backoffice.slideTitulo');
    $this->dados['separador']="webSlide";
    $this->dados['funcao']="edit";
    $this->dados['obj'] = \DB::table('conteudos_slide')->where('id',$id)->first();  

    $count = \DB::table('conteudos_slide')->count();   

    $array_ordem = [];
    for ($i=1; $i <= $count ; $i++) { 
      $array_ordem[] = [
        'valor' => $i
      ];
    }

    $this->dados['array_ordem'] = $array_ordem;

    $this->dados['colors']=\DB::table('conteudos_slide_cor')->get();
    return view('backoffice/pages/web-slide-new', $this->dados);
  }


  public function colors(){
    $this->dados['headTitulo']=trans('backoffice.slideTitulo');
    $this->dados['separador']="webSlide_colors";
    $this->dados['funcao']="all";

    $this->dados['colors']=\DB::table('conteudos_slide_cor')->orderBy('ordem','ASC')->get();
    

    return view('backoffice/pages/web-slide-colors', $this->dados);
  }

  public function colorsNew(){
    $this->dados['headTitulo']=trans('backoffice.slideTitulo');
    $this->dados['separador']="webSlide_colors";
    $this->dados['funcao']="new";

    return view('backoffice/pages/web-slide-colors-new', $this->dados);
  }

  public function colorsEdit($id){
    $this->dados['headTitulo']=trans('backoffice.slideTitulo');
    $this->dados['separador']="webSlide_colors";
    $this->dados['funcao']="edit";

    $this->dados['colors']=\DB::table('conteudos_slide_cor')->where('id',$id)->first();

    return view('backoffice/pages/web-slide-colors-new', $this->dados);
  }

  

  public function formCor(Request $request){
    $id=trim($request->id);
    $nome=trim($request->nome);
    $gradiente_1=trim($request->gradiente_1);
    $gradiente_2=trim($request->gradiente_2);

    

    if($id){
      \DB::table('conteudos_slide_cor')
        ->where('id',$id)
        ->update(['nome'=>$nome,
                  'gradiente_1'=>$gradiente_1,
                  'gradiente_2'=>$gradiente_2 ]);

    }
    else{
      $id=\DB::table('conteudos_slide_cor')
              ->insertGetId(['nome'=>$nome,
                        'gradiente_1'=>$gradiente_1,
                        'gradiente_2'=>$gradiente_2 ]);
    }


    $resposta = [
      'estado' => 'sucesso',
      'id' => $id
    ];
    return json_encode($resposta,true);
  }

  

  public function form(Request $request)
  {    
    $id=trim($request->id);
    $titulo_pt=trim($request->titulo_pt);
    $titulo_en=trim($request->titulo_en);
    $titulo_es=trim($request->titulo_es);
    $titulo_fr=trim($request->titulo_fr);

    $texto_pt=trim($request->texto_pt);
    $texto_en=trim($request->texto_en);
    $texto_es=trim($request->texto_es);
    $texto_fr=trim($request->texto_fr);

    $bt_texto_pt=trim($request->bt_texto_pt);
    $bt_texto_en=trim($request->bt_texto_en);
    $bt_texto_es=trim($request->bt_texto_es);
    $bt_texto_fr=trim($request->bt_texto_fr);

    $fd_cor=trim($request->fd_cor);
    $url=trim($request->url);
    $tipo = trim($request->tipo) ? trim($request->tipo) : 'img_texto';
    $ordem = trim($request->ordem) ? trim($request->ordem) : 1;
    $online = (isset($request->online)) ? 1 : 0;

    $img_antiga_xs=trim($request->img_antiga_xs);
    $ficheiro_xs=$request->file('ficheiro_xs');
    $img_antiga=trim($request->img_antiga);
    $ficheiro=$request->file('ficheiro');
        
    if($id){
      \DB::table('conteudos_slide')
        ->where('id',$id)
        ->update(['titulo_pt'=>$titulo_pt,
                  'titulo_en'=>$titulo_en,
                  'titulo_es'=>$titulo_es,
                  'titulo_fr'=>$titulo_fr,
                  'texto_pt'=>$texto_pt,
                  'texto_en'=>$texto_en,
                  'texto_es'=>$texto_es,
                  'texto_fr'=>$texto_fr,
                  'bt_texto_pt'=>$bt_texto_pt,
                  'bt_texto_en'=>$bt_texto_en,
                  'bt_texto_es'=>$bt_texto_es,
                  'bt_texto_fr'=>$bt_texto_fr,
                  'fd_cor'=>$fd_cor,
                  'url'=>$url,
                  'ordem' => $ordem,
                  'tipo'=>$tipo,
                  'online'=>$online ]);
      
      if(empty($img_antiga) || count($ficheiro) || empty($img_antiga_xs) || count($ficheiro_xs)){
        $linha = \DB::table('conteudos_slide')->where('id',$id)->first();
        if($linha->img && (empty($img_antiga) || count($ficheiro))){
          if(file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }
          \DB::table('conteudos_slide')->where('id',$linha->id)->update(['img'=>'']);
        }
        if($linha->img_xs && (empty($img_antiga_xs) || count($ficheiro_xs))){
          if(file_exists(base_path('public_html'.$linha->img_xs))){ \File::delete('../public_html'.$linha->img_xs); }
          \DB::table('conteudos_slide')->where('id',$linha->id)->update(['img_xs'=>'']);
        }
      }
    }else{
      $id=\DB::table('conteudos_slide')
              ->insertGetId(['titulo_pt'=>$titulo_pt,
                             'titulo_en'=>$titulo_en,
                             'titulo_es'=>$titulo_es,
                             'titulo_fr'=>$titulo_fr,
                             'texto_pt'=>$texto_pt,
                             'texto_en'=>$texto_en,
                             'texto_es'=>$texto_es,
                             'texto_fr'=>$texto_fr,
                             'bt_texto_pt'=>$bt_texto_pt,
                             'bt_texto_en'=>$bt_texto_en,
                             'bt_texto_es'=>$bt_texto_es,
                             'bt_texto_fr'=>$bt_texto_fr,
                             'fd_cor'=>$fd_cor,
                             'url'=>$url,
                             'ordem' => $ordem,
                             'tipo'=>$tipo,
                             'online'=>$online,
                             'data'=>\Carbon\Carbon::now()->timestamp ]);
    }


    $novoNome=$img_antiga;
    if(count($ficheiro)){
      $pasta = base_path('public_html/site_v2/img/slide/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      
      $novoNome = 'slide'.$id.'-'.$cache.'.'.$extensao;
      $width = 1600; $height = 1600;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      $novoNome='/site_v2/img/slide/'.$novoNome;
      \DB::table('conteudos_slide')->where('id',$id)->update([ 'img'=>$novoNome ]);
    }
    $novoNomeXs=$img_antiga_xs;
    if(count($ficheiro_xs)){
      $pasta = base_path('public_html/site_v2/img/slide/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro_xs->getClientOriginalExtension());
      
      $novoNomeXs = 'slide'.$id.'-xs-'.$cache.'.'.$extensao;
      $width = 600; $height = 600;
      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro_xs,$antigoNome,$novoNomeXs,$pasta,$width,$height);
      $novoNomeXs='/site_v2/img/slide/'.$novoNomeXs;
      \DB::table('conteudos_slide')->where('id',$id)->update([ 'img_xs'=>$novoNomeXs ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id,
        'imagem_xs' => $novoNomeXs,
        'imagem' => $novoNome ];
    return json_encode($resposta,true);
  }
}