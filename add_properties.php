<?php 
require 'include/main_head.php';
if(isset($_GET['id']))
{	
	if ($_SESSION['stype'] == 'Staff' && !in_array('Update', $property_per)) {
    

    
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
if ($_SESSION['stype'] == 'Staff' && !in_array('Write', $property_per)) {
    

    
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
                     Property Management</h3>
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
					 $data = $rstate->query("select * from tbl_property where id=".$_GET['id']."")->fetch_assoc();
					 ?>
					 <form method="post" enctype="multipart/form-data">
                                    
                                    <div class="card-body">
                                        
										<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Title</label>
                                            <input type="text" class="form-control" placeholder="Enter Property Title" name="title" value="<?php echo $data['title'];?>" required="">
                                        </div>
										</div>
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <label>Property Image</label>
                                            <input type="file" class="form-control" name="cat_img" >
											<input type="hidden" name="type" value="edit_property"/>
											<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
											<br>
									<img src="<?php echo $data['image'];?>" width="100" height="100"/>
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Sell Or Rent ?</label>
											 <select name="pbuysell" id="pbuysell" class="form-control" required>
											<option value="" selected disabled>Select Option</option>
                                           <option value="1" <?php if($data['pbuysell'] == 1){echo 'selected';}?>>Rent</option>
									<option value="2"  <?php if($data['pbuysell'] == 2){echo 'selected';}?>>Buy</option>
									</select>
                                        </div>
										</div>
										
										
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label id="plable"><?php if($data['pbuysell'] == 1){ echo 'Property Price Per Night';}else { echo 'Property Sell Price';}?></label>
                                            <input type="text" class="form-control numberonly" id="price" placeholder="<?php if($data['pbuysell'] == 1){ echo 'Enter Price Per Night';}else { echo 'Enter Property Sell Price';}?>" name="price" value="<?php echo $data['price'];?>" required="">
                                        </div>
										</div>
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Country ?</label>
											 <select name="country_id" class="form-control" required>
											<option value="">Select Country</option>
                                          <?php 
$zones = $rstate->query("select * from tbl_country");
while($rows = $zones->fetch_assoc())
{
	?>
	<option value="<?php echo $rows['id'];?>" <?php if($data['country_id'] == $rows['id']){echo 'selected';}?>><?php echo $rows['title'];?></option>
	<?php 
}
?>
									</select>
                                        </div>
										</div>
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										 <div class="form-group mb-3">
                                            <label>Property Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
									<option value="0" <?php if($data['status'] == 0){echo 'selected';}?>>Unpublish</option>
											</select>
                                        </div>
                                        </div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label id="limitlable">Property Total Person Allowed?</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Person Limit" id="plimit" name="plimit" value="<?php echo $data['plimit'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Full Address</label>
                                            <input type="text" class="form-control" placeholder="Enter Full Address" name="address" value="<?php echo $data['address'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Select Property Facility</label>
                                            <select name="facility[]" id="product" class="select2-multi-select form-control" multiple required>
											<option value=""> Select Property Facility</option>
									  <?php 
$zone = $rstate->query("select * from tbl_facility");
$people = explode(',',$data['facility']);
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>" <?php if(in_array($row['id'], $people)){echo 'selected';}?>><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
								<div class="form-group mb-3">
									<label for="cname">Property Description </label>
									<textarea class="form-control" rows="10" name="description"  style="resize: none;"><?php echo $data['description'];?></textarea>
								</div>
							</div>	
							<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
							<div class="row">
							<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Total Beds</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Beds Count" name="beds"  value="<?php echo $data['beds'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Total Bathroom</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Bathroom Count" name="bathroom"  value="<?php echo $data['bathroom'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property SQFT.</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Property SQFT." name="sqft"  value="<?php echo $data['sqrft'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Rating</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Property Rating" name="rate"  value="<?php echo $data['rate'];?>" required="">
                                        </div>
										</div>
										
										
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Select Property Type</label>
                                            <select name="ptype" id="product" class=" form-control" required>
											<option value=""> Select Property Type</option>
									  <?php 
$zone = $rstate->query("select * from tbl_category");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>" <?php if($data['ptype'] == $row['id']){echo 'selected';}?>><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										</div>
										
										
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Latitude</label>
                                            <input type="text" class="form-control" placeholder="Enter Latitude" name="latitude"  value="<?php echo $data['latitude'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Longtitude</label>
                                            <input type="text" class="form-control" placeholder="Enter Longtitude" name="longtitude" value="<?php echo $data['longtitude'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control mobile" placeholder="Enter Mobile" name="mobile" value="<?php echo $data['mobile'];?>" required="">
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>City,Country</label>
                                            <input type="text" class="form-control" placeholder="Like New york,Us" name="ccount" value="<?php echo $data['city'];?>" required="">
                                        </div>
										</div>
										
										</div>
										</div>
										</div>
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Edit  Property</button>
                                    </div>
                                </form>
					 <?php 
				 }
				 else 
				 {
				 ?>
                  <form method="post" enctype="multipart/form-data">
                                    
                                    <div class="card-body">
                                        
										<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Title</label>
                                            <input type="text" class="form-control" placeholder="Enter Property Title" name="title"  required="">
                                        </div>
										</div>
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <label>Property Image</label>
                                            <input type="file" class="form-control" name="cat_img"  required="">
											<input type="hidden" name="type" value="add_property"/>
                                        </div>
										</div>
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Sell Or Rent ?</label>
											 <select name="pbuysell" id="pbuysell" class="form-control" required>
											<option value="" selected disabled>Select Option</option>
                                           <option value="1">Rent</option>
									<option value="2" >Buy</option>
									</select>
                                        </div>
										</div>
										
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label id="plable">Property Price Per Night</label>
                                            <input type="text" class="form-control numberonly" id="price" placeholder="Enter Price Per Night" name="price"  required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Country ?</label>
											 <select name="country_id" class="form-control" required>
											<option value="">Select Country</option>
                                          <?php 
$zones = $rstate->query("select * from tbl_country");
while($rows = $zones->fetch_assoc())
{
	?>
	<option value="<?php echo $rows['id'];?>"><?php echo $rows['title'];?></option>
	<?php 
}
?>
									</select>
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										 <div class="form-group mb-3">
                                            <label>Property Status</label>
                                            <select name="status" class="form-control" required>
											<option value="">Select Status</option>
											<option value="1">Publish</option>
											<option value="0">UnPublish</option>
											</select>
                                        </div>
                                        </div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label id="limitlable">Property Total Person Allowed?</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Person Limit" id="plimit" name="plimit"  required="">
                                        </div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Full Address</label>
                                            <input type="text" class="form-control" placeholder="Enter Full Address" name="address"  required="">
                                        </div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Select Property Facility</label>
                                            <select name="facility[]" id="product" class="select2-multi-select form-control" multiple required>
											<option value=""> Select Property Facility</option>
									  <?php 
$zone = $rstate->query("select * from tbl_facility");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
								<div class="form-group mb-3">
									<label for="cname">Property Description </label>
									<textarea class="form-control" rows="10" name="description"  style="resize: none;"></textarea>
								</div>
							</div>	
							<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
							<div class="row">
							<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Total Beds</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Beds Count" name="beds"  required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Total Bathroom</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Bathroom Count" name="bathroom"  required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property SQFT.</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Property SQFT." name="sqft"  required="">
                                        </div>
										</div>
										
										<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Property Rating</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Property Rating" name="rate"  required="">
                                        </div>
										</div>
										
										
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Select Property Type</label>
                                            <select name="ptype" id="product" class=" form-control" required>
											<option value=""> Select Property Type</option>
									  <?php 
$zone = $rstate->query("select * from tbl_category");
while($row = $zone->fetch_assoc())
{
	?>
	<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
	<?php 
}
?>
									   </select>
                                        </div>
										</div>
										
										
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Latitude</label>
                                            <input type="text" class="form-control" placeholder="Enter Latitude" name="latitude"  required="">
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Longtitude</label>
                                            <input type="text" class="form-control" placeholder="Enter Longtitude" name="longtitude"  required="">
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control mobile" placeholder="Enter Mobile" name="mobile"  required="">
                                        </div>
										</div>
										
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>City,Country</label>
                                            <input type="text" class="form-control" placeholder="Like New york,Us" name="ccount"  required="">
                                        </div>
										</div>
										
										</div>
										</div>
										</div>
                                    </div>
                                    <div class="card-footer text-left">
                                        <button  class="btn btn-primary">Add Property</button>
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