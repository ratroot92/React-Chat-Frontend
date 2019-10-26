
<!-- Basic page needs -->
<meta charset="utf-8">
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<![endif]-->

<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Rowwn (Replace old with new)</title>
<meta name="description" content="best template, template free, responsive theme, fashion store, responsive theme, responsive html theme, Premium website templates, web templates, Multi-Purpose Responsive HTML5 Template">
<meta name="keywords" content="bootstrap, ecommerce, fashion, layout, responsive, responsive template, responsive template download, responsive theme, retail, shop, shopping, store, Premium website templates, web templates, Multi-Purpose Responsive HTML5 Template"/>

<!-- Mobile specific metas  -->
<meta name="viewport" content="width=device-width, initial-scale=1">



<!-- Favicon  -->
<link rel="shortcut icon" type="image/x-icon" href="{{asset('/images/logo1.png')}}">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

<!-- CSS Style -->

<!-- Bootstrap CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">

<!-- font-awesome & simple line icons CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.css')}}" media="all">
<link rel="stylesheet" type="text/css" href="{{asset('css/simple-line-icons.css')}}" media="all">

<!-- owl.carousel CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('css/owl.carousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/owl.theme.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/owl.transitions.css')}}">

<!-- animate CSS  -->
<link rel="stylesheet" type="text/css" href="{{asset('css/animate.css')}}" media="all">

<!-- flexslider CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('css/flexslider.css')}}" >

<!-- jquery-ui.min CSS  -->
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}">

<!-- Mean Menu CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('css/meanmenu.min.css')}}">

<!-- nivo-slider css -->
<link rel="stylesheet" type="text/css" href="{{asset('css/nivo-slider.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/revolution-slider.css')}}">

