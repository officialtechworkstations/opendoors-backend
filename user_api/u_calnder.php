<?php 
require dirname( dirname(__FILE__) ).'/include/reconfig.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pol = array();
$c = array();
$pro_id  = $data['pro_id'];
if($pro_id == '' )
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else 
{
 $book = $rstate->query("select check_in,check_out from tbl_book where prop_id=".$pro_id." and book_status!='Cancelled'");
 $l = array();
 while($row = $book->fetch_assoc())
 {
	 $l[] = $row;
 }
 if(empty($l))
{
	$returnArr = array("datelist"=>$l,"ResponseCode"=>"200","Result"=>"false","ResponseMsg"=>"date Not Founded!");
}
else 
{
$returnArr = array("datelist"=>$l,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"date List Founded!");
}
}
echo json_encode($returnArr);
?>