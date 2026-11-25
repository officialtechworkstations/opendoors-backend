<?php
/**
 * Social login (Google / Apple).
 *
 * Body: { "provider": "google|apple", "token": "<id_token>" }
 *
 * Verifies the provider ID token server-side, then resolves the user by
 * (social_provider, social_id). A matching email without that identity needs
 * an authenticated account-linking flow; an ID token alone cannot link it.
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
require dirname(dirname(__FILE__)) . '/include/auth.php';
header('Content-type: text/json');

$data     = json_decode(file_get_contents('php://input'), true);
$provider = isset($data['provider']) ? strtolower(trim($data['provider'])) : '';
$token    = $data['token'] ?? '';

if ($provider === '' || $token === '') {
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

$social_id = $rstate->real_escape_string($identity['social_id']);
$email      = $identity['email'] ? $rstate->real_escape_string(strtolower(trim($identity['email']))) : '';

// 1) Match on the social identity first.
$user = $rstate->query(
    "SELECT * FROM tbl_user WHERE social_provider='" . $provider . "' AND social_id='" . $social_id . "' AND status = 1"
)->fetch_assoc();

// A matching email is not proof that this provider identity owns the account.
if (! $user && $email !== '') {
    $existing = $rstate->query("SELECT id, status FROM tbl_user WHERE LOWER(TRIM(email))='" . $email . "' LIMIT 1")->fetch_assoc();
    if ($existing) {
        http_response_code(409);
        echo json_encode([
            'ResponseCode' => '409',
            'Result' => 'false',
            'ResponseMsg' => 'An account with this email exists. Sign in with your existing method to link Google or Apple.',
        ]);
        exit;
    }
}

if (! $user) {
    http_response_code(404);
    echo json_encode([
        "ResponseCode" => "404",
        "Result"       => "false",
        "ResponseMsg"  => "Account not registered. Please sign up.",
    ]);
    exit;
}

$tokenData = issueToken((int)$user['id']);

echo json_encode([
    "UserLogin"    => $user,
    "access_token" => $tokenData['token'],
    "expires_in"   => $tokenData['expires_in'],
    "currency"     => $set['currency'],
    "ResponseCode" => "200",
    "Result"       => "true",
    "type"         => "user",
    "ResponseMsg"  => "Login successfully!",
]);
exit;
