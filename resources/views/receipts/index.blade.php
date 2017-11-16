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
                    <div class="col-md-4"><strong>SHIP TO</strong></div>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-5">PRODUCT</div>
                        <div class="col-md-2">QUANTITY</div>
                        <div class="col-md-5">OPTIONS</div>
                      </div>
                    </div>
                  </div>
                  @foreach($collection->receipts as $receipt)
                    @if(!$receipt->hideFromView)
                      <div class="row" style="border:1px solid #CCCCCC; padding: 5px">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4"><pre>{{$receipt->formattedAddress}}</pre></div>
                            <div class="col-md-8">
                              @foreach($receipt->transactions as $transaction)
                                <div class="row">
                                  <div class="col-md-5">{{$transaction->title}}</div>
                                  <div class="col-md-2">{{$transaction->quantity}}</div>
                                  <div class="col-md-5">
                                    @foreach($transaction->variations as $variation)
                                      <div class="row">
                                        {{$variation->formattedName}}: {{$variation->formattedValue}}
                                      </div>
                                    @endforeach
                                  </div>
                                </div>
                              @endforeach
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <form action="/order-processed" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="receiptId" value="{{$receipt->id}}">
                                <input type="hidden" name="redirectTo" value="/receipts">
                                <button class="btn btn-primary">
                                  mark as processed
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                  @if($page > 1)
                  <a href="/receipts?page={{$page-1}}" class="btn">Previous Page of Results</a>
                  @endif
                  @if($areMorePages)
                  <a href="/receipts?page={{$page+1}}" class="btn">Next Page of Results</a>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
