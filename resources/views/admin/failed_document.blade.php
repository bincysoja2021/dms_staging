<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.313/pdf.min.js"></script>

<title>Upload Failed Docmuments :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Upload Failed Docmuments</h2>
     <input type = "hidden" name = "_token" value = '<?php echo csrf_token(); ?>'> 
    <div class="dash-all pt-0">
      <div class="dash-table-all">        
        
        <table class="table table-striped failed_doc-datatable">
          <thead>
            <tr>
              <th width="8%"><input type="checkbox" id="select-all">&nbsp&nbsp&nbsp
              <button class="btn btn-primary" id="delete-selected">Delete</button></th>
              <th width="5%">Sl.</th>
              <th width="8%">Document ID</th>
              <th width="8%">Document Type</th>
              <th width="8%">Uploaded Date</th>
              <th width="10%">Thumbnail</th>
              <th width="6%">Status</th>
              <!-- <th>Thumbnail</th> --> 
              <th width="10%">Action</th>
           </tr>
          </thead>
          <tbody>
           
          </tbody>
        </table>

        <div id="thumbnailContainer"></div> 
        
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function renderPDF(data,fileName)
{
 return pdfjsLib.getDocument(data).promise.then(function(pdf)
 {
      return pdf.getPage(1).then(function(page)
      {
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        
        var viewport = page.getViewport({scale: 1.0});
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        var renderContext = {
            canvasContext: context,
            viewport: viewport
        };
          
        return page.render(renderContext).promise.then(function() {
            var thumbnail = document.createElement('img');
            thumbnail.src = canvas.toDataURL();
            thumbnail.width = 100; // Adjust thumbnail size as needed
            thumbnail.height = thumbnail.width * (viewport.height / viewport.width);
            
            $('#thumbnailContainer').empty().append(thumbnail);
          function dataURItoBlob(dataURI){
            var binary=atob(dataURI.split(',')[1]);
            var array=[];
            for(i=0;i<binary.length;i++){
            array.push(binary.charCodeAt(i));
            }
            return new Blob([new Uint8Array(array)],{type:'image/png'});
          }
          let blob=(dataURItoBlob(canvas.toDataURL()));
          fileData=new File([blob],`${fileName}.png`,{type:"image/png",lastModified:new Date().getTime()});
          return fileData
          });
      });
 });
}
</script>
<script type="text/javascript">

$(document).on('change', '#image', function() 
{
  let formdata = new FormData();
  var  image = document.getElementById("image").value;
  const fileInput = document.getElementById('image');
  const fileList = fileInput.files;
  const file = fileList[0];
  const failedDocId = $(this).siblings('.failed_doc_id').val(); // Get the corresponding document ID
  formdata.append('id', failedDocId);
  formdata.append('document_file', file);
  var allowedExtensions = /(\.pdf)$/i;
  if(!allowedExtensions.exec(image))
  {
    swal({
    title: "Error!",
    text: "Please upload file having extensions .pdf only.",
    icon: "error",
    });
  }
  else
  {
    const fsize = file.size;
    const filesize = Math.round((fsize / 1024));
    if (filesize >= 2048)
    {
      swal({
      title: "Error!",
      text: "File too Big, please select a file less than 2mb.",
      icon: "error",
      }); 
    }
    else if(filesize < 15)
    {
      swal({
      title: "Error!",
      text: "File too small, please select a file greater than 15kb.",
      icon: "error",
      });
    }
    else
    {
        var fileReader = new FileReader();
        fileReader.onload = async function(){
        var typedarray = new Uint8Array(this.result);
        myfiledata = await renderPDF(typedarray,(file.name).split('.').slice(0, -1).join('.'));
        formdata.append('thumbnail', myfiledata);

        $.ajax({
          url: '{{url("/failed_document_re_upload_docs")}}',
          type: 'POST',
          data: formdata,
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          contentType: false,
          processData: false,
          success: function(response) {
            if(response.success == 1)
            {  
              window.location.href="{{url("schedule_document")}}/" +failedDocId;
            }
          },
          error: function(xhr, status, error) {
          // Handle error
          }
        });

      };

      fileReader.readAsArrayBuffer(file);
    }
  }
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
    var table = $('.failed_doc-datatable').DataTable
    ({
        processing: true,
        serverSide: true,
        ajax: "{{ route('get_failed_doc_list.list') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            {data: 'id', name: 'id', render: function (data, type, row, meta) {
                    return meta.row + 1; // meta.row is zero-based index
                }},
            {data: 'doc_id', name: 'doc_id'},
            {data: 'document_type', name: 'document_type'},
            {data: 'date', name: 'date'},
            {data: 'thumbnail',name:'thumbnail', orderable: false, searchable: false},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
         columnDefs: [
        {
          "render": function(data, type, row) {
              if (row.status=="Failed") {
                  return '<span class="text-danger">Failed</span>';
              }
           }, "targets": [6]
        }]
    });
  });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script type="text/javascript">
  function delete_faileddoc_modal(id)
  {
    var id = id; 
    swal({
      title: 'Are you sure?',
      text: "Are you sure you want to delete this  document?",
      icon: 'warning',
      buttons: true,
      dangerMode:true
    }).then((isConfirm) => {
    if (isConfirm){
       $.ajax({
              type:'GET',
              url:'{{url("/delete_failed_docs")}}/' +id,
              data:{
                  "_token": "{{ csrf_token() }}",
              },
              success:function(data) {
              swal({

              title: "Success!",

              text: "Failed document has been deleted!..",

              icon: "success",

              });
              setTimeout(function()
              {
                window.location.href="{{url("failed_document")}}";
              }, 2000);
              }
           });
    }
    })
    .then((willCancel) => {
      if (willCancel){
        window.location.href="{{url("failed_document")}}";
      }

    });
  }
  $('#delete-selected').on('click', function() {
        var ids = $('input[name="item_checkbox[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: '{{url("/delete_multi_failed_docs")}}',
            method: 'POST',
            data: { 
                    ids: ids,
                    _token: "{{ csrf_token() }}",
                  },
            success: function(response) {
              swal({

              title: "Success!",

              text: "Selected failed documents has been deleted!..",

              icon: "success",

              });
             setTimeout(function()
              {
                window.location.href="{{url("failed_document")}}";
              }, 2000);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
</script>

@include("admin.include.footer")
