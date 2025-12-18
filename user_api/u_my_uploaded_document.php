<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';

header('Content-Type: application/json');

$returnArr = [
    "ResponseCode" => 401,
    "Result" => "false",
    "ResponseMsg" => "We could not complete your request at this time. Please try again"
];

// ===== REQUIRE POST =====
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'Result' => 'false',
        'ResponseMsg' => 'Method not allowed',
    ]);
    exit;
}

$uid = $_GET['uid'];
$user_type = $_GET['user_type'];

if (!$uid) {
    http_response_code(422);
    echo json_encode(["ResponseCode" => 422, 'Result' => 'false', 'ResponseMsg' => 'User not found']);
    exit;
}

$sql = "SELECT DISTINCT rd.* FROM tbl_required_documents as rd
        INNER JOIN tbl_user_documents as ud  ON rd.id = ud.document_id
        WHERE rd.user_type = '{$user_type}'
            AND ud.user_id = '{$uid}'
            AND ud.deleted_at IS NULL
            AND rd.deleted_at IS NULL";

$documents = $rstate->query($sql);
$uploaded_documents = [];

if ($documents->num_rows) {
    $row = $documents->fetch_assoc();
    $doc_key = 0;

    do {
        $uploaded_documents[$doc_key] = [
            'name' => $row['name'],
            'description' => $row['description'],
            'user_type' => $row['user_type'],
            'upload_type' => $row['upload_type'],
            'status' => 'pending',
            'reason' => null,
            'files' => [],
        ];
        // Get the document for this user here
        $user_doc = $rstate->query("SELECT * FROM `tbl_user_documents` WHERE `user_id` = '{$uid}' AND document_id = '{$row['id']}' AND `deleted_at` IS NULL");

        if ($user_doc->num_rows) {
            $doc = $user_doc->fetch_assoc();

            do {
                $uploaded_documents[$doc_key]['files'][] = getConfig('BASE_URL').'uploads/'.$doc['file_path'];
                $uploaded_documents[$doc_key]['status'] = $doc['status'];
                $uploaded_documents[$doc_key]['reason'] = $doc['reason'];
            } while ($doc = $user_doc->fetch_assoc());
        }

        $doc_key++;
    } while ($row = $documents->fetch_assoc());

    $returnArr = [
        "ResponseCode" => 200,
        "Result" => "true",
        "documents" => $uploaded_documents,
        "ResponseMsg" => "We have retrieved your documents successfully"
    ];
}

http_response_code($returnArr['ResponseCode']);
echo json_encode($returnArr);
exit;
