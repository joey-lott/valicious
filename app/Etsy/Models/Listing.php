<?php

namespace App\Etsy\Models;

class Listing {

  public $id;
  public $title;

  public function getEtsyLink() {
    return "https://www.etsy.com/listing/".$this->id;
  }

}
