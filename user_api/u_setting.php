<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
header('Content-type: text/json');

$settings = $rstate->query("SELECT * FROM `tbl_setting` ORDER BY `id` DESC LIMIT 1 ");
$response = [];
if ($settings->num_rows > 0) {
    $data = $settings->fetch_assoc();
    $response = [
        'webname' => $data['webname'],
        'weblogo' => $data['weblogo'],
        'timezone' => $data['timezone'],
        'currency' => $data['currency'],
        'notice_message' => $data['notice_message'],
        "package_name" => $data['package_name'],
        "playstore_url" => $data['playstore_url'],
        "playstore_version" => $data['playstore_version'],
        "playstore_buildnumber" => $data['playstore_buildnumber'],
        "appstore_url" => $data['appstore_url'],
        "appstore_version" => $data['appstore_version'],
        "appstore_buildnumber" => $data['appstore_buildnumber'],
    ];
}
$returnArr = [
    "FaqData" => $response,
    "ResponseCode" => "200",
    "Result" => "true",
    "ResponseMsg" => "Faq List Get Successfully!!"
];
echo json_encode($returnArr);
exit;
