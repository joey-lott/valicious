<div class="row">
  <div class="col-md-12">
    <a href="javascript:openNewOrderWindow()">place order on gearbubble</a>
    <br>
  </div>
</div>
<div class="row">
  <form>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-4">
          <label style="font-size: 11px">First Name</label>
        </div>
        <div class="col-md-8 input-group">
          <input type="text" value="{{$firstName}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-4">
          <label style="font-size: 11px">Last Name</label>
        </div>
        <div class="col-md-8 input-group">
          <input type="text" value="{{$lastName}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-4">
          <label style="font-size: 11px">Address Line 1</label>
        </div>
        <div class="col-md-8 input-group">
          <input type="text" value="{{$receipt->firstLine}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-4">
          <label style="font-size: 11px">Address Line 2</label>
        </div>
        <div class="col-md-8 input-group">
          <input type="text" value="{{$receipt->secondLine}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12">
          <label style="font-size: 11px">City</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <input type="text" value="{{$receipt->city}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12">
          <label style="font-size: 11px">Postal Code</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <input type="text" value="{{$receipt->zip}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12">
          <label style="font-size: 11px">County</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <input type="text" value="{{$country}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this)">
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12">
          <label style="font-size: 11px">State</label>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <input type="text" value="{{$receipt->state}}" class="form-control" style="height: 20px" readonly onclick="copyToClipboard(this, 'copied-{{$receipt->id}}')">
        </div>
      </div>
    </div>
  </form>
  <br>
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-success" id="copied-{{$receipt->id}}" style="visibility: hidden">value copied to clipboard</div>
    </div>
  </div>
  <br>
</div>
