@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Follow-Ups</div>

                <div class="panel-body">

                  <div class="row">
                    <div class="col-md-12">
                      <a href="https://www.etsy.com/your/orders/{{$receiptId}}">#{{$receiptId}}</a>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <form method="post" action="/followups/delete" class="col-md-3">
                        {{csrf_field()}}
                        <input type="hidden" name="followupId" value="{{$followupId}}">
                        <button class="form-control btn btn-default">CLOSE</button>
                      </form>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <select id="messageName" class="form-control" onchange="onSelectMessage(this)">
                        @foreach($messages as $message)
                          <option value="{{$message->message}}">{{$message->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <textarea id="message" class="form-control" rows="20">@if(count($messages) > 0){{$messages[0]->message}}@endif</textarea>
                    </div>
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
  function onSelectMessage(select) {
    display = document.getElementById("message");
    display.value = select.value;
  }
</script>
@endsection
