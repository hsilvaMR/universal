<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class Support extends Controller{

    private $dados = [];
     
    public function index(){

        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Support';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();
        $this->dados['msg_ticket'] = \DB::table('configuracoes')
                                           ->where('tag','ticket_contacto')
                                           ->first();
                                           
        $tickets = \DB::table('tickets')
                       ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                       ->get();

        $array =[];
        foreach ($tickets as $value) {
            switch ($value->estado) {
                case 'aberto':
                    $estado = '<i class="fas fa-circle tx-lightgreen margin-right5"></i>'.trans('seller.open');
                    break;

                case 'respondido':
                    $estado = '<i class="fas fa-circle tx-navy margin-right5"></i>'.trans('seller.answered');
                    break;

                case 'resposta':
                    $estado = '<i class="fas fa-circle tx-amarelo-claro margin-right5"></i>'.trans('seller.answer');
                    break;
                
                default:
                    $estado = '<i class="fas fa-circle tx-red margin-right5"></i>'.trans('seller.close');
                    break;
            }
            $array []=[
                'id' => $value->id,
                'assunto' => $value->assunto,
                'data' => $value->data,
                'estado' => $estado
            ];
        }

        
        $this->dados['tickets'] = $array;
        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

        return view('seller/pages/ticket',$this->dados);
    }

    public function newTicket(){
        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Support';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

        return view('seller/pages/ticketNew',$this->dados);
    }

    public function msgTicket($id){
        $this->dados['headTitulo'] = trans('seller.t_data');
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = 'Support';

        $this->dados['configuracoes'] = \DB::table('empresas_config')
                                           ->where('id_comerciante',Cookie::get('cookie_comerc_id'))
                                           ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                           ->get();

        $this->dados['ticket'] = \DB::table('tickets')->where('id',$id)->first();

        $tickets_msg = \DB::table('tickets_msg')
                           ->select('tickets_msg.*','tickets.*','comerciantes.foto AS foto','tickets_msg.data AS data_msg','tickets_msg.id AS id_msg')
                           ->leftJoin('tickets','tickets_msg.id_ticket','tickets.id')
                           ->leftJoin('comerciantes','tickets.id_comerciante','comerciantes.id')
                           ->where('tickets.id_comerciante',Cookie::get('cookie_comerc_id'))
                           ->where('tickets_msg.id_ticket',$id)
                           ->get();

        $arrayFicheiros = [];
        foreach ($tickets_msg as $value) {
            $file = json_decode($value->ficheiros);

            foreach ($file as $val) {
                $arrayFicheiros[] = [
                    'ficheiro' => $val,
                    'id_msg' => $value->id_msg
                ];
            }  
        } 
     
        $this->dados['arrayFicheiros'] = $arrayFicheiros;

        $this->dados['tickets_msg'] = $tickets_msg;
        $this->dados['id_ticket'] = $id;

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();
        return view('seller/pages/ticketMsg',$this->dados);
    }

    public function newTicketPost(Request $request){

        $id_ticket = trim($request->id_ticket);
        $assunto = trim($request->assunto);
        $mensagem = trim($request->mensagem);
        $ficheiros = $request->file('ficheiros');

        if(strlen($mensagem) > 1000){return 'A sua mensagem execede o número de caracteres.';}

        if (empty($id_ticket)) {
            if (empty($assunto)) { return trans('seller.Field_subject_txt'); }
            $id_ticket = \DB::table('tickets')
                            ->insertGetId([
                                'id_comerciante' => Cookie::get('cookie_comerc_id'),
                                'id_empresa' => Cookie::get('cookie_comerc_id_empresa'),
                                'assunto' => $assunto,
                                'data' => \Carbon\Carbon::now()->timestamp
                            ]);

            $arrayFicheiros = [];
            if($request->hasFile('ficheiros')){
                foreach ($ficheiros as $file) {
                    $destinationPath = base_path('public_html/doc/support/');
                    $extension = strtolower($file->getClientOriginalExtension());
                    $getName = $file->getPathName();
                    $cache = str_random(3);

                    $file_support = 'ticket_'.Cookie::get('cookie_comerc_id_empresa').'_user_'.Cookie::get('cookie_comerc_id').'_'.$cache.'.'.$extension;
                    
                    move_uploaded_file($getName, $destinationPath.$file_support);

                    $arrayFicheiros[] = ['ficheiro' => $file_support];
                }
            }

            $ticket = \DB::table('tickets')->where('id',$id_ticket)->first();


            \DB::table('tickets_msg')
                ->insert([
                    'id_ticket' => $id_ticket,
                    'mensagem' => $mensagem,
                    'ficheiros' => json_encode($arrayFicheiros),
                    'autor' => 'cliente',
                    'data' => $ticket->data
                ]);

            $resposta = [
                'estado' => 'sucesso'
            ];
        }
        else{
            if (empty($mensagem)) { return trans('seller.Field_message_txt'); }
            //update para alterar o estado da mensagem
            \DB::table('tickets')->where('id',$id_ticket)->update(['estado' => 'resposta']);

            $arrayFicheiros = [];
            if($request->hasFile('ficheiros')){
                foreach ($ficheiros as $file) {
                    $destinationPath = base_path('public_html/doc/support/');
                    $extension = strtolower($file->getClientOriginalExtension());
                    $getName = $file->getPathName();
                    $cache = str_random(3);

                    $file_support = 'ticket_'.Cookie::get('cookie_comerc_id_empresa').'_user_'.Cookie::get('cookie_comerc_id').'_'.$cache.'.'.$extension;
                    
                    move_uploaded_file($getName, $destinationPath.$file_support);

                    $arrayFicheiros[] = ['ficheiro' => $file_support];
                }
            }

            $id_ticket_msg = \DB::table('tickets_msg')
                                ->insertGetId([
                                    'id_ticket' => $id_ticket,
                                    'mensagem' => $mensagem,
                                    'ficheiros' => json_encode($arrayFicheiros),
                                    'autor' => 'cliente',
                                    'data' => \Carbon\Carbon::now()->timestamp
                                ]);

            $ticket_msg = \DB::table('tickets_msg')->where('id',$id_ticket_msg)->first();
            $seller = \DB::table('comerciantes')->where('id',Cookie::get('cookie_comerc_id'))->first();

            if ($seller->foto) { $html_foto = $seller->foto;}
            else{ $html_foto = 'default.svg';}

            $html_files = '';
            if ($ticket_msg->ficheiros != '[]') {

                $html_file = '';
                $file = json_decode($ticket_msg->ficheiros);
             
                foreach ($file as $val) {    
                    $html_file .= '<a href="/doc/support/'.$val->ficheiro.'" download>'.$val->ficheiro.'</a><br>';
                }            

                $html_files = '
                    <label class="margin-top10">Anexos</label>
                    <div class="div-msg-files">
                        '.$html_file.'
                    </div>
                ';
            }

            $resposta = [
                'estado' => 'sucesso',
                'resposta_html' => '
                <div class="row" style="padding:0px 20px;">
                    <div class="div-msg-img">
                        <img src="\img\comerciantes\/'.$html_foto.'">
                    </div>
                    <div class="div-msg-conteudo">
                        <div style="border:1px solid #ccc;padding:20px;">
                          <label class="div-msg-mensagem-label">'.$ticket_msg->mensagem.'</label>
                          <label class="font12 float-right">'.date('Y-m-d H:m:s',$ticket_msg->data).'</label>
                        </div>

                        '.$html_files.'
                    </div>
                </div>
                <div class="div-msg-line"></div>'
            ];
        }
        return json_encode($resposta,true);   
    }
}