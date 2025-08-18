<?php 
require 'reconfig.php';
$id = $_POST['id'];
?>
<option value=""> Select Gallery Category</option>
<?php 
$sel = $rstate->query("select * from tbl_gal_cat where pid=".$id." and add_user_id=0");
while($row = $sel->fetch_assoc())
{
?>
<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
<?php } ?>