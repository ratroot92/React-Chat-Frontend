
<div class="container table-responsive"  style="border:1px solid black; margin-top: 20px;">
    <br>
    <h3 align="center">Request For Replace</h3>
    <form id="request_for_replace_form" action="{{url('/replace_save')}}" method="post" action="" role="form" enctype="multipart/form-data" >
         {{csrf_field()}}
        <div class="messages"></div>
        <div class="controls">
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_Category">Product Category *</label>
                        <select id="produt_catagory" name="product_category" class="form-control" required="required" data-error="Please specify your need.">
                            <option value="">Select Product Category </option>
                            @foreach($category as $category_row)
                            <option value="{{$category_row->id}}">{{$category_row->category_name}}</option>
                            @endforeach
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10 " >
                        <label for="form_name">Product Name *</label>
                        <input id="product_name" type="text" name="product_name" class="form-control" placeholder="enter your Product Name " required="required" value="">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10" >
                        <label for="form_type">Type*</label>
                        <select id="product_type" name="product_type" class="form-control" required="required" data-error="Please specify your need.">
                            <option value="">Select Product Type  </option>
                            <option value="1">New</option>
                            <option value="0">Old</option>
                            <option value="2">Normal</option>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_price">Price(PK) *</label>
                        <input id="product_price" type="text" name="product_price" class="form-control" placeholder="enter product price " required="required" >
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_need">Quantity*</label>
                        <input id="product_quantity" type="number" name="product_quantity" class="form-control" placeholder="enter quantity*"  min="1">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_image">Product Image *</label>
                        <input id="product_image" type="file" name="file" class="form-control">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>

            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10" >
                        <label for="form_type">Sale/New</label>
                        <select id="sale_new" name="sale_new" class="form-control" required="required" data-error="Please specify your need.">
                            <option value="">Select Type</option>
                            <option value="0" selected>Sale</option>
                            <option value="1">New</option>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10" >
                        <label for="form_type">I want to: </label>
                        <select id="replace_sell" name="replace_sell" class="form-control" required="required" data-error="Please specify your need.">
                            <option value="1" selected>Replace</option>
                            <option value="0">Sell</option>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-9">
                    <div class="form-group col-md-11">
                        <label for="form_Description">Description *</label>
                        <textarea id="product_description" name="product_description" class="form-control" placeholder="Description *" rows="4" required="required" data-error="Please, leave us a message."></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12 text-center" style="margin-bottom: 20px; margin-top: 20px;">
                    @if(Route::has('login'))
                        @auth()
                    <input type="submit" class="btn btn-success btn-send" value="Request For Replace">
                            @else
                            <input type="submit" class="btn btn-success btn-send" value="First log in" disabled>
                        @endauth
                    @endif
                    <input type="button" class="btn btn-danger btn-send" value="Cancel">
                </div>


            </div>

        </div>

    </form>
</div>

<script type="text/javascript">
  /*  $('#request_for_replace_form').submit(function (e) {
       e.preventDefault();
       $.ajax({
          url           : '/replace_save',
          type          : 'post',
           data         :$('#request_for_replace_form').serialize(),
           dataType     : 'json',
           success      : function (data) {

           },
           error        : function () {
               console.log('in error');
           }
       });
    });*/
</script>
