<style>
#parent{

  width: 100%;

}
#child{

width:80%;
position: relative;
margin-left: auto;
margin-right: auto;
z-index: 999;
box-shadow: 0px 0px 5px 5px #fced;


}

</style>
<!-- starting -->
<!-- enging -->
<div id="renderformhere">
<div class="container-fluid " id="parent" style="padding:0px;margin:0px;">

  <div style="background-image:url({{asset('images/contact_us.png')}});
           background-size: cover;
           background-repeat: no-repeat;
           height: 400px;
            "  >
            <div class="text-white text-center font-weight-bold "style="text-align: center;" > 
             <h1 style="color:white;padding-top: 100px!important;" >Contact Our Sales Team</h1>
             <h4 style="color:white;">Our Team 'll Happy to Answer your Questions...Fill Out fORM and we 'll be in touch as soon as possible.</h4></div>

           </div>
<div class="jumbotron" id="child" style=";height: auto;background-color: white!important;top: -120px!important;">
<div class="container-fluid" style="">
  <div class="row">
    <div class="col-md-12">
      
<!-- i am  -->
 <div class="col-sm-6 " >
       <h3 class="page-subheading">Make an enquiry</h3>
      <div class="">
        <form id="send_message_form" method="post" role="form">
          {{csrf_field()}}
          <div class="row">
            <div class="form-group">
              <label for="fname" class="control-label col-form-label">Name:</label>
              <input type="hidden" class="form-control" id="signature_text" name="signature_text">
              <input type="text" class="form-control" id="Name" name="Name" placeholder="Nmae" value="Name" readonly="required">
            </div>
            <div class="form-group">
              <label for="fname" class="control-label col-form-label">Email:</label>
              <input type="hidden" class="form-control" id="signature_text" name="signature_text">
              <input type="text" class="form-control" id="Email" name="Email" placeholder="Enter Email"readonly="required" >
            </div>
            
            <div class="form-group">
              <label for="fname" class="control-label col-form-label">Cell No.</label>
              <input type="hidden" class="form-control" id="signature_text" name="signature_text">
              <input type="Email" class="form-control" id="Cell No." name="Cell No." placeholder="Cell No." readonly="required">
            </div>
            <div class="form-group">
              <label for="fname" class="control-label col-form-label">Massege:</label>
              <input type="hidden" class="form-control" id="signature_text" name="signature_text">
              <input type="text" class="form-control" id="Massege" name="Massege" placeholder="Massege" readonly="required">
            </div>
            <div class="form-group">
              <label for="fname" class="control-label col-form-label">Massege Title:</label>
              <input type="hidden" class="form-control" id="signature_text" name="signature_text">
              <input type="text" class="form-control" id="Massege_title" name="Massege_title" placeholder="Massege_title"readonly="required" >
            </div>
          <div id="alertMsg">
                <button type="submit" class="button"><i class="fa fa-send"></i>&nbsp; <span>Send Message</span></button></div>
          </div>
        </form>
      </div>
    
    </div>
    <div class="col-md-1"></div>
    <div class="col-sm-5 " style="margin-top:10px; background-color: #fef6e4; height:440px;">
     <h3 class="page-subheading" style="margin-top:10px;">Let's get in touch</h3>
      <p style="margin-top:10px;">If you have any query feel free to contact with us. Send us a quick mail at contact@rown.pk or fill out the form below. We will respond to your email as soon as possiable.</p>
      <br/>
      <ul class="store_info">
        <li><i class="fa fa-home"></i>Palaza Number 65 Wallayt Complex Bahria Town Phase 7 Rawalpindi/Islamabad</li>
        <li><i class="fa fa-phone"></i><span>+923014558745</span></li>
        <li><i class="fa fa-envelope"></i>Email: <span><a href="mailto:contact@rown.pk">contact@rown.pk</a></span></li>
      </ul>
    </div>
      <!-- throw content here keep khial karo jo content dalo wo is ka anadar ho bt us content ki container col rowZ -->
    </div>
  </div>
</div>
</div>
</div>
</div>
   
 <!-- footer -->
 <div class="container-fluid">
