@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Receipts</div>

                <div class="panel-body">

                  <div class="row">
                    <span class="col-md-12">
                      There are {{$collection->count}} {{$label}}
                    </span>
                  </div>
                  <div class="row">
                    &nbsp;
                  </div>
                  @if($showAlerts != "true")
                    <div class="row">

                      <form action="/receipts" method="get">
                        {{csrf_field()}}
                        <input type="hidden" name="page" value="{{$page}}">
                        <div class="col-md-3">
                          <select class="form-control" name="show">
                            <option value="all">all</option>
                            <option value="not_processed" <?php if($show == "not_processed") echo "selected";?>>not processed</option>
                            <option value="not_shipped" <?php if($show == "not_shipped") echo "selected";?>>processed but not shipped</option>
                          </select>
                        </div>
                        <div class="col-md-9">
                          <button class="btn">update view</button>
                        </div>
                      </form>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <a href="/receipts?showAlerts=true">VIEW ALERTS</a>
                      </div>
                    </div>
                  @else
                    <div class="row">
                      <div class="col-md-12">
                        <a href="/receipts">VIEW ALL RECEIPTS</a>
                      </div>
                    </div>
                  @endif
                  <div class="row">&nbsp;</div>

                  <div class="row">
                    <div class="col-md-4"><strong>SHIP TO</strong></div>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-5"><strong>PRODUCT</strong></div>
                        <div class="col-md-2"><strong>QNTY</strong></div>
                        <div class="col-md-5"><strong>OPTIONS</strong></div>
                      </div>
                    </div>
                  </div>

                  @foreach($collection->getDisplayReceipts() as $receipt)
                    <?php
                      $nameParts = explode(" ", $receipt->name);
                      $lastName = array_pop($nameParts);
                      $firstName = implode(" ", $nameParts);
                      $addressParts = explode("\n", $receipt->formattedAddress);
                      $country = array_pop($addressParts);
                      $weekAgo = new \DateTime("now");
                      $week = new \DateInterval("P7D");
                      $weekAgo->sub($week);
                      $tenAgo = new \DateTime("now");
                      $ten = new \DateInterval("P10D");
                      $tenAgo->sub($ten);
                      $fortAgo = new \DateTime("now");
                      $fort = new \DateInterval("P14D");
                      $fortAgo->sub($fort);
                    ?>
                      <div class="well row">
                        <a id="{{$receipt->id}}"></a>
                        <div class="col-md-12">

                          @if($receipt->purchaseDate <= $fortAgo->getTimestamp())
                          <div class="alert alert-danger">More Than 14 Days</div>
                          @elseif($receipt->purchaseDate <= $tenAgo->getTimestamp())
                          <div class="alert alert-warning">More Than 10 Days</div>
                          @elseif($receipt->purchaseDate <= $weekAgo->getTimestamp())
                          <div class="alert alert-warning">More Than 7 Days</div>
                          @endif

                          <div class="row">
                            <div class="col-md-3">
                              <span>Order # {{$receipt->id}}</span>
                            </div>
                            <div class="col-md-9">
                              <a href="https://www.etsy.com/your/orders/{{$receipt->id}}">see on Etsy (send convo to buyer)</a>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <pre>{{$receipt->formattedAddress}}</pre>
                              @if($receipt->processed)
                                <a href="https://www.gearbubble.com/dropship_users/dashboard?name={{$lastName}}" target="new">look up on gearbubble</a>
                              @endif
                            </div>
                            <div class="col-md-8">
                              <?php for($i = 0; $i < count($receipt->transactions); $i++) {
                                $transaction = $receipt->transactions[$i];
                                $listing = $receipt->listings[$i];
                                ?>
                                <div class="row">
                                  <div class="col-md-5">
                                    {{$transaction->title}} <a href="{{$listing->getEtsyLink()}}">(link)</a><br>
                                    <img src="{{$listing->mainImageUrl}}">
                                  </div>
                                  <div class="col-md-2">{{$transaction->quantity}}</div>
                                  <div class="col-md-5">
                                    @foreach($transaction->variations as $variation)
                                      <div class="row">
                                        {{$variation->formattedName}}: {{$variation->formattedValue}}
                                      </div>
                                    @endforeach
                                  </div>
                                </div>
                                <div class="row">&nbsp;</div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              Paid: {{$receipt->grandTotal}}<br>
                              Shipping: {{$receipt->totalShippingCost}}
                            </div>
                            <div class="col-md-8">
                              <?php
                                $cDate = new \Carbon\Carbon("@".$receipt->purchaseDate);
                                $displayDate = $cDate->toDayDateTimeString();
                              ?>
                              Purchased On: {{$displayDate}} UTC
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              @if(!$receipt->processed)
                                <form action="/order-processed" method="post">
                                  {{csrf_field()}}
                                  <input type="hidden" name="receiptId" value="{{$receipt->id}}">
                                  <input type="hidden" name="redirectTo" value="/receipts?page={{$page}}&show={{$show}}#{{$receipt->id}}">
                                  <button class="btn btn-primary">
                                    MARK AS PROCESSED
                                  </button>
                                </form>
                                <!--a href="javascript:fillFormOnOrderWindow()">fill out order form</a-->
                              @else
                                <form action="/receipt/ship" method="post">
                                  {{csrf_field()}}
                                  <input type="hidden" name="receiptId" value="{{$receipt->id}}">
                                  <input type="hidden" name="receiptTitle" value="{{implode(",", $receipt->getListingsTitles())}}">
                                  <input type="hidden" name="receiptAddress" value="{{$receipt->formattedAddress}}">
                                  <input type="hidden" name="redirectTo" value="/receipts?page={{$page}}&show={{$show}}<?php if(isset($previousAnchor)) echo '#'.$previousAnchor; ?>">
                                  <button class="btn btn-success">
                                    MARK AS SHIPPED WITH TRACKING NUMBER
                                  </button>
                                </form>

                              @endif
                              @if(!$receipt->processed)
                                @include("receipts.partials.addressToCopy")
                              @endif
                            </div>
                          </div>

                          @if(isset($receipt->messageFromBuyer) && $receipt->messageFromBuyer != "")
                          <div class="row alert alert-info">
                            <div class="col-md-12">
                              <label>MESSAGE FROM BUYER</label><br>
                              {{$receipt->messageFromBuyer}}
                            </div>
                          </div>
                          @endif


                          <div class="row">
                            <div class="col-md-12">
                              <form action="/receipt/note" method="get">
                                {{csrf_field()}}
                                <input type="hidden" name="receiptId" value="{{$receipt->id}}">
                                <input type="hidden" name="redirectTo" value="/receipts?page={{$page}}&show={{$show}}<?php if(isset($previousAnchor)) echo '#'.$previousAnchor; ?>">
                                <button class="btn btn-default">
                                  ADD NOTE
                                </button>
                              </form>
                            </div>
                          </div>

                          @foreach($receipt->getNotes() as $note)
                            <div class="row alert @if($note->requiresResolution && !$note->resolved) alert-warning @else alert-info @endif">
                              <div class="col-md-1">
                                <form action="/receipt/note/delete" method="get">
                                  <input type="hidden" name="noteId" value="{{$note->id}}">
                                  <input type="hidden" name="redirectTo" value="/receipts?page={{$page}}&show={{$show}}<?php if(isset($previousAnchor)) echo '#'.$previousAnchor; ?>">
                                  <button class="btn btn-danger">
                                    x
                                  </button>
                                </form>
                              </div>

                              <div class="col-md-10">
                                {!!$note->note!!}
                              </div>

                              <div class="col-md-1">
                                @if($note->requiresResolution && !$note->resolved)
                                  <form action="/receipt/note/resolve" method="get">
                                    <input type="hidden" name="noteId" value="{{$note->id}}">
                                    <input type="hidden" name="redirectTo" value="/receipts?page={{$page}}&show={{$show}}<?php if(isset($previousAnchor)) echo '#'.$previousAnchor; ?>">
                                    <button class="btn btn-success">
                                      rslv
                                    </button>
                                  </form>
                                @endif
                              </div>

                            </div>
                          @endforeach

                        </div>
                      </div>
                      <div class="row">&nbsp;</div>
                    <?php $previousAnchor = $receipt->id; // This is to redirect to the previous anchor after marking as shipped. ?>
                  @endforeach
                  @if($page > 1)
                    <form action="/receipts" method="get">
                      {{csrf_field()}}
                      <input type="hidden" name="page" value="{{$page-1}}">
                      <input type="hidden" name="show" value="{{$show}}">
                      <div class="col-md-9">
                        <button class="btn">&#60;&#60; Previous</button>
                      </div>
                    </form>
                  @endif
                  @if($areMorePages)
                    <form action="/receipts" method="get">
                      {{csrf_field()}}
                      <input type="hidden" name="page" value="{{$page+1}}">
                      <input type="hidden" name="show" value="{{$show}}">
                      <div class="col-md-9">
                        <button class="btn">Next &#62;&#62;</button>
                      </div>
                    </form>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  var currentOrderWindow;

  function openNewOrderWindow() {
    var url = "https://www.gearbubble.com/dropship_order/select_campaign?mode=individual"
    currentOrderWindow = window.open(url, "newOrderWindow");
  }

  // Doesn't work because not same domain
  function fillFormOnOrderWindow() {
    var doc = currentOrderWindow.document;
    var firstName = doc.getElementById("dropship_order_first_name");
    console.log(firstName);
  }

  var copyIndicator;

  function copyToClipboard(input, indicatorId) {
    input.select();
    document.execCommand("copy");
    copyIndicator = document.getElementById(indicatorId);
    copyIndicator.style.visibility = "visible";
    setTimeout(hideIndicator, 2000)
  }

  function hideIndicator() {
    copyIndicator.style.visibility = "hidden";
  }

</script>
@endsection
