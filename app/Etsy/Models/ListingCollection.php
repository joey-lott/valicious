<?php


namespace App\Etsy\Models;

class ListingCollection {

  public $listings;

  static public function createInnerArray($results) {
    $array = [];
    foreach($results as $listing) {
      $l = new Listing();
      $l->id = $listing["listing_id"];
      if(isset($listing["title"])) {
        $l->title = $listing["title"];
      }
      else {
        $l->title = "*** ".$listing["state"]." ***";
      }
      $array[] = $l;
    }
    return $array;
  }

  public function get($key) {
    return $this->listings->get($key);
  }

}
