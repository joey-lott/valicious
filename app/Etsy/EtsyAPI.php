<?php

namespace App\Etsy;

use GuzzleHttp\Client;
use \OAuth;

class EtsyAPI
{
    private $apiKey;
    private $secret;

    public static $ALL_LISTINGS = "all_listings";
    public static $PAGE_LISTINGS = "page_listings";

    public function __construct($apiKey, $secret) {
      $this->apiKey = $apiKey;
      $this->secret = $secret;
    }

    // Scope can be, for example, listings_w%20listings_r
    public function getEtsyAuthorizeLink($scope) {
      $a = $this->apiKey;
      $b = $this->secret;

      $oauth = new \OAuth($this->apiKey, $this->secret);
      try {
        $response = $oauth->getRequestToken("https://openapi.etsy.com/v2/oauth/request_token?scope=".$scope, route("finalizeAuthorization"), "GET");
        setcookie("token_secret", $response["oauth_token_secret"]);
        return $response["login_url"];
      }
      catch (\OAuthException $e) {
        dd($e);
      }
    }

    public function finalizeAuthorization($secret, $token, $verifier) {
      $oauth = new \OAuth($this->apiKey, $this->secret);
      $oauth->setToken($token, $secret);
      try {
        $response = $oauth->getAccessToken("https://openapi.etsy.com/v2/oauth/access_token", null, $verifier, "GET");
        $oauthToken = $response["oauth_token"];
        $oauthTokenSecret = $response["oauth_token_secret"];
        return ["token" => $oauthToken, "secret" => $oauthTokenSecret];
      }
      catch(\OAuthException $exception) {
        return false;
      }

    }

    public function fetchShopCurrentUser() {
      // Use the __SELF__ token that Etsy supports to retrieve the shop for the current user.
      // This requires OAuth to work even though otherwise the endpoint does not require OAuth.
      $response = $this->callOAuth("users/__SELF__/shops", null, OAUTH_HTTP_METHOD_GET);
      return (object) $response["results"][0];
    }

    public function callGet($endpoint, $params="") {
      $client = new Client;
      $response = $client->request("GET", "https://openapi.etsy.com/v2/".$endpoint."?api_key=".$this->apiKey."".$params);
      return json_decode($response->getBody());
    }

    public function callPostV3($endpoint) {
      $client = new Client;
      $params = ["headers" => ["x-api-key" => $this->apiKey]];
      $response = $client->request("POST", "https://openapi.etsy.com/v3/".$endpoint, $params);
      return json_decode($response->getBody());
    }

    public function callOAuth($endpoint, $params, $method=OAUTH_HTTP_METHOD_POST, $requestEngineCurl = false, $returnJson = false) {
      $oauth = new OAuth($this->apiKey, $this->secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
      $user = auth()->user();
      $etsyAuth = $user->etsyAuth;
      $oauth->setToken($etsyAuth->oauthToken, $etsyAuth->oauthTokenSecret);
      if($requestEngineCurl) {
        $oauth->setRequestEngine(OAUTH_REQENGINE_CURL);
      }
      $url = "https://openapi.etsy.com/v2/".$endpoint;
      try{
        if(count($params) == 0) {$params = null;}
        $response = $oauth->fetch($url, $params, $method);
        $json = $oauth->getLastResponse();
        if($returnJson) return $json;
        $obj = json_decode($json, true);
        return $obj;
      }
      catch(\OAuthException $e) {
        dump("Your request produced an unhandled error. Please copy the following and send it in an email to joeylott@gmail.com with a subject line of 'GB Lightning Lister Bug Report'. Thank you.");
        dump($url);
        dump($params);
        dd($e);
      }
    }

}
