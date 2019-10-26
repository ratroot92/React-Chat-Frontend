<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<!-- Costum files-->
<link href="{{asset('/custom_files/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
<style>
    a[aria-expanded=true] .fa-chevron-right {
        display: none;
    }
    a[aria-expanded=false] .fa-chevron-up {
        display: none;}
</style>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul>
                    <li class="home"> <a title="Go to Home Page" href="#">Home</a><span>&raquo;</span></li>
                    <li class=""> <a title="Go to Home Page" href="#">Rown.pk Member </a><span>&raquo;</span></li>
                    <li><strong>Entrepreneur</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumbs End -->
<!-- Main Container -->
<div class="main-container col2-left-layout" id="page">
    <div class="container">
        <div class="header-top" style="background-color: lightgreen;">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-5 col-xs-6">
                        <!-- Default Welcome Message -->
                        <span class="phone hidden-xs hidden-sm"><i class="fa fa-phone fa-lg"></i> {{Auth::guard('entr_vendr')->user()->contact_no }}</span>
                        <div class="welcome-msg hidden-xs">{{Auth::guard('entr_vendr')->user()->name }} Welcome to Vendor Dashboard! </div>
                    </div>

                    <!-- top links -->
                    <div class="headerlinkmenu col-lg-6 col-md-6 col-sm-7 col-xs-6 text-right">
                        <div class="links">
                            <div class="jtv-user-info" >
                            <!-- <a class="dropdown-item" href="{{ url('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="color: white;"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                <form id="logout-form" action="{{ url('entr_vendor/logout') }}" method="POST" style="display: none;">
                                    @csrf
                                    </form> -->
                                <a data-toggle="loguot_entr" href="{{url('/entr_vendor/logout')}}" style="color: white;"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                            </div>
                            <div class="services hidden-xs"><a title="servicesg" href="#"></a></div>
                            <div class="myaccount hidden-xs"><a title="My Account" href="#"></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br><br>
        <div class="row">
            <div class="col-main col-sm-9 col-xs-12 col-sm-push-3" id="my_section" style="border:1px solid black;margin-bottom: 13px;">

                <div class="shop-inner">
                    <div class="page-title">
                        <h2>Dashboard</h2>
                    </div>

                    <div class="product-grid-area">
                        <ul class="products-grid" style="margin-left: 30px;">
                            <li class="item col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <div class="card w-50" style="background-color: orange;height: 100px;width: 120px;">
                                    <div class="card-body">
                                        <br>
                                        <h3 class="card-title" align="center">15</h3>
                                        <p align="center">Total Applications</p>
                                    </div>
                                </div>
                            </li>
                            <li class="item col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <div class="card w-50" style="background-color: lightblue;height: 100px;width: 120px;">
                                    <div class="card-body">
                                        <br>
                                        <h3 class="card-title" align="center">15</h3>
                                        <p align="center">Approve Applications</p>
                                    </div>
                                </div>
                            </li>
                            <li class="item col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <div class="card w-50" style="background-color: lightsalmon;height: 100px;width: 120px;">
                                    <div class="card-body">
                                        <br>
                                        <h3 class="card-title" align="center">15</h3>
                                        <p align="center">Pending Application</p>
                                    </div>
                                </div>
                            </li>
                            <li class="item col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <div class="card w-50" style="background-color: rosybrown;height: 100px;width: 120px;">
                                    <div class="card-body">
                                        <br>
                                        <h3 class="card-title" align="center">15</h3>
                                        <p align="center">Completed Applications</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr> <br>
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Application No</th>
                                <th>Title</th>
                                <th>Application For</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>001</td>
                                <td>MY Application</td>
                                <td>For books</td>
                                <td>Pending</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                            <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu pull-left" role="menu">
                                            <li>
                                                <a href="#">
                                                    <i class="icon-trash"></i> Delete </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Application No</th>
                                <th>Title</th>
                                <th>Application For</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <aside class="sidebar col-sm-3 col-xs-12 col-sm-pull-9">
                <div class="block shop-by-side">
                    <div class="sidebar-bar-title">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="block-content">
                        <p class="block-subtitle">Select an Option</p>
                        <div class="layered-Category">
                            <h2 class="saider-bar-title">Vendor Applications  <a data-toggle="collapse" href="#collapse1"
                                                                      aria-expanded="false">
                                    <i class="fa fa-chevron-right pull-right"></i>
                                    <i class="fa fa-chevron-up pull-right"></i>
                                </a>
                            </h2>
                            <div class="layered-content">
                                <ul class="check-box-list" id="collapse1" class="collapse out">
                                    <li>All Application</li>
                                    <li><a data-toggle="apply" href="{{url('entr_vendor/vendr_apply')}}">Apply New</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="color-area">
                            <h2 class="saider-bar-title">Color</h2>
                            <div class="color">
                                <ul>
                                    <li><a href="#"></a></li>
                                    <li><a href="#"></a></li>
                                    <li><a href="#"></a></li>
                                    <li><a href="#"></a></li>
                                    <li><a href="#"></a></li>
                                    <li><a href="#"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
<div class="container">
    <div class="header-top" style="background-color: lightgreen;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-5 col-xs-6">
                    <!-- Default Welcome Message -->
                    <span class="phone hidden-xs hidden-sm"><i class="fa fa-phone fa-lg"></i> {{Auth::guard('entr_vendr')->user()->contact_no }}</span>
                    <div class="welcome-msg hidden-xs">{{Auth::guard('entr_vendr')->user()->contact_no }} Welcome to Entrepreneur Dashboard! </div>
                </div>
            </div>
        </div>
    </div>
</div><br>
<!-- Main Container End -->
<!-- our clients Slider -->
<script src="{{asset('/custom_files/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });

    $(document).on('click','a[data-toggle="apply"]',function () {
        event.preventDefault();
        $('#my_section').load($(this).attr('href'));
    });

    //loguot_entr
    $(document).on('click','a[data-toggle="loguot_entr"]',function () {
        event.preventDefault();
        $.ajax({
            url        : $(this).attr('href'),
            type       : 'get',
            dataType   : 'json',
            success    : function (result) {
                $('#pageLoad_Area').empty();
                $('#pageLoad_Area').load('/entrpreneur/'+result+'');
            },
            error      : function (result) {
                console.log('in error')
            }
        })
    });
</script>