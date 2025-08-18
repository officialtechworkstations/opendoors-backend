<?php 
require 'include/main_head.php';
if(isset($_GET['id']))
{	
	if ($_SESSION['stype'] == 'Staff' && !in_array('Update', $payment_per)) {
    

    
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
                     Payment Gateway Management</h3>
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
					if(isset($_GET['id']))
					{
						$data = $rstate->query("select * from tbl_payment_list where id=".$_GET['id']."")->fetch_assoc();
						?>
						
						<form method="POST" enctype="multipart/form-data">
								
								<div class="form-group mb-3">
                                            <label>Payment Gateway Name</label>
                                            <input type="text" class="form-control " disabled placeholder="Enter Payment Gateway Name" value="<?php echo $data['title'];?>" name="cname" required="">
											 <input type="hidden" name="type" value="edit_payment"/>
										<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Payment Gateway SubTitle</label>
                                            <input type="text" class="form-control" placeholder="Enter Payment Gateway SubTitle" value="<?php echo $data['subtitle'];?>" name="ptitle" required="">
                                        </div>
										
                                        <div class="form-group mb-3">
                                            <label><span class="text-danger">*</span> Gateway Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="cat_img" class="custom-file-input form-control" >
                                                <label class="custom-file-label">Choose Service Gateway Image</label>
												<br>
												<img src="<?php echo $data['img'];?>" width="100" height="100"/>
                                            </div>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Payment Gateway Attributes<?php if($_GET['id'] == 1){echo ' ( 1 for Live Paypal And 0 for Sendbox Paypal. )';}?></label>
                                            <input type="text" class="form-control"  data-role="tagsinput" value="<?php echo $data['attributes'];?>" name="p_attr"  required="">
                                        </div>
										
										 <div class="form-group mb-3">
                                            <label>Payment Gateway Status</label>
                                            <select name="status" class="form-control">
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
											<option value="0" <?php if($data['status'] == 0){echo 'selected';}?> >UnPublish</option>
											</select>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Show On Wallet?</label>
                                            <select name="p_show" class="form-control">
											<option value="1" <?php if($data['p_show'] == 1){echo 'selected';}?>>Yes</option>
											<option value="0" <?php if($data['p_show'] == 0){echo 'selected';}?> >No</option>
											</select>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Show On Subscribe Package?</label>
                                            <select name="s_show" class="form-control">
											<option value="1" <?php if($data['s_show'] == 1){echo 'selected';}?>>Yes</option>
											<option value="0" <?php if($data['s_show'] == 0){echo 'selected';}?> >No</option>
											</select>
                                        </div>
							
                                    <button type="submit" class="btn btn-primary">Edit Payment Gateway</button>
                                </form>
								<?php 
					}
					else 
					{
						?>
						<script>
						window.location.href="payment_method.php";
						</script>
						<?php 
					}
					?>
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