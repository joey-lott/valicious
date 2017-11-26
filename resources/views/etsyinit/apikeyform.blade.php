@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Set Etsy API Credentials</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/api-key">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="key" class="col-md-4 control-label">Keyphrase</label>

                            <div class="col-md-6">
                                <input id="key" type="text" class="form-control" name="key" value="{{ old('secret') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="secret" class="col-md-4 control-label">Secret</label>

                            <div class="col-md-6">
                                <input id="secret" type="text" class="form-control" name="secret" value="{{ old('secret') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Set API Credentials
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                      <span class="col-sm-12"><a href="https://www.etsy.com/developers/your-apps">find them here</a>
                    </div>
                    <div class="row">
                      <span class="col-sm-12"><a href="https://www.etsy.com/developers/register">or create a new Etsy app here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
