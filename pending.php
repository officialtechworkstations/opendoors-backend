<?php 
require 'include/main_head.php';
if ($_SESSION['stype'] == 'Staff' && !in_array('Read', $booking_per)) {
    

    
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
                     Pending Booking Management</h3>
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
				<?php 
				if(isset($_GET['cn_id']))
				{
					?>
					<form method="POST"  enctype="multipart/form-data">
								
								<div class="form-group mb-3">
                                   
                                        <label  id="basic-addon1">Enter Reason </label>
                                    
                                  <textarea type="text" class="form-control" placeholder="Enter Reason"  name="reason" aria-label="Username" aria-describedby="basic-addon1" style="resize:none;"></textarea>
								  <input type="hidden" name="type" value="update_reason"/>
										<input type="hidden" name="id" value="<?php echo $_GET['cn_id'];?>"/>
                            
								</div>
								
								
                                    <button type="submit" class="btn btn-primary">Cancle Booking</button>
                                </form>
					<?php 
				}
				else 
				{
				?>
				<div class="table-responsive">
                <table class="display" id="basic-1">
                        <thead>
                          <tr>
                            <th>Sr No.</th>
							<th>Property Title</th>
							<th>Property Image</th>
											
												<th>Property Price</th>
												<th>Property Total Day</th>
												<th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                           <?php 
										$city = $rstate->query("select * from tbl_book where add_user_id=0 and book_status='Booked'");
										$i=0;
										while($row = $city->fetch_assoc())
										{
											$i = $i + 1;
											?>
											<tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                                
												<td class="align-middle">
                                                   <?php echo $row['prop_title'];?>
                                                </td>
												
                                                <td class="align-middle">
                                                   <img src="<?php echo $row['prop_img']; ?>" width="70" height="80"/>
                                                </td>
                                                
												<td class="align-middle">
                                                   <?php echo $row['prop_price'].$set['currency'];?>
                                                </td>
												
												<td class="align-middle">
                                                   <?php echo $row['total_day'].' Days';?>
                                                </td>
                                               
                                                <td style="white-space: nowrap; width: 15%;"><div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                           <div class="btn-group btn-group-sm" style="float: none;">
										  <button class="btn btn-info preview_d" style="float: none; margin: 5px;" data-id="<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#myModal">View Details</button>
										  <button class="btn btn-success drop" style="float: none; margin: 5px;" data-id="<?php echo $row['id'];?>" data-status="Confirmed"  data-type="update_status" coll-type="confirmed_book">Confirmed</button>
										  <a href="?cn_id=<?php echo $row['id'];?>" class="btn btn-danger" style="float: none; margin: 5px;">Cancelled</a>
										  
										   </div>
                                           
                                       </div></td>
                                                </tr>
											<?php 
										}
										?>
                          
                        </tbody>
                      </table>
					  </div>
				<?php } ?>
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
	
	<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg ">

    
    <div class="modal-content gray_bg_popup">
      <div class="modal-header">
        <h4>Order Preivew</h4>
        <button type="button" class="close popup_open" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body p_data">
      
      </div>
     
    </div>

  </div>
</div>

    <!-- latest jquery-->
    <?php 
require 'include/footer.php';
?>
  </body>
</html>