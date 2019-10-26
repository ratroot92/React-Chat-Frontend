<div class="container">
    <br><br>
    <h2>My Orders</h2>
<div class="order-detail-content">
    <div class="table-responsive">
        <table class="table table-bordered cart_summary">
            <thead>
            <tr>
                <th class="cart_product">Order #</th>
                <th>Invoice #</th>
                <th>Due Amount</th>
                <th>Paid Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <?php $total_orders=0?>
            <tbody id="termsrow">
            @foreach($orders as $orders_row)
                <?php $total_orders+=1?>
                <tr>
                    <td>{{$orders_row->id}}</td>
                    <td>{{$orders_row->invoice_no}}</td>
                    <td>Rs. {{$orders_row->due_amount}}</td>
                    <td>Rs. {{$orders_row->paid_amount}}</td>
                    <td><?php if($orders_row->status==1){echo '<span class="label  label-success">Approve</span>';} else{echo '<span class="label label-danger">Pending..</span>';}?></td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a data-toggle="view_orders" href="{{url('/view_my_order/'.$orders_row->id)}}">
                                        <i class="icon-eye"></i> View Order </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="icon-trash"></i> delete Order </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
            <tr class="first last">
                <td colspan="50" class="a-right last"><button type="button" title="total items" class="button btn-continue" ><span id="totalItems">Total orders: <?php echo $total_orders;?></span></button>
                    <button type="submit" name="update_cart_action" value="update_qty" title="Update Cart" class="button btn-update"><span>Update Cart</span></button>
                    <a  name="empty_cart_button" value="empty_cart" title="Clear Cart" class="btn btn-danger" id="empty_cart_button"><span>Clear Cart</span></a></td>
            </tr>
            </tfoot>
        </table>
    </div>

</div>
</div>
<div class="modal fade" id="view_orders_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<script type="text/javascript">
    $(document).on('click','a[data-toggle=view_orders]',function () {
        event.preventDefault();
        $('#view_orders_modal').find('.modal-body').load($(this).attr('href'));
        $('#view_orders_modal').modal('show');
    });
</script>