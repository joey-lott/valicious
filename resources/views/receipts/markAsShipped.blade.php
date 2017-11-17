@extends('layouts.app')

@section('content')

<script>

  function guessCarrier(input) {
    var carrier = document.getElementById("carrier");
    if(input.value.substring(0,2) == "GM") {
      carrier.value = "DHL Global Mail";
    }
    else if(input.value.substring(0,1) == "7") {
      carrier.value = "FedEx";
    }
    else if(input.value.substring(0,1) == "9") {
      carrier.value = "USPS";
    }

  }

</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Mark As Shipped</div>

                <div class="panel-body">
                  <div class="form-group row">
                    <form method="post" action="/receipt/shipped" class="col-md-12">
                      {{csrf_field()}}
                      <input type="hidden" name="receiptId" value="{{$receiptId}}">
                      <input type="hidden" name="redirectTo" value="{{$redirectTo}}">
                      <div class="row form-group">
                        <label class="col-md-2">Tracking Number</label>
                        <div class="col-md-10">
                          <input type="text" name="trackingNumber" class="form-control" onkeyup="guessCarrier(this)">
                        </div>
                      </div>
                      <div class="row form-group">
                        <label class="col-md-2">Carrier</label>
                        <div class="col-md-10">
                          <select name="carrier" class="form-control" id="carrier">
                            <option>USPS</option>
                            <option>FedEx</option>
                            <option>DHL Global Mail</option>
                          </select>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-md-12">
                          <button class="btn btn-primary">MARK AS SHIPPED</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="well">
                    <div class="row form-group">
                      <div class="col-md-12">
                        You are marking this order as shipped:
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-md-12">
                        Title: {{$receiptTitle}}
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-md-12">
                        <pre>{{$address}}</pre>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
