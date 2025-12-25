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
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'Result' => 'false',
        'ResponseMsg' => 'Method not allowed',
    ]);
    exit;
}

// ===== VALIDATE REQUIRED FIELDS =====
if (
    !isset($_POST['document_id']) ||
    !isset($_POST['uid']) ||
    !isset($_FILES['file'])
) {
    http_response_code(422);
    echo json_encode(["ResponseCode" => 422, 'Result' => 'false', 'ResponseMsg' => 'required document, user data and file are required']);
    exit;
}

$document_id = $_POST['document_id'];
$uid = $_POST['uid'];
$files = $_FILES['file'];

// Get the document to be uploaded
$db_doc = $rstate->query("SELECT * FROM `tbl_required_documents` WHERE `id` = '{$document_id}' AND `deleted_at` IS NULL AND `status` = 'active' LIMIT 1");

if (!$db_doc->num_rows) {
    http_response_code(422);
    echo json_encode(["ResponseCode" => 422, 'Result' => 'false', 'ResponseMsg' => 'We cannot find the requested document. Please try again']);
    exit;
}

$user_doc_approved = $rstate->query("SELECT * FROM `tbl_user_documents` WHERE `document_id` = '{$document_id}' AND `user_id` = '{$uid}' AND `deleted_at` IS NULL AND `status` = 'approved' LIMIT 1");

if ($user_doc_approved->num_rows) {
    http_response_code(200);
    echo json_encode(["ResponseCode" => 200, 'Result' => 'true', 'ResponseMsg' => 'This document has already been approved. You cannot replace an already approved document']);
    exit;
}

$doc = $db_doc->fetch_assoc();
$allowedTypes = explode(',', $doc['accpetable_file_types']);

// ===== CONFIG =====
$uploadDir = __DIR__ . '/../uploads';
$allowedMimeTypes = [];

if (in_array('pdf', $allowedTypes)) {
    $allowedMimeTypes[] = 'application/pdf';
}

if (in_array('image', $allowedTypes)) {
    $allowedMimeTypes[] = 'image/jpeg';
    $allowedMimeTypes[] = 'image/png';
}

// Create directory if missing
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// ===== NORMALIZE MULTIPLE FILES =====
$fileCount = is_array($files['name']) ? count($files['name']) : 1;

if ($doc['upload_type'] == 'single') {
    if ($fileCount > 1) {
        http_response_code(422);
        echo json_encode(["ResponseCode" => 422, 'Result' => 'false', 'ResponseMsg' => 'You can only upload one file for this document. Please try again']);
        exit;
    }
}

$storedFiles = [];

logger("Uploading {$fileCount} files for document ID {$document_id} by user ID {$uid}");

for ($i = 0; $i < $fileCount; $i++) {
    $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
    $tmp   = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
    $name  = is_array($files['name']) ? $files['name'][$i] : $files['name'];

    if ($error !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(["ResponseCode" => 400, 'Result' => 'false', 'ResponseMsg' => 'File upload failed', 'file' => $name]);
        exit;
    }

    // ===== MIME VALIDATION =====
    $mimeType = mime_content_type($tmp);

    if (!in_array($mimeType, $allowedMimeTypes)) {
        http_response_code(415);
        echo json_encode(["ResponseCode" => 415, 'Result' => 'false', 'ResponseMsg' => 'Invalid file type', 'file' => $name]);
        exit;
    }

    // ===== SAFE FILE NAME =====
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $newName = sprintf(
        '%s_%s_%s.%s',
        $document_id,
        $uid,
        uniqid(),
        $extension
    );

    $destination = $uploadDir . '/' . $newName;

    if (!move_uploaded_file($tmp, $destination)) {
        http_response_code(500);
        echo json_encode(["ResponseCode" => 500, 'Result' => 'false', 'ResponseMsg' => 'Failed to store file', 'file' => $name]);
        exit;
    }

    $storedFiles[] = $newName;
}

logger("Stored files: " . implode(', ', $storedFiles));

if (count($storedFiles)) {
    // Delete existing document first before re-uploading
    $sql = "UPDATE `tbl_user_documents` SET `deleted_at` = '".date("Y-m-d H:i:s")."' WHERE `document_id` = '{$document_id}' AND `user_id` = '{$uid}'";
    $rstate->query($sql);

    $h = new Estate();

    foreach ($storedFiles as $file) {
        $file_type = explode('.', $file)[1] ?? '';
        $h->restateinsertdata_Api_Id([
            'document_id',
            'user_id',
            'file_path',
            'file_type',
        ], [
            $document_id,
            $uid,
            $file,
            $file_type,
        ], 'tbl_user_documents');
    }

    $returnArr = [
        "ResponseCode" => 200,
        "Result" => "true",
        "ResponseMsg" => "Document uploaded successfully"
    ];
}

http_response_code($returnArr['ResponseCode']);
echo json_encode($returnArr);
exit;
