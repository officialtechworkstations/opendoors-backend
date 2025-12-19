<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

if (!function_exists('getEnvironment')) {
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

if (!function_exists('logger')) {
	function logger($message)
	{
		$date = date('Y-m-d');
		$logFile = __DIR__ . '/../logs/app-' . $date . '.log';
		$date = date('Y-m-d H:i:s');
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

		$mail_host = getConfig('mail_host');
		$mail_port = getConfig('mail_port');
		$mail_username = getConfig('mail_username');
		$mail_password = getConfig('mail_password');
		$mail_fromEmail = getConfig('mail_from');
		$mail_fromName = getConfig('mail_from_name');

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
