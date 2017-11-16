<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrdersProcessed;

class OrdersProcessedController extends Controller
{

    public function markAsProcessed(Request $request) {
      $op = new OrdersProcessed();
      $op->receiptId = $request->receiptId;
      $op->save();
      return redirect($request->redirectTo);
    }
}
