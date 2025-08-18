<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
     
	 $status = $data["status"];
        $title = $rstate->real_escape_string($data["title"]);
		$address = $rstate->real_escape_string($data["address"]);
		$description = $rstate->real_escape_string($data["description"]);
		$ccount = $rstate->real_escape_string($data["ccount"]);
		$facility = $data['facility'];
		$ptype = $data['ptype'];
		$beds = $data['beds'];
		$bathroom = $data['bathroom'];
		$sqft = $data['sqft'];
		$rate = $data['rate'];
		$latitude = $data['latitude'];
		$longtitude = $data['longtitude'];
		$mobile = $data['mobile'];
		$listing_date = date("Y-m-d H:i:s");
		$price = $data['price'];
		$user_id = $data['uid'];
		$prop_id = $data['prop_id'];
		$plimit = $data['plimit'];
		$country_id = $data['country_id'];
		$pbuysell = $data['pbuysell'];
		
if ($prop_id == '' or $pbuysell == '' or $country_id == '' or $plimit == '' or $user_id == '' or $status == '' or $title == '' or $address == '' or $description == '' or $ccount == '' or $facility == '' or $ptype == '' or $beds == '' or $bathroom == '' or $sqft == '' or $rate == '' or $latitude == '' or $mobile == '' or $listing_date == '' or $price == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	
$check_owner = $rstate->query("select * from tbl_property where id=" . $prop_id . " and add_user_id=".$user_id."")->num_rows;
if($check_owner !=0)
{
	if($data['img'] == '0')
	{
		$table = "tbl_property";
                $field = ["pbuysell"=>$pbuysell,"country_id"=>$country_id,"plimit"=>$plimit,"status" => $status, "title" => $title,"price"=>$price,"address"=>$address,"facility"=>$facility,"description"=>$description,"beds"=>$beds,"bathroom"=>$bathroom,"sqrft"=>$sqft,"rate"=>$rate,"ptype"=>$ptype,"latitude"=>$latitude,"longtitude"=>$longtitude,"mobile"=>$mobile,"city"=>$ccount,"listing_date"=>$listing_date];
                $where = "where id=" . $prop_id . " and add_user_id=".$user_id."";
                $h = new Estate();
                $check = $h->restateupdateData_Api($field, $table, $where);
	}
	else 
	{
		$img = $data['img'];
 $img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$path = 'images/property/'.uniqid().'.png';
$fname = dirname( dirname(__FILE__) ).'/'.$path;

file_put_contents($fname, $data);

		 $table = "tbl_property";
                $field = ["pbuysell"=>$pbuysell,"country_id"=>$country_id,"plimit"=>$plimit,"status" => $status, "image" => $path, "title" => $title,"price"=>$price,"address"=>$address,"facility"=>$facility,"description"=>$description,"beds"=>$beds,"bathroom"=>$bathroom,"sqrft"=>$sqft,"rate"=>$rate,"ptype"=>$ptype,"latitude"=>$latitude,"longtitude"=>$longtitude,"mobile"=>$mobile,"city"=>$ccount,"listing_date"=>$listing_date];
                $where = "where id=" . $prop_id . " and add_user_id=".$user_id."";
                $h = new Estate();
                $check = $h->restateupdateData_Api($field, $table, $where);
	}
	
	$returnArr    = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Property Update Successfully"
            );
}
else 
{
	$returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Edit Your Own Property!"
    );
}
}

echo json_encode($returnArr);
?>