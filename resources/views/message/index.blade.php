@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Message Templates</div>

                <div class="panel-body">

                    <div class="row">
                      <div class="col-md-12">
                        >>> <a href="/messages/new">NEW MESSAGE</a>
                      </div>
                    </div>
                    @foreach($messages as $message)
                      <div class="row">
                        <div class="col-md-12">
                          <a href="/messages/{{$message->id}}">{{$message->name}}</a>
                        </div>
                      </div>
                    @endforeach
                  </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
