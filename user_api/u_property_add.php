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
		$plimit = $data['plimit'];
		$country_id = $data['country_id'];
		$pbuysell = $data['pbuysell'];
		$user_id = $data['uid'];
		
if ($user_id == '' or $pbuysell == '' or $country_id == '' or $plimit == '' or $status == '' or $title == '' or $address == '' or $description == '' or $ccount == '' or $facility == '' or $ptype == '' or $beds == '' or $bathroom == '' or $sqft == '' or $rate == '' or $latitude == '' or $mobile == '' or $listing_date == '' or $price == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {

$img = $data['img'];
 $img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$path = 'images/property/'.uniqid().'.png';
$fname = dirname( dirname(__FILE__) ).'/'.$path;

file_put_contents($fname, $data);

            $table = "tbl_property";
            $field_values = ["image", "status", "title","price","address","facility","description","beds","bathroom","sqrft","rate","ptype","latitude","longtitude","mobile","city","listing_date","add_user_id","pbuysell","country_id","plimit"];
            $data_values = ["$path", "$status", "$title","$price","$address","$facility","$description","$beds","$bathroom","$sqft","$rate","$ptype","$latitude","$longtitude","$mobile","$ccount","$listing_date","$user_id","$pbuysell","$country_id","$plimit"];

            $h = new Estate();
            $check = $h->restateinsertdata_Api($field_values, $data_values, $table);
        $returnArr    = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Property Add Successfully"
            );
}

echo json_encode($returnArr);
?>