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
      <table class="table table-striped">        
        <tr>
          <td>Username</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="scandan" disabled>
          </td>
        </tr>
        <tr>
          <td>User email</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="scandan@gtntextiles.com" disabled>
          </td>
        </tr>
        <tr>
          <td>User Full name</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="Scandan Full name">
          </td>
        </tr>
        <tr>
          <td>Employee ID</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="GTN152890" disabled>
          </td>
        </tr>
        <tr>
          <td>User type</td>
          <td width="10">:</td>
          <td>
            <select class="form-control">
              <option>Manager</option>
              <option>Super Admin</option>
            </select>            
          </td>
        </tr>
        <tr>
          <td>User created on</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="05-08-2023" disabled>
          </td>
        </tr>
        <tr>
          <td>User active status</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="Active" disabled>
          </td>
        </tr>
        <tr>
          <td>User las login</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="01-02-2024, 02:19:45 PM" disabled>
          </td>
        </tr>
        <tr>
          <td>Reset password</td>
          <td width="10">:</td>
          <td>
            <a href="#">Reset user password</a>
          </td>
        </tr>
        <tr>
          <td>Office</td>
          <td width="10">:</td>
          <td>
            <select class="form-control">              
              <option>GTN Textiles</option>              
            </select>
          </td>
        </tr>
        <tr>
          <td>Department/Section</td>
          <td width="10">:</td>
          <td>
            <select class="form-control">              
              <option>Sales</option>
              <option>Finance</option>
            </select>
          </td>
        </tr>
      </table>
      <div class="btn-groups">        
        <a href="#" class="btn btn-warning">De-activate User</a>
        <a href="{{ url('/view_user')}}" class="btn btn-primary">Save</a>
      </div>
    </div>
  </div>
</div>

@include("admin.include.footer")
