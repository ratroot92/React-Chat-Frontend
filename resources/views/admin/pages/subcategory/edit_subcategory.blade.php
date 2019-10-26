@include('admin.includes.header')

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>SUB-CATEGORY</h3>
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
                    <h2>Subcategory <small>Sub-admin</small></h2>
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
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                           <th>Parent category</th>
                          <th>Description</th>
                          <th>Image</th>
                          <th>Status</th>
                          <th>Operations</th>
                        </tr>
                      </thead>


                      <tbody id="table">

                        @foreach($subcategories->all() as $key)
                        <tr>
                        <th>{{$key->id}}</th>
                        <th>{{$key->name}}</th>
                        <th>{{$key->category->category_name}}</th>
                        <th>{{$key->description}}</th>
                        <th class=""> <img src="{{ asset(''.$key->image) }}" style="display:block;" width="50px" height="50px"></th>

                        <th>{{$key->status}}</th>
                        <td  style="display:flex;text-align: center;justify-content: center;">
<button type="button" class="btn btn-success addMore editBtn" id="editBtn" data-toggle="modal"  data-task="{{$key->id}}" data-target="#edit_modal"><span class="fa fa-pencil"></span></button> 

<a class="btn btn-danger delBtn"title="view" href="/admin/del_sub_cat/{{$key->id}}"  ><span class="fa fa-trash"></span></a>
                           
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
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Sub-Category </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body m-0 p-0 ">
      {{-- edit modal category --}}
      
<form class="form-horizontal form-label-left" method="POST" action="{{url('/admin/edit_subcategory')}}"  enctype="multipart/form-data" id="editform" name="editform" >
@csrf


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Category ID <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="id" name="id" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly">
                        </div>
                      
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Category Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Parent Category <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="pr_category" id="pr_category" class="form-control" required>
                          <option value="">Select Parent Category</option>
                       
                         @foreach($pr_category->all() as $key)
                         <option value="{{$key->category_name}}">{{$key->category_name}}</option>
                         @endforeach()
                       
                       
                        
                           </select>
                        </div>
                         @if ($errors->has('pr_category'))
                      <div style="color:red;">
                        @foreach ($errors->get('pr_category') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Category Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <textarea name="description" id="description" rows="10" style="width: 100%;" ></textarea>
                        </div>
                         @if ($errors->has('description'))
                      <div style="color:red;">
                        @foreach ($errors->get('description') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>
{{-- 
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Category Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" id="file" name="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" /required>
                        </div>
                       @if ($errors->has('file'))
                      <div style="color:red;">
                        @foreach ($errors->get('file') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>
 --}}

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Category Status <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="status" id="status" class="form-control" required>
                          <option value="">Select Status</option>
                          <option value="1">Active</option>
                          <option value="0">In Active</option>
                        </select>
                        </div>
                         @if ($errors->has('status'))
                      <div style="color:red;">
                        @foreach ($errors->get('status') as $errormessage) 
                          {{ $errormessage }}
                           @endforeach
                      </div> 
                        @endif
                      </div>
                     
                    
                      

                  
      {{-- edit modal category  --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-success" value="Save changes">
      </div>
        </form>
    </div>
  </div>
</div>
        {{-- modal end  --}}

       
@include('admin.includes.footer');

<script>
$(document).ready(function(){
//edit form  
$('#table').on('click','.editBtn',function(){
    var id=$(this).data('task');
  
$.ajax({
type:'get',
url:'/admin/get_sub_cat_for_edit/'+id,
data:{id:id},
success:function(response){
$('#id').val(response.id);
$('#name').val(response.name);
var pr_category=response.parent_category;
$('#pr_category').val(""+pr_category);
$('#description').val(response.description);
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