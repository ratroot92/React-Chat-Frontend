<style>
    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        border-top: 2px solid;
    }
</style>
<div class="container">
    <br>
    @if($delivery_info=="")
        <h4 style="margin-left: 6%;"> <strong>Warning!! </strong> Add your Delivery info it's necessory</h4>
    @elseif(count($payment_info)<1)
       <h4 style="margin-left: 7%;"> <strong>Warning!! </strong> Add at least one payment Method</h4>
        @else
    <div class="row">
        <div class="col-md-5">
            <div class="invoice-title">
                <h3>Invoice # <?php for($i=0;$i<(5-strlen($invoice_no));$i++){echo "0";}echo $invoice_no;?></h3><h3 class="pull-right">Order # {{$orderID}}</h3>
            </div>
            <hr style="border:solid 3px;">
            <div class="row">
                <div class="col-xs-5">
                    <address>
                        <strong><b>Billed To:</b></strong><br>
                        {{Auth::user()->name}}<br>
                        {{Auth::user()->address}},{{Auth::user()->city}}<br>
                        {{Auth::user()->contact_no}}<br>
                        {{Auth::user()->email}}
                    </address>
                </div>
                <div class="col-md-6 text-right">
                    <address>
                        <strong><b>Shipped To:</b></strong><br>
                        {{Auth::user()->name}}<br>
                        {{$delivery_info->address}}<br>
                        {{$delivery_info->zone}},<?php if ($delivery_info->city==1){echo 'Rawalpindi';} else {echo 'Islamabad';}  ?><br>
                       {{Auth::user()->contact_no}}
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    <address>
                        <strong><b>Payment Method:</b></strong><br>
                        @foreach($payment_info as $payment_row)
                       <b><u>{{$payment_row->payment_name}}</u></b><br>
                        {{$payment_row->payment_description}}<br>
                            @endforeach
                    </address>
                </div>
                <div class="col-xs-6 text-right">
                    <address>
                        <strong>Order Date:</strong><br>
                        {{$today_date}}<br><br>
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5" align="center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Order summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <td><strong>Item</strong></td>
                                <td class="text-center"><strong>Price</strong></td>
                                <td class="text-center"><strong>Quantity</strong></td>
                                <td class="text-right"><strong>Totals</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                            @if($items!=null)
                            @foreach($items->items as $item_row)
                                <tr>
                                    <td>{{$item_row['item']['product_name']}}</td>
                                    <td class="text-center">{{$item_row['item']['product_price']}}</td>
                                    <td class="text-center">{{$item_row['qty']}}</td>
                                    <td class="text-right">{{$item_row['price']}}</td>
                                </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td class="thick-line"></td>
                                <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                <td class="thick-line text-right" colspan="2">Rs. <?php if($items!=null)echo $items->total_price;?></td>
                            </tr>
                            <tr>
                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Discount:</strong></td>
                                <td class="no-line text-right" colspan="2">Rs. {{$discount}}</td>
                            </tr>
                            <tr>

                                <td class="no-line"></td>
                                <td class="no-line text-center"><strong>Amount to be paid:</strong></td>
                                <td class="no-line text-right" colspan="2">Rs. <?php echo ($items->total_price - $discount);?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="animated flash">Thank Your For Your Shopping</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" align="center">
            <form id="submit_order_form" method="post">
                {{csrf_field()}}
            <input type="hidden" name="shopping_cart_id" id="shopping_cart_id" value="">
            <input type="hidden" name="invoice_no" id="invoice_no" value="{{$invoice_no}}">
            <input type="hidden" name="due_amount" id="due_amount" value="<?php echo ($items->total_price - $discount); ?>">
            <input type="hidden" name="delivery_ifo" id="delivery_ifo" value="{{$delivery_info->id}}">
            <button type="submit"  class="btn btn btn-success" >Order Now&nbsp;&nbsp;<i class="fa fa-first-order" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel&nbsp;&nbsp;<i class="glyphicon glyphicon-remove"></i></button>
            </form>
        </div><br>
    </div>
        @endif
</div>

<script type="text/javascript">
    $('#submit_order_form').submit(function () {
       event.preventDefault();
        var Total_Items = 0;
        var Total_Price = 0;
        // variables for accessing shopping cart
        var basket = '';
        var productlist = '';
        var subtotals   = '';
        var cart_table  = '';
       $.ajax({
          url     : '/orders',
          type    : 'post',
          data : $('#submit_order_form').serialize(),
          dataType : 'json',
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

              var str = '<div class="alert alert-success alert-dismissible"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Congratulation!!</strong>Your Order has been Sumbitted Successfully wait for reply</div>';
              $('#alermsg_order').html(str);
        },
           error:function (result) {
               console.log('in error');
           }
       });
    });
</script>
