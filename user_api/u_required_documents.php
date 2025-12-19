<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
$user_type = $data['user_type'];
$uid = $data['uid'];

$documents = $rstate->query("SELECT * 
                            FROM `tbl_required_documents` 
                            WHERE `user_type` = '$user_type' 
                                AND `status` = 'active' 
                                AND `deleted_at` IS NULL 
                                ORDER BY `created_at` DESC");
$response = [];
if ($documents->num_rows > 0) {
    $row = $documents->fetch_assoc();

    do {
        $response[] = [
            'name' => $row['name'],
            'description' => $row['description'],
            'upload_type' => $row['upload_type'],
            'accpetable_file_types' => explode(',', $row['accpetable_file_types']),
        ];
    } while ($row = $documents->fetch_assoc());
}
$returnArr = [
    "documents" => $response,
    "ResponseCode" => 200,
    "Result" => "true",
    "ResponseMsg" => "Required documents retrieved"
];
echo json_encode($returnArr);
exit;
