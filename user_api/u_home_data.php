<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($data['uid'] == '' or $data['country_id'] == '') {
    $returnArr = [
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Something Went Wrong!",
    ];
} else {

    $uid = $data['uid'];
    if ($uid == 0) {

    } else {
        $udata     = $rstate->query("select * from tbl_user where id=" . $uid . "")->fetch_assoc();
        $timestamp = date("Y-m-d");
        if ($udata['end_date'] < $timestamp) {
            $table = "tbl_user";
            $field = ["start_date" => null, "end_date" => null, "pack_id" => "0", "is_subscribe" => "0"];
            $where = "where id=" . $uid . "";
            $h     = new Estate();
            $check = $h->restateupdateDatanull_Api($field, $table, $where);

            $table = "plan_purchase_history";
            $where = "where uid=" . $uid . "";
            $h     = new Estate();
            $check = $h->restateDeleteData_Api($where, $table);

            $table = "tbl_property";
            $field = "status=0";
            $where = "where add_user_id=" . $uid . "";
            $h     = new Estate();
            $check = $h->restateupdateData_single($field, $table, $where);
        }
    }
    $country_id = $data['country_id'];
    $f          = [];
    $fp         = [];
    $vop        = [];
    $fpv        = [];
    $fps        = [];
    $cat        = [];

    $wo           = [];
    $wo['id']     = "0";
    $wo['title']  = "All";
    $wo['img']    = "images/category/grid-circle.png";
    $wo['status'] = "1";
    $sql          = $rstate->query("select * from tbl_category where status=1");
    while ($rp = $sql->fetch_assoc()) {
        $vop['id']     = $rp['id'];
        $vop['title']  = $rp['title'];
        $vop['img']    = $rp['img'];
        $vop['status'] = $rp['status'];
        $cat[]         = $vop;
    }
    array_unshift($cat, $wo);
    $propertySelect = "SELECT p.*,
					COALESCE(ROUND(r.avg_rate, 0), p.rate) AS effective_rate,
					CASE WHEN f.property_id IS NULL THEN 0 ELSE 1 END AS is_favourite
					FROM tbl_property p
					LEFT JOIN (
						SELECT prop_id, AVG(total_rate) AS avg_rate
						FROM tbl_book
						WHERE book_status='Completed' AND total_rate != 0
						GROUP BY prop_id
					) r ON r.prop_id = p.id
					LEFT JOIN (
						SELECT DISTINCT property_id
						FROM tbl_fav
						WHERE uid = " . intval($uid) . "
					) f ON f.property_id = p.id
					WHERE p.country_id=" . intval($country_id) . " 
						AND p.status = 1 
						AND p.is_sell = 0";
    $ownerFilter = ($uid == 0) ? "" : " AND p.add_user_id!=" . intval($uid);
    $prop        = $rstate->query($propertySelect . $ownerFilter . " AND p.is_featured=1 ORDER BY p.id DESC LIMIT 5");
    while ($row = $prop->fetch_assoc()) {
        $fp['id']            = $row['id'];
        $fp['title']         = $row['title'];
        $fp['buyorrent']     = $row['pbuysell'];
        $fp['latitude']      = $row['latitude'];
        $fp['longtitude']    = $row['longtitude'];
        $fp['plimit']        = $row['plimit'];
        $fp['rate']          = $row['effective_rate'];
        $fp['city']          = $row['city'];
        $fp['property_type'] = $row['ptype'];
        $fp['beds']          = $row['beds'];
        $fp['bathroom']      = $row['bathroom'];
        $fp['sqrft']         = $row['sqrft'];
        $fp['image']         = $row['image'];
        $fp['price']         = $row['price'];
        $fp['is_featured']   = (int) $row['is_featured'];
        $fp['IS_FAVOURITE']  = (int) $row['is_favourite'];
        $f[]                 = $fp;
    }

    $props = $rstate->query($propertySelect . $ownerFilter);

    while ($rows = $props->fetch_assoc()) {
        $fps['id']            = $rows['id'];
        $fps['title']         = $rows['title'];
        $fps['buyorrent']     = $rows['pbuysell'];
        $fps['latitude']      = $rows['latitude'];
        $fps['longtitude']    = $rows['longtitude'];
        $fps['plimit']        = $rows['plimit'];
        $fps['rate']          = $rows['effective_rate'];
        $fps['city']          = $rows['city'];
        $fps['beds']          = $rows['beds'];
        $fps['bathroom']      = $rows['bathroom'];
        $fps['sqrft']         = $rows['sqrft'];
        $fps['property_type'] = $rows['ptype'];
        $fps['image']         = $rows['image'];
        $fps['price']         = $rows['price'];
        $fps['is_featured']   = (int) $rows['is_featured'];
        $fps['IS_FAVOURITE']  = (int) $rows['is_favourite'];
        $fpv[]                = $fps;
    }

    $tbwallet = $rstate->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
    if ($uid == 0) {
        $wallet = "0";
    } else {
        $wallet = $tbwallet['wallet'];
    }

    $kp = ['Catlist' => $cat, "currency" => $set['currency'], "wallet" => $wallet, "Featured_Property" => $f, "cate_wise_property" => $fpv, "show_add_property" => $set['show_property']];

    $returnArr = ["ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Home Data Get Successfully!", "HomeData" => $kp];

}
echo json_encode($returnArr);
