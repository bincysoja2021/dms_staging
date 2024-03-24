<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Reset Password :: GTN Textiles</title>
<link rel="icon" href="favicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

<!-- Bootstrap core CSS -->
<link href="{{ asset ('css/bootstrap.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset ('font-awesome/css/font-awesome.min.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

<!-- Custom styles for this template -->
<link href="{{ asset ('css/style.css') }}" rel="stylesheet">
<style type="text/css">
  .reset_password{
  position: relative;
}
.eye_show {
  z-index: 9999;
  position: absolute;
  top: 68%;
  right: 10px;
}
</style>
</head>

<body>

<div class="login-cover">
  <img src="{{ asset ('images/logo.svg') }}" class="login-logo">
  <div class="form-wrap">
  <div class="login-box">
    <h3>DMS - Reset Password</h3>
    <div class="login-in">
      <form method="POST" action="{{ route('reset_password_submit') }}">
        @csrf    
        
        <div class="reset_password">
          <label>Enter new password</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your username" name="password" autocomplete="off" required id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters">
          
          <span toggle="#password_show" class="fa fa-fw fa-eye field_icon toggle_reset_password eye_show"></span>
          @error('password')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
          <label>Re-enter new password</label>
          <input type="password" class="form-control @error('password-confirm') is-invalid @enderror" placeholder="Enter your password" name="password-confirm" autocomplete="off" required id="password-confirm" title="Re-enter Password Same as the Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}">
          @error('password-confirm')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
          @enderror
        <input type="submit" class="btn btn-primary btn-login"> 
      </form>     
    </div>
  </div>
  <!--- add validation ---->
      <div id="message">
          <h3>Password must contain the following:</h3>
          <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
          <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
          <p id="number" class="invalid">A <b>number</b></p>
          <p id="length" class="invalid">Minimum <b>8 characters</b></p>
          <p id="repswd" class="invalid">Re-enter Password is valid</b>
          </p>
      </div>
      </div>
  <h6>
    <i class="fa fa-copyright" aria-hidden="true"></i> 2024-25 GTN Enterprises.    
  </h6>
  <h6>
    GTN-DMS2024.V.0.1 by Exacore
  </h6>
  <h6>
    DMS2024 App Info <i class="fa fa-info-circle" aria-hidden="true"></i>
  </h6>  
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript">
  $(document).on('click', '.toggle_reset_password', function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $("#password");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});
</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="{{ asset ('js/bootstrap.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
  @if(session()->has('message'))
      swal({
  
          title: "Success!",
  
          text: "{{ session()->get('message') }}",
  
          icon: "success",
  
      });
  @endif
  
  @if(session()->has('error'))
      swal({
  
          title: "Error!",
  
          text: "Old Password Mismatch!",
  
          icon: "error",
  
      });
  @endif
  
  function CheckOldPassword(){
    var x = document.getElementById("old_pswd").value;
    $.ajax({
      type: "GET",
      headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
      url: '/check-current-password',
      data: { "_token": "{{ csrf_token() }}",
        old_pswd: x,
      },
      success: function(newData) {
        if(newData.success == 1){  
          swal({
  
            title: "Error!",
  
            text: newData.message,
  
            icon: "error",
  
          });
          document.getElementById("old_pswd").value = null;
        } 
      },
              
    });
  }
  
  var myInput = document.getElementById("password");
  var reInput = document.getElementById("password-confirm");
  var letter = document.getElementById("letter");
  var capital = document.getElementById("capital");
  var number = document.getElementById("number");
  var length = document.getElementById("length");
  
  myInput.onfocus = function() {
    document.getElementById("message").style.display = "block";
  }
  
  myInput.onkeyup = function() {
    // Validate lowercase letters
    var lowerCaseLetters = /[a-z]/g;
    if(myInput.value.match(lowerCaseLetters)) {  
      letter.classList.remove("invalid");
      letter.classList.add("valid");
    } else {
      letter.classList.remove("valid");
      letter.classList.add("invalid");
    }
    
    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if(myInput.value.match(upperCaseLetters)) {  
      capital.classList.remove("invalid");
      capital.classList.add("valid");
    } else {
      capital.classList.remove("valid");
      capital.classList.add("invalid");
    }
  
    // Validate numbers
    var numbers = /[0-9]/g;
    if(myInput.value.match(numbers)) {  
      number.classList.remove("invalid");
      number.classList.add("valid");
    } else {
      number.classList.remove("valid");
      number.classList.add("invalid");
    }
    
    // Validate length
    if(myInput.value.length >= 8) {
      length.classList.remove("invalid");
      length.classList.add("valid");
    } else {
      length.classList.remove("valid");
      length.classList.add("invalid");
    }
    // Validate match
        if(myInput.value  == reInput.value) {
          repswd.classList.remove("invalid");
          repswd.classList.add("valid");
        } else {
          repswd.classList.remove("valid");
          repswd.classList.add("invalid");
        }
    
  }
  
  reInput.onkeyup = function() {
      // Validate match
        if(myInput.value  == reInput.value) {
          repswd.classList.remove("invalid");
          repswd.classList.add("valid");
        } else {
          repswd.classList.remove("valid");
          repswd.classList.add("invalid");
        }
  }
</script>
<style type="text/css">
    input[type=submit] {
      background-color: #04AA6D;
      color: white;
    }
    #message {
      display:none;
      background: #f1f1f1;
      color: #000;
      position: relative;
      padding: 20px;
      margin-left: 10px;
    }
    
    #message p {
      padding: 10px;
      font-size: 14px;
    }
    
    .valid {
      color: green;
    }
    
    .valid:before {
      position: relative;
      left: -10px;
      content: "✔";
    }
    
    .invalid {
      color: red;
    }
    
    .invalid:before {
      position: relative;
      left: -10px;
      content: "✖";
    }
</style>
</style>
</body>
</html>