<div class="row">
  <div class="col-md-12">
     <footer>
        <div class="footer-newsletter">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="footer-logo"><a href="{{url('/index')}}"><img src="{{asset('images/logo_chemleon.png')}}" alt="fotter logo" height="7%" width="90%"></a>
                            <p>Lorem Ipsum is simply dummy text of the printing.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <h3 class="">Sign up for newsletter</h3>
                        <span>Get the latest deals and special offers</span></div>
                    <div class="col-md-5 col-sm-12">
                        <form id="newsletter_submit_form" method="post" >
                            {{csrf_field()}}
                            <div class="newsletter-inner">
                                <input type="email" class="newsletter-email" name='email' placeholder='Enter Your Email'/>
                                <button class="button subscribe" type="submit" title="Subscribe">Subscribe</button>
                            </div>
                            <div id="alert_news_letter"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" style=" background-color: #fef6e4;">
            <div class="row" style="padding:20px;">
                <div class="col-sm-6 col-md-3 col-xs-12 col-lg-3 collapsed-block">
                    <div class="footer-links">
                        <h3 class="links-title"><strong>Information</strong><a class="expander visible-xs" href="#TabBlock-1">+</a></h3>
                        <div class="tabBlock" id="TabBlock-1">
                            <ul class="list-links list-unstyled">
                                <li><a href="#s">Delivery Information</a></li>
                                <li><a href="#">Discount</a></li>
                                <li><a href="sitemap.blade.php">Sitemap</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="faq.blade.php">FAQs</a></li>
                                <li><a href="#">Terms &amp; Condition</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-xs-12 col-lg-3 collapsed-block">
                    <div class="footer-links">
                        <h3 class="links-title"><strong>Insider</strong><a class="expander visible-xs" href="#TabBlock-3">+</a></h3>
                        <div class="tabBlock" id="TabBlock-3">
                            <ul class="list-links list-unstyled">
                                <li> <a href="sitemap.blade.php"> Sites Map </a> </li>
                                <li> <a href="#">News</a> </li>
                                <li> <a href="#">Trends</a> </li>
                                <li> <a href="about_us.blade.php">About Us</a> </li>
                                <li> <a href="contact_us.blade.php">Contact Us</a> </li>
                                <li> <a href="#">My Orders</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2 col-xs-12 col-lg-3 collapsed-block">
                    <div class="footer-links">
                        <h3 class="links-title"><strong>Service</strong><a class="expander visible-xs" href="#TabBlock-4">+</a></h3>
                        <div class="tabBlock" id="TabBlock-4">
                            <ul class="list-links list-unstyled">
                                <li> <a href="account_page.blade.php">Account</a> </li>
                                <li> <a href="account_setting.blade.php">Wishlist</a> </li>
                                <li> <a href="shopping_cart.blade.php">Shopping Cart</a> </li>
                                <li> <a href="#">Return Policy</a> </li>
                                <li> <a href="#">Special</a> </li>
                                <li> <a href="#">Lookbook</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xs-12 col-lg-3">
                    <h3 class="links-title"><strong>Contact us</strong></h3>
                    <div class="footer-content">
                        <div class="email"> <i class="fa fa-envelope"></i>
                            <p>Support@themes.com</p>
                        </div>
                        <div class="phone"> <i class="fa fa-phone"></i>
                            <p>(800) 0123 456 789</p>
                        </div>
                        <div class="address"> <i class="fa fa-map-marker"></i>
                            <p> My Company, 12/34 - West 21st Street, New York, USA</p>
                        </div>
                    </div>
                    <div class="social" >
                        <ul class="inline-mode">
                            <li class="social-network fb"><a title="Connect us on Facebook" target="_blank" href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a></li>
                            <li class="social-network googleplus"><a title="Connect us on Google+" target="_blank" href="https://plus.google.com/"><i class="fa fa-google-plus"></i></a></li>
                            <li class="social-network tw"><a title="Connect us on Twitter" target="_blank" href="https://twitter.com/"><i class="fa fa-twitter"></i></a></li>
                            <li class="social-network linkedin"><a title="Connect us on Linkedin" target="_blank" href="https://www.pinterest.com/"><i class="fa fa-linkedin"></i></a></li>
                           <!--  <li class="social-network rss"><a title="Connect us on Instagram" target="_blank" href="https://instagram.com/"><i class="fa fa-rss"></i></a></li> -->
                            <li class="social-network instagram"><a title="Connect us on Instagram" target="_blank" href="https://instagram.com/"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-coppyright">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 coppyright"> Copyright © 2019 <a href="#"> Chemleon </a>. All Rights Reserved. </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="payment">
                            <ul>
                                <li><a href="#"><img title="Visa" alt="Visa" src="{{asset('images/visa.png')}}"></a></li>
                                <li><a href="#"><img title="Paypal" alt="Paypal" src="{{asset('images/paypal.png')}}"></a></li>
                                <li><a href="#"><img title="Discover" alt="Discover" src="{{asset('images/discover.png')}}"></a></li>
                                <li><a href="#"><img title="Master Card" alt="Master Card" src="{{asset('images/master-card.png')}}"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


  </div>
</div>

 </div>

    <!-- samjho  -->


    <!-- samjho end  -->
    <a href="#" class="totop"> </a>


<script type="text/javascript">
  $('#send_message_form').submit(function () {
     event.preventDefault();
     $.ajax({
         url      : '/user_messages',
         type     : 'post',
         data     :  $('#send_message_form').serialize(),
         dataType : 'json',
         success  : function (result) {
             var str = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Thanks!!</strong> '+result+' </div>';
             $('#alertMsg').html(str);
             $("input[type=text]").val('');
             $("input[type=email]").val('');
             $('#message').val('');
         },
         error    : function () {
             console.log('error')
         }
     })
  });
</script>