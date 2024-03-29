<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiKeys;
use App\EtsyAuth;
use App\MwsCredentials;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct() {
       $this->middleware('auth');
       $this->middleware('subscribed');
     }

    /**+
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $hasApiKey = ApiKeys::where("user_id", auth()->user()->id)->get()->count() > 0;
      $hasEtsyAuth = EtsyAuth::where("user_id", auth()->user()->id)->get()->count() > 0;
      $hasMwsCredentials = MwsCredentials::where("user_id", auth()->user()->id)->get()->count() > 0;
      if($hasApiKey && $hasEtsyAuth) { // don't require mws && $hasMwsCredentials) {
        return view('home');
      }
      else if(!$hasApiKey) {
        return redirect("api-key");
      }
      else if(!$hasEtsyAuth) {
        return redirect("authorize");
      }
      else if(!$hasMwsCredentials) {
        return redirect("mws");
      }
    }
}
