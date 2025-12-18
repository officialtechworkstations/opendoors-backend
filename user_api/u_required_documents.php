<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
$user_type = $data['user_type'];
$uid = $data['uid'];

$settings = $rstate->query("SELECT * FROM `tbl_required_documents` WHERE `deleted_at` IS NULL AND `user_type` = '$user_type'  ORDER BY `created_at` DESC ");
$response = [];
if ($settings->num_rows > 0) {
    $row = $settings->fetch_assoc();

    do {
        $response[] = [
            'name' => $row['name'],
            'description' => $row['description'],
            'upload_type' => $row['upload_type'],
            'accpetable_file_types' => explode(',', $row['accpetable_file_types']),
        ];
    } while ($row = $settings->fetch_assoc());
}
$returnArr = [
    "documents" => $response,
    "ResponseCode" => 200,
    "Result" => "true",
    "ResponseMsg" => "Required documents retrieved"
];
echo json_encode($returnArr);
exit;
