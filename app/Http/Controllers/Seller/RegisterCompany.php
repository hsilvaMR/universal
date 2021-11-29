<?php 
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Classes\uploadImage;

use Cookie;

class RegisterCompany extends Controller{

    private $dados = [];

    public function index(){

        $this->dados['headTitulo'] = 'Universal - Área Comercial';
        $this->dados['headDescricao'] = 'Universal';
        $this->dados['separador'] = '';
        
        $this->dados['comerciantes'] = \DB::table('comerciantes')
                                            ->where('id', Cookie::get('cookie_comerc_id'))
                                            ->first();
                            

        $this->dados['company'] = \DB::table('empresas')->where('id',Cookie::get('cookie_comerc_id_empresa'))->first();

        $this->dados['moradas'] = \DB::table('moradas')
                                        ->where('id_empresa', Cookie::get('cookie_comerc_id_empresa'))
                                        ->where('tipo','morada_sede')
                                        ->first();

        $lang = Cookie::get('locale');

        if ($lang == '') { $lang = 'en'; }

        $this->dados['paises'] = \DB::table('pais')
                                      ->select('id','nome_'.$lang.' AS nome')
                                      ->orderBy('nome_'.$lang,'ASC')
                                      ->get();

        $this->dados['comerciante_resp'] = \DB::table('comerciantes')
                                                ->where('id_empresa',Cookie::get('cookie_comerc_id_empresa'))
                                                ->where('tipo','admin')
                                                ->first();

        if(Cookie::get('cookie_comerc_type') != 'comerciante'){ return view('seller/pages/registerCompany',$this->dados); }
        else{ return redirect()->route('personalDataV2'); }
    }
         
}