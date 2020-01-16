<?php

namespace App\Etsy\Models;

use App\ListingImages;

class Listing extends EtsyModel{

  public $id;
  public $title;
  public $mainImageUrl;

  public function getEtsyLink() {
    return "https://www.etsy.com/listing/".$this->id;
  }

  public function getListingMainImage($pause = false) {

    /*
commenting this section because this was caching old images
    $listingImage = ListingImages::where("listingId", $this->id)->get()->first();
    if($listingImage != null) {
      // Image is stored in DB. Use that value and return early.
      $this->mainImageUrl = $listingImage->imageUrl;
      return;
    }
    */
    $endpoint = "listings/".$this->id."?includes=MainImage";
    $response = $this->etsyApi->callOAuth($endpoint, null, OAUTH_HTTP_METHOD_GET);
    $listing = $response["results"][0];
    $this->mainImageUrl = $listing["MainImage"]["url_170x135"];
    $listingImage = new ListingImages();
    $listingImage->listingId = $this->id;
    $listingImage->imageUrl = $this->mainImageUrl;
    $listingImage->save();
    if($pause) {
      usleep(200000);
    }
  }

}
