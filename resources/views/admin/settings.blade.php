<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Settings :: DMS</title>
@include("admin.include.header")

<div class="main-content">
  @include("admin.include.menu_left")
  <div class="main-area">
    <h2 class="main-heading">Settings</h2>
    <h4 class="sub-heading">Profile</h4>
    <div class="tag-block">
      <table class="table table-striped">
        <tr>
          <td>Full name</td>
          <td>: {{ucfirst(Auth::user()->full_name)}}</td>
        </tr>
        <tr>
          <td>User type</td>
          <td>: {{Auth::user()->user_type}}</td>
        </tr>
        <tr>
          <td>User Email</td>
          <td>: {{ Auth::user()->email }}</td>
        </tr>
        @if(Auth::user()->user_type=="Manager")
          <tr>
            <td>Password reset</td>
            <td>: <a href="{{ url('reset_user_password') }}">click here to reset password</a></td>
          </tr>
        @endif
        <tr>
          <td>User registered on</td>
          <td>: {{ Auth::user()->created_at }}</td>
        </tr>
      </table>
      <a href="{{ url('/edit_profile/'.Auth::user()->id)}}" class="btn btn-info">Edit Profile</a>
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

@include("admin.include.footer")
