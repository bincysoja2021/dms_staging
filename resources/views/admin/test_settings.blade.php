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
    <h2 class="main-heading">Test Settings</h2>
    <h4 class="sub-heading">Profile</h4>
    <a href= "{{url('/getDownload')}}">downlod</a><br><br><br><br>
    <iframe id="ifrm" src="{{ asset ('tested_failed_document_reupload/Thermax Code of Conduct Alt_04.pdf') }}" width="100%" height="600" width="50px" height="50px"   frameborder="0"disable></iframe>

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
