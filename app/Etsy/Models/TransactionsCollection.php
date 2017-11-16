<?php


namespace App\Etsy\Models;

class TransactionsCollection {

  public $transactions;
  public $count;

  static public function createFromAPIResponse($response) {
    $tc = new TransactionsCollection();
    $tc->count = $response["count"];
    $array = self::createInnerArray($response["results"]);
    $tc->transactions = collect($array);
    return $tc;
  }

  static public function createInnerArray($results) {
    $array = [];
    foreach($results as $t) {
      $t = Transaction::createFromAPIResponse($t);
      $array[] = $t;
    }
    return $array;
  }

}
