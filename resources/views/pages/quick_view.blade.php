<style>
    .product_view .modal-dialog{max-width: 800px; width: 100%;}
    .pre-cost{text-decoration: line-through; color: #a5a5a5;}
</style>

<div class="row">
    <div class="col-md-5 product_img">
        @if($product->sal_new==1)
            <div class="icon-new-label new-left">New</div>
        @endif
        @if($product->sal_new==0)
            <div class="icon-sale-label sale-left">Sale</div>
        @endif
        <img src="{{url('/images/'.$product->product_image)}}" class="product-image-photo img-responsive">
    </div>
    <div class="col-md-7 product_content">
        <h3 align="center">Product Name: <span>{{$product->product_name}}</span></h3>
        <h4 class="cost" align="center"><span ></span>Rs. {{$product->product_price}} <small class="pre-cost">Rs. 60.00</small></h4>
       <hr>
        <div class="rating">
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star"></span>
            <span class="glyphicon glyphicon-star"></span>
            (10 reviews)
            <h5 class="pull-right" style="color: black">Availability: <p class="label label-small label-success">In Stock</p> </h5>
        </div>
        <hr>
        <p >{{$product->product_description}}</p>
        <hr>

        <div class="row">
            <form id="add_items_to_cart" method="post" role="form">
                {{csrf_field()}}
                        <input type="hidden"  id="USERid">

                <input type="hidden" value="{{$product->id}}" id="PRODUCTid">
                <input type="hidden" value="{{$product->product_price}}" id="productprice" name="productprice">
            <div class="col-lg-5" >
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                            <span class="glyphicon glyphicon-minus" style="font-size: 20px;"></span>
                        </button>
                    </span>
                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="3" >
                    <span class="input-group-btn">
                        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="" >
                            <span class="glyphicon glyphicon-plus" style="font-size: 20px;"></span>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-block btn-danger"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
            </div>
            <!-- end col -->
            </form>
        </div><hr>
        <div class="row">
            <div class="col-lg-offset-1 col-md-6">
                <a><span class="glyphicon glyphicon-heart"></span>&nbsp;&nbsp;&nbsp;Add to Wishlist</a>
            </div>

        </div>

    </div>

</div>

<script type="text/javascript">
    // incremental buttons
    $(document).ready(function(){
        var quantitiy=0;
        $('.quantity-right-plus').click(function(e){

            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var quantity = parseInt($('#quantity').val());

            // If is not undefined

            $('#quantity').val(quantity + 1);


            // Increment

        });

        $('.quantity-left-minus').click(function(e){
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var quantity = parseInt($('#quantity').val());

            // If is not undefined

            // Increment
            if(quantity>0){
                $('#quantity').val(quantity - 1);
            }
        });

    });

    // add items to cart
    $('#add_items_to_cart').submit(function () {
        event.preventDefault();
        var userID = $('#USERid').val();
        var productID = $('#PRODUCTid').val();
        var Total_Items = 0;
        var Total_Price = 0;
        // variables for accessing shopping cart
        var basket = '';
        var productlist = '';
        var subtotals   = '';
        $.ajax({
            url  : '/add_items_cart/' + productID,
            type : 'get',
            data : $('#add_items_to_cart').serialize(),
            success : function (result) {
                $.each(result.items,function (i,item) {
                    productlist += '<li class="item odd"> <a href="#" title="Ipsums Dolors Untra" class="product-image"><img src="/images/'+item['item']['product_image']+'" alt="Lorem ipsum dolor" width="65"></a><div class="product-details"> <a data-toggle="delete_cart_item" href="/delete_items/'+item.id+'" title="Remove This Item" class="remove-cart"><i class="icon-close"></i></a> <p class="product-name"><a href="#">'+item['item']['product_name']+'</a></p><strong>'+item['qty']+'</strong> x <span class="price">'+item['item']['product_price']+'</span><span> = '+item['qty']*item['item']['product_price']+'</span></div>';
                });
                basket += '<a href="#"><div class="cart-icon"><i class="fa fa-shopping-cart"></i></div><div class="shoppingcart-inner"><span class="cart-title">Shopping Cart</span> <span class="cart-total">'+result['total_quantity']+' Item(s): Rs.'+result['total_price']+'</span></div></a>';
                subtotals+= 'Subtotal: <span class="price">Rs. '+result['total_price']+'</span>';
                $('.basket').html(basket);
                $('#cart-sidebar').html(productlist);
                $('.top-subtotal').html(subtotals   );
                $('#product_view').modal('toggle');
                console.log(Total_Items + '   ' + Total_Price);
            } ,
            error : function (result) {
                console.log('in error');
            }
        });
    });
</script>