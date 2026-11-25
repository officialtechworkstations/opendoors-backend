<?php
/** Link Google or Apple after signing in to the existing account. */
require dirname(__DIR__) . '/include/reconfig.php';
require dirname(__DIR__) . '/include/auth.php';
header('Content-type: application/json');

// Never use the legacy uid fallback: the existing account must prove ownership.
$uid = requireAuth();
$data = json_decode(file_get_contents('php://input'), true);
$provider = strtolower(trim((string) ($data['provider'] ?? '')));
$token = (string) ($data['token'] ?? '');
if (! in_array($provider, ['google', 'apple'], true) || $token === '') {
    http_response_code(422);
    echo json_encode(['ResponseCode'=>'422','Result'=>'false','ResponseMsg'=>'Provider and identity token are required.']);
    exit;
}
$identity = verifySocialToken($provider, $token);
if (! $identity || ! $identity['email_verified'] || empty($identity['email'])) {
    http_response_code(401);
    echo json_encode(['ResponseCode'=>'401','Result'=>'false','ResponseMsg'=>'A verified provider email is required to link this account.']);
    exit;
}
$email = $rstate->real_escape_string(strtolower(trim($identity['email'])));
$socialId = $rstate->real_escape_string($identity['social_id']);
$user = $rstate->query('SELECT id, email, status, social_provider, social_id FROM tbl_user WHERE id=' . $uid . ' LIMIT 1')->fetch_assoc();
if (! $user || (int) $user['status'] !== 1 || strtolower(trim($user['email'])) !== strtolower(trim($identity['email']))) {
    http_response_code(403);
    echo json_encode(['ResponseCode'=>'403','Result'=>'false','ResponseMsg'=>'The verified provider email must match your signed-in account.']);
    exit;
}
if ($rstate->query("SELECT id FROM tbl_user WHERE social_provider='$provider' AND social_id='$socialId' AND id<>$uid LIMIT 1")->num_rows) {
    http_response_code(409);
    echo json_encode(['ResponseCode'=>'409','Result'=>'false','ResponseMsg'=>'This provider identity is linked to another account.']);
    exit;
}
if (! empty($user['social_id']) && ($user['social_provider'] !== $provider || $user['social_id'] !== $identity['social_id'])) {
    http_response_code(409);
    echo json_encode(['ResponseCode'=>'409','Result'=>'false','ResponseMsg'=>'This account already has a different social identity. Contact support.']);
    exit;
}
$updated = $rstate->query("UPDATE tbl_user SET social_provider='$provider', social_id='$socialId' WHERE id=$uid AND status=1 AND (social_id IS NULL OR social_id='' OR (social_provider='$provider' AND social_id='$socialId'))");
if (! $updated || $rstate->affected_rows === 0) {
    http_response_code(409);
    echo json_encode(['ResponseCode'=>'409','Result'=>'false','ResponseMsg'=>'Account linkage changed. Please try again.']);
    exit;
}
echo json_encode(['ResponseCode'=>'200','Result'=>'true','ResponseMsg'=>'Social account linked successfully.']);
