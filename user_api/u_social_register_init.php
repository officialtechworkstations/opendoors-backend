<?php
/**
 * Step 1 of social sign-up: verify the social token, validate the phone number,
 * and send an OTP via the configured SMS provider.
 *
 * Body: { "provider": "google|apple", "token": "<id_token>",
 *         "mobile": "8030001122", "ccode": "234" }
 *
 * Returns the OTP in the response (verified client-side, matching the existing
 * OTP endpoints). The app then calls u_social_register.php to finalize.
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
require dirname(__FILE__) . '/otp_helper.php';
header('Content-type: text/json');

$data     = json_decode(file_get_contents('php://input'), true);
$provider = isset($data['provider']) ? strtolower(trim($data['provider'])) : '';
$token    = $data['token'] ?? '';
$mobile   = isset($data['mobile']) ? trim(strip_tags(mysqli_real_escape_string($rstate, $data['mobile']))) : '';
$ccode    = isset($data['ccode']) ? trim(strip_tags(mysqli_real_escape_string($rstate, $data['ccode']))) : '';

if ($provider === '' || $token === '' || $mobile === '' || $ccode === '') {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Something Went Wrong!",
    ]);
    exit;
}

$identity = verifySocialToken($provider, $token);

if ($identity === false) {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "We could not verify your {$provider} sign-in. Please try again.",
    ]);
    exit;
}

// Already registered? Tell the app to log in instead.
$social_id = $rstate->real_escape_string($identity['social_id']);
$existing  = $rstate->query(
    "SELECT id FROM tbl_user WHERE social_provider='" . $provider . "' AND social_id='" . $social_id . "'"
)->num_rows;

if (! $existing && $identity['email']) {
    $email    = $rstate->real_escape_string($identity['email']);
    $existing = $rstate->query("SELECT id FROM tbl_user WHERE email='" . $email . "'")->num_rows;
}

if ($existing) {
    echo json_encode([
        "ResponseCode" => "409",
        "Result"       => "false",
        "ResponseMsg"  => "Account already exists. Please log in.",
    ]);
    exit;
}

// Phone must be unused (matches mobile_check.php semantics).
$checkmob = $rstate->query("SELECT id FROM tbl_user WHERE mobile='" . $mobile . "' AND ccode='" . $ccode . "'")->num_rows;
if ($checkmob != 0) {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Mobile Number Already Used!",
    ]);
    exit;
}

$otp  = rand(111111, 999999);
$dial = preg_replace('/[^0-9]/', '', $ccode . $mobile);

if (sendOtpViaProvider($dial, $otp)) {
    echo json_encode([
        "ResponseCode" => "200",
        "Result"       => "true",
        "ResponseMsg"  => "OTP sent successfully",
        "otp"          => $otp,
    ]);
} else {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "We could not send the OTP at this time. Please try again.",
    ]);
}
exit;