<!-- style CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}" media="all">
<style>
    .modal-header1 {
        background-color: #C49F0F;
        padding:9px 15px;
        color:#FFF;
        font-family:Verdana, sans-serif;
        border-bottom:1px solid #eee;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
   .btn-search{
    height: 50px;
   background-color: #FCAA00!important; 
   }
   .cate-dropdown{
     height: 50px;

   }

</style>
</head>
<div id="page">


<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->



    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-5 col-xs-6">
                            <!-- Default Welcome Message -->
                            <span class="phone hidden-xs hidden-sm"><i class="fa fa-phone fa-lg"></i> +123.456.789</span>
                            <div class="welcome-msg hidden-xs">
                                @if (Route::has('login'))
                                    <div class="top-right links">
                                        @auth
                                            {{ Auth::user()->name }}
                                            <input type="hidden" id="authenticate_user" name="authenticate_user" value="{{ Auth::user()->id }}">
                                        @else
                                            Welcome to Chemeleon!
                                        @endauth
                                    </div>
                                @endif

                            </div>
                        </div>

                        <!-- top links -->
                        <div class="headerlinkmenu col-lg-6 col-md-6 col-sm-7 col-xs-6 text-right">
                            <div class="links">
                                <div class="jtv-user-info">
                                    <div class="dropdown">
                                        @if (Route::has('login'))
                                            @auth
                                                <a class="current-open" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                                    <img src="{{url('/images/'.Auth::user()->image)}}" height="4%"  alt="Avatar" style=" border-radius: 50%;">
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a href="#" id="user_dashboard" style="color: black"><i class="fa fa-dashboard"></i>&nbsp;&nbsp;&nbsp;Dashboard</a></li>
                                                    <li><a href="#" id="profile_setting" style="color: black"><i class="fa fa-cog"></i>&nbsp;&nbsp;&nbsp;Profile Settings</a></li>
                                                    <li><a href="#" class="replace_items" style="color: white;background-color: green" ><i class="fa fa-reply"></i>&nbsp;&nbsp;&nbsp Request for Replace</a></li>
                                                    <li><a href="#" id="myorders" style="color: black"><i class="fa fa-first-order"></i>&nbsp;&nbsp;&nbsp;My Orders</a></li>
                                                    <li><a href="" style="color: black"><i class="fa fa-buysellads"></i>&nbsp;&nbsp;&nbsp;Dashboard Post Ad</a></li>
                                                    <li><a href="#" id="company_registration" style="background-color:red;color: white;"><i class="fa fa-compass"></i>&nbsp;&nbsp;&nbsp; Company Registration</a></li>
                                                    <li><a href="#" id="payment_detail" style="color: black"><i class="fa fa-paypal"></i>&nbsp;&nbsp;&nbsp;Payments</a></li>
                                                    <li><a href="" style="color: black"><i class="fa fa-heart"></i>&nbsp;&nbsp;&nbsp;My Favourite</a></li>
                                                    <li><a href="#" id="privacy_setting" style="color: black"><i class="fa fa-key"></i>&nbsp;&nbsp;&nbsp;Privacy Settings</a></li>
                                                    <li><a href="" style="color: black"><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp;Checkout</a></li>
                                                    <li class="divider" style="color: black"></li>

                                                     <li>
                                                                <a href="{{ route('logout') }}"
                                                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="color: black"><i class="fa fa-lock"></i>&nbsp;&nbsp;&nbsp;Log Out</a>
                                                            </li>
                                                </ul>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @else
                                                <a class="current-open" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                                MY Account<i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu" role="menu">
                                                <li style="color: black">
                                                    <a href="#" data-toggle="modal" data-target="#login-modal" style="color: black">Log in</a>
                                                </li>
                                                </ul>
                                            @endauth

                                        @endif

                                    </div>
                                </div>
                               <!--  <div class="services hidden-xs"><a title="servicesg" href="#">Services</a></div>
                                <div class="myaccount hidden-xs"><a title="My Account" href="#">My Support</a></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container" >
                <div class="row">
                    <div class="col-sm-3 ">
                        <!-- Header Logo -->
                        <div class="logo " ><a title="e-commerce" href="{{url('index')}}"><img alt="e-commerce" src="{{asset('images/logo_chemleon.png')}}"height="8%" width="90%" ></a> </div>
                        <!-- End Header Logo -->
                    </div>
                    <div class="col-sm-5">
                        <!-- Search -->

                        <div class="top-search">
                            @if(Session::has('message'))
                                <p class="alert alert-{{ Session::get('message-type') }} alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <i class="glyphicon glyphicon-{{ Session::get('message-type') == 'success' ? 'ok' : 'remove'}}"></i> {{ Session::get('message') }}
                                                </p>
                            @endif
                                @if(Session::has('company_msg'))
                                    <p class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <i class="glyphicon glyphicon-ok"></i> {{ Session::get('company_msg') }}
                                    </p>
                                @endif
                            <div id="search">
                                <form>
                                    <div class="input-group" style="border-radius: 6px;">
                                        <select class="cate-dropdown hidden-xs hidden-sm" name="category_id">
                                            <option>Categories</option>
                                            
                                            <option>&nbsp;&nbsp;&nbsp;Mobiles </option>
                                            <option>&nbsp;&nbsp;&nbsp;Books</option>
                                            <option>&nbsp;&nbsp;&nbsp;Furniture</option>
                                            
                                            
                                        </select>
                                       <input type="text" class="form-control snize-input -style" placeholder="Search" name="search" autocomplete="off">
                                        <button class="btn-search" type="button" ><i class="fa fa-search"></i></button> 
                                        
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- End Search -->
                    </div>
                    <div class=" col-sm-4  top-cart">
                        <!-- <div class="link-wishlist"> <a href="#"> <span class="wishlist-count" id="whishlist_count">3</span> <i class="fa fa-heart fa-lg"></i> </a> </div> -->
                        <div class="row">
                            <div class="col-sm-8">
                        <div class="top-cart-contain">
                            <div class="mini-cart">
                                <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle"> <a href="#">
                                        <div class="cart-icon"><i class="fa fa-shopping-cart"></i></div>
                                        <div class="shoppingcart-inner"><span class="cart-title">Shopping Cart</span>
                                            <span class="cart-total">
                                                <?php
                                                if($cart!=null)
                                                    {
                                                     echo $cart->total_quantity.' Items Rs.'.$cart->total_price;
                                                    }
                                                else{
                                                    echo '0 Items Rs. 00.00';
                                                }
                                                   ?>
                                            </span>
                                        </div>
                                    </a></div>
                                <div>
                                    <div class="top-cart-content">
                                        <div class="block-subtitle hidden-xs">Recently added item(s)</div>
                                        <ul id="cart-sidebar" class="mini-products-list">
                                            @if($cart!=null)
                                            @foreach($cart->items as $cart_row)
                                            <li class="item odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="{{asset('/images/'.$cart_row['item']['product_image'])}}" alt="Lorem ipsum dolor" width="65"></a>
                                                <div class="product-details"> <a data-toggle="delete_cart_item" href="{{url('/delete_items/'.$cart_row['item']['id'])}}" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a>
                                                    <p class="product-name"><a href="#">{{$cart_row['item']['product_name']}}</a> </p>
                                                    <strong>{{$cart_row['qty']}}</strong> x <span class="price">{{$cart_row['item']['product_price']}}</span> <span> = <?php echo $cart_row['qty']*$cart_row['item']['product_price'];?> </span></div>
                                            </li>
                                                @endforeach
                                                @else
                                                <li>
                                                   <p>Your Shopping will be start on one click</p>
                                                </li>
                                                @endif
                                        </ul>
                                        <div class="top-subtotal">Subtotal: <span class="price"><?php if ($cart!=null){echo "Rs.".$cart->total_price;} else {echo 00.00;} ?></span></div>
                                        <div class="actions">
                                            <button  class="viewcart btn-checkout" type="button"><i class="fa fa-check"></i><span>Checkout</span></button>
                                            <button   class="viewcart view-cart" type="button"><i class="fa fa-shopping-cart"></i> <span>View Cart</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">

                        <div class="link-wishlist"> <a href="#"> <span class="wishlist-count" id="whishlist_count" style="margin-left:50px;">3</span> <i class="fa fa-heart fa-lg"></i> </a> </div></div>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </header>
    <!-- end header -->

    <!-- Start Menu Area -->
    <div class="menu-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 hidden-xs">
                    <div class="main-menu">
                        <nav class="navbar sticky-top">
                            <ul>
                                <li class="active custom-menu"><a href="{{url('index')}}">Home</a></li>
                               
                               <li class="megamenu"><a href="#" id="aboutUs">About Us</a>

                                </li>
                                <li class="custom-menu"><a>Buy</a>
                                    <ul class="dropdown">
                                        <li> <a href="#" id="old_mobile_page">Mobiles</a></li>
                                        <li> <a href="#" id="old_book_page">Books</a></li>
                                        <li><a href="#" id="old_furniture_page">Furniture</a></li>
                                
                               
                                       
                                    </ul>
                                </li>
                               
                                  <li class="megamenu"><a href="#" id="contactUs">Contact Us</a>
                                </li> 
                               
                                <li class="custom-menu"><a>Join Us</a>
                                    <!-- <ul class="dropdown">
                                        @if(Auth::guard('entr_vendr')->check())
                                            <li><a data-toggle="entrepreneur_login" href="{{url('/entr_vendor/dashboard')}}" id="old_mobile_page">As Entrepreneur</a></li>
                                            @else
                                            <li> <a data-toggle="entrepreneur_login" href="{{url('/entrpreneur/1')}}" id="old_mobile_page">As Entrepreneur</a></li>
                                            @endif
                                        <li> <a data-toggle="entrepreneur_login" href="{{url('/entrpreneur/2')}}"  id="old_book_page">As Vendor</a></li>
                                    </ul> -->
                                </li>

                            </ul>
                        </nav>


                        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header"> <img id="img_logo" src="{{asset('images/logo1.png')}}" alt="logo" height="10%">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> </button>
                                    </div>
                                    <div id="div-forms">
                                        <form id="login-form" method="post" action="{{route('login')}}">
                                            {{csrf_field()}}
                                            <div class="modal-body">
                                                <div id="div-login-msg"> <span id="text-login-msg">Username or email address </span> </div>
                                                <input id="email" name="email" class="form-control" type="text" placeholder="User name" required>
                                                <input id="password" name="password" class="form-control" type="password" placeholder="Password" required>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="remember" id="remember">
                                                        Remember me </label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div>
                                                    <button type="submit" class="btn-login">Login</button>
                                                </div>
                                                <div>
                                                    <button  id="login_register_btn" type="button" class="btn btn-link">Register</button>
                                                </div>
                                                <div>
                                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                                        {{ __('Forgot Your Password?') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END # MODAL LOGIN -->

                        <!--  Sign up if already loged in -->
                        <div class="modal fade" id="signup-already_loged_in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header1">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Warnning Message!!!</h4>
                                    </div>
                                    <div class="modal-body">
                                    <p >You are alerady log in please make sure that you are not login</p><br>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-left: 85%;">Okay</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--    End of alert alreasy loged in -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Menu Area -->

<a id="part"></a>
<div id="pageLoad_Area">
    <!-- Main Slider Area -->
    <div id="banner-container" class="rev_slider_wrapper fullwidthbanner-container" data-alias="classic4export" data-source="gallery" style="margin:0px auto;background-color:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
        <!-- START REVOLUTION SLIDER 5.3.0.2 auto mode -->
        <div id="banner-slide" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.3.0.2">
            <div class="slotholder"></div>
            <ul>
                <!-- SLIDE  -->

                <!-- SLIDE 3 -->
                <li data-index="rs-3049" data-transition="slideremovedown" data-slotamount="7" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000"    data-rotate="0"  data-saveperformance="off"  data-title="Ken Burns" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">

                    <!-- MAIN IMAGE -->
                    <img src="{{asset('images/slider/slider-img1.png')}}"  alt=""  data-bgposition="center center" data-kenburns="off" data-duration="30000" data-ease="Linear.easeNone" data-scalestart="100" data-scaleend="120" data-rotatestart="0" data-rotateend="0" data-offsetstart="0 0" data-offsetend="0 0" data-bgparallax="10" class="rev-slidebg" data-no-retina>
                    <!-- LAYERS -->

                    <!-- LAYER NR. 12 -->
                    <div class="tp-caption title-slide color-white letter-spacing3px"
                         id="slide-3049-layer-1"
                         data-x="['left','left','left','left']" data-hoffset="['39','39','39','39']"
                         data-y="['middle','middle','middle','middle']" data-voffset="['-105','-77','-77','-77']"
                         data-fontsize="['55','52','45','35']"
                         data-lineheight="['60','57','50','40']"
                         data-fontweight="['600','600','600','600']"
                         data-width="none"
                         data-height="none"
                         data-whitespace="nowrap"

                         data-type="text"
                         data-responsive_offset="on"

                         data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"y:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'

                         data-textAlign="['left','left','left','left']"
                         data-paddingtop="[10,10,10,10]"
                         data-paddingright="[0,0,0,0]"
                         data-paddingbottom="[0,0,0,0"
                         data-paddingleft="[0,0,0,0]"

                         style="z-index: 16; white-space: nowrap;text-transform:left;">We Create Beauty<br>
                        Custom Furniture Design </div>

                    <!-- LAYER NR. 13 -->
                    <div class="tp-caption sub-title color-white"
                         id="slide-3049-layer-4"
                         data-x="['left','left','left','left']" data-hoffset="['37','37','37','37']"
                         data-y="['middle','middle','middle','middle']" data-voffset="['30','30','30','0']"
                         data-fontsize="['20',18','18','14']"
                         data-lineheight="['30','28','28','24']"
                         data-fontweight="['400','400','400','400']"
                         data-width="none"
                         data-height="none"
                         data-whitespace="nowrap"

                         data-type="text"
                         data-responsive_offset="on"

                         data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":1500,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
                         data-textAlign="['left','left','left','left']"
                         data-paddingtop="[0,0,0,0]"
                         data-paddingright="[0,0,0,0]"
                         data-paddingbottom="[0,0,0,0]"
                         data-paddingleft="[0,0,0,0]"

                         style="z-index: 17; white-space: nowrap;text-transform:left;">We provide the best quality of  <br>products to you. We are always here to.</div>
                    <a href="#" target="_self" class="tp-caption flat-button-slider bg-blue"

                       data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":2000,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'

                       data-x="['left','left','left','left']" data-hoffset="['36','36','36','36']"
                       data-y="['middle','middle','middle','middle']" data-voffset="['140','140','140','80']"
                       data-fontsize="['14','14','14','14']"
                       data-width="['auto']"
                       data-height="['auto']">SHOPPING NOW </a><!-- END LAYER LINK -->

                    <a href="#" target="_self" class="tp-caption flat-button-slider bg-transparent"

                       data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":2000,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'

                       data-x="['left','left','left','left']" data-hoffset="['205','205','205','205']"
                       data-y="['middle','middle','middle','middle']" data-voffset="['140','140','140','80']"
                       data-fontsize="['14',14','14','14']"
                       data-width="['auto']"
                       data-height="['auto']">Show More </a><!-- END LAYER LINK -->
                </li>

                <!-- SLIDE 2 -->
                <li data-index="rs-3048" data-transition="random-static" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000"    data-rotate="0"  data-saveperformance="off"  data-title="Ken Burns" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">

                    <!-- MAIN IMAGE -->
                    <img src="{{asset('images/slider/slider-img2.png')}}"  alt=""  data-bgposition="center center" data-kenburns="off" data-duration="30000" data-ease="Linear.easeNone" data-scalestart="100" data-scaleend="120" data-rotatestart="0" data-rotateend="0" data-offsetstart="0 0" data-offsetend="0 0" data-bgparallax="10" class="rev-slidebg" data-no-retina>

                    <!-- LAYER NR. 12 -->
                    <div class="tp-caption title-slide color-white"
                         id="slide-3048-layer-1"
                         data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                         data-y="['middle','middle','middle','middle']" data-voffset="['-95','-95','-95','-55']"
                         data-fontsize="['58','55','45','30']"
                         data-lineheight="['60','57','55','35']"
                         data-fontweight="['600','600','600','600']"
                         data-width="none"
                         data-height="none"
                         data-whitespace="nowrap"

                         data-type="text"
                         data-responsive_offset="on"

                         data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"y:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'

                         data-textAlign="['center','center','center','center']"
                         data-paddingtop="[10,10,10,10]"
                         data-paddingright="[0,0,0,0]"
                         data-paddingbottom="[0,0,0,0"
                         data-paddingleft="[0,0,0,0]"

                         style="z-index: 16; white-space: nowrap;text-transform:left;">We will make your life </div>

                    <!-- LAYER NR. 13 -->
                    <div class="tp-caption sub-title color-white"
                         id="slide-3048-layer-4"
                         data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                         data-y="['middle','middle','middle','middle']" data-voffset="['-15','-15','0','0']"
                         data-fontsize="['20',18','18','14']"
                         data-lineheight="['30','28','28','24']"
                         data-fontweight="['400','400','400','400']"
                         data-width="none"
                         data-height="none"
                         data-whitespace="nowrap"

                         data-type="text"
                         data-responsive_offset="on"

                         data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":1500,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
                         data-textAlign="['center','center','center','center']"
                         data-paddingtop="[0,0,0,0]"
                         data-paddingright="[0,0,0,0]"
                         data-paddingbottom="[0,0,0,0]"
                         data-paddingleft="[0,0,0,0]"

                         style="z-index: 17; white-space: nowrap;text-transform:left;">Ut sit amet turpis. In est arcu, sollicitudin eu,<br> vehicula venenatis. Maecenas eu.</div>
                    <a href="#" target="_self" class="tp-caption flat-button-slider bg-transparent"

                       data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":2000,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'

                       data-x="['center','center','center','center']" data-hoffset="['-18','-18','0','0']"
                       data-y="['middle','middle','middle','middle']" data-voffset="['100','100','100','70']"
                       data-fontsize="['14',14','14','14']"
                       data-width="['188']"
                       data-height="['auto']">Purchase Template </a><!-- END LAYER LINK -->
                </li>
            </ul>
        </div>
    </div>
    <!-- End Main Slider Area -->

    <!-- main container -->

    <div  class="main-container col2-left-layout">
        <div class="container">
            <div class="row">
                <div class="col-main col-md-9 col-sm-8 col-xs-12 col-sm-push-4 col-md-push-3">
                    <!-- Home Tabs  -->

                    <div class="home-tab">
                        <div class="tab-info">
                            <h3 class="new-product-title pull-left">Products category</h3>
                            <ul class="nav home-nav-tabs home-product-tabs">
                                <li class="active"><a href="#all" data-toggle="tab" aria-expanded="false">All</a></li>
                                <li> <a href="#mobiles" data-toggle="tab" aria-expanded="false">Mobiles</a> </li>
                                <li> <a href="#books" data-toggle="tab" aria-expanded="false">Books</a> </li>
                                <li> <a href="#furniture" data-toggle="tab" aria-expanded="false">Furniture</a> </li>
                            </ul>
                            <!-- /.nav-tabs -->
                        </div>
                        <div id="productTabContent" class="tab-content">

                            <div class="tab-pane active in" id="all">
                                <div class="slider-items-products">
                                    <div id="all-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($products as $product_row){ ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($product_row->sal_new==1)
                                                        <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                            @if($product_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                        <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$product_row->id)}}"><span>Quick View</span></a></div>
                                                        <a > <img class="product-image-photo" src="{{url('/images/'.$product_row->product_image)}}" alt="" ></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$product_row->product_name}}" href="#">{{$product_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$product_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">

                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$product_row->id)}}" class="action add-to-cart btn" type="button" name="allitems" title="Add to Cart"> <span>Add to Cart</span> </a>

                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a data-toggle="whishlist" href="{{url('/add_to_whishlist/'.$product_row->id)}}" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a><a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mobiles">
                                <div class="slider-items-products">
                                    <div id="women-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($mobiles as $mobiles_row){ ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($mobiles_row->sal_new==1)
                                                            <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                        @if($mobiles_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                            <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$mobiles_row->id)}}"><span>Quick View</span></a></div>
                                                        <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{url('/images/'.$mobiles_row->product_image)}}" alt=""></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$mobiles_row->product_name}}" href="#">{{$mobiles_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$mobiles_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">
                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$mobiles_row->id)}}" class="action add-to-cart btn" type="button" name="mobiles" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="books">
                                <div class="slider-items-products">
                                    <div id="men-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($books as $books_row) { ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($books_row->sal_new==1)
                                                            <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                        @if($books_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                            <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$books_row->id)}}"><span>Quick View</span></a></div>
                                                        <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{asset('/images/'.$books_row->product_image)}}" alt=""></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$books_row->product_name}}" href="#">{{$books_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$books_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">
                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$books_row->id)}}" class="action add-to-cart btn" type="button" name="books" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="furniture">
                                <div class="slider-items-products">
                                    <div id="kids-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($furniture as $furniture_row){ ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($furniture_row->sal_new==1)
                                                            <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                        @if($furniture_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                        <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$furniture_row->id)}}"><span>Quick View</span></a></div>
                                                        <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{asset('/images/'.$furniture_row->product_image)}}" alt=""></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$furniture_row->product_name}}" href="#">{{$furniture_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$furniture_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">
                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$furniture_row->id)}}" class="action add-to-cart btn" type="button"  title="Add to Cart"> <span>Add to Cart</span> </a>
                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php }?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- anaext row of categories-->
                    <div class="jtv-banner-block ">
                        <div id="productTabContent" class="tab-content">

                            <div class="tab-pane active in" id="all">
                                <div class="slider-items-products">
                                    <div id="all-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($products as $product_row){ ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($product_row->sal_new==1)
                                                        <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                            @if($product_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                        <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$product_row->id)}}"><span>Quick View</span></a></div>
                                                        <a > <img class="product-image-photo" src="{{url('/images/'.$product_row->product_image)}}" alt="" ></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$product_row->product_name}}" href="#">{{$product_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$product_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">

                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$product_row->id)}}" class="action add-to-cart btn" type="button" name="allitems" title="Add to Cart"> <span>Add to Cart</span> </a>

                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a data-toggle="whishlist" href="{{url('/add_to_whishlist/'.$product_row->id)}}" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a><a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mobiles">
                                <div class="slider-items-products">
                                    <div id="women-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($mobiles as $mobiles_row){ ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($mobiles_row->sal_new==1)
                                                            <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                        @if($mobiles_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                            <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$mobiles_row->id)}}"><span>Quick View</span></a></div>
                                                        <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{url('/images/'.$mobiles_row->product_image)}}" alt=""></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$mobiles_row->product_name}}" href="#">{{$mobiles_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$mobiles_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">
                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$mobiles_row->id)}}" class="action add-to-cart btn" type="button" name="mobiles" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="books">
                                <div class="slider-items-products">
                                    <div id="men-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($books as $books_row) { ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($books_row->sal_new==1)
                                                            <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                        @if($books_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                            <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$books_row->id)}}"><span>Quick View</span></a></div>
                                                        <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{asset('/images/'.$books_row->product_image)}}" alt=""></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$books_row->product_name}}" href="#">{{$books_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$books_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">
                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$books_row->id)}}" class="action add-to-cart btn" type="button" name="books" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="furniture">
                                <div class="slider-items-products">
                                    <div id="kids-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($furniture as $furniture_row){ ?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        @if($furniture_row->sal_new==1)
                                                            <div class="icon-new-label new-left">New</div>
                                                        @endif
                                                        @if($furniture_row->sal_new==0)
                                                            <div class="icon-sale-label sale-left">Sale</div>
                                                        @endif
                                                        <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$furniture_row->id)}}"><span>Quick View</span></a></div>
                                                        <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{asset('/images/'.$furniture_row->product_image)}}" alt=""></a> </div>
                                                    <div class="pro-box-info">
                                                        <div class="item-info">
                                                            <div class="info-inner">
                                                                <div class="item-title"> <a title="{{$furniture_row->product_name}}" href="#">{{$furniture_row->product_name}} </a> </div>
                                                                <div class="item-content">
                                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                                    <div class="item-price">
                                                                        <div class="price-box"> <span class="regular-price"> <span class="price">{{$furniture_row->product_price}}</span> </span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="box-hover">
                                                            <div class="product-item-actions">
                                                                <div class="pro-actions">
                                                                    <a data-toggle="quickview" href="{{url('/quickview/'.$furniture_row->id)}}" class="action add-to-cart btn" type="button"  title="Add to Cart"> <span>Add to Cart</span> </a>
                                                                </div>
                                                                <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <?php }?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- Best selling -->
                    <div class="best-selling-slider">
                        <h3 class="products_title">Best selling</h3>
                        <div class="slider-items-products">
                            <div id="best-selling-slider" class="product-flexslider hidden-buttons">
                                <div class="slider-items slider-width-col4">
                                    @foreach($best_selling as $best_selling_products)
                                    <div class="product-item">
                                        <div class="item-inner">
                                            <div class="product-thumbnail">
                                                @if($best_selling_products->getProdUCt->sal_new==1)
                                                    <div class="icon-new-label new-left">New</div>
                                                @endif
                                                @if($best_selling_products->getProdUCt->sal_new==0)
                                                    <div class="icon-sale-label sale-left">Sale</div>
                                                @endif
                                                <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$best_selling_products->getProdUCt->id)}}"><span>Quick View</span></a></div>
                                                <a href="#" class="product-item-photo"> <img class="product-image-photo" src="{{asset('/images/'.$best_selling_products->getProdUCt->product_image)}}" alt=""></a> </div>
                                            <div class="pro-box-info">
                                                <div class="item-info">
                                                    <div class="info-inner">
                                                        <div class="item-title"> <a title="{{$best_selling_products->getProdUCt->product_name}}" href="#">{{$best_selling_products->getProdUCt->product_name}} </a> </div>
                                                        <div class="item-content">
                                                            <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                            <div class="item-price">
                                                                <div class="price-box"> <span class="regular-price"> <span class="price">{{$best_selling_products->getProdUCt->product_price}}</span> </span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-hover">
                                                    <div class="product-item-actions">
                                                        <div class="pro-actions">
                                                            <a data-toggle="quickview" href="{{asset(url('/quickview/'.$best_selling_products->getProdUCt->id))}}" class="btn action add-to-cart" type="button" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                        </div>
                                                        <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- prom banner-->
                    <div class="jtv-promotion">
                        <div class="wrap">
                            <div class="wow animated fadeInRight animated">
                                <div class="box jtv-custom">
                                    <div class="box-content">
                                        <div class="promotion-center">
                                            <p class="text_medium">end of season sale</p>
                                            <div class="text_large">
                                                <div class="theme-color">up to</div>
                                                50% off</div>
                                            <p class="text_small">Fashion for all outerwear, shirt &amp; accessories</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Featured Products -->
                    <div class="featured-products-slider">
                       <!--  <h3 class="products_title">Featured products</h3> -->
                        <div class="slider-items-products">
                            <div id="featured-products-slider" class="product-flexslider hidden-buttons">
                                <dsiv class="slider-items slider-width-col4">
                                    @foreach($featured_products as $featured_products_row)
                                    <div class="product-item">
                                        <div class="item-inner">
                                            <div class="product-thumbnail">
                                                @if($featured_products_row->getProducT->sal_new==1)
                                                    <div class="icon-new-label new-left">New</div>
                                                @endif
                                                @if($featured_products_row->getProducT->sal_new==0)
                                                    <div class="icon-sale-label sale-left">Sale</div>
                                                @endif
                                                <div class="btn-quickview"> <a data-toggle="quickview" href="{{url('/quickview/'.$featured_products_row->getProducT->id)}}"><span>Quick View</span></a></div>
                                                <div class="add-to-links"> <a href="#" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="#" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                <a href="single_product.blade.php" class="product-item-photo"> <img class="product-image-photo" src="{{asset('/images/'.$featured_products_row->getProducT->product_image)}}" alt=""></a> </div>
                                            <div class="pro-box-info">
                                                <div class="item-info">
                                                    <div class="info-inner">
                                                        <div class="item-title"> <a title="{{$featured_products_row->getProducT->product_image}}" href="#">{{$featured_products_row->getProducT->product_name}}</a> </div>
                                                        <div class="item-content">
                                                            <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                            <div class="item-price">
                                                                <div class="price-box"> <span class="regular-price"> <span class="price">{{$featured_products_row->getProducT->product_price}}</span> </span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-hover">
                                                    <div class="product-item-actions">
                                                        <div class="pro-actions">
                                                            <a data-toggle="quickview" href="{{url('/quickview/'.$featured_products_row->getProducT->id)}}" class="btn action add-to-cart" type="button" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        @endforeach
                                </dsiv>
                            </div>
                        </div>
                    </div>

                    <!-- Latest news start -->

                  
                </div>
                <aside class="sidebar col-md-3 col-sm-4 col-xs-12 col-sm-pull-8 col-md-pull-9">
                    <div class="best-sale-product">
                        <div class="sidebar-bar-title">
                            <h3>Best sale of week</h3>
                        </div>
                        <div class="block-content">
                            <div class="slider-items-products">
                                <div id="best-sale-slider" class="product-flexslider hidden-buttons">
                                    <div class="slider-items slider-width-col4">
                                        <div class="product-item">
                                            @foreach ($best_products as $best_products_of_week)
                                            <div class="best-sale-item">
                                                <div class="products-block-left"> <a data-toggle="quickview" href="{{url('/quickview/'.$best_products_of_week->getproduct->id)}}" title="best sale of week Product" class="product-image"><img src="{{asset('/images/'.$best_products_of_week->getproduct->product_image)}}" alt="Sample Product "></a></div>
                                                <div class="products-block-right">
                                                    <p class="product-name"> <a data-toggle="quickview" href="{{url('/quickview/'.$best_products_of_week->getproduct->id)}}">{{$best_products_of_week->getproduct->product_name}}</a> </p>
                                                    <span class="price">{{$best_products_of_week->getproduct->product_price}}</span>
                                                    <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Hot deal -->
                    <div class="hot-products">
                        <div class="hot-deal"> <span class="title-text">Hot deal</span>
                            <ul class="products-grid">
                                @if($hot_deal=="")
                                    <li class="item">
                                        <b>&nbsp;&nbsp;&nbsp;No Product for hot deal at this time</b>
                                    </li>
                                @else
                                <li class="item" id="hot_deal_item">
                                    <div class="product-item">
                                        <div class="item-inner">
                                            <div class="product-thumbnail">
                                                <div class="icon-hot-label hot-right">Hot</div>
                                                <div class="btn-quickview"> <a  data-toggle="quickview" href="{{url('/quickview/'.$hot_deal->getProdUct->id)}}"><span>Quick View</span></a></div>
                                                <a data-toggle="quickview" href="{{url('/quickview/'.$hot_deal->getProdUct->id)}}"> <img class="product-image-photo" src="{{asset('images/'.$hot_deal->getProdUct->product_image)}}" alt=""></a>
                                                <div class="jtv-box-timer">
                                                    <div class="countbox_1 jtv-timer-grid"></div>
                                                </div>
                                            </div>
                                            <div class="pro-box-info">
                                                <div class="item-info">
                                                    <div class="info-inner">
                                                        <div class="item-title"> <a title="{{$hot_deal->getProdUct->product_name}}" href="#">{{$hot_deal->getProdUct->product_name}} </a> </div>
                                                        <div class="item-content">
                                                            <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> </div>
                                                            <div class="item-price">
                                                                <div class="price-box"> <span class="regular-price"> <span class="price">Rs. {{$hot_deal->hot_price}}</span>  </span> <small class="pre-cost">Rs. {{$hot_deal->getProdUct->product_price}}</small></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-hover">
                                                    <div class="product-item-actions">
                                                        <div class="pro-actions">
                                                            <a data-toggle="quickview" href="{{url('/quickview/'.$hot_deal->getProdUct->id)}}" class="btn action add-to-cart" type="button" title="Add to Cart"> <span>Add to Cart</span> </a>
                                                        </div>
                                                        <div class="add-to-links" data-role="add-to-links"> <a href="account_setting.blade.php" class="action add-to-wishlist" title="Add to Wishlist"> <span>Wishlist</span> </a> <a href="compare.blade.php" class="action add-to-compare" title="Add to Compare"> <span>Compare</span> </a> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                    @endif
                            </ul>
                        </div>
                    </div>
                    <!--Hot deal -->
                    <div class="banner4"><a href="#"><img alt="banner" src="{{asset('images/top-banner1.jpg')}}"></a></div>
                    <div class="block special-product">
                        <div class="sidebar-bar-title">
                            <h3>Special Products</h3>
                        </div>
                        <div class="block-content">
                            <ul>
                                @foreach($special_products as $special_products_row)
                                <li class="item">
                                    <div class="products-block-left"> <a href="#" title="Product" class="product-image"><img src="{{asset('/images/'.$special_products_row->getProducT->product_image)}}" alt="Product image "></a></div>
                                    <div class="products-block-right">
                                        <p class="product-name"> <a data-toggle="quickview" href="{{url('/quickview/'.$special_products_row->getProducT->id)}}">{{$special_products_row->getProducT->product_name}}</a> </p>
                                        <span class="price">{{$special_products_row->getProducT->product_price}}</span>
                                        <div class="rating"> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Testimonials Box -->
                    <div class="testimonials">
                        <div class="slider-items-products">
                            <div id="testimonials-slider" class="product-flexslider hidden-buttons home-testimonials">
                                <div class="slider-items slider-width-col4 ">
                                    <div class="holder">
                                        <p>sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                            minim veniam, quis nostrud exercitation. </p>
                                        <div class="thumb"> <img src="{{asset('images/testimonials-img3.jpg')}}" alt="testimonials img"> </div>
                                        <div class="author"> <strong class="name">John Doe</strong> <strong class="designation">CEO, Company</strong></div>
                                    </div>
                                    <div class="holder">
                                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                                            ex ea commodo consequat. </p>
                                        <div class="thumb"> <img src="{{asset('images/testimonials-img1.jpg')}}" alt="testimonials img"> </div>
                                        <div class="author"> <strong class="name">Vince Roy</strong> <strong class="designation">CEO, Newspaper</strong></div>
                                    </div>
                                    <div class="holder">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                            eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                        <div class="thumb"> <img src="{{asset('images/testimonials-img2.jpg')}}" alt="testimonials img"> </div>
                                        <div class="author"> <strong class="name">John Doe</strong> <strong class="designation">CEO, ABC Softwear</strong></div>
                                    </div>
                                    <div class="holder">
                                        <p>sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                            minim veniam, quis nostrud exercitation.</p>
                                        <div class="thumb"> <img src="{{asset('images/testimonials-img4.jpg')}}" alt="testimonials img"> </div>
                                        <div class="author"> <strong class="name">Vince Roy</strong> <strong class="designation">CEO, XYZ Softwear</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Testimonials Box -->
                    <div class="block popular-tags-area ">
                        <div class="sidebar-bar-title">
                            <h3>Popular Tags</h3>
                        </div>
                        <div class="tag">
                            <ul>
                                <li><a href="#">Boys</a></li>
                                <li><a href="#">Camera</a></li>
                                <li><a href="#">good</a></li>
                                <li><a href="#">Computers</a></li>
                                <li><a href="#">Phone</a></li>
                                <li><a href="#">clothes</a></li>
                                <li><a href="#">girl</a></li>
                                <li><a href="#">shoes</a></li>
                                <li><a href="#">women</a></li>
                                <li><a href="#">accessoties</a></li>
                                <li><a href="#">View All Tags</a></li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- end main container -->

    <!-- category area start -->
   <!--  -->
    <!-- category-area end -->

    <!-- our clients Slider -->

    <div class="our-clients">
        <div class="slider-items-products">
            <div id="our-clients-slider" class="product-flexslider hidden-buttons">
                <div class="slider-items slider-width-col6">

                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="{{asset('images/brand1.png')}}" alt="Image"></a> </div>
                    <!-- End Item -->

                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="{{asset('images/brand2.png')}}" alt="Image"></a> </div>
                    <!-- End Item -->

                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="{{asset('images/brand3.png')}}" alt="Image"></a> </div>
                    <!-- End Item -->

                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="{{asset('images/brand4.png')}}" alt="Image"></a> </div>
                    <!-- End Item -->
                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="images/brand5.png" alt="Image"></a> </div>
                    <!-- End Item -->
                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="{{asset('images/brand6.png')}}" alt="Image"></a> </div>
                    <!-- End Item -->
                    <!-- Item -->
                    <div class="item"> <a href="#"><img src="{{asset('images/brand7.png')}}" alt="Image"></a> </div>
                    <!-- End Item -->

                </div>
            </div>
        </div>
    </div>
    <div class="bottom-service" style="background-color: #FCAA00!important;">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="bottom-service-box">
                    <div class="box-outer">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="service-box-center"> <span class="bottom-service-title">Buy 2 items</span>
                                <div class="description">
                                    <div class="heading">Get one for free!</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 column">
                            <div class="service-box-center"> <span class="bottom-service-title">Daily Sales</span>
                                <div class="description">
                                    <div class="heading">Today up to 45% off!</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 column">
                            <div class="service-box-center"> <span class="bottom-service-title">NEW ARRIVAL</span>
                                <div class="description">
                                    <div class="heading">Sale up to 75% off</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 column">
                            <div class="service-box-center"> <span class="bottom-service-title">UpTO 45% off</span>
                                <div class="description">
                                    <div class="heading">Your first purchase</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->

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

    <!-- End Footer -->


    <!--Newsletter Popup Start
    <div id="myModal" class="modal fade">
        <div class="modal-dialog newsletter-popup">
            <div class="modal-content"> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <div class="modal-body">
                    <h4 class="modal-title">Don't Miss out</h4>
                    <form id="newsletter-form" method="post" action="#">
                        <div class="content-subscribe">
                            <div class="form-subscribe-header">
                                <label>Subscribe to be the first to know about Sales, Events, and Exclusive Offers!</label>
                            </div>
                            <div class="input-box">
                                <input type="text" class="input-text newsletter-subscribe" title="Sign up for our newsletter" name="email" placeholder="Enter your email address">
                            </div>
                            <div class="actions">
                                <button class="button-subscribe" title="Subscribe" type="submit">Subscribe</button>
                            </div>
                        </div>
                    </form>
                    <div class="subscribe-bottom">
                        <input name="notshowpopup" id="notshowpopup" type="checkbox">
                        Don’t show this popup again </div>
                </div>

            </div>
        </div>
    </div>
    End of Newsletter Popup-->

    <!--   quick view Start -->
    <div class="modal fade product_view" id="product_view" data-height="1150">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: white;">
                <div class="modal-header">
                    <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <!-- End of quik view -->

