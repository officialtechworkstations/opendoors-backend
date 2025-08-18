<?php 
require 'include/main_head.php';
if ($_SESSION['stype'] == 'Staff' && !in_array('Read', $ulist_per)) {
    

    
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
                    User  List Management</h3>
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
                                                <th></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>mobile</th>
                                                <th>Join Date</th>
												
                                                <?php 
												if($_SESSION['stype'] == 'Staff')
		{
			if (in_array('Update', $ulist_per)) {
			?>
			<th>Status</th>
			<?php
			}			
		}
		else 
		{
												?>
												<th>Status</th>
												<?php } ?>
                                                <th>Refer Code</th>
                                                <th>Parent Code</th>
                                                <th>Wallet</th>
												<th>Is Subscribe?</th>
												<th>Package Name</th>
												<th>Start Date</th>
												<th>Expired Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
											 $stmt = $rstate->query("SELECT * FROM `tbl_user`");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$pname = $rstate->query("select title from tbl_package where id=".$row['pack_id']."")->fetch_assoc();
	$i = $i + 1;
											?>
                                            <tr>
											<td>
                                                 <?php 
												 if(empty($row['pro_pic']))
												 {
												 }
												 else 
												 {
?>												 
                                                <img class="rounded-circle" width="35" height="35" src="<?php echo $row['pro_pic'];?>" alt="">
												 <?php } ?></td>
                                                <td><?php echo $row['name'];?></td>
												<td><?php echo $row['email'];?></td>
												<td><?php echo $row['mobile'];?></td>
												<td><?php echo $row['reg_date'];?></td>
												<?php 
												if($_SESSION['stype'] == 'Staff')
		{
			if (in_array('Update', $ulist_per)) {
				?>
												<?php if($row['status'] == 1) { ?>
												
                                                <td><span  data-id="<?php echo $row['id'];?>" data-status="0" data-type="update_status" coll-type="userstatus" class="drop badge badge-danger">Make Deactive</span></td>
												<?php } else { ?>
												
												<td>
												<span data-id="<?php echo $row['id'];?>" data-status="1" data-type="update_status" coll-type="userstatus" class="badge drop  badge-success">Make Active</span></td>
												<?php } ?>
												<?php 
									   }			
		}
		else 
		{
			?>
			
			<?php if($row['status'] == 1) { ?>
												
                                                <td><span  data-id="<?php echo $row['id'];?>" data-status="0" data-type="update_status" coll-type="userstatus" class="drop badge badge-danger">Make Deactive</span></td>
												<?php } else { ?>
												
												<td>
												<span data-id="<?php echo $row['id'];?>" data-status="1" data-type="update_status" coll-type="userstatus" class="badge drop  badge-success">Make Active</span></td>
												<?php } ?>
												
		<?php } ?>
												
												<td><?php echo $row['refercode'];?></td>
												<td><?php echo $row['parentcode'];?></td>
												<td><?php echo $row['wallet'].$set['currency'];?></td>
                                               	<?php if($row['is_subscribe'] == 1) { ?>
												
                                                <td><span    class=" badge badge-success">Subscribe</span></td>
												<?php } else { ?>
												
												<td>
												<span class="badge   badge-danger">Not Subscribe</span></td>
												<?php } ?>
                                               			
<?php
if(empty($pname['title']))
	{
		?>
		<td>
												<span class="badge   badge-danger">Not Subscribe</span></td>
		<?php 
		
	}
	else 
	{
		?>
		<td><span    class=" badge badge-success"><?php echo $pname['title']; ?></span></td>
		<?php 
		
	}	
?>	

<?php
if(empty($row['start_date']))
	{
		?>
		<td>
												<span class="badge   badge-danger">Not Subscribe</span></td>
		<?php 
		
	}
	else 
	{
		?>
		<td><span    class=" badge badge-success"><?php echo $row['start_date']; ?></span></td>
		<?php 
		
	}	
?>	

<?php
if(empty($row['end_date']))
	{
		?>
		<td>
												<span class="badge   badge-danger">Not Subscribe</span></td>
		<?php 
		
	}
	else 
	{
		?>
		<td><span    class=" badge badge-success"><?php echo $row['end_date']; ?></span></td>
		<?php 
		
	}	
?>												
                                            </tr>
<?php } ?>
                                            
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