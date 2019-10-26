@include('admin.includes.header')

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Drivers</h3>
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
                  <div cslass="x_title">
                    <h2>Drivers List</h2>
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
                          <th>Name</th>
                          <th>Code</th>
                          <th>Total Rides</th>
                          <th>Email</th>
                          <th>Earnings</th>
                          <th>City</th>
                          <th>Date</th>
                          <th>Actions</th>
                        </tr>
                      </thead>


                      <tbody>
                        <tr>
                          <td>John</td>
                          <td>2331</td>
                          <td>19</td>
                          <td>John@gmail.com</td>
                          <td>$590</td>
                          <td>London</td>
                          <td>09/23/2019</td>
                          <td>
                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#view"><span class="fa fa-eye"></span></button>
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#edit"><span class="fa fa-pencil-square"></span></button>
                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#"><span class="fa fa-trash"></span></button>
                          </td>
                        </tr>
                        <tr>
                          <td>John</td>
                          <td>2331</td>
                          <td>19</td>
                          <td>John@gmail.com</td>
                          <td>$590</td>
                          <td>London</td>
                          <td>09/23/2019</td>
                          <td>
                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#view"><span class="fa fa-eye"></span></button>
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#edit"><span class="fa fa-pencil-square"></span></button>
                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#"><span class="fa fa-trash"></span></button>
                          </td>
                        </tr>
                        <tr>
                          <td>John</td>
                          <td>2331</td>
                          <td>19</td>
                          <td>John@gmail.com</td>
                          <td>$590</td>
                          <td>London</td>
                          <td>09/23/2019</td>
                          <td>
                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#view"><span class="fa fa-eye"></span></button>
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#edit"><span class="fa fa-pencil-square"></span></button>
                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#"><span class="fa fa-trash"></span></button>
                          </td>
                        </tr>
                        <tr>
                          <td>John</td>
                          <td>2331</td>
                          <td>19</td>
                          <td>John@gmail.com</td>
                          <td>$590</td>
                          <td>London</td>
                          <td>09/23/2019</td>
                          <td>
                            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#view"><span class="fa fa-eye"></span></button>
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#edit"><span class="fa fa-pencil-square"></span></button>
                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#"><span class="fa fa-trash"></span></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
@include('admin.includes.footer')