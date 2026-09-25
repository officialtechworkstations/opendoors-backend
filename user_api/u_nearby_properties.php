<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['latitude']) || !isset($data['longitude'])) {
    $returnArr = [
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Latitude and Longitude are required!",
    ];
} else {
    $uid = isset($data['uid']) ? (int)$data['uid'] : 0;
    
    // pagination and radius
    $lat = (float)$data['latitude'];
    $lng = (float)$data['longitude'];
    $radius_km = isset($data['radius_km']) ? (float)$data['radius_km'] : 50;
    $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
    $offset = isset($data['offset']) ? (int)$data['offset'] : 0;
    
    // optional country id filter
    $country_id = isset($data['country_id']) ? (int)$data['country_id'] : 0;
    
    $additional_query = "";
    if ($country_id > 0) {
        $additional_query .= " AND p.country_id = " . $country_id;
    }

    $propertySelect = "SELECT p.*,
                    COALESCE(ROUND(r.avg_rate, 0), p.rate) AS effective_rate,
                    CASE WHEN f.property_id IS NULL THEN 0 ELSE 1 END AS is_favourite,
                    (6371 * acos(cos(radians($lat)) * cos(radians(p.latitude)) * cos(radians(p.longtitude) - radians($lng)) + sin(radians($lat)) * sin(radians(p.latitude)))) AS distance
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
                        WHERE uid = " . $uid . "
                    ) f ON f.property_id = p.id
                    WHERE p.status = 1 
                        AND p.is_sell = 0
                        AND p.latitude != '' AND p.longtitude != ''
                        AND p.latitude != '0' AND p.longtitude != '0'
                        " . $additional_query;
                        
    $ownerFilter = ($uid == 0) ? "" : " AND p.add_user_id != " . $uid;
    
    $query = $propertySelect . $ownerFilter . " HAVING distance <= $radius_km ORDER BY distance ASC LIMIT $offset, $limit";
    
    $props = $rstate->query($query);
    
    $fpv = [];
    if ($props && $props->num_rows > 0) {
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
            $fps['distance']      = round($rows['distance'], 2);
            $fpv[]                = $fps;
        }
    }

    $returnArr = ["ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Nearby Properties Get Successfully!", "NearbyData" => $fpv];
}
echo json_encode($returnArr);
