
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
<link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo1.png')}}">

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
</head>

<body class="cms-index-index cms-home-page">

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
                                    @else
                                        Welcome to NewLook!
                                    @endauth
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- top links -->
                    <div class="headerlinkmenu col-lg-6 col-md-6 col-sm-7 col-xs-6 text-right">
                        <div class="links">
                            <div class="jtv-user-info">
                                <div class="dropdown"> <a class="current-open" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"><span>My Account </span> <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="account_page.blade.php" style="color: black">Account</a></li>
                                        <li><a href="wishlist.blade.php" style="color: black">Wishlist</a></li>
                                        <li><a href="checkout.blade.php" style="color: black">Checkout</a></li>
                                        <li class="divider" style="color: black"></li>
                                        <li><a href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="color: black">Log Out</a></li>
                                    </ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                            <div class="services hidden-xs"><a title="servicesg" href="#">Services</a></div>
                            <div class="myaccount hidden-xs"><a title="My Account" href="#">My Support</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3 col-xs-12">
                    <!-- Header Logo -->
                    <div class="logo"><a title="e-commerce" href="{{url('index')}}"><img alt="e-commerce" src="{{asset('images/logo1.png')}}" height="8%" width="90%"></a></div>
                    <!-- End Header Logo -->
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-6">
                    <!-- Search -->

                    <div class="top-search">
                        <div id="search">
                            <form>
                                <div class="input-group">
                                    <select class="cate-dropdown hidden-xs hidden-sm" name="category_id">
                                        <option>All Categories</option>
                                        <option>OLD</option>
                                        <option>&nbsp;&nbsp;&nbsp;Mobiles </option>
                                        <option>&nbsp;&nbsp;&nbsp;Books</option>
                                        <option>&nbsp;&nbsp;&nbsp;Furniture</option>
                                        <option>NEW</option>
                                        <option>&nbsp;&nbsp;&nbsp;Mobiles </option>
                                        <option>&nbsp;&nbsp;&nbsp;Books </option>
                                        <option>&nbsp;&nbsp;&nbsp;Furniture </option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="Search" name="search">
                                    <button class="btn-search" type="button" ><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- End Search -->
                </div>
                <div class="col-lg-3 col-sm-4 col-xs-12 top-cart">
                    <div class="link-wishlist"> <a href="#"> <span class="wishlist-count">3</span> <i class="fa fa-heart fa-lg"></i> </a> </div>
                    <div class="top-cart-contain">
                        <div class="mini-cart">
                            <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle"> <a href="#">
                                    <div class="cart-icon"><i class="fa fa-shopping-cart"></i></div>
                                    <div class="shoppingcart-inner"><span class="cart-title">Shopping Cart</span> <span class="cart-total">4 Item(s): $520.00</span></div>
                                </a></div>
                            <div>
                                <div class="top-cart-content">
                                    <div class="block-subtitle hidden-xs">Recently added item(s)</div>
                                    <ul id="cart-sidebar" class="mini-products-list">
                                        <li class="item odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="{{asset('images/products/img07.jpg')}}" alt="Lorem ipsum dolor" width="65"></a>
                                            <div class="product-details"> <a href="#" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a>
                                                <p class="product-name"><a href="#">Lorem ipsum dolor sit amet Consectetur</a> </p>
                                                <strong>1</strong> x <span class="price">$20.00</span> </div>
                                        </li>
                                        <li class="item even"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="{{asset('images/products/img08.jpg')}}" alt="Lorem ipsum dolor" width="65"></a>
                                            <div class="product-details"> <a href="#" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a>
                                                <p class="product-name"><a href="#">Consectetur utes anet adipisicing elit</a> </p>
                                                <strong>1</strong> x <span class="price">$230.00</span> </div>
                                        </li>
                                        <li class="item last odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="{{asset('images/products/img10.jpg')}}" alt="Lorem ipsum dolor" width="65"></a>
                                            <div class="product-details"> <a href="#" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a>
                                                <p class="product-name"><a href="#">Sed do eiusmod tempor incidist</a> </p>
                                                <strong>2</strong> x <span class="price">$420.00</span> </div>
                                        </li>
                                    </ul>
                                    <div class="top-subtotal">Subtotal: <span class="price">$520.00</span></div>
                                    <div class="actions">
                                        <button onclick="location.href='checkout.html'" class="btn-checkout" type="button"><i class="fa fa-check"></i><span>Checkout</span></button>
                                        <button onclick="location.href='shopping_cart.blade.php'"  class="view-cart" type="button"><i class="fa fa-shopping-cart"></i> <span>View Cart</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <nav>
                        <ul>
                            <li class="active custom-menu"><a href="{{url('index')}}">Home</a>
                            <li class="megamenu"><a href="shop_grid.blade.php">Shop</a>
                                <div class="mega-menu">
                    <span>
                      <a class="mega-title" href="#">Request For Replace</a>
                      <a href="shop_grid.blade.php">Mobiles</a>
                      <a href="shop_grid.blade.php">Books</a>
                      <a href="shop_grid.blade.php">Furniture</a>
                    </span>
                                    <span>
                      <a class="mega-title" href="#">Buy Old </a>
                      <a href="shop_grid.blade.php">Mobiles</a>
                      <a href="shop_grid.blade.php">Books</a>
                      <a href="shop_grid.blade.php">Furniture</a>
                    </span>
                                    <span>
                      <a class="mega-title" href="#">Buy New </a>
                     <a href="shop_grid.blade.php">Mobiles</a>
                      <a href="shop_grid.blade.php">Books</a>
                      <a href="shop_grid.blade.php">Furniture</a>
                    </span>
                                    <span class="mega-menu-img hidden-sm"> <a href="shop_grid.blade.php">
                        <img src="{{asset('images/menu-img3.jpg')}}" alt="Bannar 3"></a>
                    </span>
                                </div>
                            </li>
                            <li class="custom-menu"><a href="blog.blade.php">Listings</a>
                                <ul class="dropdown">
                                    <li> <a href="blog_right_sidebar.blade.php"> Ad Listing </a></li>
                                    <li> <a href="blog_left_sidebar.blade.php"> Listing Detail </a></li>
                                </ul>
                            </li>
                            <li class="megamenu"><a href="{{url('/about_us')}}">About Us</a>

                            </li>
                            <li class="megamenu"><a href="{{url('/contact_us')}}">Contact Udds</a>

                            </li>
                            <li class="custom-menu"><a href="blog.blade.php">News</a>
                                <ul class="dropdown">
                                    <li> <a href="blog_right_sidebar.blade.php">News List </a></li>
                                    <li> <a href="blog_left_sidebar.blade.php"> News Detail </a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <!-- Signup -->
                    <p class="top-Signup"><a href="#" class="" role="button" data-toggle="modal" data-target="#login-modal">Login/Signup</a></p>
                    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header"> <img id="img_logo" src="{{asset('images/logo1.png')}}" alt="logo" height="20%">
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
                                                    <input type="checkbox">
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
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END # MODAL LOGIN -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Menu Area -->

