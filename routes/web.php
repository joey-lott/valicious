<?php

Route::get('/', function () {
    return view('welcome');
});

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Etsy\Models\Receipt;
use Illuminate\Support\Facades\Cookie;

Route::get("/search-customer", function(Request $request){
  $client = new Client(["base_uri" => env("APP_URL")]);
  $response = $client->post("/proxy", ["form_params" => ["url" => "https://www.gearbubble.com/dropship_users/dashboard?name={$request->name}"]]);
  return $response->getBody()->getContents();
});

Route::get("/stripe-return", function(){
});


Route::get("/affiliate/{id}", function($id) {
  $hundredYears = 60 * 24 * 365 * 100;
  Cookie::queue("affiliate-id", $id, $hundredYears);
  return redirect("/view-affiliate-id");
});

Route::get("/view-affiliate-id", function() {
  dump(Cookie::get("affiliate-id"));
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
