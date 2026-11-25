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
require_once __DIR__ . '/registration_otp.php';
header('Content-type: text/json');
http_response_code(200);

$data     = json_decode(file_get_contents('php://input'), true);
$provider = isset($data['provider']) ? strtolower(trim($data['provider'])) : '';
$token    = $data['token'] ?? '';
$rawMobile = $data['mobile'] ?? '';
$rawCcode  = $data['ccode'] ?? '';
$phone     = normalizePhoneIdentity($rawMobile, $rawCcode);
$mobile    = $phone['mobile'];
$ccode     = $phone['ccode'];

if ($provider === '' || $token === '' || $mobile === '' || $ccode === '') {
    http_response_code(422);
    echo json_encode([
        "ResponseCode" => "422",
        "Result"       => "false",
        "ResponseMsg"  => "Something Went Wrong!",
    ]);
    exit;
}

$identity = verifySocialToken($provider, $token);

if ($identity === false) {
    http_response_code(401);
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
    $email    = $rstate->real_escape_string(strtolower(trim($identity['email'])));
    $existing = $rstate->query("SELECT id FROM tbl_user WHERE LOWER(TRIM(email))='" . $email . "'")->num_rows;
}

if ($existing) {
    http_response_code(409);
    echo json_encode([
        "ResponseCode" => "409",
        "Result"       => "false",
        "ResponseMsg"  => "An account with this identity or email exists. Sign in with your existing method to link Google or Apple.",
    ]);
    exit;
}

// Phone must be unused (matches mobile_check.php semantics).
$checkmob = findUserByPhone($rstate, $rawMobile, $rawCcode) !== null;
if ($checkmob != 0) {
    http_response_code(409);
    echo json_encode([
        "ResponseCode" => "409",
        "Result"       => "false",
        "ResponseMsg"  => "Mobile Number Already Used!",
    ]);
    exit;
}

$identityKey = $provider . ':' . $identity['social_id'];
try { [$challenge, $otp] = registrationOtpCreate($rstate, $mobile, $ccode, 'social', $identityKey); }
catch (Throwable $e) { http_response_code(500); echo json_encode(['ResponseCode'=>'500','Result'=>'false','ResponseMsg'=>'OTP service is unavailable']); exit; }
$dial = preg_replace('/[^0-9]/', '', $ccode . $mobile);

if (sendOtpViaProvider($dial, $otp)) {
    echo json_encode([
        "ResponseCode" => "200",
        "Result"       => "true",
        "ResponseMsg"  => "OTP sent successfully",
        "challenge_id" => $challenge,
    ]);
} else {
    registrationOtpCancel($rstate, $challenge);
    http_response_code(503);
    echo json_encode([
        "ResponseCode" => "503",
        "Result"       => "false",
        "ResponseMsg"  => "We could not send the OTP at this time. Please try again.",
    ]);
}
exit;
