<?php
require dirname(dirname(__FILE__)) . "/include/reconfig.php";
header("Content-type: text/json");
$data = json_decode(file_get_contents("php://input"), true);
$pol = [];
$c = [];
$uid = $data["uid"];
$keyword = $data["keyword"];
$country_id = $data["country_id"];
$facility = $data['facility'];
$price = $data['price'];
$is_featured = $data['is_featured'];

if (($keyword == "" && $price == "" && empty($facility)) or $uid == "" or $country_id == "") {
    $returnArr = [
        "ResponseCode" => "401",
        "Result" => "false",
        "data" => $data,
        "ResponseMsg" => "Something Went Wrong!",
    ];
} else {
    $fp = [];
    $f = [];

    $additional_query = '';
    if (is_array($facility)) {
        foreach ($facility as $value) {
            if ($value) {
                $additional_query .= ' AND FIND_IN_SET('.($value).', facility) > 0';
            }
        }
    } elseif (is_string($facility)) {
        $additional_query .= ' AND FIND_IN_SET('.($facility).', facility) > 0';
    }

    if ($price) {
        $additional_query .= ' AND price <= '.$price;
    }

    if ($is_featured) {
        $additional_query .= ' AND `is_featured` = 1';
    }

    if ($uid == 0) {
        $sel = $rstate->query(
            "SELECT * FROM `tbl_property` WHERE `title` COLLATE utf8mb4_general_ci LIKE '%" .
                $keyword .
                "%'  AND `country_id`=" .
                $country_id .
                " AND `status` = 1 and `is_sell` = 0 ".
                $additional_query
        );
    } else {
        $sel = $rstate->query(
            "SELECT * FROM `tbl_property` WHERE `title` COLLATE utf8mb4_general_ci LIKE '%" .
                $keyword .
                "%'  AND `country_id` = " .
                $country_id .
                "  AND `add_user_id` != " .
                $uid .
                " AND `status` = 1 AND `is_sell` = 0 ".
                $additional_query
        );
    }

    if ($sel->num_rows > 0) {
        $row = $sel->fetch_assoc();

        do {
            $fp["id"] = $row["id"];
            $fp["title"] = $row["title"];
            $checkrate = $rstate->query("SELECT *  FROM tbl_book where prop_id=" . $row["id"] . " and book_status='Completed' and total_rate !=0")->num_rows;
            if ($checkrate != 0) {
                $rdata_rest = $rstate->query("SELECT sum(total_rate)/count(*) as rate_rest FROM tbl_book where prop_id=" . $row["id"] . " and book_status='Completed' and total_rate !=0")->fetch_assoc();
                $fp["rate"] = number_format( (float) $rdata_rest["rate_rest"], 0, ".", "");
            } else {
                $fp["rate"] = $row["rate"];
            }
            $fp["buyorrent"] = $row["pbuysell"];
            $fp["plimit"] = $row["plimit"];
            $fp["city"] = $row["city"];
            $fp["image"] = $row["image"];
            $fp["property_type"] = $row["ptype"];
            $fp["price"] = $row["price"];
            $fp["IS_FAVOURITE"] = $rstate->query(
                "select * from tbl_fav where uid=" .
                    $uid .
                    " and property_id=" .
                    $row["id"] .
                    ""
            )->num_rows;
            $f[] = $fp;
        } while ($row = $sel->fetch_assoc());
    }

    if (empty($f)) {
        $returnArr = [
            "search_propety" => $f,
            "ResponseCode" => "200",
            "Result" => "false",
            "ResponseMsg" => "Search Property Not Founded!",
        ];
    } else {
        $returnArr = [
            "search_propety" => $f,
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Property List Founded!",
        ];
    }
}
echo json_encode($returnArr);
exit;