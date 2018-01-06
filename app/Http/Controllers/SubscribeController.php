<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\AppSecrets;

class SubscribeController extends Controller
{

    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('not.subscribed')->only(["subscribe", "showPaymentForm"]);
        $this->middleware('subscribed')->except(["subscribe", "showPaymentForm"]);
    }

    public function showPaymentForm() {
      $stripeKey = AppSecrets::get("STRIPE_KEY");
      $stripeErrorMessage = null;
      if(session()->has("stripeError")) {
        $message = session()->get("stripeError");
        $stripeErrorMessage = $message;
      }
      return view("subscribe.subscribeForm", ["stripeError" => $stripeErrorMessage, "stripeKey" => $stripeKey]);
    }

    public function subscribe(Request $request) {
      $subscriptionType = "valicious-basic-monthly";
      $stripeToken = $request->stripeToken;
      $user = auth()->user();
      $subscriptionRequest = $user->newSubscription("basic-access", $subscriptionType)->trialDays(7);
      if($request->coupon != "" && $request->coupon != null) {
        $subscriptionRequest = $subscriptionRequest->withCoupon($request->coupon);
      }
      // this is the link to get information about the version of cashier I am using to support Stripe Connect
https://github.com/laravel/cashier/issues/406
      try {
        $response = $subscriptionRequest->create($stripeToken, ["email" => $user->email]);
      }
      catch(\Exception $ir) {
        return redirect()->back()->with("stripeError", $ir->getMessage());
      }
      return redirect("/account/welcome");
    }

    public function cancel(Request $request) {
      $response = $request->user()->getCurrentSubscription()->cancel();
      return view("account.cancelled", ["endsAt" => Carbon::parse($response->ends_at)]);
    }

    public function resume(Request $request) {
      $subscription = $request->user()->getCurrentSubscription();
      if(isset($subscription)) {
        $response = $subscription->resume();
        return redirect("/account");
      }
    }

    public function welcome() {
      return view("account.welcome");
    }
}
