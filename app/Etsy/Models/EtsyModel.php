<?php


namespace App\Etsy\Models;

class EtsyModel {

  protected $etsyApi;

  public function __construct() {
    $this->etsyApi = resolve("\App\Etsy\EtsyAPI");
  }


}
