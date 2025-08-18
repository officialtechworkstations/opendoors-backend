<?php 
require 'include/reconfig.php';
if(isset($_POST['book_date']))
{
	$timestamp = $_POST['book_date'];
	$query = "select count(*) as total_book from tbl_book where book_date='".$timestamp."'";
		  $totalbook = $rstate->query($query)->fetch_assoc();
		  
		  $query = "select count(*) as total_complete from tbl_book where book_date='".$timestamp."' and book_status='Completed' ";
		  $totalcom = $rstate->query($query)->fetch_assoc();
						
					$query = "select count(*) as total_complete from tbl_book where book_date='".$timestamp."' and book_status='Cancelled'";
		  $totalcan = $rstate->query($query)->fetch_assoc();
		  
		   $query ="select sum(`total`) as total from tbl_book where book_status='Completed' and book_date='".$timestamp."'"; 
		   $sales = $rstate->query($query)->fetch_assoc();
                           $bs=0;
	if(empty($sales['total'])){}else {$bs = number_format((float)($sales['total']), 2, '.', ''); }
	?>
	<tr>
											
											<td>
                                                   <b> Total Booked Property</b>
                                                </td>
												
                                                <td>
                                                    <b><span class="text text-warning"><?php echo $totalbook['total_book']; ?></span></b>
                                                </td>
                                                </tr>
												
												<tr>
												<td>
                                                   <b> Total Booking Completed</b>
                                                </td>
												
                                                <td>
                                                   <b> <span class="text text-success"><?php echo $totalcom['total_complete']; ?></span></b>
                                                </td>
												
                                                </tr>
												
												<tr>
												<td>
                                                   <b> Total Cancelled Booking</b>
                                                </td>
												
                                                <td>
                                                   <b> <span class="text text-danger"><?php echo $totalcan['total_complete']; ?></span></b>
                                                </td>
												
                                                </tr>
												
												<tr>
												<td>
                                                   <b> Total Booked Earning</b>
                                                </td>
												
                                                <td>
                                                   <b> <span class="text text-primary"><?php echo $bs.$set['currency']; ?></span></b>
                                                </td>
												
                                                </tr>
	
	<?php 
}
?>