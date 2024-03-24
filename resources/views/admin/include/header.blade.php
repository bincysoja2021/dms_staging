<link rel="icon" href="favicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<!-- Bootstrap core CSS -->
<link href="{{ asset ('css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset ('font-awesome/css/font-awesome.min.css') }}">
<link href="{{ asset ('css/jquery.fancybox.css') }}" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="{{ asset ('css/style.css') }}" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
  <a class="navbar-brand" href="{{ url('/home') }}">
    <img src="{{ asset ('images/logo.svg') }}">
  </a>
  <!--
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  -->
  <div class="date mr-auto">
    <?php echo date('d-m-Y')?>,<?php echo date('l')?>
  </div>
  <div class="account-sec ml-auto">
    <a href="{{ url ('notification') }}">
      <img src="{{ asset ('images/notification-small-icon-black.svg') }}">
    </a>
    <span class="dropdown">      
      <a class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ asset ('images/user-image.png') }}"> <span>{{ Auth::user()->full_name }}</span>
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ url ('/view_users/'.Auth::user()->id)}}">User Profile</a>
        <a class="dropdown-item" href="{{ url ('/settings')}}">Settings</a>
        <a class="dropdown-item" href="{{ url('user_logout') }}">Logout</a>
      </div>
    </span>
  </div>
</nav>