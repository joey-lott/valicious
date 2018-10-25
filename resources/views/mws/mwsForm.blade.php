@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Set MWS Credentials</div>

                <div class="panel-body">

                  <form action="/mws" method="post">
                    {{csrf_field()}}
                    merchant id <input type="text" name="merchantId"><br>
                    marketplace id <input type="text" name="marketplaceId" value="ATVPDKIKX0DER"><br>
                    key <input type="text" name="key"><br>
                    secret <input type="text" name="secret"><br>
                    service URL <input type="text" name="serviceUrl" value="https://mws.amazonservices.com/"><br>
                    <button>SUBMIT</button>
                  </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
