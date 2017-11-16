<?php


namespace App\Etsy\Models;

class ListingCollection {

  public $listings;

  static public function createInnerArray($results) {
    $array = [];
    foreach($results as $listing) {
      $l = new Listing();
      $l->id = $listing["listing_id"];
      $array[] = $l;
    }
    return $array;
  }

}
