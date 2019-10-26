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
        <div class="row">
            <div class="col-md-5">
                <div class="invoice-title">
                    <h3>Invoice #<?php for($i=0;$i<(5-strlen($orders->invoice_no));$i++){echo "0";}echo $orders->invoice_no;?></h3><h3 class="pull-right">Order # {{$orders->id}}</h3>
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
                            {{$orders->getdeliveryaccc->address}}<br>
                            {{$orders->getdeliveryaccc->zone}},<?php if ($orders->getdeliveryaccc->city==1){echo 'Rawalpindi';} else {echo 'Islamabad';}  ?><br>
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
                            {{$orders->created_at}}<br><br>
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
                                <?php $subtotal=0;?>
                                @foreach($ordered_products as $ordered_products)
                                    <?php $subtotal+=$ordered_products->total_amount;?>
                                    <tr>
                                        <td>{{$ordered_products->getprodct->product_name}}</td>
                                        <td class="text-center">{{$ordered_products->getprodct->product_price}}</td>
                                        <td class="text-center">{{$ordered_products->total_items}}</td>
                                        <td class="text-right">{{$ordered_products->total_amount}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                    <td class="thick-line text-right" colspan="2">Rs. <?php echo $subtotal;?></td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center"><strong>Discount:</strong></td>
                                    <td class="no-line text-right" colspan="2">Rs. <?php echo ($subtotal-$orders->due_amount);?></td>
                                </tr>
                                <tr>

                                    <td class="no-line"></td>
                                    <td class="no-line text-center"><strong>Amount to be paid:</strong></td>
                                    <td class="no-line text-right" colspan="2">Rs.{{$orders->due_amount}}</td>
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

            </div><br>
        </div>
</div>

