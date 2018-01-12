@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Message Templates</div>

                <div class="panel-body">
                  <form method="post" action="/messages/save">
                    {{csrf_field()}}
                    <input type="hidden" name="messageId" value="{{$message->id}}">

                    <div class="row">
                      <div class="col-md-3">
                        <label>Name</label>
                      </div>
                      <div class="col-md-9">
                        <input type="text" name="name" class="form-control" value="{{$message->name}}">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3">
                        <label>Message</label>
                      </div>
                      <div class="col-md-9">
                        <textarea name="message" class="form-control" rows="10">{{$message->message}}</textarea>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3">
                      </div>
                      <div class="col-md-9">
                        <button class="form-control btn btn-primary">SAVE</button>
                      </div>
                    </div>

                  </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
