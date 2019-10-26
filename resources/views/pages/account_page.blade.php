


<div id="page1">

  <div class="breadcrumbs">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <ul>
            <li class="home"> <a title="Go to Home Page" href="index-2.blade.php">Home</a><span>&raquo;</span></li>
            <li><strong>My Account</strong></li>
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
            <form id="sing-up-form" method="post">
                {{csrf_field()}}
         <div class="col-md-4">
             <br><br>
            <img src="{{asset('images/signup.jpg')}}">
         </div>
            <div class="col-md-8" align="center">
                <h2>Register</h2><p>Create your every own account</p>

            </div>
                <div class="col-md-8" id="alertmsg1"></div>
                <div class="col-md-4"></div>
            <div class="col-md-8">
                <div class="col-md-1"></div>
                <div class="col-md-4">
                <div class="row form-group">
                    <label for="name">Name<span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="row form-group">
                        <label for="email">email<span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
            </div>
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <div class="col-md-1"></div>
                    <div class="col-md-4">
                        <div class="row form-group">
                            <label for="city">City<span class="required">*</span></label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>

                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="row form-group">
                            <label for="contact-no">Contact No<span class="required">*</span></label>
                            <input type="text" name="contact_no" id="contact_no" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="row form-group">
                            <label for="address">Address<span class="required">*</span></label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>

                    </div>
                </div>
                <div class="col-md-4"></div>
            <div class="col-md-8">
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <div class="row form-group">
                        <label for="password">Password<span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="row form-group">
                        <label for="password-confirm">Confirm Password<span class="required">*</span></label>
                        <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
          <div class="col-md-8" align="center">
              <br>
              <div class="col-md-4">
              </div>
              <div class="col-md-4">
              <button class="button btn-block" type="submit"><i class="fa fa-user"></i>&nbsp; <span>Register</span></button>
          </div>
          </div>
            </form>
    </div>
    </div>
  </section>


  <!-- Footer -->


  <a href="#" class="totop"> </a> </div>

<script type="text/javascript">
    $('#sing-up-form').submit(function () {
      event.preventDefault();
      $.ajax({
         url  : 'register',
         type : 'post',
         data : $('#sing-up-form').serialize(),
         success : function (result) {
          console.log("successed");
          window.location = "index";
         } ,
          error : function (data) {
             var response = JSON.parse(data.responseText);
             var str = '';
             $.each(response.errors,function (i,item) {
                 var html = '<p class="alert alert-danger alert-dismissible"><strong>Error!!</strong> &nbsp;&nbsp; '+item+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
                  str = str + html;
             });
            $('#alertmsg1').html(str);
            $('#password').val('');
            $('#password-confirm').val('');
          }
      });
    });

</script>