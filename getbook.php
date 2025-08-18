<?php 
require 'include/reconfig.php';
if(isset($_POST['start_date']) && isset($_POST['end_date']))
{
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
$city = $rstate->query("SELECT * FROM `tbl_book` where book_date between '".$start_date."' and '".$end_date."'   order by id desc");
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
												</tr>
											<?php 
										}
}
?>