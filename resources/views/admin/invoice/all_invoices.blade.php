<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

<title>All Invoices :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">All Invoices</h2>    
    <div class="dash-all pt-0">
      <div class="dash-table-all">        
        
        <table class="table table-striped allinvoice-datatable" id="invoice-datatable">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all">&nbsp&nbsp&nbsp
                <button class="btn btn-primary" id="delete-selected">Delete</button></th>
                <th>Sl.</th>
                <th>Document ID</th>
                <th>Document Type</th>
                <th>Uploaded Date</th>
                <th width="10%">Thumbnail</th>  
               <!--  <th>Tags</th>
                <th>Thumbnail</th> -->
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
           
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="pdfinvoiceModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-danger">Thumbnail preview image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
          <img src="" id="pdfPreview"  width="100%" height="300px">
      </div>
            
    </div>
  </div>
</div>
<script type="text/javascript">
// Handle click event on the file link
$('document').ready(function() {
  $('#invoice-datatable').on('click', '.view_image', function(e)
  {
    e.preventDefault();
    var imageUrl = $(this).data('image');
    $('#pdfPreview').attr('src', imageUrl);
    $('#pdfinvoiceModal').modal('show');
  });
});
</script>

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
<script type="text/javascript">
 $('#select-all').on('change', function() {
        $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });
     
  $(function () {
    var table = $('.allinvoice-datatable').DataTable
    ({
        processing: true,
        serverSide: true,
        ajax: "{{ route('get_allinvoice_list.list') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
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
  function delete_invoice_modal(id)
  {
    var id = id; 
    swal({
      title: 'Are you sure?',
      text: "Are you sure you want to delete this invoice documents?",
      icon: 'warning',
      buttons: true,
      dangerMode:true
    }).then((isConfirm) => {
    if (isConfirm){
       $.ajax({
              type:'GET',
              url:'{{url("/delete_invoice")}}/' +id,
              data:{
                  "_token": "{{ csrf_token() }}",
              },
              success:function(data) {
              swal({

              title: "Success!",

              text: "Invoice has been deleted!..",

              icon: "success",

              });
              setTimeout(function()
              {
                window.location.href="{{url("all_invoices")}}";
              }, 2000);
              }
           });
    }
    })
    .then((willCancel) => {
      if (willCancel){
        window.location.href="{{url("all_invoices")}}";
      }

    });
  }
  $('#delete-selected').on('click', function() {
        var ids = $('input[name="item_checkbox[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: '{{url("/delete_multi_invoice")}}',
            method: 'POST',
            data: { 
                    ids: ids,
                    _token: "{{ csrf_token() }}",
                  },
            success: function(response) {
              swal({

              title: "Success!",

              text: "Selected invoice  documents has been deleted!..",

              icon: "success",

              });
             setTimeout(function()
              {
                window.location.href="{{url("all_invoices")}}";
              }, 2000);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
</script>
@include("admin.include.footer")
