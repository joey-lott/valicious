<?php


namespace App\Etsy\Models;

class ReceiptsCollection {

  public $receipts;
  public $count;

  public function order($direction) {
    if($direction == "asc") {
      $this->receipts = $this->receipts->reverse();
    }
  }

}
