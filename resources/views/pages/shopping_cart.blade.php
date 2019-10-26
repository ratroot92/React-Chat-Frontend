
<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
        <div class="col-main">
            <div class="cart">

                <div class="page-content page-order"><div class="page-title">
                        <h2>Shopping Cart</h2>
                        <div id="alermsg_order"></div>
                    </div>
                    <div class="order-detail-content">
                        <div class="table-responsive">
                            <table class="table table-bordered cart_summary">
                                <thead>
                                <tr>
                                    <th class="cart_product">Product</th>
                                    <th>Description</th>
                                    <th>Avail.</th>
                                    <th>Unit price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th  class="action"><i class="fa fa-trash-o"></i></th>
                                </tr>
                                </thead>
                                <tbody id="termsrow">
                                @if($cart!=null)
                                    @foreach($cart->items as $cart_row)
                                    <tr id="row{{$cart_row['item']['id']}}">
                                        <td class="cart_product"><a href="#"><img src="{{asset('/images/'.$cart_row['item']['product_image'])}}" alt="Product"></a></td>
                                        <td class="cart_description"><p class="product-name"><a href="#">{{$cart_row['item']['product_name']}}</a></p>
                                            <small><a href="#">Color : Red</a></small><br>
                                            <small><a href="#">Size : M</a></small></td>
                                        <td class="availability in-stock"><span class="label">In stock</span></td>
                                        <td class="price"><span>{{$cart_row['item']['product_price']}}</span></td>
                                        <td class="qty"><input class="form-control input-sm" type="text" value="{{$cart_row['qty']}}"></td>
                                        <td class="price"><span>{{$cart_row['price']}}</span></td>
                                        <td class="action"><a data-toggle="delete_cart_item" href="{{url('/delete_items/'.$cart_row['item']['id'])}}"><i class="icon-close"></i></a></td>
                                    </tr>
                                @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                <tr class="first last">
                                    <td colspan="50" class="a-right last"><button type="button" title="total items" class="button btn-continue" ><span id="totalItems">Total Items: <?php if($cart!=null)echo $cart->total_quantity;?></span></button>
                                        <button type="submit" name="update_cart_action" value="update_qty" title="Update Cart" class="button btn-update"><span>Update Cart</span></button>
                                        <a  name="empty_cart_button" value="empty_cart" title="Clear Cart" class="btn btn-danger" id="empty_cart_button"><span>Clear Cart</span></a></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>

                    <div class="cart-collaterals row">
                        <div class="col-sm-4">
                            <div class="shipping">
                                <h3>Delivery Information</h3>
                                <div class="shipping-form">
                                    <form id="shipping_zip_form" method="post" role="form">
                                        {{csrf_field()}}
                                        <p>Enter your destination to get a shipping at your door.</p>
                                        <ul class="form-list">
                                            <li>
                                                <label for="country" class="required">City<em>*</em></label>
                                                <div class="input-box">
                                                    <select name="city" id="city" class="validate-select" title="city" required>
                                                        <option value="1">Rawalpandi</option>
                                                        <option value="0">Islamabad</option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label for="postcode">Enter Your Zone*</label>
                                                <div class="input-box">
                                                    <input type="text" class="input-text validate-postcode" id="zone" name="zone" value="" required>
                                                </div>
                                            </li>
                                            <li>
                                                <label for="postcode">Full Address*</label>
                                                <div class="input-box">
                                                    <input type="text" class="input-text validate-postcode" id="address" name="address" value="" required>
                                                </div>
                                            </li>
                                        </ul>
                                        <div id="alrtmsg_delivery"></div>
                                        <div class="buttons-set11">
                                            <button type="submit" title="Delivery info" class="button get-quote"><span>Submit</span></button>
                                        </div>
                                        <!--buttons-set11-->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="discount">
                                <h3>Discount Codes</h3>
                                <form id="discount_coupon_form"  method="post">
                                    {{csrf_field()}}
                                    <label for="coupon_code">Enter your promo code if you have one.</label>
                                    <input type="hidden" name="on_total_amount" id="on_total_amount" value="<?php if($cart!=null)echo $cart->total_price;?>">
                                    <input type="text" class="input-text fullwidth" id="promo_code" name="promo_code" value="" required><br><br>
                                    <p id="error_alert"></p>
                                    <button type="submit" style="margin-left: 10%;" title="Apply promo code" class="button coupon "><span>Apply Code</span></button>
                                </form>
                            </div>
                        </div>
                        <div class="totals col-sm-4">
                            <h3>Shopping Cart Total</h3>
                            <div class="inner">
                                <table id="shopping-cart-totals-table" class="table shopping-cart-table-total">
                                    <colgroup>
                                        <col>
                                        <col width="1">
                                    </colgroup>
                                    <tfoot>
                                    <tr>
                                        <td style="" class="a-left" colspan="1"><strong>Grand Total</strong>
                                            <input type="hidden"  value="0" name="discount" id="discount"></td>
                                        <td style="" class="a-right"><strong><span class="price" id="grand_price">Rs. <?php if($cart!=null)echo $cart->total_price;?></span></strong></td>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    <tr>
                                        <td style="" class="a-left" colspan="1"> Subtotal </td>
                                        <td style="" class="a-right"><span class="price" id="sub_total">Rs. <?php if($cart!=null)echo $cart->total_price;;?></span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <ul class="checkout">
                                    <li>
                                        <button id="proceed_checkout"  title="Proceed to Checkout" class="button btn-proceed-checkout"><span>Proceed to Checkout</span></button>
                                    </li>
                                </ul>
                            </div>
                            <!--inner-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="centralModalSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header" style="background-color:green;color: white">
                <p class="heading lead" align="center" >You are goin to oder</p>  <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
            </div>

            <!--Body-->
            <div class="modal-body">

            </div>

        </div>
        <!--/.Content-->
    </div>
