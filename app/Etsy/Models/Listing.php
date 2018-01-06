<?php

namespace App\Etsy\Models;

class Listing extends EtsyModel{

  public $id;
  public $title;
  public $mainImageUrl;

  public function getEtsyLink() {
    return "https://www.etsy.com/listing/".$this->id;
  }

  public function getListingMainImage($pause = false) {
    $endpoint = "listings/".$this->id."?includes=MainImage";
    $response = $this->etsyApi->callOAuth($endpoint, null, OAUTH_HTTP_METHOD_GET);
    $listing = $response["results"][0];
    $this->mainImageUrl = $listing["MainImage"]["url_170x135"];
    if($pause) {
      sleep(.05);
    }
  }

}
