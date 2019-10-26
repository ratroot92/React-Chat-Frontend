<html lang="en">

  <!-- Breadcrumbs -->
  
  <div class="breadcrumbs">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <ul>
            <li class="home"> <a title="Go to Home Page" href="index-2.blade.php">Home</a><span>&raquo;</span></li>
            <li><strong>User Account Settings</strong></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumbs End --> 
  <!-- Main Container -->
  <section class="main-container col2-right-layout">
    <div class="main container">
      <div class="row">
        <div class="col-main col-md-12">
          <div class="my-account">
            <div class="page-title">
              <h2 align="center" style="margin-left: 15%;"><u>Profile Settings</u></h2>
            </div>
            <div class="wishlist-item table-responsive">
              <div class="container bootstrap snippet">
                <div class="col-sm-3"><!--left col-->
                  <form id="change_pic_form" action="{{url('/update_userpic/'.$user->id)}}"  role="form" files="true" enctype="multipart/form-data" method="post" >
                    {{csrf_field()}}
                    <div class="text-center">
                      <img src="{{asset('/images/'.$user->image)}}" class="avatar img-circle img-thumbnail" alt="avatar" width="40%" style="border-radius: 50%">
                      <h6>Upload a different photo...</h6>
                      <input type="file" class="text-center center-block file-upload" name="file" id="file">
                    </div><br>
                    <button type="submit" class="btn btn-success btn-block">Change Profile Pic</button>
                  </form>
                  </div><!--/col-3-->
                  <div class="col-sm-8">
                    <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#user">User Info</a></li>
                      <li><a data-toggle="tab" href="#changepass">Change Password</a></li>
                      <li><a data-toggle="tab" href="#settings">Dummy Tab</a></li>
                    </ul>


                    <div class="tab-content">
                      <div class="tab-pane active" id="user">
                        <div id="alrtmsg">
                        </div>
                        <form id="user_detail_form" role="form" method="post">
                          {{csrf_field()}}
                       <div class="form-group ">
                         <div class="col-md-5">
                           <label>Name:</label>
                           <input type="hidden" id="userId" name="userId" value="{{$user->id}}">
                           <input type="text" class="form-control" required="required" placeholder="Name" id="user_name" name="user_name" value="{{$user->name}}"/>
                         </div>
                         <div class="col-md-1"></div>
                         <div class="col-md-5">
                           <label>Email:</label>
                           <input type="email" class="form-control" required="required" placeholder="Email" id="user_email" name="user_email" value="{{$user->email}}"/>
                           <br>
                         </div>
                         <div class="col-md-5">
                           <label>City:</label>
                           <input type="text" class="form-control" required="required" placeholder="City" id="user_city" name="user_city" value="{{$user->city}}"/>
                         </div>
                         <div class="col-md-1"></div>
                         <div class="col-md-5">
                           <label>Contact #:</label>
                           <input type="text" class="form-control" required="required" placeholder="Cell No" id="user_contact" name="user_contact" value="{{$user->contact_no}}"/>
                           <br>
                         </div>
                        </div>
                        <div class="col-md-9">
                          <label>Address:</label>
                          <input type="text" class="form-control" required="required" placeholder="Address" id="user_address" name="user_address" value="{{$user->address}}"/>
                        <br>
                          <div class="col-lg-offset-3">
                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;Save</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;Reset</button><br><br>
                          </div>
                        </div>

                        </form>

                      </div><!--/tab-pane-->

                      <div class="tab-pane" id="changepass">
                        <div id="alrtmsg1">

                        </div>
                        <form id="change_password_form" method="post">
                          {{csrf_field()}}
                      <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                          <label>Current Password:</label>
                          <input type="password" class="form-control" required="required"  name="user_password" id="user_password"/>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-offset-2 col-md-4">
                          <label>New Password:</label>
                          <input type="password" class="form-control" required="required"  name="password" id="password"/>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-offset-2 col-md-4">
                          <label>Conform Password:</label>
                          <input type="password" class="form-control" required="required"  name="password_confirmation" id="password_confirmation"/>
                        </div>
                        <div class="col-md-8"> <br></div>
                        <div class="col-md-offset-6 col-md-6">
                          <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;&nbsp;Change Password</button>&nbsp;&nbsp;&nbsp;
                          <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-remove-sign"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                        </div>
                        <div class="col-md-8"> <br></div>
                      </div>

                        </form>
                      </div><!--/tab-pane-->
                      <div class="tab-pane" id="settings">

                      </div>

                    </div><!--/tab-pane-->
                  </div><!--/tab-content-->

              </div><!--/row-->
               </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</html>

  <script type="text/javascript">
      $(document).ready(function() {
          var readURL = function(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();

                  reader.onload = function (e) {
                      $('.avatar').attr('src', e.target.result);
                  };

                  reader.readAsDataURL(input.files[0]);
              }
          };

          $(".file-upload").on('change', function(){
              readURL(this);
          });
      });
      $('#user_detail_form').submit(function () {
          event.preventDefault();
          var user = $('#userId').val();
          var str = '';
          $.ajax({
              url  : '/update_userinfo/'+user,
              type : 'post',
              data : $('#user_detail_form').serialize(),
              dataType : 'json',
              success : function (result) {
               var html = '<p class="alert alert-danger alert-dismissible"><strong>Success!!</strong> Record has been updated Successfully <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>'
               str += html;
                  $('#alrtmsg').html(str);
              } ,
              error : function (data) {
                  console.log('in error');
              }
          });
      });

      $('#change_password_form').submit(function () {
          event.preventDefault();
          var str  = '';
          var user = $('#userId').val();
          $.ajax({
              url  : '/change_pass/'+user,
              type : 'post',
              data : $('#change_password_form').serialize(),
              dataType : 'json',
              success : function (result) {
                  var html = '<p class="alert alert-danger alert-dismissible"><strong>Success!!</strong> passowrd has been changed Successfully <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>'
                  str += html;
                  $('#alrtmsg1').html(str);
              } ,
              error : function (data) {
                  var response = JSON.parse(data.responseText);
                  var str = '';
                  $.each(response.errors,function (i,item) {
                      var html = '<p class="alert alert-danger alert-dismissible"><strong>Error!!</strong> &nbsp;&nbsp; '+item+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
                      str = str + html;
                  });
                  $('#alrtmsg1').html(str);
              }
          });
      });
  </script>
