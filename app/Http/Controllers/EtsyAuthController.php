<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etsy\EtsyAPI;
use App\EtsyAuth;

class EtsyAuthController extends Controller
{
    public function authLink() {
      $etsyApi = resolve("\App\Etsy\EtsyAPI");
      $link = $etsyApi->getEtsyAuthorizeLink("transactions_r%20transactions_w");
      return view("etsyinit.authorizelink", ["link" => $link]);
    }

    public function finalizeAuthorization(Request $request) {
      $etsyApi = resolve("\App\Etsy\EtsyAPI");
      $secret = $_COOKIE['token_secret'];
      $credentials = $etsyApi->finalizeAuthorization($secret, $request->oauth_token, $request->oauth_verifier);
      // Must save auth token and secret before getting shop
      $userId = auth()->user()->id;
      EtsyAuth::where("user_id", $userId)->delete();
      $etsyAuth = new EtsyAuth();
      $etsyAuth->oauthToken = $credentials["token"];
      $etsyAuth->oauthTokenSecret = $credentials["secret"];
      $etsyAuth->user_id = $userId;
      $etsyAuth->save();
      $shop = $etsyApi->fetchShopCurrentUser();
      $etsyAuth->shopId = $shop->shop_id;
      $etsyAuth->etsyUserId = $shop->user_id;
      $etsyAuth->update();
      return redirect("/home");
    }
}
