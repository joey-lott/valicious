@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="/receipts">Your Receipts</a><br><br>
                    <hr>
                    <u>test features</u><br>
                    <a href="/followups">Your Follow-ups</a><br><br>
                    <a href="/messages">Your Message Templates</a><br><br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
