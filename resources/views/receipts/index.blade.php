@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Receipts</div>

                <div class="panel-body">

                  <div class="row">
                    There are {{$collection->count}} unshipped orders
                  </div>
                  <div class="row">
                    &nbsp;
                  </div>

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
                    <div class="col-md-4"><strong>SHIP TO</strong></div>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-5"><strong>PRODUCT</strong></div>
                        <div class="col-md-2"><strong>QNTY</strong></div>
                        <div class="col-md-5"><strong>OPTIONS</strong></div>
                      </div>
                    </div>
                  </div>
                  @foreach($collection->receipts as $receipt)
                    @if((!$receipt->processed && $show == "not_processed") || ($show == "all" || !isset($show)) || ($show == "not_shipped" && $receipt->processed))
                      <div class="well row">
                        <a id="{{$receipt->id}}"></a>
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <pre>{{$receipt->formattedAddress}}</pre><br>
                              <?php
                                $nameParts = explode(" ", $receipt->name);
                                $lastName = array_pop($nameParts);
                              ?>
                              <a href="https://www.gearbubble.com/dropship_users/dashboard?name={{$lastName}}" target="new">look up on gearbubble</a>
                            </div>
                            <div class="col-md-8">
                              <?php for($i = 0; $i < count($receipt->transactions); $i++) {
                                $transaction = $receipt->transactions[$i];
                                $listing = $receipt->listings[$i];
                                ?>
                                <div class="row">
                                  <div class="col-md-5">{{$transaction->title}} <a href="{{$listing->getEtsyLink()}}">(link)</a></div>
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
                              Paid: {{$receipt->grandTotal}}
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
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">&nbsp;</div>
                    @endif
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
@endsection
