<?php

Route::get('/', function () {
    return view('welcome');
});

use GuzzleHttp\Client;
use Illuminate\Http\Request;

Route::get("/search-customer", function(Request $request){
  $client = new Client(["base_uri" => env("APP_URL")]);
  $response = $client->post("/proxy", ["form_params" => ["url" => "https://www.gearbubble.com/dropship_users/dashboard?name={$request->name}"]]);
  return $response->getBody()->getContents();
});

Route::get("/test", function(){
  return "<form action='/proxy' method='post'>".csrf_field()."<input type='text' name='url'><button>GO</button></form>";
});
Route::post('/proxy', function(Request $request) {
  $url = $request->url;
  $client = new Client();
  $response = $client->get($url);
  return $response->getBody()->getContents();
});

Auth::routes();

Route::get('/subscribe', 'SubscribeController@showPaymentForm');
Route::post('/subscribe', 'SubscribeController@subscribe');

Route::get("/account/welcome", 'SubscribeController@welcome');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/api-key', 'ApiKeysController@form');
Route::post('/api-key', 'ApiKeysController@submit');

Route::get('/authorize', 'EtsyAuthController@authLink');
Route::get('/authorize/finalize', 'EtsyAuthController@finalizeAuthorization')->name("finalizeAuthorization");

Route::get('/receipts', 'ReceiptsController@index');
Route::get('/transactions', 'TransactionsController@index');
Route::post('/order-processed', 'OrdersProcessedController@markAsProcessed');

Route::post('/receipt/ship', 'ReceiptsController@markAsShippedForm');
Route::post('/receipt/shipped', 'ReceiptsController@markAsShippedSubmit');
