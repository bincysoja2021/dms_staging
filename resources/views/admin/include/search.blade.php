<div class="search-box">
  <div class="input-group row">
    <div class="col-md-9">
      <input type="text" placeholder="" class="form-control" name="searchval" id="searchval" required="">
      <label>(Search using Invoice numbers, Sales order numbers, shipping bill numbers, client name, ect.)</label>
      <input type="submit" value="Clear" class="btn btn-dark btn-md btn-search" name="Clear" id="Clear">

    </div>
    <div class="col-md-3">
      <input type="submit" class="btn btn-primary" value="Search" name="Search" id="Search">
      <label class="search-label"><a href="{{url('/advanced_search')}}">Advanced Search</a></label>
    </div>
  </div>
</div>


<script type="text/javascript">
  $("#Clear").click(function(e){

   window.location.reload();
});
</script>

