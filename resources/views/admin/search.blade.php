<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<style type="text/css">
  .fa-trash {
    color: white; /* Change to your desired color */
}
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<title>Normal search :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Normal search</h2>
<!--     @include("admin.include.search") --->
<div class="search-box">
  <div class="input-group row">
    <div class="col-md-9">
      <input type="text" placeholder="" class="form-control" name="searchval" id="searchval" required="">
      <label>(Search using Invoice numbers, Sales order numbers, shipping bill numbers, client name, ect.)</label>

    </div>
    <div class="col-md-3">
      <input type="submit" class="btn btn-primary" value="Search" name="Search" id="Search" class="Searchclass">
      <input type="submit" value="Clear" class="btn btn-dark" name="Clear" id="Clear">
      <label class="search-label"><a href="{{url('/advanced_search')}}">Advanced Search</a></label>
    </div>
  </div>
</div>


  <div class="dash-all">
    <div class="dash-table-all">
      <h4 class="sub-heading">Search Results</h4>
      
      <table class="table table-striped doc-datatable" id="doc-datatable">
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

      <table class="table table-striped ajax_doc-datatable" id="ajax_doc-datatable" style="display: none">
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
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
  @if(session()->has('message'))
      swal({

          title: "Success!",

          text: "{{ session()->get('message') }}",

          icon: "success",

      });
  @endif
  </script>
  <script>
    var loadImagesRoute = "{{ route('load_images','') }}";
    var loadpdf = "{{ route('download.pdf','') }}";
</script>

<script type="text/javascript">
$("#Search").click(function(e){
  e.preventDefault();
  var submitButton = document.getElementById("Search");
  submitButton.disabled = true;

  var form = $('#searchval').val();
  $.ajax({
    url: '{{ url("/normal_ajax_search") }}',
    type: 'GET',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data : {
    '_token': "{{ csrf_token() }}",
    'form':form
    },

    success: function(response) {
      if(response=='')
      {
          $("#doc-datatable").hide();
          $("#ajax_doc-datatable").show();
          $('#ajax_doc-datatable').append('<tr><td>No data</td><td>No data</td><td>No data</td><td>No data</td><td>No data</td><td>No data</td></tr>');
      }
      else
      {
          $.each(response, function(index, value) {
          $("#doc-datatable").hide();
          $("#ajax_doc-datatable").show();
          var imageURL = loadImagesRoute + '/' + value.thumbnail;
          var loadpdfURL = loadpdf + '/' + value.filename;
          $('#ajax_doc-datatable').append('<tr><td>' + value.id + '</td><td>' + value.doc_id + '</td><td>' + value.document_type + '</td><td>' + value.date + '</td><td><img src="' + imageURL + '" width="100px" height="100px" ></td><td><a href="'+ loadpdfURL +'"><i class="fa fa-download" aria-hidden="true"></i></a></td></tr>');
          }); 

      }
    },
    error: function(xhr, status, error) {
    }
  });
});

</script>

<script type="text/javascript">
 $('#select-all').on('change', function() {
        $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });
     
  $(function () {
    var table = $('.doc-datatable').DataTable
    ({
        processing: true,
        serverSide: true,
        ajax: "{{ route('normal_search') }}",
        columns: [
            {data: 'id', name: 'id', render: function (data, type, row, meta) {
                    return meta.row + 1; // meta.row is zero-based index
                }},
            {data: 'doc_id', name: 'doc_id'},
            {data: 'document_type', name: 'document_type'},
            {data: 'date', name: 'date'},
            {data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
  });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script type="text/javascript">
  function delete_doc_modal(id)
  {
    var id = id; 
    swal({
      title: 'Are you sure?',
      text: "Are you sure you want to delete this document?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      confirmButtonText: 'Yes, delete it!',
      buttonsStyling: false
    }).then((isConfirm) => {
    if (isConfirm){
       $.ajax({
              type:'GET',
              url:'{{url("/delete_docs")}}/' +id,
              data:{
                  "_token": "{{ csrf_token() }}",
              },
              success:function(data) {
              swal({

              title: "Success!",

              text: "Document has been deleted!..",

              icon: "success",

              });
              setTimeout(function()
              {
                window.location.href="{{url("all_document")}}";
              }, 2000);
              }
           });
    }
    });
  }
  $('#delete-selected').on('click', function() {
        var ids = $('input[name="item_checkbox[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: '{{url("/delete_multi_docs")}}',
            method: 'POST',
            data: { 
                    ids: ids,
                    _token: "{{ csrf_token() }}",
                  },
            success: function(response) {
              swal({

              title: "Success!",

              text: "Selected documents has been deleted!..",

              icon: "success",

              });
             setTimeout(function()
              {
                window.location.href="{{url("all_document")}}";
              }, 2000);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
</script>

<script type="text/javascript">
  $("#Clear").click(function(e){

   window.location.reload();
});
</script>
@include("admin.include.footer")
