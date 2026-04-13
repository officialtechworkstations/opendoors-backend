<?php

require_once __DIR__. '/src/Termii/Termii.php';

use user_api\Termii;

require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require 'src/Twilio/autoload.php';

$termii = new Termii();

$data = json_decode(file_get_contents('php://input') , true);
header('Content-type: text/json');
$mobile = $data['mobile'];
if ($mobile == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "The mobile number field is required"
    );
} else {
    $to = $mobile; // twilio trial verified number
    $otp = rand(111111, 999999);

    $settings = $rstate->query("SELECT * FROM `tbl_setting` ORDER BY `id` DESC LIMIT 1 ");

    try {
        if ($settings->num_rows > 0) {
            $setting = $settings->fetch_assoc();
            $companyName = $setting['webname'];

            $msg_sent = $termii->sendSms($to, "Your {$companyName} verification pin is {$otp}. Valid for 10 minutes, one-time use only.");

            if ($msg_sent) {
                $returnArr = array(
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "ResponseMsg" => "OTP sent successfully",
                    "otp" => $otp
                );
            } else {
                $returnArr = array(
                    "ResponseCode" => "401",
                    "Result" => "false",
                    "ResponseMsg" => "We could not send the OTP at this time. Please try again or contact Tech support for help",
                );
            }
        } else {
            $returnArr = array(
                "ResponseCode" => "401",
                "Result" => "false",
                "ResponseMsg" => "Unable to retrieve company information for OTP message"
            );
        }
    } catch(Exception $e) {
        // Handle exceptions such as invalid number, message failures, etc.
        $returnArr = array(
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => $e->getMessage()
        );
        // Log the error or take other necessary actions
    }

}

echo json_encode($returnArr);
exit;