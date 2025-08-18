<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
$uid = $data['uid'];
$rid = $data['pid'];
$property_type = $data['property_type'];
if($uid == '' or $rid == '' or $property_type == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{
 $check = $rstate->query("select * from tbl_fav where uid=".$uid." and property_id=".$rid." and property_type=".$property_type."")->num_rows;
 if($check != 0)
 {
      
	  
	  $table="tbl_fav";
$where = "where uid=".$uid." and property_id=".$rid." and property_type=".$property_type."";
$h = new Estate();
	$check = $h->restateDeleteData_Api($where,$table);
	
      $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property Successfully Removed In Favourite List !!");
	  
 }
 else 
 {
     
	 
	 $table="tbl_fav";
  $field_values=array("uid","property_id","property_type");
  $data_values=array("$uid","$rid","$property_type");
  $h = new Estate();
  $check = $h->restateinsertdata_Api($field_values,$data_values,$table);
   $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Property Successfully Saved In Favourite List!!!");
   
    
 }
}
echo json_encode($returnArr);
?>