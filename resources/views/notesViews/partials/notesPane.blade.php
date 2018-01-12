@foreach($notes as $note)
  <div class="row alert @if($note->requiresResolution && !$note->resolved) alert-warning @else alert-info @endif">
    <div class="col-md-1">
      <form action="/receipt/note/delete" method="get">
        <input type="hidden" name="noteId" value="{{$note->id}}">
        <input type="hidden" name="redirectTo" value="{{$redirectTo}}">
        <button class="btn btn-danger">
          x
        </button>
      </form>
    </div>

    <div class="col-md-10">
      {!!$note->note!!}
    </div>

    <div class="col-md-1">
      @if($note->requiresResolution && !$note->resolved)
        <form action="/receipt/note/resolve" method="get">
          <input type="hidden" name="noteId" value="{{$note->id}}">
          <input type="hidden" name="redirectTo" value="{{$redirectTo}}">
          <button class="btn btn-success">
            rslv
          </button>
        </form>
      @endif
    </div>

  </div>
@endforeach
