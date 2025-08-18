<?php 
require 'include/main_head.php';
if(isset($_GET['id']))
{	
	if ($_SESSION['stype'] == 'Staff' && !in_array('Update', $package_per)) {
    

    
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
if ($_SESSION['stype'] == 'Staff' && !in_array('Write', $package_per)) {
    

    
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
                     Package  Management</h3>
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
					 $data = $rstate->query("select * from tbl_package where id=".$_GET['id']."")->fetch_assoc();
					 ?>
					 <form method="post" enctype="multipart/form-data">
                                     <div class="card-body">
                                        
										
										
										
										<div class="form-group mb-3">
                                            <label>Package Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Package" name="title" value="<?php echo $data['title'];?>" required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Package Total Day</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Total Day" name="day" value="<?php echo $data['day'];?>"  required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Package Price</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Price" name="price" value="<?php echo $data['price'];?>"  required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Package Description</label>
                                            <textarea class="form-control" rows="5" name="description" id="cdesc" style="resize: none;"><?php echo $data['description'];?></textarea>
                                        </div>
										
										
                                        
										<div class="form-group mb-3">
                                            <label>Package Image</label>
                                            <input type="file" class="form-control" name="cat_img"  >
											 <input type="hidden" name="type" value="edit_package"/>
											 <br>
											<img src="<?php echo $data['image']?>" width="100px"/>
										<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
                                        </div>
										
										
										 <div class="form-group mb-3">
                                            <label>Package  Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
                                        <option value="0" <?php if($data['status'] == 0){echo 'selected';}?>>Unpublish</option>
											</select>
                                        </div>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Edit  Package</button>
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
                                            <label>Package Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Package" name="title"  required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Package Total Day</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Total Day" name="day"  required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Package Price</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Price" name="price"   required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Package Description</label>
                                            <textarea class="form-control" rows="5" name="description" id="cdesc" style="resize: none;"></textarea>
                                        </div>
										
										
                                        
										<div class="form-group mb-3">
                                            <label>Package Image</label>
                                            <input type="file" class="form-control" name="cat_img"  required="">
											<input type="hidden" name="type" value="add_package"/>
                                        </div>
										
										
										 <div class="form-group mb-3">
                                            <label>Package  Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1">Publish</option>
											<option value="0">UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Add Package </button>
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