@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add a Note</div>

                <div class="panel-body">
                  <div class="form-group row">
                    <form method="post" action="/receipt/note/submit" class="col-md-12">
                      {{csrf_field()}}
                      <input type="hidden" name="receiptId" value="{{$receiptId}}">
                      <input type="hidden" name="redirectTo" value="{{$redirectTo}}">
                      <div class="row form-group">
                        <label class="col-md-2">Note</label>
                        <div class="col-md-10">
                          <textarea id="note" name="note" rows="20"></textarea>
                        </div>
                      </div>

                      <div class="row form-group">
                        <label class="col-md-2">Requires Resolution</label>
                        <div class="col-md-10">
                          <input type="checkbox" name="requiresResolution">
                        </div>
                      </div>

                      <div class="row form-group">
                        <div class="col-md-12">
                          <button class="btn btn-primary">ADD NOTE</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>

<script>
  ClassicEditor
        .create( document.querySelector( '#note' ) )
        .catch( error => {
            console.error( error );
        } );
</script>


@endsection
