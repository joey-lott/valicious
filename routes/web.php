<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
  dd(phpinfo());
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/api-key', 'ApiKeysController@form');
Route::post('/api-key', 'ApiKeysController@submit');

Route::get('/authorize', 'EtsyAuthController@authLink');
Route::get('/authorize/finalize', 'EtsyAuthController@finalizeAuthorization')->name("finalizeAuthorization");

Route::get('/receipts', 'ReceiptsController@index');
Route::get('/transactions', 'TransactionsController@index');
Route::post('/order-processed', 'OrdersProcessedController@markAsProcessed');

Route::post('/receipt/shipped', 'ReceiptsController@markAsShippedForm');