</div>
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
<script type="text/javascript">

    // Check out modal
    $('#proceed_checkout').on('click',function () {
        event.preventDefault();
        var trows    = $('#termsrow tr').length;
        var discount = $('#discount').val();
        if(trows<1)
        {
            alert("Cart is empty first add items to cart");

        }
        else
        {
           @if(Route::has('login'))
                   @auth()
                   var alrt   = confirm("You are going to checkout");
                   if (alrt==true)
                   {
                       $('#centralModalSuccess').find('.modal-body').load('/invoice/'+discount);
                       $('#centralModalSuccess').modal('toggle');
                   }
                  @else
                $('#login-modal').modal('show');
                   @endauth
                   @endif
        }
    });

    // Applying Promo Codes
    $('#discount_coupon_form').submit(function () {
        event.preventDefault();
        var tamount = $('#on_total_amount').val();
        $.ajax({
            url  : '/add_code/' + tamount,
            type : 'post',
            data : $('#discount_coupon_form').serialize(),
            dataType : 'json',
            success : function (result) {
                var final_amount = tamount - result;
                $('#grand_price').html(final_amount);
                $('#discount').val(result);
                var str = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Promo code Applied </div>';
                $('#error_alert').html(str);
            } ,
            error : function (result) {
                console.log('in error');
                var str = '<div class="alert alert-danger alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Promo code does not exist now </div>';
                $('#error_alert').html(str);
            }
        });
    });
    // Delete single item from shopping cart
    $(document).on('click','a[data-toggle=delete_cart_item]',function () {
        event.preventDefault();
        // variables for accessing shopping cart
        var basket = '';
        var productlist = '';
        var subtotals   = '';
        var cart_table  = '';
        $.ajax({
            url  : $(this).attr('href'),
            type : 'get',
            dataType : 'json',
            success : function (result) {
                $.each(result.items,function (i,item) {
                    productlist += '<li class="item odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="/images/'+item['item']['product_image']+'" alt="Lorem ipsum dolor" width="65"></a><div class="product-details"> <a data-toggle="delete_cart_item" href="/delete_items/'+item['item']['id']+'" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a> <p class="product-name"><a href="#">'+item['item']['product_name']+'</a></p><strong>'+item['qty']+'</strong> x <span class="price">'+item['price']+'</span><span> = '+item['qty']*item['price']+'</span></div>';
                    cart_table += ' <tr id="row'+item['item']['id']+'"></tr><td class="cart_product"><a href="#"><img src="/images/' +item['item']['product_image']+'" alt="Product"></a></td> <td class="cart_description"><p class="product-name"><a href="#">' + item['item']['product_name'] + '</a></p>\n' +
                        '                        <small><a href="#">Color : Red</a></small><br>\n' +
                        '                        <small><a href="#">Size : M</a></small></td><td class="availability in-stock"><span class="label">In stock</span></td><td class="price"><span>'+item['price']+'</span></td><td class="qty"><input class="form-control input-sm" type="text" value="'+item['qty']+'"></td>' +
                        '<td class="price"><span>'+item['price']+'</span></td><td class="action"><a data-toggle="delete_cart_item" href="/delete_items/'+item['item']['id']+'"><i class="icon-close"></i></a></td>';
                });
                basket += '<a href="#"><div class="cart-icon"><i class="fa fa-shopping-cart"></i></div><div class="shoppingcart-inner"><span class="cart-title">Shopping Cart</span> <span class="cart-total">'+result['total_quantity']+' Item(s): Rs.'+result['total_price']+'</span></div></a>';
                subtotals+= 'Subtotal: <span class="price">Rs. '+result['total_price']+'</span>';
                $('.basket').html(basket);
                $('#cart-sidebar').html(productlist);
                $('.top-subtotal').html(subtotals);
                $('#termsrow').html(cart_table);
                $('#grand_price').html(result['total_price']);
                $('#sub_total').html(result['total_price']);
                $('#totalItems').html("Total Items: " + result['total_quantity']);
            } ,
            error : function (result) {
                console.log('in error');
            }
        });
    });
    // delete all items from Shopping cart
    $('#empty_cart_button').on('click',function () {
        event.preventDefault();
        var alrt = confirm('Are you sure To delete all items from your shopping cart!!');
        var Total_Items = 0;
        var Total_Price = 0;
        // variables for accessing shopping cart
        var basket = '';
        var productlist = '';
        var subtotals   = '';
        var cart_table  = '';
        if(alrt==true) {
            $.ajax({
                url: '/empty_cart',
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    $.each(result, function (i, item) {
                        Total_Items += item.total_items;
                        Total_Price += item.total_amount;
                        productlist += '<li class="item odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="/images/' + item.getacc.product_image + '" alt="Lorem ipsum dolor" width="65"></a><div class="product-details"> <a data-toggle="delete_cart_item" href="/delete_items/' + item.id + '" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a> <p class="product-name"><a href="#">' + item.getacc.product_name + '</a></p><strong>' + item.total_items + '</strong> x <span class="price">' + item.getacc.product_price + '</span><span> = ' + item.total_amount + '</span></div>';
                        cart_table += ' <tr id="row' + item.id + '"></tr><td class="cart_product"><a href="#"><img src="/images/' + item.getacc.product_image + '" alt="Product"></a></td> <td class="cart_description"><p class="product-name"><a href="#">' + item.getacc.product_name + '</a></p>\n' +
                            '                        <small><a href="#">Color : Red</a></small><br>\n' +
                            '                        <small><a href="#">Size : M</a></small></td>'
                    });
                    $('#termsrow').html(cart_table);
                    $('#grand_price').html(Total_Price);
                    $('#sub_total').html(Total_Price);
                    $('#totalItems').html("Total Items: " + Total_Items);
                    basket += '<a href="#"><div class="cart-icon"><i class="fa fa-shopping-cart"></i></div><div class="shoppingcart-inner"><span class="cart-title">Shopping Cart</span> <span class="cart-total">' + Total_Items + ' Item(s): Rs.' + Total_Price + '</span></div></a>';
                    subtotals += 'Subtotal: <span class="price">Rs. ' + Total_Price + '</span>';
                    $('.basket').html(basket);
                    $('#cart-sidebar').html(productlist);
                    $('.top-subtotal').html(subtotals);
                },
                error: function (result) {
                    console.log('in error');
                }
            });
        }
    });

    // delivery info
    $('#shipping_zip_form').submit(function () {
        event.preventDefault();
        $.ajax({
            url : '/delivery',
            type : 'post',
            data : $('#shipping_zip_form').serialize()  ,
            dataType : 'json',
            success: function (result) {
                var str = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong>'+result+'</div>';
                $('#alrtmsg_delivery').html(str);
            },
            error: function (result) {
                console.log('in error');
            }
        });
    });
</script>


