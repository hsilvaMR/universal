<?php namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Language extends Controller{

    public function getLang($lang){
    	if($lang){ Cookie::queue(Cookie::make('locale', $lang, 43200)); }
    	return redirect()->back();
    }

    public function postLang(Request $request){
   		$lang=trim($request->lang);
   		if($lang){ Cookie::queue(Cookie::make('locale', $lang, 43200)); }
    	return redirect()->back();
    }
}