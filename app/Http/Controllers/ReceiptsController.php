<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etsy\Models\Receipt;
use App\OrdersProcessed;

class ReceiptsController extends Controller
{

  public function __construct() {
    $this->middleware('auth');
    $this->middleware('subscribed');
  }

    public function index(Request $request) {
      $page = isset($request->page) ? $request->page : 1;
      $receiptsPerPage = isset($request->receiptsPerPage) ? $request->receiptsPerPage : 50;
      $receipt = new Receipt();
      $receipts = $receipt->fetchAllReceipts($page);
      $receipts = $this->filterReceiptsAlreadyProcessed($receipts);
      $areMorePages = $receipts->count > ($page * $receiptsPerPage);
      return view("receipts.index", ["collection" => $receipts, "page" => $page, "receiptsPerPage" => $receiptsPerPage, "areMorePages" => $areMorePages, "show" => $request->show]);
    }

    public function markAsShippedForm(Request $request) {
      return view("receipts.markAsShipped", ["redirectTo" => $request->redirectTo, "receiptId" => $request->receiptId, "receiptTitle" => $request->receiptTitle, "address" => $request->receiptAddress]);
    }

    public function markAsShippedSubmit(Request $request) {
      $this->validate($request, ["trackingNumber" => "required"]);
      $receipt = new Receipt();
      $receipt->id = $request->receiptId;
      $receipt->markAsShipped($request->trackingNumber, $request->carrier);
      return redirect($request->redirectTo);
    }

    private function filterReceiptsAlreadyProcessed($receipts) {
      foreach($receipts->receipts as $receipt) {
        $processed = OrdersProcessed::where("receiptId", $receipt->id)->get();
        $receipt->processed = count($processed) > 0;
      }
      return $receipts;
    }
}
