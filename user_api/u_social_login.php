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
    "SELECT * FROM tbl_user WHERE social_provider='" . $provider . "' AND social_id='" . $social_id . "' LIMIT 1"
)->fetch_assoc();
if ($user && (int) $user['status'] !== 1) {
    http_response_code(403);
    echo json_encode(['ResponseCode'=>'403','Result'=>'false','ErrorCode'=>'ACCOUNT_DISABLED','ResponseMsg'=>'This account is disabled. Please contact support.']);
    exit;
}

// A matching email is not proof that this provider identity owns the account.
if (! $user && $email !== '') {
    $existing = $rstate->query("SELECT id, status FROM tbl_user WHERE LOWER(TRIM(email))='" . $email . "' LIMIT 1")->fetch_assoc();
    if ($existing) {
        if ((int) $existing['status'] !== 1) {
            http_response_code(403);
            echo json_encode(['ResponseCode'=>'403','Result'=>'false','ErrorCode'=>'ACCOUNT_DISABLED','ResponseMsg'=>'This account is disabled. Please contact support.']);
            exit;
        }
        http_response_code(409);
        echo json_encode([
            'ResponseCode' => '409',
            'ErrorCode' => 'SOCIAL_ACCOUNT_NOT_LINKED',
            'Result' => 'false',
            'ResponseMsg' => 'An account exists for this email. Sign in using your existing method to link ' . ucfirst($provider) . '.',
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
