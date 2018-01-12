<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FollowUp;
use App\FollowUpMessage;
use App\Note;

class FollowUpController extends Controller
{
  public function __construct() {
    $this->middleware("auth");
  }

  public function index() {
    $user = auth()->user();
    $followups = FollowUp::where("userId", $user->id)->get()->all();
    return view("followups.index", ["followups" => $followups]);
  }

  public function process(Request $request) {
    $messages = FollowUpMessage::where("userId", auth()->user()->id)->get()->all();
    return view("followups.process", ["receiptId" => $request->receiptId, "followupId" => $request->followupId, "messages" => $messages]);
  }

}
