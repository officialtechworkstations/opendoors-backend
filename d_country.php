<?php 
require 'include/main_head.php';
if ($_SESSION['stype'] == 'Staff' && !in_array('Update', $country_per)) {
    

    
    header('HTTP/1.1 401 Unauthorized');
    ?>
    <style>
        .loader-wrapper {
            display: none;
        }
    </style>
    <?php
    require 'auth.php';
    exit();
}
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
                     Default Country Management</h3>
                </div>
                <div class="col-6">
                  
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">      
               <div class="col-sm-12">
                <div class="card">
               
                  <form method="post" enctype="multipart/form-data">
                                    
                                    <div class="card-body">
                                        
										<input type="hidden" name="type" value="set_country"/>
										<select class="form-control" name="d_con">
										<option>select a country</option>
										<?php 
										$counlist = $rstate->query("select * from tbl_country");
										while($row = $counlist->fetch_assoc())
										{
											?>
											<option value="<?php echo $row["id"];?>" <?php if($row["d_con"]== 1){echo 'selected';} ?>><?php echo $row["title"];?></option>
											<?php 
										}
										?>
										</select>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Set Default Country</button>
                                    </div>
                                </form>
				 
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