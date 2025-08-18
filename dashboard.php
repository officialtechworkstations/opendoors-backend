<?php 
require 'include/main_head.php';
?>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <?php 
	  require 'include/inside_top.php';
	  ?>
      <!-- Page Header Ends                              -->
      <!-- Page Body Start-->
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <?php 
		require 'include/sidebar.php';
		?>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                  <h3>
                     Report Dashboard</h3>
                </div>
                <div class="col-6">
                  
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid ecommerce-page">
            <div class="row">      
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body"> 
                    <div class="d-flex">    
                      <div class="flex-shrink-0">          
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="map-pin"></i></div>
                          <div class="sale-content">
                            <h3>Total Country</h3>
                            <p><?php echo $rstate->query("select * from tbl_country")->num_rows;?> </p>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">         
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="list"></i></div>
                          <div class="sale-content">
                            <h3>Total Category</h3>
                            <p><?php echo $rstate->query("select * from tbl_category")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                     
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="gift"></i></div>
                          <div class="sale-content">
                            <h3>Total Coupon</h3>
                            <p><?php echo $rstate->query("select * from tbl_coupon")->num_rows;?> </p>
                          </div>
                        </div>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="dollar-sign"></i></div>
                          <div class="sale-content">
                            <h3>Total Payment Method</h3>
                            <p><?php echo $rstate->query("select * from tbl_payment_list")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="users"></i></div>
                          <div class="sale-content">
                            <h3>Total Enquiry</h3>
                            <p><?php echo $rstate->query("select * from tbl_enquiry")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="home"></i></div>
                          <div class="sale-content">
                            <h3>Total Property</h3>
                            <p><?php echo $rstate->query("select * from tbl_property")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="camera"></i></div>
                          <div class="sale-content">
                            <h3>Total Extra Images</h3>
                            <p><?php echo $rstate->query("select * from tbl_extra")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="bluetooth"></i></div>
                          <div class="sale-content">
                            <h3>Total Facility</h3>
                            <p><?php echo $rstate->query("select * from tbl_facility")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="folder"></i></div>
                          <div class="sale-content">
                            <h3>Total Gallery Category</h3>
                            <p><?php echo $rstate->query("select * from tbl_gal_cat")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="camera"></i></div>
                          <div class="sale-content">
                            <h3>Total Gallery</h3>
                            <p><?php echo $rstate->query("select * from tbl_gallery")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="calendar"></i></div>
                          <div class="sale-content">
                            <h3>Total Waiting Booking</h3>
                            <p><?php echo $rstate->query("select * from tbl_book where book_status='Booked'")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="check-square"></i></div>
                          <div class="sale-content">
                            <h3>Total Confirmed Booking</h3>
                            <p><?php echo $rstate->query("select * from tbl_book where book_status='Confirmed'")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="eye"></i></div>
                          <div class="sale-content">
                            <h3>Total Check In Booking</h3>
                            <p><?php echo $rstate->query("select * from tbl_book where book_status='Check_in'")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="check-circle"></i></div>
                          <div class="sale-content">
                            <h3>Total Completed Booking</h3>
                            <p><?php echo $rstate->query("select * from tbl_book where book_status='Completed'")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="book"></i></div>
                          <div class="sale-content">
                            <h3>Total Page</h3>
                            <p><?php echo $rstate->query("select * from tbl_page")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="help-circle"></i></div>
                          <div class="sale-content">
                            <h3>Total FAQ</h3>
                            <p><?php echo $rstate->query("select * from tbl_faq")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="users"></i></div>
                          <div class="sale-content">
                            <h3>Total Users</h3>
                            <p><?php echo $rstate->query("select * from tbl_user")->num_rows;?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                  <div class="card-body">
                    <div class="d-flex">    
                      <div class="flex-shrink-0">                
                        <div class="sale-detail">
                          <div class="icon"><i data-feather="credit-card"></i></div>
                          <div class="sale-content">
                            <h3>Total Booked Earning</h3>
                            <p><?php $earn =  $rstate->query("select sum(`total`) as total from tbl_book where book_status='Completed'")->fetch_assoc(); 
                            echo number_format((float)$earn['total'], 2, '.', '').' '.$set['currency'];?></p>
                          </div>
                        </div>
                      </div>
                    
                    </div>
                  </div>
                </div>
              </div>
              
             
             
          
             
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        
      </div>
    </div>
    <!-- latest jquery-->
    <?php 
require 'include/footer.php';
?>
  </body>
</html>