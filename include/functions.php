<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../vendor/autoload.php';

if (! function_exists('getEnvironment')) {
    function getEnvironment()
    {
        $env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/.env', false, INI_SCANNER_RAW);
        return $env['environment'];
    }
}

if (! function_exists('getConfig')) {
    function getConfig($value)
    {
        $env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/.env', false, INI_SCANNER_RAW);

        $environment = $env['environment'];

        return $env[strtoupper($environment . '_' . $value)];
    }
}

if (! function_exists('logger')) {
    function logger($message)
    {
        $date             = date('Y-m-d');
        $logFile          = __DIR__ . '/../logs/app-' . $date . '.log';
        $date             = date('Y-m-d H:i:s');
        $formattedMessage = "[{$date}] {$message}\n";
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}

if (! function_exists('sendOutgoingEmail')) {
    function sendOutgoingEmail($email, $subject, $body, $headers = [])
    {
        $mail = new PHPMailer(true);

        $environment = getEnvironment();

        if ($environment == 'development') {
            return true;
        }

        $mail_host      = getConfig('mail_host');
        $mail_port      = getConfig('mail_port');
        $mail_username  = getConfig('mail_username');
        $mail_password  = getConfig('mail_password');
        $mail_fromEmail = getConfig('mail_from');
        $mail_fromName  = getConfig('mail_from_name');

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = $mail_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $mail_port;

            // Sender & recipient
            $mail->setFrom($mail_fromEmail, $mail_fromName);
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            logger("Sending email to {$email} with subject '{$subject}'");

            return $mail->send();
        } catch (Exception $e) {
            logger("Email failed to {$email}: {$mail->ErrorInfo}");
        }

        return false;
    }
}

if (! function_exists('fetchJwks')) {
    /**
     * Fetch a provider's JSON Web Key Set, caching it on disk for 12h so we
     * don't hit Google/Apple on every verification (and survive brief outages).
     */
    function fetchJwks($url, $cacheKey)
    {
        $cacheFile = sys_get_temp_dir() . '/jwks_' . preg_replace('/[^a-z0-9_]/i', '', $cacheKey) . '.json';
        $cached    = (is_file($cacheFile)) ? file_get_contents($cacheFile) : '';

        if ($cached !== '' && (time() - filemtime($cacheFile) < 43200)) {
            return json_decode($cached, true);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false || $code !== 200) {
            // Fall back to a stale-but-valid cache if the fetch failed.
            return ($cached !== '') ? json_decode($cached, true) : null;
        }

        file_put_contents($cacheFile, $body);
        return json_decode($body, true);
    }
}

