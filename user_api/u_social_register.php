<?php
/**
 * Step 2 of social sign-up: finalize registration and log the user in.
 *
 * Body: { "provider": "google|apple", "token": "<id_token>",
 *         "mobile": "8030001122", "ccode": "234", "name": "optional fallback",
 *         "refercode": "optional",
 *         "accept_newsletter": 0|1, "accept_privacy_policy": 0|1,
 *         "accept_terms_condition": 0|1 }
 *
 * The token is re-verified here (the init step is not trusted). The OTP itself
 * is verified client-side, consistent with the existing registration flow.
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
header('Content-type: text/json');

$data = json_decode(file_get_contents('php://input'), true);

function social_generate_random()
{
    global $rstate;
    $six_digit_random_number = mt_rand(100000, 999999);
    $c_refer                 = $rstate->query("select * from tbl_user where refercode=" . $six_digit_random_number . "")->num_rows;
    if ($c_refer != 0) {
        return social_generate_random();
    }
    return $six_digit_random_number;
}

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

// A false result means the token itself is invalid (bad signature/iss/aud/exp).
if ($identity === false) {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "We could not verify your {$provider} sign-in. Please try again.",
    ]);
    exit;
}

$social_id = $rstate->real_escape_string($identity['social_id']);

// Email: prefer the verified token, else the client fallback. Apple only sends
// the email on the FIRST authorization (and to the app, not every token), so the
// app should forward the email it captured then. Login below does not need it.
$email_plain = $identity['email'] ?: (isset($data['email']) ? strtolower(trim($data['email'])) : '');
$email       = $rstate->real_escape_string($email_plain);

// Already registered? Match on the social identity first (works even when the
// token carries no email), then on email if we have one. Auto-link & log in.
$lookup = "SELECT * FROM tbl_user WHERE (social_provider='" . $provider . "' AND social_id='" . $social_id . "')";
if ($email !== '') {
    $lookup .= " OR email='" . $email . "'";
}
$lookup .= " LIMIT 1";
$existing = $rstate->query($lookup)->fetch_assoc();

if ($existing) {
    if ($existing['social_id'] === null || $existing['social_id'] === '') {
        $rstate->query(
            "UPDATE tbl_user SET social_provider='" . $provider . "', social_id='" . $social_id . "' WHERE id=" . (int) $existing['id']
        );
    }
    $c = $rstate->query("SELECT * FROM tbl_user WHERE id=" . (int) $existing['id'])->fetch_assoc();
    echo json_encode([
        "UserLogin"    => $c,
        "currency"     => $set['currency'],
        "ResponseCode" => "200",
        "Result"       => "true",
        "type"         => "user",
        "ResponseMsg"  => "Login successfully!",
    ]);
    exit;
}

// New account: we need an email. Apple omits it on repeat auth and the client
// didn't forward one -> ask the app to send the email captured at first sign-in.
if ($email_plain === '') {
    echo json_encode([
        "ResponseCode" => "422",
        "Result"       => "false",
        "ResponseMsg"  => "Email is required to complete sign-up. Please pass the email from the first Apple sign-in.",
    ]);
    exit;
}

// Name comes from the token (Google) or the client fallback (Apple omits it).
$name = $identity['name'] ?: ($data['name'] ?? '');
$name = trim(strip_tags(mysqli_real_escape_string($rstate, $name)));
if ($name === '') {
    $name = strtok($email_plain, '@');
}

$accept_newsletter      = ! empty($data['accept_newsletter']) ? 1 : 0;
$accept_privacy_policy  = ! empty($data['accept_privacy_policy']) ? 1 : 0;
$accept_terms_condition = ! empty($data['accept_terms_condition']) ? 1 : 0;
$refercode              = isset($data['refercode']) ? trim(strip_tags(mysqli_real_escape_string($rstate, $data['refercode']))) : '';

// Phone must still be unused.
if ($rstate->query("SELECT id FROM tbl_user WHERE mobile='" . $mobile . "' AND ccode='" . $ccode . "'")->num_rows != 0) {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Mobile Number Already Used!",
    ]);
    exit;
}

// Social accounts authenticate via token; store a random (unusable) password.
$password  = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
$timestamp = date("Y-m-d H:i:s");
$prentcode = social_generate_random();

$field_values = [
    "name", "email", "mobile", "reg_date", "password", "ccode", "refercode",
    "parentcode", "social_provider", "social_id",
    "accept_newsletter", "accept_privacy_policy", "accept_terms_condition",
];
$data_values = [
    $name, $email, $mobile, $timestamp, $password, $ccode, $prentcode,
    $prentcode, $provider, $identity['social_id'],
    "$accept_newsletter", "$accept_privacy_policy", "$accept_terms_condition",
];

// Referral credit (mirrors u_reg_user.php).
$valid_refer = false;
if ($refercode !== '') {
    $valid_refer = $rstate->query("select * from tbl_user where refercode=" . (int) $refercode . "")->num_rows != 0;
    if ($valid_refer) {
        $wallet         = $rstate->query("select * from tbl_setting")->fetch_assoc();
        $fin            = $wallet['scredit'];
        $field_values[] = "wallet";
        $data_values[]  = "$fin";
        // overwrite parentcode with the referrer's code
        $data_values[7] = $refercode;
    }
}

$h   = new Estate();
$uid = $h->restateinsertdata_Api_Id($field_values, $data_values, "tbl_user");

if ($valid_refer) {
    $h->restateinsertdata_Api(
        ["uid", "message", "status", "amt", "tdate"],
        ["$uid", 'Sign up Credit Added!!', 'Credit', "$fin", "$timestamp"],
        "wallet_report"
    );
}

$c = $rstate->query("select * from tbl_user where id=" . (int) $uid . "")->fetch_assoc();

if ($accept_newsletter == 1) {
    oneSignalNewsLetterSubscription($uid, true, ['email' => $email_plain]);
}

echo json_encode([
    "UserLogin"    => $c,
    "currency"     => $set['currency'],
    "ResponseCode" => "200",
    "Result"       => "true",
    "type"         => "user",
    "ResponseMsg"  => "Sign Up Done Successfully!",
]);
exit;
