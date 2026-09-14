<?php
/**
 * user_api/auth/login.php
 *
 * POST /user_api/auth/login.php
 *
 * Issue a JWT access token after validating email + password credentials.
 *
 * Request body (JSON):
 *   { "email": "user@example.com", "password": "secret" }
 *
 * Success response:
 *   {
 *     "ResponseCode": "200",
 *     "Result":       "true",
 *     "ResponseMsg":  "Login successful.",
 *     "token":        "<jwt>",
 *     "expires_in":   900,
 *     "uid":          42
 *   }
 *
 * Error responses:
 *   400  AUTH_MISSING_FIELDS   — email or password not provided
 *   401  AUTH_INVALID          — credentials don't match any active account
 *   405  (no body)             — wrong HTTP method
 */

require dirname(dirname(__DIR__)) . '/include/reconfig.php';
require dirname(dirname(__DIR__)) . '/include/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    errorResponse('Method Not Allowed. Expected POST.', 405);
}

$data     = json_decode(file_get_contents('php://input'), true) ?? [];
$email    = trim($data['email']    ?? ($_POST['email']    ?? ''));
$password = trim($data['password'] ?? ($_POST['password'] ?? ''));

if (empty($email) || empty($password)) {
    errorResponse('Email and password are required.', 400, 'AUTH_MISSING_FIELDS');
}

// Look up the user by email
$stmt = $rstate->query(
    "SELECT id, password, status FROM tbl_user WHERE email = '"
    . $rstate->real_escape_string(strtolower($email)) . "' LIMIT 1"
);

$user = $stmt ? $stmt->fetch_assoc() : null;

if (! $user) {
    errorResponse('Invalid email or password.', 401, 'AUTH_INVALID');
}

// Verify password — support both bcrypt and legacy MD5 hashes
$passwordMatches = password_verify($password, $user['password'])
    || ($user['password'] === md5($password));

if (! $passwordMatches) {
    errorResponse('Invalid email or password.', 401, 'AUTH_INVALID');
}

if ((int)$user['status'] !== 1) {
    errorResponse('Your account is inactive. Please contact support.', 401, 'AUTH_ACCOUNT_INACTIVE');
}

$uid    = (int)$user['id'];
$issued = issueToken($uid);

successResponse('Login successful.', [
    'token'      => $issued['token'],
    'expires_in' => $issued['expires_in'],
    'uid'        => $uid,
]);
