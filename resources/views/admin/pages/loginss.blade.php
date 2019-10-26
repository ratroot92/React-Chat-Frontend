
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Rown Admin Login</title>

    <link href="{{asset('/rownadmin/img/favicon.144x144.html')}}" rel="apple-touch-icon" type="image/png" sizes="144x144">
    <link href="{{asset('/rownadmin/img/favicon.114x114.html')}}" rel="apple-touch-icon" type="image/png" sizes="114x114">
    <link href="{{asset('/rownadmin/img/favicon.72x72.html')}}" rel="apple-touch-icon" type="image/png" sizes="72x72">
    <link href="{{asset('/rownadmin/img/favicon.57x57.html')}}" rel="apple-touch-icon" type="image/png">
    <link href="{{asset('/rownadmin/img/favicon.html')}}" rel="icon" type="image/png">
    <link href="{{asset('/rownadmin/img/favicon-2.html')}}" rel="shortcut icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{asset('/rownadmin/css/lib/font-awesome/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/rownadmin/css/main.css')}}">


<div class="page-center">
    <div class="page-center-in">
        <div class="container-fluid">
            <form id="sign_in" method="post" action="{{url('/admin/login')}}" class="sign-box">
                {{csrf_field()}}
                <div class="sign-avatar">
                    <img src="{{asset('/rownadmin/img/avatar-sign.png')}}" alt="">
                </div>
                <header class="sign-title">Sign In</header>
                <div class="form-group">
                    <input type="text" name="email" id="email" class="form-control" placeholder="E-Mail or Phone" required/>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required/>
                </div>
                <div class="form-group">
                    <div class="checkbox float-left">
                        <input type="checkbox" id="signed-in"/>
                        <label for="signed-in">Keep me signed in</label>
                    </div>
                    <div class="float-right reset">
                        <a href="reset-password.html">Reset Password</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-rounded">Sign in</button>
                <p class="sign-note">New to our website? <a href="sign-up.html">Sign up</a></p>
                <!--<button type="button" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>-->
            </form>
        </div>
    </div>
</div><!--.page-center-->

<script src="{{asset('/rownadmin/js/lib/jquery/jquery.min.js')}}"></script>
<script src="{{asset('/rownadmin/js/lib/tether/tether.min.js')}}"></script>
<script src="{{asset('/rownadmin/js/lib/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{asset('/rownadmin/js/plugins.js')}}"></script>
<script src="{{asset('/rownadmin/js/app.js')}}"></script>

    <script type="text/javascript">
       /* $('#sign_in').submit(function () {
           event.preventDefault();
           $.ajax({
               url   : '/admin_login',
               type  : 'post',
               data  : $('#sign_in').serialize(),
               dataType : 'json',
               success  : function () {
                   console.log('success')
               },
               error    : function () {
                   console.log('in error');
               }
           })
        });*/
    </script>