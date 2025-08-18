<?php 
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if ($data['prop_id'] == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went Wrong!"
    );
} else {
	
	$prop_id = $data['prop_id'];
	
		$wow = array();
		$cat = array();
		$vp = $rstate->query("SELECT * FROM `tbl_gal_cat` where pid=".$prop_id."  and status=1");
		while($row = $vp->fetch_assoc())
		{
			$wow['title'] = $row['title'];
			$vop = array();
			$gal = $rstate->query("SELECT img FROM `tbl_gallery` where cat_id=".$row['id']." and status=1");
			while($tog = $gal->fetch_assoc())
			{
				 
				$vop[] = $tog['img'];
			}
			$wow['imglist'] = $vop;
			$cat[] = $wow;
		}
		
		
		$returnArr = array(
		"gallerydata"=>$cat,
        "ResponseCode" => "200",
        "Result" => "true",
        "ResponseMsg" => "Gallery Photos Get Successfully!!!"
    );
}
echo json_encode($returnArr);
?>