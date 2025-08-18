<?php 
require 'include/main_head.php';
if(isset($_GET['id']))
{	
	if ($_SESSION['stype'] == 'Staff' && !in_array('Update', $gal_per)) {
    

    
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
else 
{
if ($_SESSION['stype'] == 'Staff' && !in_array('Write', $gal_per)) {
    

    
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
                     Gallery  Management</h3>
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
                 <?php 
				 if(isset($_GET['id']))
				 {
					 $data = $rstate->query("select * from tbl_gallery where id=".$_GET['id']."")->fetch_assoc();
					 ?>
					 <form method="post" enctype="multipart/form-data">
                                     <div class="card-body">
                                        
										
										<div class="form-group mb-3">
                                            <label>Select Property</label>
                                            <select name="property" id="property" class="select2-multi-selects form-control" required>
											<option value=""> Select Property</option>
									  <?php 
$zone = $rstate->query("select * from tbl_property where add_user_id=0");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>" <?php if($data['pid'] == $row['id']){echo 'selected';}?>><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Select Gallery Category</label>
                                            <select name="galcat" id="galcat" class="select2-multi-cat form-control" required>
											<option value=""> Select Gallery Category</option>
									  <?php 
$zone = $rstate->query("select * from tbl_gal_cat");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>" <?php if($data['cat_id'] == $row['id']){echo 'selected';}?>><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										
										
										
                                        
										<div class="form-group mb-3">
                                            <label>Gallery Image</label>
                                            <input type="file" class="form-control" name="cat_img">
											<br>
											<img src="<?php echo $data['img']?>" width="100px"/>
											<input type="hidden" name="type" value="edit_gal"/>
											
										<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
                                        </div>
										
										
										 <div class="form-group mb-3">
                                            <label>Gallery  Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
											<option value="0" <?php if($data['status'] == 0){echo 'selected';}?> >UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Edit  Gallery Category</button>
                                    </div>
                                </form>
					 <?php 
				 }
				 else 
				 {
				 ?>
                  <form method="post" enctype="multipart/form-data">
                                    
                                    <div class="card-body">
                                        
										
										<div class="form-group mb-3">
                                            <label>Select Property</label>
                                            <select name="property" id="property" class="select2-multi-selects form-control" required>
											<option value=""> Select Property</option>
									  <?php 
$zone = $rstate->query("select * from tbl_property where add_user_id=0");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Select Gallery Category</label>
                                            <select name="galcat" id="galcat" class="select2-multi-cat form-control" required>
											<option value=""> Select Gallery Category</option>
									  
									   </select>
                                        </div>
										
										
										
                                        
										<div class="form-group mb-3">
                                            <label>Gallery Image</label>
                                            <input type="file" class="form-control" name="cat_img"  required="">
											<input type="hidden" name="type" value="add_gal"/>
                                        </div>
										
										
										 <div class="form-group mb-3">
                                            <label>Gallery  Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1">Publish</option>
											<option value="0">UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Add Gallery </button>
                                    </div>
                                </form>
				 <?php } ?>
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