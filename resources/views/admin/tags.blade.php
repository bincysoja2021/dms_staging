<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<style type="text/css">
  .fa-trash {
    color: #007bff; /* Change to your desired color */
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<title>Tags :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
<div class="main-area">
    <h2 class="main-heading">Add Tags</h2>
    <div class="adv-search">
      @if($data==null)
      <form method="POST" action="{{ url('/submit_tags')}}">
        @csrf
      <div class="row mb-3">
        <div class="col-md-3">
          <label>Tag name</label>
          <input type="text" class="form-control" name="tag_name" id="tag_name" autocomplete="off" placeholder="Enter a tag name" required="" autocomplete="off">
        </div>
        
        <div class="col-md-3">
          <input type="submit" value="Add" class="btn btn-primary btn-md btn-search" name="Search" id="Search">
          <input type="submit" value="Clear" class="btn btn-dark btn-md btn-search" name="Clear" id="Clear">
        </div>
      </div>
    </form>
    @else
      <form method="POST" action="{{ url('/submit_tags')}}">
        @csrf
      <div class="row mb-3">
        <div class="col-md-3">
          <label>Tag name</label>
          <input type="hidden" name="id" value="{{$data->id}}">
          <input type="text" class="form-control" name="tag_name" id="tag_name" autocomplete="off" placeholder="Enter a tag name" required="" autocomplete="off" value="{{$data->tag_name}}">
        </div>
        
        <div class="col-md-3">
          <input type="submit" value="Update" class="btn btn-primary btn-md btn-search" name="Search" id="Search">
          <input type="submit" value="Clear" class="btn btn-dark btn-md btn-search" name="Clear" id="Clear">
        </div>
      </div>
    </form>
    @endif
    </div>  
    <table class="table table-striped ajax_advanced_tag-datatable" id="ajax_advanced_tag-datatable" style="display: none">
      <thead>
        <tr>
        <th width="15%">Sl.No</th>
        <th width="15%">Document ID</th>
        <th width="15%">Document Type</th>
        <th width="15%">Uploaded Date</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <h2 class="main-heading">Tags</h2>
    <div class="tag-block">
      <table class="table table-striped">
        <thead>
        <tr>
        <th width="15%"></th>
        <th width="15%">Tag name</th>
        <th width="15%">Actions</th>
        </tr>
      </thead>
      <tbody>
       @foreach($tag_data as $key=>$val) 
        <tr>
          <td width="20"><input type="checkbox" class="checkbox" value="{{$val->id}}" id="searchTags" class="searchTags"></td>          
          <td>{{ucfirst($val->tag_name)}}</td>
          <td><a href="{{ url('/edit_tags/'.$val->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
          <a   onclick="delete_tag_modal({{$val->id}})" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>              
        </tr>
        @endforeach  
        {{$tag_data->links("pagination::bootstrap-4")}}
        </tbody>

      </table>
    </div>
 



  </div>
 
</div>

<script type="text/javascript">
$("#Clear").click(function(e){
  e.preventDefault();
  $('#tag_name').val('');
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
  function delete_tag_modal(id)
  {
    var id = id; 
    swal({
      title: 'Are you sure?',
      text: "Are you sure you want to delete this tag?",
      icon: 'warning',
      buttons: true,
      dangerMode:true
    }).then((isConfirm) => {
    if (isConfirm){
       $.ajax({
              type:'GET',
              url:'{{url("/delete_tags")}}/' +id,
              data:{
                  "_token": "{{ csrf_token() }}",
              },
              success:function(data) {
              swal({

              title: "Success!",

              text: "Tag has been deleted!..",

              icon: "success",

              });
              setTimeout(function()
              {
                window.location.href="{{url("tags")}}";
              }, 2000);
              }
           });
    }
    })
    .then((willCancel) => {
      if (willCancel){
        window.location.href="{{url("tags")}}";
      }

    });
  }
</script>
<script type="text/javascript">
$(document).ready(function() {
$(document).on('click', '#searchTags', function() {
    if(this.checked)
    {
      var id = $(this).val(); //-->this will alert id of checked checkbox.
      $.ajax({
      url: '{{ url("/tags_search") }}',
      type: 'GET',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data : {
      '_token': "{{ csrf_token() }}",
      'id':id
      },

      success: function(response) 
      {
        if(response=='')
        {
          console.log("response null=>")
          $("#ajax_advanced_tag-datatable").show();
          $('#ajax_advanced_tag-datatable').append('<tr><td>No data</td><td>No data</td><td>No data</td><td>No data</td></tr>');
        }
        else
        {
          $.each(response, function(index, value) {
          $("#ajax_advanced_tag-datatable").show();
          $('#ajax_advanced_tag-datatable').append('<tr><td>'+ value.id+'</td><td>' + value.doc_id + '</td><td>' + value.doc_id + '</td><td>' + value.document_type + '</td></tr>');
          }); 
        }
      },
      error: function(xhr, status, error) 
      {
      }
      });

    }
    else
    {
      $("#ajax_advanced_tag-datatable").hide();
      window.location.reload();
    }
});
});
</script>
@include("admin.include/footer")
