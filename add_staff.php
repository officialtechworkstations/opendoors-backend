<?php 
require 'include/main_head.php';
if($_SESSION['stype'] == 'Staff')
	{
		header('HTTP/1.1 401 Unauthorized');
    
    
	?>
	<style>
	.loader-wrapper
	{
		display:none;
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
                     Staff  Management</h3>
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
					 $data = $rstate->query("SELECT * FROM `tbl_staff` where id=".$_GET['id']."")->fetch_assoc();
					 ?>
				    <form method="post">
				
											
										
    <div class="card-body">
    <div class="row">
        <div class="col-3">
            <div class="form-group mb-3">
                <label>Country</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="country[]" value="Read" <?php if(in_array('Read', explode(',',$data['country']))){echo 'checked';}?>>
                    <label for="readCat">Read</label>

                    <input type="checkbox" id="writeCat" name="country[]" value="Write" <?php if(in_array('Write', explode(',',$data['country']))){echo 'checked';}?>>
                    <label for="writeCat">Write</label>

                    <input type="checkbox" id="updateCat" name="country[]" value="Update" <?php if(in_array('Update', explode(',',$data['country']))){echo 'checked';}?>>
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Category</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="category[]" value="Read" <?php if(in_array('Read', explode(',',$data['category']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="category[]" value="Write" <?php if(in_array('Write', explode(',',$data['category']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="category[]" value="Update" <?php if(in_array('Update', explode(',',$data['category']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Coupon</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="coupon[]" value="Read" <?php if(in_array('Read', explode(',',$data['coupon']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="coupon[]" value="Write" <?php if(in_array('Write', explode(',',$data['coupon']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="coupon[]" value="Update" <?php if(in_array('Update', explode(',',$data['coupon']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Enquiry</label>
                <div class="checkbox-group">
                   
                    <input type="checkbox" id="updatePage" name="enquiry[]" value="Read" <?php if(in_array('Read', explode(',',$data['enquiry']))){echo 'checked';}?>>
                    <label for="updatePage">Read</label>
                </div>
            </div>
			
			
			
        </div>

        <div class="col-3">
            <div class="form-group mb-3">
                <label>Payout</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="payout[]" value="Read" <?php if(in_array('Read', explode(',',$data['payout']))){echo 'checked';}?>>
                    <label for="readCat">Read</label>

                    

                    <input type="checkbox" id="updateCat" name="payout[]" value="Update" <?php if(in_array('Update', explode(',',$data['payout']))){echo 'checked';}?>>
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Property</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="property[]" value="Read" <?php if(in_array('Read', explode(',',$data['property']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="property[]" value="Write" <?php if(in_array('Write', explode(',',$data['property']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="property[]" value="Update" <?php if(in_array('Update', explode(',',$data['property']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Extra Image</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="eimg[]" value="Read" <?php if(in_array('Read', explode(',',$data['eimg']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>
					
					<input type="checkbox" id="writePage" name="eimg[]" value="Write" <?php if(in_array('Write', explode(',',$data['eimg']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="eimg[]" value="Update" <?php if(in_array('Update', explode(',',$data['eimg']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Facility</label>
                <div class="checkbox-group">
                   
                    <input type="checkbox" id="updatePage" name="facility[]" value="Read" <?php if(in_array('Read', explode(',',$data['facility']))){echo 'checked';}?>>
                    <label for="updatePage">Read</label>
					
					<input type="checkbox" id="writePage" name="facility[]" value="Write" <?php if(in_array('Write', explode(',',$data['facility']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="facility[]" value="Update" <?php if(in_array('Update', explode(',',$data['facility']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
					
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="form-group mb-3">
                <label>Gallery Category</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="gcat[]" value="Read" <?php if(in_array('Read', explode(',',$data['gcat']))){echo 'checked';}?>>
                    <label for="readCat">Read</label>

                    <input type="checkbox" id="writeCat" name="gcat[]" value="Write" <?php if(in_array('Write', explode(',',$data['gcat']))){echo 'checked';}?>>
                    <label for="writeCat">Write</label>

                    <input type="checkbox" id="updateCat" name="gcat[]" value="Update" <?php if(in_array('Update', explode(',',$data['gcat']))){echo 'checked';}?>>
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Gallery</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="gal[]" value="Read" <?php if(in_array('Read', explode(',',$data['gal']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="gal[]" value="Write" <?php if(in_array('Write', explode(',',$data['gal']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="gal[]" value="Update" <?php if(in_array('Update', explode(',',$data['gal']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Package</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="package[]" value="Read" <?php if(in_array('Read', explode(',',$data['package']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="package[]" value="Write" <?php if(in_array('Write', explode(',',$data['package']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="package[]" value="Update" <?php if(in_array('Update', explode(',',$data['package']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Booking</label>
                <div class="checkbox-group">
                    

                    <input type="checkbox" id="writePage" name="booking[]" value="Read" <?php if(in_array('Read', explode(',',$data['booking']))){echo 'checked';}?>>
                    <label for="writePage">Read</label>

                   
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="form-group mb-3">
                <label>Page</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="page[]" value="Read" <?php if(in_array('Read', explode(',',$data['page']))){echo 'checked';}?>>
                    <label for="readCat">Read</label>

                    <input type="checkbox" id="writeCat" name="page[]" value="Write" <?php if(in_array('Write', explode(',',$data['page']))){echo 'checked';}?>>
                    <label for="writeCat">Write</label>

                    <input type="checkbox" id="updateCat" name="page[]" value="Update" <?php if(in_array('Update', explode(',',$data['page']))){echo 'checked';}?>>
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>FAQ</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="faq[]" value="Read" <?php if(in_array('Read', explode(',',$data['faq']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="faq[]" value="Write" <?php if(in_array('Write', explode(',',$data['faq']))){echo 'checked';}?>>
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="faq[]" value="Update" <?php if(in_array('Update', explode(',',$data['faq']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>User List</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="ulist[]" value="Read" <?php if(in_array('Read', explode(',',$data['ulist']))){echo 'checked';}?>>
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="updatePage" name="ulist[]" value="Update" <?php if(in_array('Update', explode(',',$data['ulist']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Payment Gateway</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="updatePage" name="payment[]" value="Read" <?php if(in_array('Read', explode(',',$data['payment']))){echo 'checked';}?>>
                    <label for="updatePage">Read</label>
                    <input type="checkbox" id="updatePage" name="payment[]" value="Update" <?php if(in_array('Update', explode(',',$data['payment']))){echo 'checked';}?>>
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			
 </div>
 
 
 
        </div>
	
	<div class="form-group mb-3">
                                   
                                        <label  id="basic-addon1">Email Address</label>
                                    
                                  <input type="text" class="form-control" placeholder="Email Address"  name="email" value="<?php echo $data['email'];?>" required>
                               
								</div>
								
								<div class="form-group mb-3">
                                   
                                        <label  id="basic-addon1">Enter Password</label>
                                    
                                  <input type="text" class="form-control" placeholder="Enter Password"  name="password" value="<?php echo $data['password'];?>" required>
                               <input type="hidden" name="type" value="edit_staff"/>
										<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
										
								</div>
								
                                    
                                   <div class="form-group mb-3">
                                    
                                        <label  for="inputGroupSelect01">Staff Status</label>
                                    
                                    <select  class="form-control" name="status" id="inputGroupSelect01" required>
                                        <option value="">Select a Status</option>
                                        <option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
                                        <option value="0" <?php if($data['status'] == 0){echo 'selected';}?>>Unpublish</option>
                                       
                                    </select>
                                </div>
</div>
	<div class="card-footer text-left">
        <button class="btn btn-primary">Edit Staff</button>
 </div>  
    </div>

    
</form>
					 <?php 
				 }
				 else 
				 {
				 ?>
                  <form method="post">
				
											
										
    <div class="card-body">
    <div class="row">
        <div class="col-3">
            <div class="form-group mb-3">
                <label>Country</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="country[]" value="Read">
                    <label for="readCat">Read</label>

                    <input type="checkbox" id="writeCat" name="country[]" value="Write">
                    <label for="writeCat">Write</label>

                    <input type="checkbox" id="updateCat" name="country[]" value="Update">
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Category</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="category[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="category[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="category[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Coupon</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="coupon[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="coupon[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="coupon[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Enquiry</label>
                <div class="checkbox-group">
                   
                    <input type="checkbox" id="updatePage" name="enquiry[]" value="Read">
                    <label for="updatePage">Read</label>
                </div>
            </div>
			
			
			
        </div>

        <div class="col-3">
            <div class="form-group mb-3">
                <label>Payout</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="payout[]" value="Read">
                    <label for="readCat">Read</label>

                    

                    <input type="checkbox" id="updateCat" name="payout[]" value="Update">
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Property</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="property[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="property[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="property[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Extra Image</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="eimg[]" value="Read">
                    <label for="readPage">Read</label>
					
					<input type="checkbox" id="writePage" name="eimg[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="eimg[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Facility</label>
                <div class="checkbox-group">
                   
                    <input type="checkbox" id="updatePage" name="facility[]" value="Read">
                    <label for="updatePage">Read</label>
					
					<input type="checkbox" id="writePage" name="facility[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="facility[]" value="Update">
                    <label for="updatePage">Update</label>
					
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="form-group mb-3">
                <label>Gallery Category</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="gcat[]" value="Read">
                    <label for="readCat">Read</label>

                    <input type="checkbox" id="writeCat" name="gcat[]" value="Write">
                    <label for="writeCat">Write</label>

                    <input type="checkbox" id="updateCat" name="gcat[]" value="Update">
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Gallery</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="gal[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="gal[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="gal[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>Package</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="package[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="package[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="package[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Booking</label>
                <div class="checkbox-group">
                    

                    <input type="checkbox" id="writePage" name="booking[]" value="Read">
                    <label for="writePage">Read</label>

                   
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="form-group mb-3">
                <label>Page</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readCat" name="page[]" value="Read">
                    <label for="readCat">Read</label>

                    <input type="checkbox" id="writeCat" name="page[]" value="Write">
                    <label for="writeCat">Write</label>

                    <input type="checkbox" id="updateCat" name="page[]" value="Update">
                    <label for="updateCat">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>FAQ</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="faq[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="writePage" name="faq[]" value="Write">
                    <label for="writePage">Write</label>

                    <input type="checkbox" id="updatePage" name="faq[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>User List</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="readPage" name="ulist[]" value="Read">
                    <label for="readPage">Read</label>

                    <input type="checkbox" id="updatePage" name="ulist[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			<div class="form-group mb-3">
                <label>Payment Gateway</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="updatePage" name="payment[]" value="Read">
                    <label for="updatePage">Read</label>
                    <input type="checkbox" id="updatePage" name="payment[]" value="Update">
                    <label for="updatePage">Update</label>
                </div>
            </div>
			
			
 </div>
 
 
 
        </div>
	
	<div class="form-group mb-3">
                                   
                                        <label  id="basic-addon1">Email Address</label>
                                    
                                  <input type="text" class="form-control" placeholder="Email Address"  name="email" required>
                               
								</div>
								
								<div class="form-group mb-3">
                                   
                                        <label  id="basic-addon1">Enter Password</label>
                                    
                                  <input type="text" class="form-control" placeholder="Enter Password"  name="password" required>
                                <input type="hidden" name="type" value="add_staff"/>
										
								</div>
								
                                    
                                   <div class="form-group mb-3">
                                    
                                        <label  for="inputGroupSelect01">Staff Status</label>
                                    
                                    <select  class="form-control" name="status" id="inputGroupSelect01" required>
                                        <option value="">Select a Status</option>
                                        <option value="1">Publish</option>
                                        <option value="0">Unpublish</option>
                                       
                                    </select>
                                </div>
</div>
	<div class="card-footer text-left">
        <button class="btn btn-primary">Add Staff</button>
 </div>  
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