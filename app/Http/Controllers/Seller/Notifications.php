<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Notifications extends Controller{

    private $dados = [];
     
    public function index(){
        $notificacoesQuery = \DB::table('notificacoes')->where('id_notificado',Cookie::get('cookie_comerc_id'))->orderBy('vista','asc')->get();
        $array_notificacoes = [];
        
        foreach($notificacoesQuery as $value){
            if($value->id_encomenda){ $encQuery = \DB::table('encomenda')->where('id', $value->id_encomenda)->first(); }
            if($value->id_empresa){ $empQuery = \DB::table('empresas')->where('id', $value->id_empresa)->first(); }
            if($value->id_comerciante){ $comercQuery = \DB::table('comerciantes')->where('id', $value->id_comerciante)->first(); }
            if($value->id_premio_empresa){ $premioQuery = \DB::table('premios_empresa')->where('id', $value->id_premio_empresa)->first(); }

            
            switch ($value->tipo) {
                //ENCOMENDA
                case 'enc_iniciada': //encomenda iniciada
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_processamento': //encomenda em processamento
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_parcial_exp': //encomenda parcialmente expedida
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_expedida': //encomenda expedida
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_concluida': //encomenda expedida
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_fat_compr': //factura comprovativo da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_nota_dev': //nota devolucao da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_recibo_disp': //recibo disponivel da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_fat_vencida': //fatura vencida da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                //EMPRESA
                case 'emp_aprovada': //empresa aprovada
                    $img = '/img/icones/company.svg';
                    $tit = str_replace("##empresa##" , $empQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'emp_reprovada': //empresa reprovada
                    $img = '/img/icones/company.svg';
                    $tit = str_replace("##empresa##" , $empQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;


                //COMERCIANTES
                case 'com_admin_aprovado': //adminstrador aprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##administrador##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'com_admin_reprovado': //adminstrador reprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##administrador##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'com_comerc_aprovado': //comerciante aprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##comerciante##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'com_comerc_reprovado': //comerciante reprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##comerciante##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                //PREMIOS
                case 'pre_processamento': //premio processamento
                    $img = '/img/icones/gift.svg';
                    $tit = str_replace("##premio##" , $premioQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'pre_enviado': //premio enviado
                    $img = '/img/icones/gift.svg';
                    $tit = str_replace("##premio##" , $premioQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'pre_concluido': //premio concluido
                    $img = '/img/icones/gift.svg';
                    $tit = str_replace("##premio##" , $premioQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'pre_data_envio': //premio data de envio
                    $img = '/img/icones/gift.svg';
                    $tit = str_replace("##premio##" , $premioQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo).date('Y-m-d',$premioQuery->data_envio).'.';
                    break;

                default:
                    $img = '';
                    $tit = $value->tipo;
                    $txt = $value->tipo;
                    break;
            }
            
            $array_notificacoes[] = [
                'id' => $value->id,
                'img' => $img,
                'tit' => $tit,
                'txt' => $txt,
                'data' => $value->data,
                'url' => $value->url,
                'vista' => $value->vista
            ];
        }

        $html_not = '';
        foreach ($array_notificacoes as $val) {
            $bg = 'bg-white';
            $cursor = '';
            $href = '';
            $href_fim = '';
            if ($val['vista'] == 0) { $bg = 'bg-aqua';}
            if (!empty($val['url'])){ 
                $cursor = 'cursor-pointer';
                $href = '<div onclick="changeVisto('.$val['id'].',\''.$val['url'].'\');">';
                $href_fim = '</div>';
            }

            $html_div= 
            '
                <div id="not_'.$val['id'].'" class="menu-config-line '.$bg.' '.$cursor.'">
                    <img src="'.$val['img'].'">
                    <span>'.$val['tit'].'</span>
                    <span class="menu-config-line-date">'.date('Y-m-d',$val['data']).'</span>
                    <br>
                    <span class="tx-jet">'.$val['txt'].'</span>
                </div>
            ';

            $html_not.= $href.$html_div.$href_fim;
        }

        return $html_not;
    }

    public function filterUnRead(){

        $notificacoesQuery = \DB::table('notificacoes')->where('id_notificado',Cookie::get('cookie_comerc_id'))->where('vista',0)->get();
        $array_notificacoes = [];
        
        foreach($notificacoesQuery as $value){
            if($value->id_encomenda){ $encQuery = \DB::table('encomenda')->where('id', $value->id_encomenda)->first(); }
            if($value->id_empresa){ $empQuery = \DB::table('empresas')->where('id', $value->id_empresa)->first(); }
            if($value->id_comerciante){ $comercQuery = \DB::table('comerciantes')->where('id', $value->id_comerciante)->first(); }

            
            switch ($value->tipo) {
                //ENCOMENDA
                case 'enc_iniciada': //encomenda iniciada
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_processamento': //encomenda em processamento
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_parcial_exp': //encomenda parcialmente expedida
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_expedida': //encomenda expedida
                    $img = '/img/icones/ship.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_fat_compr': //factura comprovativo da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_nota_dev': //nota devolucao da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_recibo_disp': //recibo disponivel da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'enc_fat_vencida': //fatura vencida da encomenda
                    $img = '/img/icones/doc.svg';
                    $tit = str_replace("##encomenda##" , $encQuery->referencia , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                //EMPRESA
                case 'emp_aprovada': //empresa aprovada
                    $img = '/img/icones/company.svg';
                    $tit = str_replace("##empresa##" , $empQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'emp_reprovada': //empresa reprovada
                    $img = '/img/icones/company.svg';
                    $tit = str_replace("##empresa##" , $empQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;


                //COMERCIANTES
                case 'com_admin_aprovado': //adminstrador aprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##administrador##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'com_admin_reprovado': //adminstrador reprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##administrador##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'com_comerc_aprovado': //comerciante aprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##comerciante##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                case 'com_comerc_reprovado': //comerciante reprovado
                    $img = '/img/icones/users.png';
                    $tit = str_replace("##comerciante##" , $comercQuery->nome , trans('seller.NOTI_TIT_'.$value->tipo));
                    $txt = trans('seller.NOTI_TXT_'.$value->tipo);
                    break;

                default:
                    $img = '';
                    $tit = $value->tipo;
                    $txt = $value->tipo;
                    break;

                
            }
            
            $array_notificacoes[] = [
                'id' => $value->id,
                'img' => $img,
                'tit' => $tit,
                'txt' => $txt,
                'data' => $value->data,
                'url' => $value->url,
                'vista' => $value->vista
            ];
        }

        $html_not = '';
        foreach ($array_notificacoes as $val) {
            $bg = 'bg-white';
            $cursor = '';
            $href = '';
            $href_fim = '';
            if ($val['vista'] == 0) { $bg = 'bg-aqua';}
            if (!empty($val['url'])){ 
                $cursor = 'cursor-pointer';
                $href = '<div onclick="changeVisto('.$val['id'].',\''.$val['url'].'\');">';
                $href_fim = '</div>';
            }

            $html_div= 
            '
                <div id="not_'.$val['id'].'" class="menu-config-line '.$bg.' '.$cursor.'">
                    <img src="'.$val['img'].'">
                    <span>'.$val['tit'].'</span>
                    <span class="menu-config-line-date">'.date('Y-m-d',$val['data']).'</span>
                    <br>
                    <span class="tx-jet">'.$val['txt'].'</span>
                </div>
            ';

            $html_not.= $href.$html_div.$href_fim;
        }

        return $html_not;
    }

    public function markAllNoti(){

        \DB::table('notificacoes')
             ->where('id_notificado',Cookie::get('cookie_comerc_id'))
             ->update([
                    'vista' => 1
             ]);

        Cookie::queue(Cookie::make('cookie_not_ative', 0, 43200));
        
        $resposta = [ 
            'estado' => 'sucesso'
        ];
        return json_encode($resposta,true);    
    }

    public function markNoti(Request $request){

        $id = trim($request->id);

        \DB::table('notificacoes')
            ->where('id',$id)
            ->update([
              'vista' => 1
            ]);

        $not_count=\DB::table('notificacoes')->where('id_notificado',Cookie::get('cookie_comerc_id'))->where('vista',0)->count();

        Cookie::queue(Cookie::make('cookie_not_ative', $not_count, 43200));
        
        $resposta = [ 
          'estado' => 'sucesso',
          'not_count' => $not_count
        ];
        return json_encode($resposta,true);    
    }
}