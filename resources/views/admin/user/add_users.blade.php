<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>All Users :: DMS</title>
@include("admin.include.header")

<div class="main-content">
@include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Add User</h2>  
    <div class="dash-all pt-0">
      <div class="dash-table-all" style="max-width:700px;">  
       <form method="POST" action="{{ url('add_user_submit') }}">
        @csrf   
       <!--  @if ($errors->any())
            @foreach ($errors->all() as $error)
              <div class="alert alert-danger">{{$error}}</div>
            @endforeach
          @endif  -->  
        <table class="table table-striped">        
        <tr>
          <td>User Email ID<span style="color: red;">*</span></td>
          <td width="10">:</td>
          <td>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="off" value="{{ old('email') }}">
            @error('email')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror  
          </td>
        </tr>
        <!-- <tr>
          <td>Employee ID<span style="color: red;">*</span></td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" value="" required autocomplete="off">
            @error('employee_id')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror  
          </td>
        </tr> -->
        <tr>
          <td>Username<span style="color: red;">*</span></td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="" required autocomplete="off" value="{{ old('user_name') }}">
            @error('user_name')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror  
          </td>
        </tr>
        <tr>
          <td>User Type<span style="color: red;">*</span></td>
          <td width="10">:</td>
          <td>
            <select class="form-control @error('user_type') is-invalid @enderror" name="user_type" required>
              <option>Select</option>
              <option value="Manager">Manager</option>
              <option value="Super Admin">Super Admin</option>
            </select> 
            @error('user_type')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror 
          </td>
        </tr>
        <tr>
          <td>Office</td>
          <td width="10">:</td>
          <td>
            <select class="form-control @error('office') is-invalid @enderror" name="office" required>
              <option>Select</option>
              <option value="GTN Textiles">GTN Textiles</option>              
            </select>
            @error('office')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror 
          </td>
        </tr>
        <tr>
          <td>Department/Section</td>
          <td width="10">:</td>
          <td>
            <select class="form-control @error('department_section') is-invalid @enderror" name="department_section" required>
              <option>Select</option>
              <option value="Sales">Sales</option>
              <option value="Finance">Finance</option>
            </select>
            @error('department_section')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror 
          </td>
        </tr>
      </table>
      <button class="btn btn-primary">Add User</button>
    </form>
      </div>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-danger">Are you Sure, you want to delete the user?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
        Once you delete the user, you will no longer be able to access the user data. Click "Yes" to proceed or else click "Cancel".
      </div>
      
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  </div>
</div>

@include("admin.include.footer")
