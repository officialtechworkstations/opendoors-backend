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
