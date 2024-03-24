<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>View User :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">View User</h2>
    <div class="tag-block">
      <table class="table table-striped">        
        <!-- <tr>
          <td>Username</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{$data->full_name}}" disabled>
          </td>
        </tr> -->
        <tr>
          <td>User email</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{$data->email}}" disabled>
          </td>
        </tr>
        <tr>
          <td>User Full name</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{$data->full_name}}" disabled>
          </td>
        </tr>
        <tr>
          <td>Employee ID</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{$data->employee_id}}" disabled>
          </td>
        </tr>
        <tr>
          <td>User type</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{$data->user_type}}" disabled>
          </td>
        </tr>
        <tr>
          <td>User created on</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{ \Carbon\Carbon::parse($data->user_registerd_date)->format('j-m-Y') }}" disabled>
          </td>
        </tr>
        <tr>
          <td>User active status</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="@if($data->active_status == 1)Active @else In-active @endif" disabled>
          </td>
        </tr>
        <tr>
          <td>User last login</td>
          <td width="10">:</td>
          <td>
            <input type="text" class="form-control" name="" value="{{$data->last_login_time}}" disabled>
          </td>
        </tr>        
        <tr>
          <td>Office</td>
          <td width="10">:</td>
          <td>
            @if($data->office!=null)
            <select class="form-control" disabled>              
              <option>{{$data->office}}</option>              
            </select>
            @else
            <select class="form-control" disabled>              
              <option>Select</option>
            </select>
            @endif
          </td>
        </tr>
        <tr>
          <td>Department/Section</td>
          <td width="10">:</td>
          <td>
            @if($data->department_section!=null)
            <select class="form-control" disabled>              
              <option>{{$data->department_section}}</option>
            </select>
            @else
            <select class="form-control" disabled>              
              <option>Select</option>
            </select>
            @endif
          </td>
        </tr>
      </table>
      <div class="btn-groups">
        <a href="{{url('/edit_users/'.$data->id)}}" class="btn btn-info">Edit</a>        
      </div>
    </div>
  </div>
</div>

@include("admin.include/footer")