</div>



<!-- JS -->

<!-- jquery js -->
<script type="text/javascript" src="js/jquery.min.js"></script>

<!-- bootstrap js -->
<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

<!-- Mean Menu js -->
<script type="text/javascript" src="{{asset('js/jquery.meanmenu.min.js')}}"></script>

<!-- owl.carousel.min js -->
<script type="text/javascript" src="{{asset('js/owl.carousel.min.js')}}"></script>

<!-- bxslider js -->
<script type="text/javascript" src="{{asset('js/jquery.bxslider.js')}}"></script>

<!--jquery-ui.min js -->
<script type="text/javascript" src="{{asset('js/jquery-ui.js')}}"></script>

<!-- countdown js -->
<script type="text/javascript" src="{{asset('js/countdown.js')}}"></script>

<!-- wow JS -->
<script type="text/javascript" src="{{asset('js/wow.min.js')}}"></script>

<!-- main js -->
<script type="text/javascript" src="{{asset('js/main.js')}}"></script>

<!-- nivo slider js -->
<script type="text/javascript" src="{{asset('js/jquery.nivo.slider.js')}}"></script>

<!-- flexslider js -->
<script type="text/javascript" src="{{asset('js/jquery.flexslider.js')}}"></script>
<!-- Hot Deals Timer 1-->
<script type="text/javascript">
    var dthen1 = new Date("<?php if($hot_deal==""){echo "0000-00-00  00:00:00" ;} else{echo $hot_deal->from_date; } ?>");
    start = "<?php if($hot_deal==""){echo "0000-00-00  00:00:00" ;} else{echo $hot_deal->to_date; } ?>";
    start_date = Date.parse(start);
    var dnow1 = new Date(start_date);
    if(CountStepper>0)
        ddiff= new Date((dnow1)-(dthen1));
    else
        ddiff = new Date((dthen1)-(dnow1));
    gsecs1 = Math.floor(ddiff.valueOf()/1000);

    if(gsecs1=="" || gsecs1==0)
    {
        $('#hot_deal_item').html('<p align="center">Time out for hot deal</p>');
    }

    var iid1 = "countbox_1";
    CountBack_slider(gsecs1,"countbox_1", 1);
