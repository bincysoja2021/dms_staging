<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Edit User :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Edit User</h2>
    <div class="tag-block">
      <form method="POST" action="{{ url('update_user_submit') }}">
        <input type="hidden" name="id" value="{{$data->id}}">
        @csrf   
        <!-- @if ($errors->any())
            @foreach ($errors->all() as $error)
              <div class="alert alert-danger">{{$error}}</div>
            @endforeach
          @endif  -->
        <table class="table table-striped">        
          <!-- <tr>
            <td>Username<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <input type="text" class="form-control" name="user_name" value="{{$data->user_name}}" required >
            </td>
          </tr> -->
          <tr>
            <td>User email<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$data->email}}"  required>
              @error('email')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror  
            </td>
          </tr>
          <tr>
            <td>User Full name<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{$data->full_name}}" required>
              @error('full_name')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror  
            </td>
          </tr>
          <tr>
            <td>Employee ID<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <input type="text" class="form-control" name="employee_id" value="{{$data->employee_id}}"   readonly>
            </td>
          </tr>
            <tr>
              <td>User type<span class="text-danger">*</td>
              <td width="10">:</td>
              <td>
                @if(Session::get('user_role')=="Super admin" || Session::get('user_role')=="Manager")
                <select class="form-control" name="user_type"  disabled>
                  <option value="Manager" @if($data->user_type== "Manager") echo selected @else "" @endif>Manager</option>
                  <option value="Super admin" @if($data->user_type== "Super admin") echo selected @else "" @endif>Super Admin</option>
                </select>   
                @endif         
              </td>
            </tr>
         
          <tr>
            <td>User created on<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <input type="text" class="form-control" name="user_registerd_date" value="{{ \Carbon\Carbon::parse($data->user_registerd_date)->format('j-m-Y') }}" disabled>
            </td>
          </tr>
          <tr>
            <td>User active status</td>
            <td width="10">:</td>
            <td>
              <input type="text" class="form-control" name="status" value="@if($data->active_status == 1)Active @else In-active @endif" disabled>
            </td>
          </tr>
          <tr>
            <td>User last login</td>
            <td width="10">:</td>
            <td>
              <input type="text" class="form-control" name="last_login_time" value="{{$data->last_login_time}}" disabled>
            </td>
          </tr>
          @if(Auth::user()->user_type=="Manager")
          <tr>
            <td>Reset password</td>
            <td width="10">:</td>
            <td>
              <a href="{{ url('reset_user_password') }}">Reset user password</a>
            </td>
          </tr>
          @endif
          <tr>
            <td>Office<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <select class="form-control" name="office" required>   
                <option>Select</option>           
                <option value="GTN Textiles" @if($data->office== "GTN Textiles") echo selected @else "" @endif>GTN Textiles</option>              
              </select>
            </td>
          </tr>
          <tr>
            <td>Department/Section<span class="text-danger">*</td>
            <td width="10">:</td>
            <td>
              <select class="form-control" name="department_section" required> 
                <option>Select</option>                  
                <option value="Sales" @if($data->department_section== "Sales") echo selected @else "" @endif>Sales</option>
                <option value="Finance" @if($data->department_section== "Finance") echo selected @else "" @endif>Finance</option>
              </select>
            </td>
          </tr>
        </table>
        <div class="btn-groups">
          <button type="submit"  class="btn btn-primary">Update</button>
          
        </div>
    </form>
    <br>
      @if(Auth::user()->user_type=="Super admin")
      <button value="{{$data->id}}" class="btn btn-warning user_deactivate" id="deactivate_user">De-activate User</button>
      @endif
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e){
  $("#deactivate_user").click(function ()
  { 
    var id = $("#deactivate_user").val(); 
    swal({
      title: 'Are you sure?',
      text: "Are you sure you want to deactivate this user?",
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
              url:'{{url("/user_deactivate")}}/' +id,
              data:{
                  "_token": "{{ csrf_token() }}",
              },
              success:function(data) {
                console.log(data)
              swal({

              title: "Success!",

              text: "User has been deactivated!..",

              icon: "success",

              });
              window.location.href="{{url("all_users")}}";
              }
           });
    }
    });
  }); 
}); 
</script>
@include("admin.include.footer")
