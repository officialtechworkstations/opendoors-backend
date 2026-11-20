<?php
/** Server-side, one-use registration OTP challenges. Run the accompanying migration first. */
function registrationOtpCreate(mysqli $db, string $mobile, string $ccode, string $purpose, string $identity = ''): array
{
    $challenge = bin2hex(random_bytes(16));
    $otp = (string) random_int(100000, 999999);
    $hash = password_hash($otp, PASSWORD_DEFAULT);
    $identityHash = hash('sha256', $identity);
    $stmt = $db->prepare('INSERT INTO registration_otp (challenge_id, mobile, ccode, purpose, identity_hash, otp_hash, expires_at) VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))');
    if (! $stmt) { throw new RuntimeException('OTP storage is unavailable'); }
    $stmt->bind_param('ssssss', $challenge, $mobile, $ccode, $purpose, $identityHash, $hash);
    if (! $stmt->execute()) { throw new RuntimeException('OTP storage is unavailable'); }
    return [$challenge, $otp];
}

function registrationOtpConsume(mysqli $db, string $challenge, string $otp, string $mobile, string $ccode, string $purpose, string $identity = ''): bool
{
    if (! preg_match('/^[a-f0-9]{32}$/', $challenge) || ! preg_match('/^[0-9]{6}$/', $otp)) { return false; }
    $identityHash = hash('sha256', $identity);
    $db->begin_transaction();
    try {
        $stmt = $db->prepare('SELECT otp_hash, attempts FROM registration_otp WHERE challenge_id=? AND mobile=? AND ccode=? AND purpose=? AND identity_hash=? AND expires_at > NOW() AND consumed_at IS NULL FOR UPDATE');
        $stmt->bind_param('sssss', $challenge, $mobile, $ccode, $purpose, $identityHash);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (! $row || (int) $row['attempts'] >= 5) { $db->rollback(); return false; }
        if (! password_verify($otp, $row['otp_hash'])) {
            $update = $db->prepare('UPDATE registration_otp SET attempts=attempts+1 WHERE challenge_id=?');
            $update->bind_param('s', $challenge);
            $update->execute();
            $db->commit();
            return false;
        }
        $update = $db->prepare('UPDATE registration_otp SET consumed_at=NOW() WHERE challenge_id=?');
        $update->bind_param('s', $challenge);
        $update->execute();
        $db->commit();
        return true;
    } catch (Throwable $e) { $db->rollback(); throw $e; }
}

function registrationOtpCancel(mysqli $db, string $challenge): void
{
    $stmt = $db->prepare('DELETE FROM registration_otp WHERE challenge_id=?');
    $stmt->bind_param('s', $challenge);
    $stmt->execute();
}
