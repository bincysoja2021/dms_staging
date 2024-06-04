<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<!-- styles and scripts -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<style type="text/css">
  
body
{
  background-color:#FAFAFA;
}

.buttonbox
{
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%)
}
.spinner-button 
{
  border: 2px solid #000;
  display: inline-block;
  padding: 8px 20px 9px;
  font-size: 12px;
  color: #000;
  background-color: transparent
}

.btn-primary:disabled 
{
  color: #fff;
  background-color: #000;
  border-color: #000;
}



.spinner-button:hover 
{
  background-color: #000;
  border: 2px solid #000;
  color: #fff 
}

.spinner-button i 
{
  color: #fff
} 

.spinner-button:hover i 
{
  color: #fff
}

.fa
{
  color:#fff;
}

.fa:hover
{
  color:#fff;
}

</style>
<title>Excel Import :: DMS</title>
@include("admin.include.header")

<div class="main-content">
 @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Excel Import</h2>  
    <div class="dash-all pt-0">
      <div class="dash-table-all"><div class="dash-all pt-0">
      <div class="dash-table-all" style="max-width:700px;">  
      
        <table class="table table-striped">        
        
        
        <tr>
          <td>Choose File<span style="color: red;">*</span></td>
          <td width="10">:</td>
          <td>
          <form id="importForm" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" required>
                <button type="submit" class="btn btn-primary spinner-button mb-2" id="btnFetch">Import</button>
            </form>
            @error('uploadfile')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror  
          </td>
        </tr>
        
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

<script type="text/javascript">
$(document).ready(function() 
{
  $("#importForm").submit(function(event) 
  {
    if ($('input[type=file]').val() === '') 
    {
      event.preventDefault();
      alert('Please select a file.');
    } 
    else 
    {
      $("#btnFetch").prop("disabled", true);
      $("#btnFetch").html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');
    }

  });
});
</script>
@include("admin.include.footer")
