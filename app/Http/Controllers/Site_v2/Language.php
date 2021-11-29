<?php namespace App\Http\Controllers\Site_v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Cookie;

class Language extends Controller{

    public function getLang($lang){
    	if($lang){
    		//Cookie::put('locale', $lang);
            //Session::flash('changedLang','1');
    		Cookie::queue(Cookie::make('locale', $lang, 43200));
    	}        
    	return redirect()->back();
    }

    public function postLang(Request $request){


   		$lang=trim($request->lang);

   		if($lang){
    		//Cookie::put('locale', $lang);
            //Session::flash('changedLang','1');
    		Cookie::queue(Cookie::make('locale', $lang, 43200));
    	}        
    	return redirect()->back();
    }
}

