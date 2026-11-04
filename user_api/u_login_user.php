<?php
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/auth.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
$identifier = trim((string) ($data['mobile'] ?? ''));
$password = (string) ($data['password'] ?? '');
$ccode = trim((string) ($data['ccode'] ?? ''));

if ($identifier === '' || $password === '' || ($ccode === '' && strpos($identifier, '@') === false)) {
    $returnArr = [
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Something Went Wrong!",
    ];
} else {
    $identifier = strip_tags($identifier);
    $password = strip_tags($password);
    $phone = normalizePhoneIdentity($identifier, $ccode);

    if (strpos($identifier, '@') !== false) {
        $email = $rstate->real_escape_string(strtolower($identifier));
        $query = $rstate->query("SELECT * FROM tbl_user WHERE LOWER(email)='" . $email . "' AND status=1 ORDER BY id DESC LIMIT 1");
        $user = $query ? $query->fetch_assoc() : null;
    } else {
        $user = findUserByPhone($rstate, $identifier, $ccode, true);
    }

    $passwordMatches = $user && (
        password_verify($password, $user['password']) ||
        (strlen((string) $user['password']) === 32 && hash_equals(strtolower($user['password']), md5($password)))
    );
    $admin = null;

    if ($passwordMatches) {
        $c = $user;

        // Upgrade legacy hashes and legacy phone formatting after a verified login.
        $updates = [];
        if (password_needs_rehash($c['password'], PASSWORD_BCRYPT)) {
            $updates[] = "password='" . $rstate->real_escape_string(password_hash($password, PASSWORD_BCRYPT)) . "'";
        }
        if (strpos($identifier, '@') === false && $phone['mobile'] !== '' && $phone['ccode'] !== '') {
            $updates[] = "mobile='" . $rstate->real_escape_string($phone['mobile']) . "'";
            $updates[] = "ccode='" . $rstate->real_escape_string($phone['ccode']) . "'";
            $c['mobile'] = $phone['mobile'];
            $c['ccode'] = $phone['ccode'];
        }
        if ($updates) {
            $rstate->query("UPDATE tbl_user SET " . implode(',', $updates) . " WHERE id=" . (int) $c['id']);
        }

        $tokenData = issueToken((int)$c['id']);

        $returnArr = [
            "UserLogin"    => $c,
            "access_token" => $tokenData['token'],
            "expires_in"   => $tokenData['expires_in'],
            "currency"     => $set['currency'],
            "ResponseCode" => "200",
            "Result"       => "true",
            "type"         => "user",
            "ResponseMsg"  => "Login successfully!",
        ];
    } else {
        $adminMobile = $rstate->real_escape_string($phone['ccode'] . $phone['mobile']);
        $adminQuery = $rstate->query("SELECT * FROM admin WHERE mobile='" . $adminMobile . "' LIMIT 1");
        $admin = $adminQuery ? $adminQuery->fetch_assoc() : null;
    }

    if (! $passwordMatches && $admin && password_verify($password, $admin['password'])) {
        $c           = $admin;
        $p           = [];
        $p["id"]     = "0";
        $p["name"]   = $c['username'];
        $p["mobile"] = $c['mobile'];
        $returnArr   = [
            "UserLogin"    => $p,
            "currency"     => $set['currency'],
            "ResponseCode" => "200",
            "Result"       => "true",
            "type"         => "admin",
            "ResponseMsg"  => "Login successfully!",
        ];
    } elseif (! $passwordMatches) {
        $returnArr = [
            "ResponseCode" => "401",
            "Result"       => "false",
            "ResponseMsg"  => "Invalid Mobile Number Or Email Address or Password!",
        ];
    }
}

echo json_encode($returnArr);
exit;
