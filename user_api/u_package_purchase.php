<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data['uid'] == '' or $data['plan_id'] == '' or $data['transaction_id'] == '' or $data['pname'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	$plan_id = $data['plan_id'];
	$transaction_id = $data['transaction_id'];
	$uid = $data['uid'];
	$pname = $data['pname'];
	$fetch = $rstate->query("select * from tbl_package where id=".$plan_id."")->fetch_assoc();
	
	 $datetime    = date("Y-m-d H:i:s");
	 $current_date = date("Y-m-d");
	 $till_date = date("Y-m-d", strtotime("+ ".$fetch['day']." day"));
	$title = "Package Purchase Successfully";
	$description = "".$fetch['title']." Package Purchase From ".$current_date." To ".$till_date.". Payment Gateway Name: ".$pname." Transaction Id: ".$transaction_id."";
	$table        = "tbl_notification";
	$field_values = array(
                    "uid",
                    "datetime",
                    "title",
                    "description"
                );
                $data_values  = array(
                    "$uid",
                    "$datetime",
                    "$title",
                    "$description"
                );
				$h     = new Estate();
                $check = $h->restateinsertdata_Api($field_values, $data_values, $table);
				
				$table="tbl_user";
  $field = array('start_date'=>$current_date,'end_date'=>$till_date,'pack_id'=>$plan_id,'is_subscribe'=>'1');
  $where = "where id=".$uid."";
$h = new Estate();
	 $check = $h->restateupdateData_Api($field,$table,$where);
	 
	 $titles = $fetch['title'];
	 $amount = $fetch['price'];
	 $day = $fetch['day'];
	 $plan_image = $fetch['image'];
	 $plan_description = $fetch['description'];
	 $table        = "plan_purchase_history";
	$field_values = array(
                    "uid",
                    "plan_id",
                    "p_name",
                    "t_date",
					"amount",
					"day",
					"plan_title",
					"plan_description",
					"expire_date",
					"start_date",
					"trans_id",
					"plan_image"
                );
                $data_values  = array(
                    "$uid",
                    "$plan_id",
                    "$pname",
                    "$datetime",
					"$amount",
					"$day",
					"$titles",
					"$plan_description",
					"$till_date",
					"$current_date",
					"$transaction_id",
					"$plan_image"
					
                );
				$h     = new Estate();
                $check = $h->restateinsertdata_Api($field_values, $data_values, $table);
				
				$table="tbl_property";
   $field = "status=1";
  $where = "where add_user_id=".$uid."";
$h = new Estate();
	  $check = $h->restateupdateData_single($field,$table,$where);
	  
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Package Purchase Successfully!");
	}
echo json_encode($returnArr);
?>