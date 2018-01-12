<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etsy\Models\Receipt;
use App\OrdersProcessed;
use App\FollowUp;

class ReceiptsController extends Controller
{

  public function __construct() {
    $this->middleware('auth');
    $this->middleware('subscribed');
  }

    public function index(Request $request) {
      $page = isset($request->page) ? $request->page : 1;
      $receiptsPerPage = isset($request->receiptsPerPage) ? $request->receiptsPerPage : 50;
      $max = null;
      $min = null;
      $label = "unshipped orders";

      if($request->showAlerts == "true") {
        $weekAgo = new \DateTime("now");
        $yearAgo = new \DateTime("now");
        $week = new \DateInterval("P7D");
        $year = new \DateInterval("P1Y");
        $weekAgo->sub($week);
        $yearAgo->sub($year);
        $min = $yearAgo->getTimestamp();
        $max = $weekAgo->getTimestamp();
        $label = "alerts";
      }

//      dump($receipt->fetchAllReceipts(, $yearAgo->getTimestamp(), $weekAgo->getTimestamp()));


      $receipt = new Receipt();
      $receipts = $receipt->fetchAllReceipts($page, 1, 0, $receiptsPerPage, $min, $max);
      $receipts = $this->filterReceiptsAlreadyProcessed($receipts);
      $receipts->setFilter($request->show);
//      $receipts->setSearch($request->search);
      if($request->showAlerts == "true") {
        $receipts->order("asc");
      }
      $areMorePages = $receipts->count > ($page * $receiptsPerPage);
      return view("receipts.index", ["collection" => $receipts, "page" => $page, "receiptsPerPage" => $receiptsPerPage, "areMorePages" => $areMorePages, "show" => $request->show, "label" => $label, "showAlerts" => $request->showAlerts]);
    }

    public function markAsShippedForm(Request $request) {
      return view("receipts.markAsShipped", ["redirectTo" => $request->redirectTo, "receiptId" => $request->receiptId, "receiptTitle" => $request->receiptTitle, "address" => $request->receiptAddress]);
    }

    public function markAsShippedSubmit(Request $request) {
      $this->validate($request, ["trackingNumber" => "required"]);
      $receipt = new Receipt();
      $receipt->id = $request->receiptId;
      $receipt->markAsShipped($request->trackingNumber, $request->carrier);

      $followup = new FollowUp();
      $followup->receiptId = $receipt->id;
      $followup->userId = auth()->user()->id;
      $followup->dateShipped = new \DateTime();
      $followup->save();

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
