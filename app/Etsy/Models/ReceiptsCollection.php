<?php


namespace App\Etsy\Models;

class ReceiptsCollection {

  public $receipts;
  public $count;
  public $filter = "all";
  public $search;

  public function order($direction) {
    if($direction == "asc") {
      $this->receipts = $this->receipts->reverse();
    }
  }

  public function setFilter($filter) {
    $this->filter = $filter == null ? "all" : $filter;
  }

  public function setSearch($search) {
    $this->search = $search;
  }

  public function getDisplayReceipts() {
    // If the filter is set to "all", don't filter on processed/ship (but to run search filter)
    if($this->filter == "all") return $this->runSearchFilter($this->receipts);

    // Otherwise, filter by processed/shipped
    $display = [];
    foreach($this->receipts as $receipt) {
      if($this->filter == "not_processed" && !$receipt->processed) $display[] = $receipt;
      if($this->filter == "not_shipped" && $receipt->processed) $display[] = $receipt;
    }
    // Run the search filter on the results
    return $this->runSearchFilter($display);
  }

  public function runSearchFilter($receipts) {
    // If there is no search, return the input
    if(!isset($this->search)) return $receipts;

    // Otherwise, filter by search results
    $display = [];
    foreach($receipts as $receipt) {
      $found = false;
      foreach($receipt->listings as $listing) {
        // Make sure not to add twice.
        if($found === true) continue;
        if(strpos(mb_strtolower($listing->title), mb_strtolower($this->search)) !== false) {
          $display[] = $receipt;
          $found = true;
        }
      }
    }
    return $display;
  }

}