</script>
<!-- Revolution Slider -->
<script type="text/javascript" src="{{asset('js/revslider.js')}}"></script>
<!-- costum java script files -->
<script>var tpj=jQuery;
    var bannerslide;
    tpj(document).ready(function() {
        if(tpj("#banner-slide").revolution == undefined){
            revslider_showDoubleJqueryError("#banner-slide");
        }else{
            bannerslide = tpj("#banner-slide").show().revolution({
                sliderType:"standard",
                sliderLayout:"auto",
                dottedOverlay:"none",
                delay:9000,
                navigation: {
                    keyboardNavigation:"off",
                    keyboard_direction: "horizontal",
                    mouseScrollNavigation:"off",
                    mouseScrollReverse:"default",
                    onHoverStop:"off",
                    touch:{
                        touchenabled:"on",
                        swipe_threshold: 75,
                        swipe_min_touches: 1,
                        swipe_direction: "horizontal",
                        drag_block_vertical: false
                    }
                    ,
                    arrows: {
                        enable: false,
                        style: 'erinyen',
                        tmp: '',
                        rtl: false,
                        hide_onleave: false,
                        hide_onmobile: true,
                        hide_under: 0,
                        hide_over: 9999,
                        hide_delay: 200,
                        hide_delay_mobile: 1200,

                        left: {
                            container: 'slider',
                            h_align: 'left',
                            v_align: 'center',
                            h_offset: 20,
                            v_offset: 0
                        },

                        right: {
                            container: 'slider',
                            h_align: 'right',
                            v_align: 'center',
                            h_offset: 20,
                            v_offset: 0
                        }
                    },

                    bullets: {

                        enable: true,
                        style: 'uranus',
                        tmp: '<span class="tp-bullet-inner"></span>',
                        direction: 'horizontal',
                        rtl: false,

                        container: 'slider',
                        h_align: 'center',
                        v_align: 'bottom',
                        h_offset: 0,
                        v_offset: 20,
                        space: 5,

                        hide_onleave: false,
                        hide_onmobile: false,
                        hide_under: 0,
                        hide_over: 9999,
                        hide_delay: 200,
                        hide_delay_mobile: 1200

                    }
                },
                viewPort: {
                    enable:true,
                    outof:"pause",
                    visible_area:"80%",
                    presize:false
                },
                responsiveLevels:[1240,1024,778,480],
                visibilityLevels:[1240,1024,778,480],
                gridwidth:[1240,1024,778,480],
                gridheight:[700,600,500,400],
                lazyType:"none",
                parallax: {
                    type:"mouse",
                    origo:"slidercenter",
                    speed:2000,
                    levels:[2,3,4,5,6,7,12,16,10,50,46,47,48,49,50,55],
                    type:"mouse",
                },
                shadow:0,
                spinner:"off",
                stopLoop:"off",
                stopAfterLoops:-1,
                stopAtSlide:-1,
                shuffle:"off",
                autoHeight:"off",
                hideThumbsOnMobile:"off",
                hideSliderAtLimit:0,
                hideCaptionAtLimit:0,
                hideAllCaptionAtLilmit:0,
                debugMode:false,
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                }
            });
        }
    }); /*ready*/
