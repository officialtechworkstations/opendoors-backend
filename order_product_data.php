<?php 

require 'include/reconfig.php';

$pid = $_POST['pid'];
$c = $rstate->query("select * from tbl_book where id=".$pid."")->fetch_assoc();
if($c['book_for'] == 'self')
{
	
}
else 
{
	$odata = $rstate->query("select * from tbl_person_record where book_id=".$pid."")->fetch_assoc();
}
$udata = $rstate->query("select * from tbl_user where id=".$c['uid']."")->fetch_assoc();
$pro_data = $rstate->query("select * from tbl_property where id=".$c['prop_id']."")->fetch_assoc();
$pdata = $rstate->query("select * from tbl_payment_list where id=".$c['p_method_id']."")->fetch_assoc();




?>

<div style="margin-left: auto;float: right;">
<button id='btn' class="btn btn-primary text-right cmd"  style="margin-left:2px;" onclick='downloadimage();'  style="float:right;"><i class="fas fa-camera" aria-hidden="true"></i></button>&nbsp;&nbsp;
<button id='btn' class="btn btn-primary text-right cmd" style="margin-left:2px;" onclick="printDiv();"  style="float:right;"><i class="fas fa-file-pdf"></i></button>
</div>

<div id="divprint">
 
  <div class="card-body bg-white mb-2">
   
                <div class="row d-flex">
                  <div class="col-md-3">
                    <!-- Heading -->
                    <h6 class="text-muted mb-1 card-title">Order No:</h6>
                    <!-- Text -->
                    <p class="mb-lg-0 font-size-sm font-weight-bold"><?php echo '#'.$pid;?></p>
                  </div>
                  <?php 
$date=date_create($c['book_date']);
$order_date =  date_format($date,"d-m-Y");
?>
                  <div class="col-md-3">
                    <!-- Heading -->
                    <h6 class="text-muted mb-1 card-title">Order date:</h6>
                    <!-- Text -->
                    <p class="mb-lg-0 font-size-sm font-weight-bold">
                      <span><?php echo $order_date;?></span>
                    </p>
                  </div>
                  
                  <div class="col-md-3">
                    <!-- Heading -->
                    <h6 class="text-muted mb-1 card-title">Mobile Number:</h6>
                    <!-- Text -->
                    <p class="mb-0 font-size-sm font-weight-bold"> <?php echo $udata['ccode'].$udata['mobile'];?></p>
                  </div>
                  
                  <div class="col-md-3">
                    <!-- Heading -->
                    <h6 class="text-muted mb-1 card-title">Customer Name:</h6>
                    <!-- Text -->
                    <p class="mb-0 font-size-sm font-weight-bold"><?php echo $udata['name'];?></p>
                  </div>
				  
				  
                  
                </div>
              </div>
              
              <div class="card style-2 mb-2">
                <div class="card-header">
                  <h4 class="mb-0 card-title">Total Order</h4>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                    <li class="list-group-item d-flex">
                      <span>Subtotal</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><?php echo $c['subtotal'].' '.$set['currency'];?></span>
                    </li>
					
					<li class="list-group-item d-flex">
                      <span>Total Day</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><?php echo $c['total_day'].' Days';?></span>
                    </li>
					
					 <li class="list-group-item d-flex">
                      <span>Tax</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><?php echo $c['tax'].' '.$set['currency'];?></span>
                    </li>
                  <?php 
  if($c['cou_amt'] != 0)
  {
  ?>
                    <li class="list-group-item d-flex">
                      <span>Coupon Code</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><?php echo $c['cou_amt'].' '.$set['currency'];?></span>
                    </li>
                     <?php } ?>
					 
					 <?php 
  if($c['wall_amt'] != 0)
  {
  ?>
                    <li class="list-group-item d-flex">
                      <span>Wallet</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><?php echo $c['wall_amt'].' '.$set['currency'];?></span>
                    </li>
                     <?php } ?>
					 
					  
                    <li class="list-group-item d-flex font-size-lg font-weight-bold">
					<?php if($pdata['title'] == 'Pay TO Owner')
					{
						?>
						<span>Net Amount <b>(Remain To Pay)</b></span>
						<?php 
					}
					else 
					{
						?>
						<span>Net Amount <b>(Paid)</b></span>
						<?php 
					}
					?>
                      
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><?php echo $c['total'].' '.$set['currency'];?></span>
                    </li>
					
					
					
					
                  </ul>
                </div>
              </div>
			  
			  <div class="card style-2 mb-2">
                <div class="card-header">
                  <h4 class="mb-0 card-title">Payment & Property Details & Check In and Out Information</h4>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
				  
			  <li class="list-group-item d-flex font-size-lg font-weight-bold">
                      <span class="card-title">Paymen Gateway?</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><b> <img src="<?php echo $pdata['img'];?>" style="width: 20px;"> <?php echo $pdata['title'];?></b></span>
                    </li>
					
					<li class="list-group-item d-flex font-size-lg font-weight-bold">
                      <span class="card-title">Property Title?</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><b><?php echo $c['prop_title'];?></b></span>
                    </li>
					
					<li class="list-group-item d-flex font-size-lg font-weight-bold">
                      <span class="card-title">Property Image?</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><img src="<?php echo $c['prop_img'];?>" style="width: 100px;"> </span>
                    </li>
					
					<li class="list-group-item d-flex font-size-lg font-weight-bold">
                      <span class="card-title">Property Check In Date?</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><b><?php echo $c['check_in'];?></b> </span>
                    </li>
					
					<li class="list-group-item d-flex font-size-lg font-weight-bold">
                      <span class="card-title">Property Check Out Date?</span>
                      <span class="ml-auto float-right" style="float: right !important;
    margin-left: auto!important;"><b><?php echo $c['check_out'];?></b></span>
                    </li>
					
					
					
					 </ul>
                </div>
              </div>
              <div class="card style-2">
                <div class="card-header">
                  <h4 class="mb-0">Property &amp; Booking Owner &amp; Booking Status Details</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                                       
                    <div class="col-12 col-md-12" style="margin-bottom: 10px;display:flex;">
                      <!-- Heading -->
					   <div class="col-md-4">
                      <p class="mb-2 card-title font-weight-bold">
                        Property Address:
                      </p>

                      <p class="mb-7 mb-md-0">
                       <?php echo $pro_data['address'];?>
                      </p>
					  </div>
					 <?php 
