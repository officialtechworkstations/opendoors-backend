<?php

require_once __DIR__. '/src/Termii/Termii.php';

use user_api\Termii;

require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require_once __DIR__ . '/registration_otp.php';
require 'src/Twilio/autoload.php';

$termii = new Termii();

$data = json_decode(file_get_contents('php://input') , true);
header('Content-type: text/json');
$mobile = $data['mobile'] ?? '';
$ccode = $data['ccode'] ?? '';
if ($mobile == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "The mobile number field is required"
    );
} else {
    if ($ccode !== '') {
        $phone = normalizePhoneIdentity($mobile, $ccode);
        $to = $phone['ccode'] . $phone['mobile'];
    } else {
        $to = preg_replace('/\D+/', '', (string) $mobile);
    }
    $phone = normalizePhoneIdentity($mobile, $ccode);
    if ($phone['mobile'] === '' || $phone['ccode'] === '') {
        http_response_code(422);
        echo json_encode(['ResponseCode'=>'422','Result'=>'false','ResponseMsg'=>'Mobile and country code are required']);
        exit;
    }
    try { [$challenge, $otp] = registrationOtpCreate($rstate, $phone['mobile'], $phone['ccode'], 'standard'); }
    catch (Throwable $e) { http_response_code(500); echo json_encode(['ResponseCode'=>'500','Result'=>'false','ResponseMsg'=>'OTP service is unavailable']); exit; }

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
                    "challenge_id" => $challenge
                );
            } else {
                registrationOtpCancel($rstate, $challenge);
                $returnArr = array(
                    "ResponseCode" => "401",
                    "Result" => "false",
                    "ResponseMsg" => "We could not send the OTP at this time. Please try again or contact Tech support for help",
                );
            }
        } else {
            registrationOtpCancel($rstate, $challenge);
            $returnArr = array(
                "ResponseCode" => "401",
                "Result" => "false",
                "ResponseMsg" => "Unable to retrieve company information for OTP message"
            );
        }
    } catch(Exception $e) {
        registrationOtpCancel($rstate, $challenge);
        // Handle exceptions such as invalid number, message failures, etc.
        $returnArr = array(
            "ResponseCode" => "401",
            "Result" => "false",
            "ResponseMsg" => "We could not send the OTP at this time."
        );
        // Log the error or take other necessary actions
    }

}

http_response_code((int) $returnArr['ResponseCode']);
echo json_encode($returnArr);
exit;
