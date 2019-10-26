<form id="update_paymentt_form" role="form" method="post">
    {{csrf_field()}}
    <div class="form-group">
        <input type="hidden" id="userId" name="userId" value="{{Auth::user()->id}}">
        <input type="hidden" id="paymentID" name="paymentID" value="{{$payement_method->id}}">
        <label for="concept" class="col-sm-3 control-label">Name :</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="payment_name" name="payment_name" value="{{$payement_method->payment_name}}" required>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-3 control-label">Description :</label>
        <div class="col-sm-9">
            <textarea id="payment_description" name="payment_description" class="form-control" placeholder="Description *" rows="4" required="required" >{{$payement_method->payment_description}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class="col-sm-3 control-label">Status :</label>
        <div class="col-sm-9">
            <select class="form-control" id="status" name="status" required>
                <?php if($payement_method->status==1){echo '<option value="1" selected>Active</option><option value="0" >In Active</option>'; }
                else{echo '<option value="1" >Active</option><option value="0" selected >In Active</option>';}
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12 text-right">
            <button type="submit" class="btn btn-success preview-add-button">
                <span class="glyphicon glyphicon-plus"></span> Update
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $('#update_paymentt_form').submit(function () {
        event.preventDefault();
        var payid = $('#paymentID').val();
        var userID    = $('#userId').val();
        $.ajax({
            url : '/update_payment/'+ payid,
            type : 'post',
            data : $('#update_paymentt_form').serialize(),
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
    });
</script>