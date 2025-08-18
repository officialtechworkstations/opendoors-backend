<?php 
require 'include/main_head.php';
if ($_SESSION['stype'] == 'Staff' && !in_array('Read', $enquiry_per)) {
    

    
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
                     Enquiry List Management</h3>
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
				<div class="card-body">
				<div class="table-responsive">
            <table class="display" id="basic-1">
                        <thead>
                          <tr>
											<th>Sr No.</th>
											<th>Property Title</th>
											<th>Property Image</th>
												<th>Enquiry Username</th>
												<th>Enquiry Mobile</th>
									</tr>
                                        </thead>
                                        <tbody>
                                           <?php 
										$city = $rstate->query("select * from tbl_enquiry where add_user_id=0");
										$i=0;
										while($row = $city->fetch_assoc())
										{
											$prop_data = $rstate->query("select * from tbl_property where id=".$row['prop_id']."")->fetch_assoc();
											$udata = $rstate->query("select * from tbl_user where id=".$row['uid']."")->fetch_assoc();
											$i = $i + 1;
											?>
											<tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
												
												<td>
                                                    <?php echo $prop_data['title']; ?>
                                                </td>
                                                
                                               <td>
                                                   <img src="<?php echo $prop_data['image']; ?>" width="100px"/>
                                                </td>
                                                
                                               <td>
                                                    <?php echo $udata['name']; ?>
                                                </td>
												 <td>
                                                    <?php echo $udata['ccode'].$udata['mobile']; ?>
                                                </td>
												
                                               
                                                </tr>
											<?php 
										}
										?>
                                           
                                        </tbody>
                      </table>
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