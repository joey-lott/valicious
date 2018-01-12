@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Follow-Ups</div>

                <div class="panel-body">

                  @foreach($followups as $followup)
                  <div class="well">
                    <div class="row">
                      <div class="col-md-5">
                        Receipt <a href="https://www.etsy.com/your/orders/{{$followup->receiptId}}">#{{$followup->receiptId}}</a>
                        <br>
                        Date shipped: {{$followup->dateShipped}}
                      </div>
                      <form method="post" action="/followups/process" class="col-md-3">
                        {{csrf_field()}}
                        <input type="hidden" name="receiptId" value="{{$followup->receiptId}}">
                        <input type="hidden" name="followupId" value="{{$followup->id}}">
                        <button class="form-control btn btn-default">PROCESS</button>
                      </form>
                      <div class="col-md-4">
                        &nbsp;
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-10 offset-md-2">
                        <div class="alert alert-information">
                          <a href="/receipt/note?receiptId={{$followup->receiptId}}&redirectTo=/followups">NEW NOTE</a>
                        </div>
                      </div>
                    </div>

                    @php $redirectTo = "/followups"; @endphp
                    @include("notesViews.partials.notesPane", ["notes" => $followup->getNotes(), "redirectTo" => $redirectTo])
                  </div>
                  @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
