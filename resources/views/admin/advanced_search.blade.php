<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- jQuery UI library -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<title>Advanced Search :: DMS</title>
@include("admin.include.header")

<div class="main-content">
@include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Advanced Search</h2>
    <div class="adv-search">
      <div class="row mb-3">
        <div class="col-md-3">
          <label>Invoice Number</label>
          <input type="text" class="form-control" name="invoice_number" id="invoice_number" autocomplete="off">
        </div>
        <div class="col-md-3">
          <label>Invoice Date</label>
          <input type="text" class="form-control" name="invoice_date" id="invoice_date_datepicker" autocomplete="off">
        </div>
        <div class="col-md-3">
          <label>Shipping Bill Number</label>
          <input type="text" class="form-control" name="shipping_bill_number" id="shipping_bill_number" autocomplete="off">
        </div>
        <div class="col-md-3">
          <label>Sales Order Number</label>
          <input type="text" class="form-control" name="sales_order_number" id="sales_order_number" autocomplete="off">
        </div>
      </div>
      <h5 class="sub-heading border-0">Document uploaded date range</h5>
      <div class="row">
        <div class="col-md-3">
          <label>From Date</label>
          <input type="text" class="form-control" name="form_date" id="datepicker" autocomplete="off">
        </div>
        <div class="col-md-3">
          <label>To Date</label>
          <input type="text" class="form-control" name="to_date" id="to_datepicker" autocomplete="off">
        </div>
        <div class="col-md-3">
          <input type="submit" value="Search" class="btn btn-primary btn-md btn-search" name="Search" id="Search">
          <input type="submit" value="Clear" class="btn btn-dark btn-md btn-search" name="Clear" id="Clear">
        </div>
      </div>
    </div>  
    <table class="table table-striped ajax_advanced_search-datatable" id="ajax_advanced_search-datatable" style="display: none">
      <thead>
        <tr>
        <th width="15%">Sl.</th>
        <th width="15%">Document ID</th>
        <th width="15%">Document Type</th>
        <th width="15%">Uploaded Date</th>
        <th width="10%">Thumbnail</th>
        <!--  <th>Tags</th>
        <th>Thumbnail</th> -->
        <th width="25%">Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

  </div>
</div>



 <script>
    var loadImagesRoute = "{{ route('load_images','') }}";
    var loadpdf = "{{ route('download.pdf','') }}";
</script>
<script type="text/javascript">
$("#Clear").click(function(e){
  e.preventDefault();
  $('#invoice_number').val('');
  $('#invoice_date_datepicker').val('');
  $('#shipping_bill_number').val('');
  $('#sales_order_number').val('');
  $('#datepicker').val('');
  $('#to_datepicker').val('');
});
</script>
<script type="text/javascript">
$("#Search").click(function(e){
  e.preventDefault();
  var submitButton = document.getElementById("Search");
  submitButton.disabled = true;
  var invoice_number = $('#invoice_number').val();
  var invoice_date = $('#invoice_date_datepicker').val();
  var shipping_bill_number = $('#shipping_bill_number').val();
  var sales_order_number = $('#sales_order_number').val();
  var form_date = $('#datepicker').val();
  var to_date = $('#to_datepicker').val();
  console.log(invoice_number);
  console.log(invoice_date);
  console.log(shipping_bill_number);
  console.log(sales_order_number);
  console.log(form_date);
  $.ajax({
    url: '{{ url("/advanced_ajax_search") }}',
    type: 'GET',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data : {
    '_token': "{{ csrf_token() }}",
    'invoice_number':invoice_number,
    'invoice_date':invoice_date,
    'shipping_bill_number':shipping_bill_number,
    'sales_order_number':sales_order_number,
    'form_date':form_date,
    'to_date':to_date
    },

    success: function(response) {
      if(response=='')
      {
        console.log("response null=>")
          $("#ajax_advanced_search-datatable").show();
          $('#ajax_advanced_search-datatable').append('<tr><td>No data</td><td>No data</td><td>No data</td><td>No data</td><td>No data</td><td>No data</td></tr>');
      }
      else
      {
        console.log("response=>")
          $.each(response, function(index, value) {
          $("#ajax_advanced_search-datatable").show();
          var imageURL = loadImagesRoute + '/' + value.thumbnail;
          var loadpdfURL = loadpdf + '/' + value.filename;
          $('#ajax_advanced_search-datatable').append('<tr><td>' + value.id + '</td><td>' + value.doc_id + '</td><td>' + value.document_type + '</td><td>' + value.date + '</td><td><img src="' + imageURL + '" width="100px" height="100px" ></td><td><a href="'+ loadpdfURL +'"><i class="fa fa-download" aria-hidden="true"></i></a></td></tr>');
           }); 

      }
    },
    error: function(xhr, status, error) {
    }
  });
});

</script>
<script>
jQuery(document).ready(function($) {
    $( "#datepicker" ).datepicker();
    $( "#to_datepicker" ).datepicker();
    $( "#invoice_date_datepicker" ).datepicker();
  });
  </script>
@include("admin.include.footer")
