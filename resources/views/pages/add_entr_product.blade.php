<div class="row" >
    <form id="submit_entrep_form" method="post" role="form" enctype="multipart/form-data" action="{{url('/save_entr_product')}}">
        @csrf
        <h2 align="center">Add Product</h2><p align="center">Registered your products</p>
        <div class="row form-group">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <label for="name">Category:<span class="required">*</span></label>
                <input type="hidden" value="1" name="type">
                <select id="product_catagory" name="product_category" class="form-control" required="required" data-error="Please specify your need.">
                    <option value="">Product Category </option>
                    @foreach($category as $category_row)
                        <option value="{{$category_row->id}}">{{$category_row->category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="name">Product Name :<span class="required">*</span></label>
                <input type="text" name="product_name" id="product_name" class="form-control" required placeholder="Enter Product Name">
            </div>
            <div class="col-md-3">
                <label for="name">Type:<span class="required">*</span></label>
                <select id="product_type" name="product_type" class="form-control" required="required">
                    <option value="">Product Type </option>
                    <option value="1">Old</option>
                    <option value="0">New</option>
                    <option value="2">Normal</option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <label for="name">Quantity :<span class="required">*</span></label>
                <input type="hidden" value="1" name="type">
                <input type="text" name="quantity" id="quantity" class="form-control" placeholder="How much Products" required>
            </div>
            <div class="col-md-4">
                <label for="name">Price (Pkr's):<span class="required">*</span></label>
                <input type="text" name="price" id="price" class="form-control" placeholder="Enter Price" required>
            </div>
            <div class="col-md-3">
                <label for="name">On Sale/New :<span class="required">*</span></label>
                <input type="hidden" value="1" name="type">
                <select id="sale_new" name="sale_new" class="form-control" required="required" data-error="Please specify your need.">
                    <option value="">Select Type</option>
                    <option value="0" selected>Sale</option>
                    <option value="1">New</option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <label for="name">I want to :<span class="required">*</span></label>
                <select id="replace_sell" name="replace_sell" class="form-control" required="required" data-error="Please specify your need.">
                    <option value="1" selected>Replace</option>
                    <option value="0">Sell</option>
                </select>
            </div>
            <div class="col-md-7">
                <label for="name">Description :<span class="required">*</span></label>
                <textarea id="product_description" name="product_description" class="form-control" placeholder="Description *" rows="1"  data-error="Please, Enter short description."></textarea>
            </div>
        </div><hr>
        <div class="row form-group">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <label for="name">Featured Image:<span class="required">*</span></label>
                <input type="file" name="featured_image" id="featured_image" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="name">Image 1:<span class="required">*</span></label>
                <input type="file" name="image1" id="image1" class="form-control" >
            </div>
            <div class="col-md-3">
                <label for="name">Image 2:<span class="required">*</span></label>
                <input type="file" name="image2" id="image2" class="form-control" >
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div id="alrt_success_msg"></div>
                <input type="submit" class="btn btn-success btn-send" value="Register Product">
                <input type="button" id="cancel_btn" class="btn btn-danger btn-send" value="Cancel">
            </div>
        </div>

    </form>
</div>

<script type="text/javascript">
    $('#cancel_btn').on('click',function () {
        $('#pageLoad_Area').empty();
       $('#pageLoad_Area').load('/entr_vendor/dashboard');
    });
    $('#submit_entrep_form').submit(function () {
       event.preventDefault();
       $.ajax({
           url         : 'entr_vendor/save_entr_product',
           type        : 'post',
           data        : new FormData(this),
           processData : false,
           contentType : false,
           dataType    : 'json',
           success     : function (result) {
               $('#submit_entrep_form')[0].reset();
               var html = '<p class="alert alert-success alert-dismissible"><strong>Success!!</strong> &nbsp;&nbsp; '+result+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
               $('#alrt_success_msg').html(html);
           },
           error       : function (result) {
               var response = JSON.parse(result.responseText);
               var str = '';
               $.each(response.errors,function (i,item) {
                   var html = '<p class="alert alert-danger alert-dismissible"><strong>Error!!</strong> &nbsp;&nbsp; '+item+'&nbsp;&nbsp;&nbsp; <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
                   str = str + html;
               });
               $('#alrt_success_msg').html(str);
           }
       })
    });
</script>