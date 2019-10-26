@include('admin.includes.header')

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Add Product</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add new product</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                    <br />
<div id="app">
@include('flash-message')
@yield('content')
</div>


                  <div class="x_content">
                    <br />
                    <form action="{{url('/admin/insert_product')}}" enctype="multipart/form-data" method="POST" data-parsley-validate class="form-horizontal form-label-left" id="productform" name="productform">
@csrf
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Product Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="name" name="name" required="required" minlength="3" maxlength="30" class="form-control col-md-7 col-xs-12">
                        </div>
                         @if ($errors->has('name'))
                      <div style="color:red;">
                        @foreach ($errors->get('name') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>







                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Product Price <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="number" id="price" name="price" minlength="1"  required="required" class="form-control col-md-7 col-xs-12" />
                        </div>
                        @if ($errors->has('price'))
                      <div style="color:red;">
                        @foreach ($errors->get('price') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>




                        <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Product type <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="tooltype" name="tooltype" class="form-control col-md-7 col-xs-12" type="text" minlength="3" maxlength="30"  required/>
                        </div>
                         @if ($errors->has('tooltype'))
                      <div style="color:red;">
                        @foreach ($errors->get('tooltype') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>


                       <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"> Product Weight <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="weight" name="weight" class="form-control col-md-7 col-xs-12" type="number" required/>
                        </div>
                         @if ($errors->has('weight'))
                      <div style="color:red;">
                        @foreach ($errors->get('weight') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>


                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"> Product Sound <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="sound" name="sound" class="form-control col-md-7 col-xs-12" type="text" required/>
                        </div>
                         @if ($errors->has('sound'))
                      <div style="color:red;">
                        @foreach ($errors->get('sound') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>

                       <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"> Product Model <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="model" name="model" class="form-control col-md-7 col-xs-12" type="text" required/>
                        </div>
                         @if ($errors->has('model'))
                      <div style="color:red;">
                        @foreach ($errors->get('model') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>

                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12"> Product Manufacturer <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="manufacturer" name="manufacturer" class="form-control col-md-7 col-xs-12" type="text" required/>
                        </div>
                         @if ($errors->has('manufacturer'))
                      <div style="color:red;">
                        @foreach ($errors->get('manufacturer') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>


                     


                        <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Product Quantity <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="quantity" class="form-control col-md-7 col-xs-12" type="number" name="quantity"  minlength="3"  required/>
                        </div>
                         @if ($errors->has('quantity'))
                      <div style="color:red;">
                        @foreach ($errors->get('quantity') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Product Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file"id="file" name="file"{{--  data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" --}}/>
                        </div>
                          @if ($errors->has('file'))
                      <div style="color:red;">
                        @foreach ($errors->get('file') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>




                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Category</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                              <div >
                                <select id="category" name="category" class="form-control col-md-7 col-xs-12">
                                    <option value="">Select Category</option>
                                   @foreach($category as $key)
                                    <option value="{{$key->category_name}}">{{$key->category_name}}</option>
                                    @endforeach
                               </select>
                              </div>
                               
                        </div>
                         @if ($errors->has('category'))
                      <div style="color:red;">
                        @foreach ($errors->get('category') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>



                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Sub Category <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div id="div1">
                            <select class="form-control col-md-7 col-xs-12" id="subcategory" name="subcategory">
                          <option value="">Select Subcategory</option>
                                   @foreach($subcategory as $key)
                                    <option value="{{$key->name}}">{{$key->name}}</option>
                                    @endforeach
                        
                          </select>
                          </div>
                          {{--  <div id="div2">
                            <select class="form-control col-md-7 col-xs-12" id="subcategory1" name="subcategory1">
                            <option value="Sub Category 1 of cat 2">Sub Category 1 of cat 2</option>
                            <option value="Sub Category 2 of cat 2">Sub Category 2 of cat 2</option>
                          </select>
                          </div> --}}
                        </div>
                         @if ($errors->has('subcategory'))
                      <div style="color:red;">
                        @foreach ($errors->get('subcategory') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>



                          <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Product Status</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                              <div >
                                <select id="status" name="status" class="form-control col-md-7 col-xs-12">
                                    <option value="1">Active</option>
                                     <option value="0">In-Active</option>
                                  
                                  
                               </select>
                              </div>
                               
                        </div>
                         @if ($errors->has('status'))
                      <div style="color:red;">
                        @foreach ($errors->get('status') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>






                    
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Product Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="description" id="description" class="editor-wrapper" style="width: 100%;"></textarea>
                        </div>
                         @if ($errors->has('description'))
                      <div style="color:red;">
                        @foreach ($errors->get('description') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-primary" type="button">Cancel</button>
                          <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Add </button>
                        </div>
                        
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->

     @include('admin.includes.footer')

