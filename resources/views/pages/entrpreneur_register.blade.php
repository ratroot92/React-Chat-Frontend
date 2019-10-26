<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>
                    <li class="home"> <a title="Go to Home Page" href="{{url('/index')}}">Home</a><span>&raquo;</span></li>
                    <li><strong>Join Rown.pk</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
            <div class="col-md-3">
                <form id="login_entr_vendr" method="post" role="form" >
                    @csrf
                    <h4>Login</h4>
                    <p class="before-login-text">Welcome back! Sign in to your account</p>
                    <label for="email">Email address<span class="required">*</span></label>
                    <input id="email" type="email" name="email" class="form-control">
                    <label for="password">Password<span class="required">*</span></label>
                    <input id="password" type="password" name="password" class="form-control">
                    <p class="forgot-pass"><a href="#">Lost your password?</a></p>
                    <div id="login_erros"></div>
                    <button class="button" type="submit"><i class="fa fa-lock"></i>&nbsp; <span>Login</span></button><label class="inline" for="rememberme">
                        <input type="checkbox" value="forever" id="rememberme" name="rememberme"> Remember me
                    </label>
                </form>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-8">
                <form id="submit_entrep_form" method="post" role="form">
                    @csrf
                    <h2 align="center">Register</h2><p align="center">Create your every own account</p>
                             <div class="col-md-2"></div>
                              <div class="row form-group">
                                  <div class="col-md-4">
                                <label for="name">Name:<span class="required">*</span></label>
                                      <input type="hidden" value="{{$value}}" name="type">
                                <input type="text" name="name" id="name" class="form-control" required>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="name">Email:<span class="required">*</span></label>
                                      <input type="email" name="email" id="email" class="form-control" required>
                                  </div>
                            </div>
                <div class="col-md-2"></div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label for="name">Address: <span class="required">*</span></label>
                        <input type="text" name="address" id="address" class="form-control" required>
                    </div>
                    <div class="offset-1 col-md-4">
                        <label for="name">Contact#<span class="required">*</span></label>
                        <input type="text" name="contact_no" id="contact_no" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label for="name">Password:<span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="offset-1 col-md-4">
                        <label for="name">Conform Password:<span class="required">*</span></label>
                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required>
                    </div>
                </div><br>
                <div class="row form-group">
                    <div class="col-md-4">
                    </div>
                    <div id="alrt_success_msg">
                    </div>
                    <div class="col-md-4">
                            <button class="button btn-block" type="submit"><i class="fa fa-user"></i>&nbsp; <span>Register</span></button>
                    </div>
                </div>
                </form>
                        </div>


            </div>

        </div>
</section>
<!-- Mainonontainer End -->
<script type="text/javascript">
    $('#submit_entrep_form').submit(function (e) {
        e.preventDefault();
        $.ajax({
           url      : '/vendor_register',
           type     : 'post',
           data     : $('#submit_entrep_form').serialize(),
           dataType : 'json',
           success  : function (result) {
               $('#submit_entrep_form')[0].reset();
           $('#alrt_success_msg').html('<p class="alert alert-success alert-dismissable">'+result+'<a href="#" class="close" data-dismiss="alert">&times;</a></p>')
           },
           error    : function (result) {
               var response = JSON.parse(result.responseText);
               var str = '';
               $.each(response.errors,function (i,item) {
                   var html = '<p class="alert alert-danger alert-dismissible"><strong>Error!!</strong> &nbsp;&nbsp; '+item+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
                   str = str + html;
               });
               $('#alrt_success_msg').html(str);
           }
        });
    });
    $('#login_entr_vendr').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url      : '/entr_login',
            type     : 'post',
            data     : $('#login_entr_vendr').serialize(),
            dataType : 'json',
            success  : function (result) {
                   if(result==1)
                   {
                       $('#pageLoad_Area').load('/entr_vendor/dashboard');
                   }
                   else if (result==2)
                   {
                       $('#pageLoad_Area').load('/entr_vendor/dashboard1');
                   }
                   else
                   {
                       var html = '<p class="alert alert-danger alert-dismissible"><strong>Error!!</strong> &nbsp;&nbsp; '+result+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
                       $('#login_erros').html(html);
                   }
                },
            error    : function (result) {
                var response   = JSON.parse(result.responseText);
                var str        = '';
                $.each(response.errors,function (i,item) {
                    var html = '<p class="alert alert-danger alert-dismissible"><strong>Error!!</strong> &nbsp;&nbsp; '+item+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
                    str = str + html;
                });
                $('#login_erros').html(str);
            }
        });
    });
    $(document).on('click','a[data-toggle=test]',function () {
       event.preventDefault();
       $('#pageLoad_Area').empty();
       $('#pageLoad_Area').load($(this).attr('href'));
    });
</script>