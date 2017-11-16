<?php


namespace App\Etsy\Models;

class Transaction extends EtsyModel {

  public $id;
  public $title;
  public $quantity;
  public $variations;

  static public function createFromAPIResponse($response) {
    $rt = (object) $response;
    $t = new Transaction();
    $t->id = $rt->transaction_id;
    $t->title = $rt->title;
    $t->quantity = $rt->quantity;
    $t->variations = [];
    foreach($rt->variations as $rv) {
      $v = new Variation();
      $rv = (object) $rv;
      $v->formattedName = $rv->formatted_name;
      $v->formattedValue = $rv->formatted_value;
      $t->variations[] = $v;
    }
    return $t;
  }

  public function __construct() {
    parent::__construct();
  }

  public function fetchAllTransactions($page = 1) {
    $shopId = auth()->user()->etsyAuth->shopId;
    $endpoint = "shops/".$shopId."/transactions";
    $response = $this->etsyApi->callOAuth($endpoint, ["limit" => 50, "page" => $page], OAUTH_HTTP_METHOD_GET);
    $tc = TransactionsCollection::createFromAPIResponse($response);
    return $tc;

    // $transactions = [];
    // foreach($transactionsArray as $t) {
    //   $t = (object) $t;
    //   $transaction = new Transaction();
    //   $transaction->id = $t->receipt_id;
    //   $transaction->orderId = $t->order_id;
    //   $transaction->formattedAddress = $t->formatted_address;
    //   $transaction->messageFromBuyer = $t->message_from_buyer;
    //   $transaction->wasPaid = $t->was_paid;
    //   $transaction->wasShipped = $r->was_shipped;
    //   $transactions[] = $transaction;
    // }
    // $collection = collect($transactions);
    // $transactionsCollection = new TransactionsCollection();
    // $transactionsCollection->receipts = $collection;
    // $transactionsCollection->count = $response["count"];
    // return $transactionsCollection;
  }

}
