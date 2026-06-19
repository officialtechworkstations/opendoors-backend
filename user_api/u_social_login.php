<?php
/**
 * Social login (Google / Apple).
 *
 * Body: { "provider": "google|apple", "token": "<id_token>" }
 *
 * Verifies the provider ID token server-side, then resolves the user by
 * (social_provider, social_id) and falls back to a verified-email match
 * (auto-linking the social identity to the existing account).
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
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
$email      = $identity['email'] ? $rstate->real_escape_string($identity['email']) : '';

// 1) Match on the social identity first.
$user = $rstate->query(
    "SELECT * FROM tbl_user WHERE social_provider='" . $provider . "' AND social_id='" . $social_id . "' AND status = 1"
)->fetch_assoc();

// 2) Fall back to a verified-email match and auto-link the social identity.
if (! $user && $email !== '' && $identity['email_verified']) {
    $user = $rstate->query(
        "SELECT * FROM tbl_user WHERE email='" . $email . "' AND status = 1"
    )->fetch_assoc();

    if ($user) {
        $rstate->query(
            "UPDATE tbl_user SET social_provider='" . $provider . "', social_id='" . $social_id . "' WHERE id=" . (int) $user['id']
        );
        $user['social_provider'] = $provider;
        $user['social_id']       = $identity['social_id'];
    }
}

if (! $user) {
    echo json_encode([
        "ResponseCode" => "404",
        "Result"       => "false",
        "ResponseMsg"  => "Account not registered. Please sign up.",
        "email"        => $identity['email'],
        "name"         => $identity['name'],
    ]);
    exit;
}

echo json_encode([
    "UserLogin"    => $user,
    "currency"     => $set['currency'],
    "ResponseCode" => "200",
    "Result"       => "true",
    "type"         => "user",
    "ResponseMsg"  => "Login successfully!",
]);
exit;
