<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiKeys;

class ApiKeysController extends Controller
{
    public function form() {
      return view("etsyinit.apikeyform");
    }

    public function submit(Request $request) {
      $apiKey = new ApiKeys();
      $apiKey->key = $request->key;
      $apiKey->secret = $request->secret;
      $apiKey->user_id = auth()->user()->id;
      $apiKey->save();
      return redirect("home");
    }
}
