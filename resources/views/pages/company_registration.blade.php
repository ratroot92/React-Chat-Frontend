<div class="container table-responsive"  style="border:1px solid black; margin-top: 20px;">
    <br>
    <h3 align="center">Register Your Company With Rown.pk</h3>
    <form id="request_for_replace_form" action="{{url('/save_company')}}" method="post" action="" role="form" enctype="multipart/form-data" >
        {{csrf_field()}}
        <div class="messages"></div>
        <div class="controls">
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_Category">Company Name: *</label>
                        <input type="hidden" name="page" value="user">
                        <input type="text" class="form-control" name="company_name" id="company_name"  placeholder="Enter Company Name" value="" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10 " >
                        <label for="form_name">Company Type: *</label>
                        <input type="text" class="form-control" name="company_type" id="company_type"  placeholder="Enter Company Type" value=""/>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10" >
                        <label for="form_type">Address: *</label>
                        <input type="text" class="form-control" name="company_address" id="company_address"  placeholder="Enter Company Address" value=""/>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_price">Email: *</label>
                        <input type="email" class="form-control" name="company_email" id="company_email"  placeholder="Company Email" value=""/>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_need">Contact # *</label>
                        <input type="text" class="form-control" name="company_no" id="company_no"  placeholder="Company Contact #" value=""/>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="form-group col-md-10">
                        <label for="form_image">Logo/Image: *</label>
                        <input type="file" class="form-control" name="file" id="file"  placeholder="Company Image" value=""/>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center" style="margin-bottom: 20px; margin-top: 20px;">
                    <input type="submit" class="btn btn-success btn-send" value="Register">
                    <a  href="{{url('/index/#part')}}" class="btn btn-danger btn-send" value="Cancel">Cancel</a>
                </div>
            </div>
        </div>

    </form>
</div>

>
