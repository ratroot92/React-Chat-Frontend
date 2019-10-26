<html>
<head>
	<title>Chameleon Admin</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="{{asset('/store_admin/login/bootstrap/css/style.css')}}">
	<link href="{{asset('/store_admin/login/bootstrap/css/bootstrap.min.css')}}" type="text/css" rel="stylesheet">
</head>
<body class="bg-pink">

	
  
  	<div class="container  bg-white  mar-top" style="border-radius: 20px;">
  <form method="post" action="{{url('/admin/login')}}" class="sign-box">
      {{csrf_field()}}
  <div class="row pt-5 pb-3">
    <div class="col-md-4  mx-auto">
      <div>
    <img src="{{asset('/store_admin/images/logo.png')}}"  style="border-radius: 50%;">
  </div>
    </div>
    <div class="col-md-4"></div>
  </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required>
    </div>
    <div class="form-group form-check">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Keep me signed in
      </label>
      <a href="" style="" class="text-allign">Reset Password</a>
    </div><br>
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4"><button type="submit" class="btn btn-primary wid text-center">Login</button></div>
     <div class="col-md-4"></div>
    </div>
 </form>
  </div>
  


</body>
</html>