/////---------------------------------------------------------------------------------

    $('#login_register_btn').on('click',function () {
       $('#pageLoad_Area').empty();
       $('#pageLoad_Area').load('/signup');
       $('#login-modal').modal('toggle');
    });

    // loading about us page
    $('#aboutUs').on('click',function () {
        $('#pageLoad_Area').load('/about_us');
    });

    // loading contact us page
    $('#contactUs').on('click',function () {
        $('#pageLoad_Area').load('/contact_us');
    });

    // old mobiles
    $('#old_mobile_page').on('click',function () {
        $('#pageLoad_Area').load('/shopping_grid/'+1);
    });
    // old books
    $('#old_book_page').on('click',function () {
        $('#pageLoad_Area').load('/shopping_grid/'+2);
    });
    // old mobiles
    $('#old_furniture_page').on('click',function () {
        $('#pageLoad_Area').load('/shopping_grid/'+3);
    });

    //entrepreneur_login
    $(document).on('click','a[data-toggle=entrepreneur_login]',function () {
       event.preventDefault();
       $('#pageLoad_Area').load($(this).attr('href'));
    });

    // loading shopping cart
    $('.viewcart').on('click',function () {
        $('#pageLoad_Area').load('/view_cart');
    });

    // DashBoard
    $('#user_dashboard').on('click',function () {
        location.href="/index";
    });
    // my orders
    $('#myorders').on('click',function () {
        $('#pageLoad_Area').load('/my_orders');
    });

    // profile setting index
    $('#profile_setting').on('click',function () {
        var $id   = $('#authenticate_user').val();
        $('#pageLoad_Area').load('/acct_setting/'+$id);
    });

    // Privacy setting index
    $('#privacy_setting').on('click',function () {
        $('#pageLoad_Area').load('/privacy_index');
    });
    // replace items index throu
    $('.replace_items').on('click',function () {
        $('#pageLoad_Area').load('replace_index');
    });
    // company registration
    $('#company_registration').on('click',function () {
        $('#pageLoad_Area').load('/company_registration');
    });
    // payment detail index
    $('#payment_detail').on('click',function () {
        var userid   = $('#authenticate_user').val();
       $('#pageLoad_Area').load('/index_payment/'+userid);
       $('#pageLoad_Area').load('/index_payment/'+userid);
    });

    // Quick view
    $(document).on('click','a[data-toggle=quickview]',function () {
        event.preventDefault();
        $('#product_view').find('.modal-body').load($(this).attr('href'));
        $('#product_view').modal('show');
    });

    // add items to whishlist
    $(document).on('click','a[data-toggle=whishlist]',function () {
        event.preventDefault();
        $.ajax({
            url  : $(this).attr('href'),
            type : 'get',
            dataType : 'json',
            success : function (result) {
                $('#whishlist_count').html(result);
            } ,
            error : function (result) {
               console.log('in error');
            }
        });
    });


    // Delete Cart Items
    $(document).on('click','a[data-toggle=delete_cart_item]',function () {
        event.preventDefault();
        // variables for accessing shopping cart
        var basket = '';
        var productlist = '';
        var subtotals   = '';
        $.ajax({
            url  : $(this).attr('href'),
            type : 'get',
            dataType : 'json',
            success : function (result) {
                $.each(result.items,function (i,item) {
                    productlist += '<li class="item odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="/images/'+item['item']['product_image']+'" alt="Lorem ipsum dolor" width="65"></a><div class="product-details"> <a data-toggle="delete_cart_item" href="/delete_items/'+item['item']['id']+'" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a> <p class="product-name"><a href="#">'+item['item']['product_name']+'</a></p><strong>'+item['qty']+'</strong> x <span class="price">'+item['price']+'</span><span> = '+item['qty']*item['price']+'</span></div>';
                });
                basket += '<a href="#"><div class="cart-icon"><i class="fa fa-shopping-cart"></i></div><div class="shoppingcart-inner"><span class="cart-title">Shopping Cart</span> <span class="cart-total">'+result['total_quantity']+' Item(s): Rs.'+result['total_price']+'</span></div></a>';
                subtotals+= 'Subtotal: <span class="price">Rs. '+result['total_price']+'</span>';
                $('.basket').html(basket);
                $('#cart-sidebar').html(productlist);
                $('.top-subtotal').html(subtotals   );
            } ,
            error : function (result) {
                console.log('in error');
            }
        });
    });
// News letters subscription
    $('#newsletter_submit_form').submit(function () {
        event.preventDefault();
        $.ajax({
            url  : '/news_letters',
            type : 'post',
            data : $('#newsletter_submit_form').serialize(),
            dataType : 'json',
            success : function (result) {
                var str = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Subscribe!</strong> '+result+' </div>';
                $('#alert_news_letter').html(str);
            } ,
            error : function (result) {
                console.log('in error');
            }
        });
    });


</script>

