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
      <form method="POST" action="{{ url('update_profile') }}">
        @csrf
         <!--  @if ($errors->any())
            @foreach ($errors->all() as $error)
              <div class="alert alert-danger">{{$error}}</div>
            @endforeach
          @endif -->
         

        <input type="hidden" name="id" value="{{$user->id}}"}>
        <table class="table table-striped">
          <tr>
            <td>Full name<span class="text-danger">*</span></td>
            <td><input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{$user->full_name}}" required>
            @error('full_name')
              <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
              </span>
            @enderror 
           </td>
          </tr>
          <tr>
            <td>User type<span class="text-danger">*</span></td>
            <td>
               @if(Session::get('user_role')=="Super admin" || Session::get('user_role')=="Manager")
              <select class="form-control" name="user_type" disabled>
              <option>Select</option>
              <option value="Super admin" @if($user->user_type=="Super admin") selected @else "" @endif>Super Admin</option>
              <option value="Manager" @if($user->user_type=="Manager") selected @else "" @endif>Manager</option>
            </select>
            @endif
          </td>
          </tr>
          <tr>
            <td>User Email<span class="text-danger">*</td>
            <td> <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required>
             @error('email')
              <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
              </span>
            @enderror 
          </td>
          </tr>
          @if(Auth::user()->user_type=="Manager")
            <tr>
              <td>Password reset</td>
              <td> <a href="{{ url('reset_user_password') }}">click here to reset password</a></td>
            </tr>
          @endif
          <tr>
            <td>User registered on</td>
            <td><input type="text" class="form-control" name="created_at" value="{{$user->created_at}}" readonly></td>
          </tr>
        </table>
        <button class="btn btn-primary">Update Profile</button>
    </form>
    </div>
  </div>
</div>
@include("admin.include.footer")
