<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Carbon\Carbon;
use Cookie;

class Cheese extends Controller
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

  /***************/
  /*  QUESTIONS  */
  /***************/
  public function indexQuestions(){
    $this->dados['headTitulo']=trans('backoffice.questionsTitulo');
    $this->dados['separador']="webCheeseQuestions";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('ppqueijinho')
                  ->select('*','resposta_'.$lang.' AS resposta')
                  ->orderBy('id','ASC')
                  ->get();
    $array =[];
    foreach ($query as $valor){
      $imagem = '<img src="'.asset('site_v2/img/ppqueijinho/ppq-back.png').'" class="table-img">';
      if($valor->img && file_exists(base_path('public_html'.$valor->img))){
        $imagem = '<a href="'.asset($valor->img).'" target="_blank"><img src="'.asset($valor->img).'" class="table-img"></a>';
      }

      $array[] = [
        'id' => $valor->id,
        'imagem' => $imagem,
        'resposta' => $valor->resposta,
        'publicacao' => $valor->data_publicacao ? date('Y-m-d',$valor->data_publicacao) : '',
        'online' => $valor->online
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/cheese-questions-all', $this->dados);
  }

  public function apagarQuestion(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('ppqueijinho')->where('id',$id)->first();
    if(isset($linha->id)){
      if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }

      \DB::table('ppqueijinho')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function newPageQuestion(){
    $this->dados['headTitulo']=trans('backoffice.questionTitulo');
    $this->dados['separador']="webCheeseQuestions";
    $this->dados['funcao']="new";
    return view('backoffice/pages/cheese-questions-new', $this->dados);
  }

  public function editPageQuestion($id){
    $this->dados['headTitulo']=trans('backoffice.questionTitulo');
    $this->dados['separador']="webCheeseQuestions";
    $this->dados['funcao']="edit";
    $this->dados['obj'] = \DB::table('ppqueijinho')->where('id',$id)->first();    
    return view('backoffice/pages/cheese-questions-new', $this->dados);
  }

  public function formQuestion(Request $request)
  {
    $id=trim($request->id);
    $resposta_pt=trim($request->resposta_pt);
    $resposta_en=trim($request->resposta_en);
    $resposta_es=trim($request->resposta_es);
    $resposta_fr=trim($request->resposta_fr);

    $link_fb=trim($request->link_fb);
    $link_insta=trim($request->link_insta);

    //$data_validade = trim($request->data_validade) ? strtotime(trim($request->data_validade)) : \Carbon\Carbon::now()->timestamp; //->toDateTimeString();
    $publicacao_pergunta = trim($request->publicacao_pergunta) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->publicacao_pergunta).' 00:00:00')->timestamp : \Carbon\Carbon::now()->timestamp;
    $publicacao_resposta = trim($request->publicacao_resposta) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->publicacao_resposta).' 23:59:59')->timestamp : \Carbon\Carbon::now()->timestamp;
    if($publicacao_pergunta>$publicacao_resposta){ $publicacao_pergunta ^= $publicacao_resposta ^= $publicacao_pergunta ^= $publicacao_resposta; }
    $online = (isset($request->online)) ? 1 : 0;


    $img_antiga=trim($request->img_antiga);
    $ficheiro=$request->file('ficheiro');

    if($id){
      \DB::table('ppqueijinho')
        ->where('id',$id)
        ->update(['resposta_pt'=>$resposta_pt,
                  'resposta_en'=>$resposta_en,
                  'resposta_es'=>$resposta_es,
                  'resposta_fr'=>$resposta_fr,
                  'link_fb'=>$link_fb,
                  'link_insta'=>$link_insta,
                  'data_publicacao'=>$publicacao_pergunta,
                  'publicacao_pergunta'=>$publicacao_pergunta,
                  'publicacao_resposta'=>$publicacao_resposta,
                  'online'=>$online ]);
      
      if(empty($img_antiga) || count($ficheiro)){
        $linha = \DB::table('ppqueijinho')->where('id',$id)->first();
        if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }
        \DB::table('ppqueijinho')->where('id',$linha->id)->update(['img'=>'']);
      }
    }else{
      $id=\DB::table('ppqueijinho')
              ->insertGetId(['resposta_pt'=>$resposta_pt,
                             'resposta_en'=>$resposta_en,
                             'resposta_es'=>$resposta_es,
                             'resposta_fr'=>$resposta_fr,
                             'link_fb'=>$link_fb,
                             'link_insta'=>$link_insta,
                             'data_publicacao'=>$publicacao_pergunta,
                             'publicacao_pergunta'=>$publicacao_pergunta,
                             'publicacao_resposta'=>$publicacao_resposta,
                             'online'=>$online ]);
    }

    $novoNome=$img_antiga;
    if(count($ficheiro)){
      $pasta = base_path('public_html/site_v2/img/ppqueijinho/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      
      $novoNome = 'question'.$id.'-'.$cache.'.'.$extensao;
      $width = 600; $height = 600;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      $novoNome='/site_v2/img/ppqueijinho/'.$novoNome;
      \DB::table('ppqueijinho')->where('id',$id)->update([ 'img'=>$novoNome ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id,
        'imagem' => $novoNome ];
    return json_encode($resposta,true);
  }


  /************/
  /*  AWARDS  */
  /************/
  public function indexAwards(){
    $this->dados['headTitulo']=trans('backoffice.awardsTitulo');
    $this->dados['separador']="webCheeseAwards";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('ppq_premios')
                  ->select('*','premio_'.$lang.' AS premio')
                  ->orderBy('tag','ASC')
                  ->get();
    $array =[];
    foreach ($query as $valor){
      $imagem = '<img src="'.asset('site_v2/img/ppqueijinho/ppq-back.png').'" class="table-img">';
      if($valor->img && file_exists(base_path('public_html'.$valor->img))){
        $imagem = '<a href="'.asset($valor->img).'" target="_blank"><img src="'.asset($valor->img).'" class="table-img"></a>';
      }

      // tag tag-cinza/verde/ouro/turquesa/azul/roxo/rosa/vermelho/laranja/amarelo
      switch ($valor->tag){
        case "1_premio": $tag = '<span class="tag tag-ouro">'.trans('backoffice.1award').'</span>'; break;
        case "2_premio": $tag = '<span class="tag tag-cinza">'.trans('backoffice.2award').'</span>'; break;
        case "3_premio": $tag = '<span class="tag tag-laranja">'.trans('backoffice.3award').'</span>'; break;
        default: $tag = '<span class="tag tag-vermelho">'.$valor->tag.'</span>';
      }

      $array[] = [
        'id' => $valor->id,
        'imagem' => $imagem,
        'premio' => $valor->premio,
        'publicacao' => $valor->data_publicacao ? date('Y-m-d',$valor->data_publicacao) : '',
        'tag' => $tag,
        'online' => $valor->online
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/cheese-awards-all', $this->dados);
  }

  public function apagarAward(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('ppq_premios')->where('id',$id)->first();
    if(isset($linha->id)){
      if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }

      \DB::table('ppq_premios')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function newPageAward(){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="webCheeseAwards";
    $this->dados['funcao']="new";
    return view('backoffice/pages/cheese-awards-new', $this->dados);
  }

  public function editPageAward($id){
    $this->dados['headTitulo']=trans('backoffice.awardTitulo');
    $this->dados['separador']="webCheeseAwards";
    $this->dados['funcao']="edit";
    $this->dados['obj'] = \DB::table('ppq_premios')->where('id',$id)->first();    
    return view('backoffice/pages/cheese-awards-new', $this->dados);
  }

  public function formAward(Request $request)
  {
    $id=trim($request->id);
    $premio_pt=trim($request->premio_pt);
    $premio_en=trim($request->premio_en);
    $premio_es=trim($request->premio_es);
    $premio_fr=trim($request->premio_fr);

    $desc_pt=trim($request->desc_pt);
    $desc_en=trim($request->desc_en);
    $desc_es=trim($request->desc_es);
    $desc_fr=trim($request->desc_fr);

    $tag=trim($request->tag) ? trim($request->tag) : '1_premio';

    //$data_validade = trim($request->data_validade) ? strtotime(trim($request->data_validade)) : \Carbon\Carbon::now()->timestamp; //->toDateTimeString();
    $data_publicacao = trim($request->data_publicacao) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_publicacao).' 00:00:00')->timestamp : \Carbon\Carbon::now()->timestamp;
    $online = (isset($request->online)) ? 1 : 0;


    $img_antiga=trim($request->img_antiga);
    $ficheiro=$request->file('ficheiro');

    if($id){
      \DB::table('ppq_premios')
        ->where('id',$id)
        ->update(['premio_pt'=>$premio_pt,
                  'premio_en'=>$premio_en,
                  'premio_es'=>$premio_es,
                  'premio_fr'=>$premio_fr,
                  'desc_pt'=>$desc_pt,
                  'desc_en'=>$desc_en,
                  'desc_es'=>$desc_es,
                  'desc_fr'=>$desc_fr,
                  'tag'=>$tag,
                  'data_publicacao'=>$data_publicacao,
                  'online'=>$online ]);
      
      if(empty($img_antiga) || count($ficheiro)){
        $linha = \DB::table('ppq_premios')->where('id',$id)->first();
        if($linha->img && file_exists(base_path('public_html'.$linha->img))){ \File::delete('../public_html'.$linha->img); }
        \DB::table('ppq_premios')->where('id',$linha->id)->update(['img'=>'']);
      }
    }else{
      $id=\DB::table('ppq_premios')
              ->insertGetId(['premio_pt'=>$premio_pt,
                             'premio_en'=>$premio_en,
                             'premio_es'=>$premio_es,
                             'premio_fr'=>$premio_fr,
                             'desc_pt'=>$desc_pt,
                             'desc_en'=>$desc_en,
                             'desc_es'=>$desc_es,
                             'desc_fr'=>$desc_fr,
                             'tag'=>$tag,
                             'data_publicacao'=>$data_publicacao,
                             'online'=>$online ]);
    }

    $novoNome=$img_antiga;
    if(count($ficheiro)){
      $pasta = base_path('public_html/site_v2/img/ppqueijinho/');
      $antigoNome='';
      $cache = str_random(3);
      $extensao = strtolower($ficheiro->getClientOriginalExtension());
      
      $novoNome = 'question'.$id.'-'.$cache.'.'.$extensao;
      $width = 600; $height = 600;

      $uploadImage = New uploadImage;
      $uploadImage->upload($ficheiro,$antigoNome,$novoNome,$pasta,$width,$height);
      $novoNome='/site_v2/img/ppqueijinho/'.$novoNome;
      \DB::table('ppq_premios')->where('id',$id)->update([ 'img'=>$novoNome ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id,
        'imagem' => $novoNome ];
    return json_encode($resposta,true);
  }


  /**********/
  /*  FAQS  */
  /**********/
  public function indexFAQs(){
    $this->dados['headTitulo']=trans('backoffice.faqsTitulo');
    $this->dados['separador']="webCheeseFAQs";
    $this->dados['funcao']="all";

    $lang = json_decode(Cookie::get('admin_cookie'))->lingua;
    $this->dados['lingua']=$lang;

    $query = \DB::table('ppq_faqs')
                  ->select('*','pergunta_'.$lang.' AS pergunta')
                  ->orderBy('id','ASC')
                  ->get();
    $array =[];
    foreach ($query as $valor){
      $array[] = [
        'id' => $valor->id,
        'pergunta' => $valor->pergunta,
        'publicacao' => $valor->data_publicacao ? date('Y-m-d',$valor->data_publicacao) : '',
        'online' => $valor->online
      ];
    }
    $this->dados['array'] = $array;
    return view('backoffice/pages/cheese-faqs-all', $this->dados);
  }

  public function apagarFAQ(Request $request)
  {
    $id=trim($request->id);
    $linha = \DB::table('ppq_faqs')->where('id',$id)->first();
    if(isset($linha->id)){
      \DB::table('ppq_faqs')->where('id',$linha->id)->delete();
      return 'sucesso';
    }
    return 'erro';
  }

  public function newPageFAQ(){
    $this->dados['headTitulo']=trans('backoffice.faqTitulo');
    $this->dados['separador']="webCheeseFAQs";
    $this->dados['funcao']="new";
    return view('backoffice/pages/cheese-faqs-new', $this->dados);
  }

  public function editPageFAQ($id){
    $this->dados['headTitulo']=trans('backoffice.faqTitulo');
    $this->dados['separador']="webCheeseFAQs";
    $this->dados['funcao']="edit";
    $this->dados['obj'] = \DB::table('ppq_faqs')->where('id',$id)->first();    
    return view('backoffice/pages/cheese-faqs-new', $this->dados);
  }

  public function formFAQ(Request $request)
  {
    $id=trim($request->id);
    $pergunta_pt=trim($request->pergunta_pt);
    $pergunta_en=trim($request->pergunta_en);
    $pergunta_es=trim($request->pergunta_es);
    $pergunta_fr=trim($request->pergunta_fr);

    $resposta_pt=trim($request->resposta_pt);
    $resposta_en=trim($request->resposta_en);
    $resposta_es=trim($request->resposta_es);
    $resposta_fr=trim($request->resposta_fr);

    //$data_validade = trim($request->data_validade) ? strtotime(trim($request->data_validade)) : \Carbon\Carbon::now()->timestamp; //->toDateTimeString();
    $data_publicacao = trim($request->data_publicacao) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', trim($request->data_publicacao).' 00:00:00')->timestamp : \Carbon\Carbon::now()->timestamp;
    $online = (isset($request->online)) ? 1 : 0;

    if($id){
      \DB::table('ppq_faqs')
        ->where('id',$id)
        ->update(['pergunta_pt'=>$pergunta_pt,
                  'pergunta_en'=>$pergunta_en,
                  'pergunta_es'=>$pergunta_es,
                  'pergunta_fr'=>$pergunta_fr,
                  'resposta_pt'=>$resposta_pt,
                  'resposta_en'=>$resposta_en,
                  'resposta_es'=>$resposta_es,
                  'resposta_fr'=>$resposta_fr,
                  'data_publicacao'=>$data_publicacao,
                  'online'=>$online ]);
    }else{
      $id=\DB::table('ppq_faqs')
              ->insertGetId(['pergunta_pt'=>$pergunta_pt,
                             'pergunta_en'=>$pergunta_en,
                             'pergunta_es'=>$pergunta_es,
                             'pergunta_fr'=>$pergunta_fr,
                             'resposta_pt'=>$resposta_pt,
                             'resposta_en'=>$resposta_en,
                             'resposta_es'=>$resposta_es,
                             'resposta_fr'=>$resposta_fr,
                             'data_publicacao'=>$data_publicacao,
                             'online'=>$online ]);
    }

    $resposta = [
        'estado' => 'sucesso',
        'id' => $id ];
    return json_encode($resposta,true);
  }
}