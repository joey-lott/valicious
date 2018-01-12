<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notes;

class FollowUp extends Model
{
  public function getNotes() {
    return Notes::where("receiptId", $this->receiptId)->get()->all();
  }
}
