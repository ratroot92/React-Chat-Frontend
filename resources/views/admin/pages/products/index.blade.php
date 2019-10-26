@include('admin.includes.header')
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Products</h3>
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
                   <br />
<div id="app">
@include('flash-message')
@yield('content')
</div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div cslass="x_title">
                    <h2>Products List</h2>
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
                  <div class="x_content">

                     <table id="table" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Price</th>     
                          <th>SubCategory</th>
                          <th>Quantity</th>
                          <th>Status</th>
                          <th>Image</th>
                           <th>Operations</th>
                        </tr>
                      </thead>


                      <tbody>
@foreach($products as $key)
<tr>
<th>{{$key->id}}</th>
<th>{{$key->name}}</th>
<th>{{$key->price}}</th>
<th>{{$key->subcategory}}</th>
<th>{{$key->quantity}}</th>
<th>{{$key->status}}</th>
<th class=""> <img src="{{ asset(''.$key->featured_image) }}" style="display:block;" width="50px" height="50px"></th>

<td style="display:flex;text-align: center;justify-content: center;">
<button type="button" class="btn btn-success editBtn" id="editBtn" data-toggle="modal"  data-task="{{$key->id}}" data-target="#edit_modal"><span class="fa fa-pencil"></span></button>

<a class="btn btn-danger delBtn" href="/admin/del_pro/{{$key->id}}"  ><span class="fa fa-trash"></span></a>
                           
                          </td>
                        </tr>
                        @endforeach
                       
                      </tbody>
                    </table>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


                {{-- modal start --}}


<!-- Modal -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mt-5 p-0 ">
      {{-- edit modal category --}}
      
<form action="{{url('/admin/edit_product')}}" enctype="multipart/form-data" method="POST" data-parsley-validate class="form-horizontal form-label-left" id="productform" name="productform">
@csrf


          <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Product Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="id" name="id" required="required"class="form-control col-md-7 col-xs-12" readonly="readonly">
                        </div>
                         @if ($errors->has('id'))
                      <div style="color:red;">
                        @foreach ($errors->get('id') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>




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


                    {{--   <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Product Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file"id="file" name="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage"/>
                        </div>
                          @if ($errors->has('file'))
                      <div style="color:red;">
                        @foreach ($errors->get('file') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div> --}}




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
                              <div class=" ">
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
                      <div class="row ">
                        <div class="col-md-8"></div>
                          <div class="col-md-4"><div class="form-group">
                        <div class="">
                          <button class="btn btn-primary" type="button">Cancel</button>
                          <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Edit product </button>
                        </div>
                        
                      </div></div>
                      </div>

                    </form>
    </div>
  </div>
</div>
        {{-- modal end  --}}





@include('admin.includes.footer')



<script>
$(document).ready(function(){
//edit form  
$('#table').on('click','#editBtn',function(){
  var id=$(this).data('task');         
$.ajax({
type:'get',
url:'/admin/get_pro_for_edit/'+id,
data:{id:id},
success:function(response){
$('#id').val(response.id);
$('#name').val(response.name);
$('#price').val(response.price);
$('#description').val(response.description);
$('#weight').val(response.weight);
$('#model').val(response.model);
$('#tooltype').val(response.tool_type);
$('#manufacturer').val(response.manufacturer);
$('#quantity').val(response.quantity);
$('#sound').val(response.sound_power);
var category=response.category;
var subcategory=response.subcategory;
$('#category').val(""+category);
$('#subcategory').val(""+subcategory);
var status=response.status;
if(status=='1'){
$('#status').val("1");
}
else{
$('#status').val("0");
}
},
error:function(error){

}
});







  });





});
</script>