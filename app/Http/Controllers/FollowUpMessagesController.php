<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FollowUpMessage;

class FollowUpMessagesController extends Controller
{
    public function messageForm() {
      $message = new FollowUpMessage();
      return view("message.form", ["message" => $message]);
    }

    public function editForm($id) {
      $message = FollowUpMessage::find($id);
      return view("message.form", ["message" => $message]);
    }

    public function saveMessage(Request $request) {
      if($request->messageId != null) {
        $message = FollowUpMessage::find($request->messageId);
      }
      else {
        $message = new FollowUpMessage();
      }
      $message->userId = auth()->user()->id;
      $message->name = $request->name;
      $message->message = $request->message;
      $message->save();
      return redirect("/messages");
    }

    public function index() {
      $messages = FollowUpMessage::where("userId", auth()->user()->id)->get()->all();
      return view("message.index", ["messages" => $messages]);
    }
}
