<?php


namespace App\Etsy\Models;

class Receipt extends EtsyModel {

  public $id;
  public $orderId;
  public $formattedAddress;
  public $messageFromBuyer;
  public $wasPaid;
  public $wasShipped;
  public $transactions;
  public $listings;
  public $grandTotal;

  public $hideFromView;

  public function __construct() {
    parent::__construct();
  }

  public function fetchAllReceipts($page = 1, $wasPaid = 1, $wasShipped = 0, $limit = 50) {
    $shopId = auth()->user()->etsyAuth->shopId;
    $endpoint = "shops/".$shopId."/receipts?includes=Transactions,Listings";
    $response = $this->etsyApi->callOAuth($endpoint, ["limit" => $limit, "page" => $page, "was_paid" => $wasPaid, "was_shipped" => $wasShipped], OAUTH_HTTP_METHOD_GET);
    $receiptsArray = $response["results"];
    $receipts = [];
    foreach($receiptsArray as $r) {
      $r = (object) $r;
      $receipt = new Receipt();
      $receipt->id = $r->receipt_id;
      $receipt->orderId = $r->order_id;
      $receipt->formattedAddress = $r->formatted_address;
      $receipt->messageFromBuyer = $r->message_from_buyer;
      $receipt->wasPaid = $r->was_paid;
      $receipt->wasShipped = $r->was_shipped;
      $receipt->grandTotal = $r->grandtotal;
      $receipt->transactions = TransactionsCollection::createInnerArray($r->Transactions);
      $receipt->listings = ListingCollection::createInnerArray($r->Listings);
      $receipts[] = $receipt;
    }
    $collection = collect($receipts);
    $receiptsCollection = new ReceiptsCollection();
    $receiptsCollection->receipts = $collection;
    $receiptsCollection->count = $response["count"];
    return $receiptsCollection;
  }

}
