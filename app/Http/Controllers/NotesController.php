<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notes;

class NotesController extends Controller
{
    public function showForm(Request $request) {
      return view("notesViews.noteForm", ["receiptId" => $request->receiptId, "redirectTo" => $request->redirectTo]);
    }

    public function submitNote(Request $request) {
      $config = \HTMLPurifier_Config::createDefault();
      $purifier = new \HTMLPurifier($config);

      $note = new Notes();
      $note->receiptId = $request->receiptId;
      $note->note = $purifier->purify($request->note);
      $note->requiresResolution = isset($request->requiresResolution);
      $note->save();
      return redirect($request->redirectTo);
    }

    public function delete(Request $request) {
      Notes::where("id", $request->noteId)->delete();
      return redirect($request->redirectTo);
    }

    public function resolve(Request $request) {
      $note = Notes::where("id", $request->noteId)->get()->first();
      $note->resolved = true;
      $note->save();
      return redirect($request->redirectTo);
    }

}