if (! function_exists('verifySocialToken')) {
    /**
     * Verify a Google/Apple ID token server-side.
     *
     * Validates the JWT signature against the provider JWKS and checks the
     * issuer, audience and expiry. Audiences (client IDs / bundle IDs) are read
     * from tbl_setting (comma-separated to allow iOS + Android + web).
     *
     * @return array|false ['social_id','email','name','email_verified'] or false.
     */
    function verifySocialToken($provider, $token)
    {
        global $set;

        if (empty($token) || empty($provider)) {
            return false;
        }

        $provider = strtolower($provider);

        if ($provider === 'google') {
            $jwksUrl    = 'https://www.googleapis.com/oauth2/v3/certs';
            $allowedIss = ['accounts.google.com', 'https://accounts.google.com'];
            $allowedAud = array_filter(array_map('trim', explode(',', (string) ($set['google_client_id'] ?? ''))));
        } elseif ($provider === 'apple') {
            $jwksUrl    = 'https://appleid.apple.com/auth/keys';
            $allowedIss = ['https://appleid.apple.com'];
            $allowedAud = array_filter(array_map('trim', explode(',', (string) ($set['apple_bundle_id'] ?? ''))));
        } else {
            return false;
        }

        if (empty($allowedAud)) {
            logger("verifySocialToken: no audience configured for {$provider}");
            return false;
        }

        $jwks = fetchJwks($jwksUrl, $provider);
        if (empty($jwks) || empty($jwks['keys'])) {
            logger("verifySocialToken: unable to load JWKS for {$provider}");
            return false;
        }

        try {
            \Firebase\JWT\JWT::$leeway = 60; // tolerate small clock skew
            $keys    = \Firebase\JWT\JWK::parseKeySet($jwks);
            $decoded = (array) \Firebase\JWT\JWT::decode($token, $keys);
        } catch (\Throwable $e) {
            logger("verifySocialToken: decode failed for {$provider}: " . $e->getMessage());
            return false;
        }

        if (! in_array(($decoded['iss'] ?? ''), $allowedIss, true)) {
            logger("verifySocialToken: bad iss for {$provider}: " . ($decoded['iss'] ?? ''));
            return false;
        }

        if (! in_array(($decoded['aud'] ?? ''), $allowedAud, true)) {
            logger("verifySocialToken: bad aud for {$provider}");
            return false;
        }

        if (empty($decoded['sub'])) {
            return false;
        }

        $emailVerified = $decoded['email_verified'] ?? false;
        if (is_string($emailVerified)) {
            $emailVerified = ($emailVerified === 'true');
        }

        return [
            'social_id'      => (string) $decoded['sub'],
            'email'          => isset($decoded['email']) ? strtolower(trim($decoded['email'])) : null,
            'name'           => $decoded['name'] ?? null,
            'email_verified' => (bool) $emailVerified,
        ];
    }
}

if (! function_exists('maskHostMobile')) {
    /**
     * Apply the admin-configured host-phone display mode.
     *  - full    : return as-is
     *  - partial : keep first 3 + last 2 digits, mask the middle
     *  - hidden  : return empty string
     */
    function maskHostMobile($number, $mode)
    {
        $number = (string) $number;

        if ($mode === 'hidden') {
            return '';
        }

        if ($mode !== 'partial') {
            return $number; // 'full' or any unexpected value
        }

        $len = strlen($number);
        if ($len <= 5) {
            return str_repeat('*', $len);
        }

        return substr($number, 0, 3) . str_repeat('*', $len - 5) . substr($number, -2);
    }
}

if (! function_exists('oneSignalNewsLetterSubscription')) {
    function oneSignalNewsLetterSubscription($uid, bool $is_subscribed, array $data)
    {
        global $set;

        $external_user_id = (string) $uid;
        $app_id           = $set['one_key'];
        $rest_api_key     = $set['one_hash'];

        if ($is_subscribed) {
            $payload = json_encode([
                "identity"      => [
                    "external_id" => $external_user_id,
                ],
                "subscriptions" => [
                    [
                        "type"  => "Email",
                        "token" => $data['email'],
                    ],
                ],
                "properties"    => [
                    "tags" => [
                        "newsletter" => "true",
                    ],
                ],
            ]);

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.onesignal.com/apps/{$app_id}/users",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    "Content-Type: application/json",
                    "Authorization: Basic {$rest_api_key}",
                ],
                CURLOPT_POSTFIELDS => $payload,
            ]);
        } else {
            $payload = json_encode([
                "properties" => [
                    "tags" => [
                        "newsletter" => null,
                    ],
                ],
            ]);

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => "https://api.onesignal.com/apps/{$app_id}/users/by/external_id/{$external_user_id}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => "PATCH",
                CURLOPT_HTTPHEADER     => [
                    "Content-Type: application/json",
                    "Authorization: Basic {$rest_api_key}",
                ],
                CURLOPT_POSTFIELDS => $payload,
            ]);
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        curl_close($ch);

        logger(print_r([
            'action' => $is_subscribed ? 'subscribe' : 'unsubscribe',
            'uid' => $uid,
            'payload' => $payload,
            'response' => $response,
            'http_code' => $http_code,
            'curl_error' => $curl_error,
        ], true));
    }
}
