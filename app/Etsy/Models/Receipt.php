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
  public $name;
  public $purchaseDate;
  public $firstLine;
  public $secondLine;
  public $city;
  public $zip;
  public $state;
  public $processed;

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
      $receipt->name = $r->name;
      $receipt->firstLine = $r->first_line;
      $receipt->secondLine = $r->second_line;
      $receipt->city = $r->city;
      $receipt->state = $r->state;
      $receipt->zip = $r->zip;
      $receipt->purchaseDate = $r->creation_tsz;
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

  public function getListingsTitles() {
    $titles = [];
    foreach($this->listings as $listing) {
      $titles[] = $listing->title;
    }
    return $titles;
  }

  public function markAsShipped($trackingNumber, $carrier) {

    // First, updte the receipt. Set was_shipped to true.
    $endpoint = "receipts/".$this->id;
    $params = ["was_shipped" => "true"];
    $response = $this->etsyApi->callOAuth($endpoint, $params, OAUTH_HTTP_METHOD_PUT);

    // Next, set the tracking information
    $shopId = auth()->user()->etsyAuth->shopId;
    $endpoint = "shops/".$shopId."/receipts/".$this->id."/tracking";
    $params = ["tracking_code" => $trackingNumber, "carrier_name" => $carrier, "send_bcc" => "true"];
    $response = $this->etsyApi->callOAuth($endpoint, $params, OAUTH_HTTP_METHOD_POST);
  }

}