if($c['book_for'] == 'self')
{
	?>
	 <div class="col-md-4">
                      <p class="mb-2 card-title font-weight-bold">
                        Booking Owners Name:
                      </p>

                      <p class="mb-7 mb-md-0">
                       <?php echo $udata['name'];?>
                      </p>
					  </div>
					  
					  <div class="col-md-4">
                      <p class="mb-2 card-title font-weight-bold">
                        Booking Owner Mobile:
                      </p>

                      <p class="mb-7 mb-md-0">
                       <?php echo $udata['ccode'].$udata['mobile'];?>
                      </p>
					  </div>
	<?php 
}
else 
{
?>	
<div class="col-md-4">
                      <p class="mb-2 card-title font-weight-bold">
                        Booking Owner Name:
                      </p>

                      <p class="mb-7 mb-md-0">
                       <?php echo $odata['fname'].$odata['lname'];?>
                      </p>
					  </div>
					  
					  <div class="col-md-4">
                      <p class="mb-2 card-title font-weight-bold">
                        Booking Owner Mobile:
                      </p>

                      <p class="mb-7 mb-md-0">
                       <?php echo $odata['ccode'].$odata['mobile'];?>
                      </p>
					  </div>
					  
<?php }?>
                    </div>
					
					
                    
                    <div class="col-12 col-md-12">
<?php 
if($c['p_method_id'] == 2)
{
}
else
{
  ?>
                      <!-- Heading -->
                      <p class="mb-2 card-title font-weight-bold">
                       Transaction Id:
                      </p>

                      <p class="mb-2 text-gray-500">
                        <?php echo $c['transaction_id'];?>
                      </p>
<?php 
}
?>
                      <!-- Heading -->
                      <p class="mb-2 card-title font-weight-bold">
                        Booking Status:
                      </p>

                      <p class="mb-0">
                        <?php echo $c['book_status'];?>
                      </p>

                    </div>
					
                  </div>
                </div>
              </div>
              
</div>