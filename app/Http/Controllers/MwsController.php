<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiKeys;
use App\EtsyAuth;
use App\MwsCredentials;
use Sonnenglas\AmazonMws\AmazonOrderList;
use Sonnenglas\AmazonMws\AmazonOrderItemList;

class MwsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct() {
       $this->middleware('auth');
       $this->middleware('subscribed');
     }

    /**+
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMwsForm()
    {
      return view("mws.mwsForm");
    }

    public function submit(Request $request) {
      $mws = new MwsCredentials();
      $mws->user_id = auth()->user()->id;
      $mws->marketplace = $request->marketplaceId;
      $mws->merchant_id = $request->merchantId;
      $mws->key = $request->key;
      $mws->secret = $request->secret;
      $mws->service_url = $request->serviceUrl;
      $mws->save();
      return redirect("/home");
    }

    public function showMwsIndex() {
       $amz = new AmazonOrderList("realpeoplegoods");
        $amz->setLimits('Modified', "- 30 days");
        //$amz->setFulfillmentChannelFilter("MFN");
        $amz->setOrderStatusFilter(
            array("Unshipped", "PartiallyShipped")
            ); 
        $amz->setUseToken(); //tells the object to automatically use tokens right away
        $amz->fetchOrders(); //this is what actually sends the request
        $orders = $amz->getList();

        foreach ($orders as $order) {

          // send orders to view. show orders at order level. must click through to see
          // products ordered. Have option on order view that displays products to flag it as
          // not needing manual fulfillment. Then save that status to the DB and don't show
          // orders flagged as such in the future.

          // This is a way to reduce overhead of having to call fetchItems() too often.
          

        //these are AmazonOrder objects
        // echo '<b>Order Number:</b> '.$order->getAmazonOrderId();
        // echo '<br><b>Purchase Date:</b> '.$order->getPurchaseDate();
        // echo '<br><b>Status:</b> '.$order->getOrderStatus();
        // echo '<br><b>Customer:</b> '.$order->getBuyerName();
        // $address=$order->getShippingAddress(); //address is an array
        // echo '<br><b>City:</b> '.$address['City'];

          $items = new AmazonOrderItemList("realpeoplegoods");
          $items->setOrderId($order->getAmazonOrderId());
          $items->fetchItems();
          $fetched = $items->getItems();
          dd($fetched);

        echo '<br><br>';
    }
    }
}
