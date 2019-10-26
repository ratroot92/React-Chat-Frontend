
<div class="container" id="loadpage">
<br>
    <div class="row">
    <div class="col-md-offset-1 col-md-10 alert alert-default text-center" style="background:#d6e9f8; color: black; font-family: 'Times New Roman';font-size: large">OFFLINE PAYMENT DETAIL</div>
    </div>

    <div class="container">
        <div class="row">

            <!-- panel preview -->
            <div class="col-sm-5">
                <h4>Add Payment Method:</h4>
                <div class="panel panel-default">
                    <div class="panel-body form-horizontal payment-form">
                        <form id="submit_payment_form" role="form" method="post">
                            {{csrf_field()}}
                        <div class="form-group">
                            <input type="hidden" id="userId" name="userId" value="{{Auth::user()->id}}">
                            <label for="concept" class="col-sm-3 control-label">Name :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="payment_name" name="payment_name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description :</label>
                            <div class="col-sm-9">
                                <textarea id="payment_description" name="payment_description" class="form-control" placeholder="Description *" rows="4" required="required" ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status :</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0" >In Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-default preview-add-button">
                                    <span class="glyphicon glyphicon-plus"></span> Add
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div> <!-- / panel preview -->
            <div class="col-sm-7">
                <h4>Preview:</h4>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table preview-table table-responsive">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="termsrow" >
                                @foreach($peyment_method as $peyment_method_row)
                                <tr id="row{{$peyment_method_row->id}}">
                                    <td>{{$peyment_method_row->payment_name}}</td>
                                    <td>{{$peyment_method_row->payment_description}}</td>
                                   <td> <?php if($peyment_method_row->status == 1){ echo '<span class="label label-sm label-success"> Active </span>'; } else { echo '<span class="label label-sm label-danger"> InActive </span>';} ?></td>
                               <td><a class="label label-default" data-toggle="editPayment" href="{{url('/editpayment/'.$peyment_method_row->id)}}"><i class="glyphicon glyphicon-pencil" title="edit payment"></i></a> <a class="label label-default" ><i class="glyphicon glyphicon-trash" id="deleteIT" onclick="delete_payment({{$peyment_method_row->id}})" title="Delete payment"></i></a></td>
                                </tr>
                                    @endforeach
                                </tbody> <!-- preview content goes here-->
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <hr style="border:1px solid;">
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<script type="text/javascript">
      $('#submit_payment_form').submit(function (e) {
         e.preventDefault();
         var str = '';
         var dbtn = '';
          var userID    = $('#userId').val();
         $.ajax({
            url           : '/savepayment',
            type          : 'post',
             data         :$('#submit_payment_form').serialize(),
             dataType     : 'json',
             success      : function (data) {
              /*  $.each(data,function (i,item) {
                    $('tbody').append(data);
                    if (item.status==1)
                    {
                dbtn = '<span class="label label-sm label-success"> Active </span>';
                    }
                    else
                    {
                        dbtn = '<span class="label label-sm label-danger"> InActive </span>'
                    }
                   var html = '<tr id="row'+item.id+'"><td>'+item.payment_name+'</td><td>'+item.payment_description+'</td><td>'+dbtn+'</td>   <td><a class="label label-default" ><i class="glyphicon glyphicon-pencil" id="deleteIT" onclick="delete_payment('+item.id+')" title="Delete payment"></i></a> <a class="label label-default" ><i class="glyphicon glyphicon-trash" title="Delete payment"></i></a></td></tr>'
                   str  += html;
                });
                $('#termsrow').html(str);*/
                 $('#loadpage').load("/index_payment/"+userID);
             },
             error        : function () {
                 console.log('in error');
             }
         });
      });

      function  delete_payment(id) {
          var paymentId = id;
          var userID    = $('#userId').val();
          var str = '';
          var dbtn = '';
          $.ajax({
              url: 'deletepayment/'+ paymentId + '/' + userID,
              type: 'get',
              dataType: 'json',
              success:function (result) {
                /*  $.each(result,function (i,item) {
                      $('tbody').append(result);
                      if (item.status==1)
                      {
                          dbtn = '<span class="label label-sm label-success"> Active </span>';
                      }
                      else
                      {
                          dbtn = '<span class="label label-sm label-danger"> InActive </span>'
                      }
                      var html = '<tr id="row'+item.id+'"><td>'+item.payment_name+'</td><td>'+item.payment_description+'</td><td>'+dbtn+'</td>   <td><a class="label label-default" ><i class="glyphicon glyphicon-pencil" id="deleteIT" onclick="delete_payment('+item.id+')" title="Delete payment"></i></a> <a class="label label-default" ><i class="glyphicon glyphicon-trash" title="Delete payment"></i></a></td></tr>'
                      str  += html;
                  });
                  $('#termsrow').html(str);*/
                $('#loadpage').load("/index_payment/"+userID);
              } ,
              error:function () {
                  console.log("In Error");

              }
          });
      }

      $(document).on('click','a[data-toggle=editPayment]',function () {
          event.preventDefault();
         $('.panel-body').load($(this).attr('href'));
         $('.panel-body').show();
      });
</script>
