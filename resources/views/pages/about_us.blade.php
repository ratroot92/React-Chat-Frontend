<div class="main container" style="margin-top: 10px!important;">
  <div class="row">
    <div class="about-page">
      <div class="col-md-8">
         <div class="single-img-add sidebar-add-slider">
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
              <div class="item active"> <img src="{{asset('images/slider_image.png')}}" alt="slide1"> </div>  
            </div>
          </div>
        </div></div>

       <div class="col-md-4">  
            <h2>Welcome to <span class="text_color">Chameleon</span></h2>
             <h3>Who We Are?</h3>
             <p> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
             tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
             quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
             consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
             cillum dolore eu fugiat nulla pariatur. .</p>

             <button type="button" class="btn btn-warning">Explore Us</button>
            </div>
          </div>
     
    </div>
    <hr>
    <div class="row">
    <div class="about-page">
      <div class="col-md-7">
        
             <h3>What We Do?</h3>
             <p> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
             tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
             quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
             consequat. Duis aute irure dolor in reprehenderit in voluptate.</p>
             their homes.</p>
        <ul>
          <li><i class="fa fa-arrow-right"></i>&nbsp; <a href="#"> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo </a></li>
          <li><i class="fa fa-arrow-right"></i>&nbsp; <a href="#">ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo </a></li>
          <li><i class="fa fa-arrow-right"></i>&nbsp; <a href="#">ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo </a></li>
          <li><i class="fa fa-arrow-right"></i>&nbsp; <a href="#">ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo </a></li>
         
        </ul>

             <button type="button" class="btn btn-warning">Discover More</button> 
         </div>

       <div class="col-md-5" style="margin-top: 30px!important;">  
        <div class="single-img-add sidebar-add-slider">
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
              <div class="item active"> <img src="{{asset('images/about_us_slide1.png')}}" alt="slide1"> </div>  
            </div>
          </div>
        </div>
          
        </div>


          </div>
    </div>




  </div>


<!-- Our Team  -->
<div class="our-team">
  <div class="container"> <div class="page-header">
      <h2>Our Team</h2>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-3 col-md-3">
        <div class="wow bounceInUp" data-wow-delay="0.2s">
          <div class="team">
            <div class="inner">
              <div class="avatar"><img src="{{asset('images/team_member.jpg')}}" alt="" class="img-responsive" /></div>
              <h5>Muzammal Hussain</h5>
              <p class="subtitle">Founder & CEO</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-3 col-md-3">
        <div class="wow bounceInUp" data-wow-delay="0.5s">
          <div class="team">
            <div class="inner">
              <div class="avatar"><img src="{{asset('images/team_member.jpg')}}" alt="" class="img-responsive" /></div>
              <h5>Muhammad Waleed</h5>
              <p class="subtitle">CTO</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-3 col-md-3">
        <div class="wow bounceInUp" data-wow-delay="0.8s">
          <div class="team">
            <div class="inner">
              <div class="avatar"><img src="{{asset('images/team_member.jpg')}}" alt="" class="img-responsive" /></div>
              <h5>Afif-Ur-Rehman</h5>
              <p class="subtitle">CMO</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- our team end
 -->
<!-- Footer Start -->
 <footer>
        <div class="footer-newsletter">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-3">
                        <div class="footer-logo"><a href="{{url('/index')}}"><img src="{{asset('images/logo_chemleon.png')}}" alt="fotter logo" height="7%" width="90%"></a>
                            <p>Lorem Ipsum is simply dummy text of the printing.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3 class="">Sign up for newsletter</h3>
                        <span>Get the latest deals and special offers</span></div>
                    <div class="col-md-5 col-sm-6">
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
    <a href="#" class="totop"> </a>
