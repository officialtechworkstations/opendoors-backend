<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
$curl = curl_init();
header('Content-type: application/json');

$kb = $rstate->query("SELECT * FROM `tbl_payment_list` WHERE id=6")->fetch_assoc();
$kk = explode(',', $kb['attributes']);

$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($status) {
    // If the status parameter is already present, do not process further
    if ($status == 'success') {
        echo "Transaction was successful";
    } else {
        echo "Transaction was Cancelled";
    }
    exit();
}

if (!$reference) {
    die(json_encode(['error' => 'No reference supplied']));
}

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Bearer " . $kk[1],
        "cache-control: no-cache"
    ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if ($err) {
    // there was an error contacting the Paystack API
    die(json_encode(['error' => 'Curl returned error: ' . $err]));
}

$tranx = json_decode($response);

if ('success' == $tranx->data->status) {
    // transaction was successful...
    // please check other things like whether you already gave value for this ref
    // if the email matches the customer who owns the product etc
    // Give value

    $status = 'success';
} else {
    $status = 'fail';
}

// Get the full URL with existing GET parameters
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Parse the URL and append the status parameter
$parsed_url = parse_url($url);
parse_str($parsed_url['query'], $query_params);
$query_params['status'] = $status;

// Build the new URL
$new_query = http_build_query($query_params);
$new_url = "{$parsed_url['scheme']}://{$parsed_url['host']}{$parsed_url['path']}?$new_query";

header("Location: " . $new_url);
exit();
?>
