<?php
/**
 * Shared OTP sender for the social sign-up flow.
 *
 * Mirrors the per-provider logic in msg_otp.php / twilio_otp.php / termii_otp.php
 * and routes on the configured tbl_setting.sms_type. Returns true on success.
 *
 * NOTE: like the existing OTP endpoints, the generated OTP is returned to the
 * caller and verified client-side. This helper only delivers it.
 */
if (! function_exists('sendOtpViaProvider')) {
    function sendOtpViaProvider($dialNumber, $otp)
    {
        global $set, $rstate;

        $provider = $set['sms_type'] ?? '';

        if ($provider === 'Msg91') {
            $url = "https://control.msg91.com/api/v5/otp?template_id=" . $set['otp_id'] .
                "&mobile=" . $dialNumber . "&authkey=" . $set['auth_key'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/JSON"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["otp" => $otp]));
            $response = curl_exec($ch);
            $code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return ($response !== false && $code >= 200 && $code < 300);
        }

        if ($provider === 'Twilio') {
            require_once dirname(__FILE__) . '/src/Twilio/autoload.php';
            try {
                $client = new \Twilio\Rest\Client($set['acc_id'], $set['auth_token']);
                $client->messages->create($dialNumber, [
                    'from' => $set['twilio_number'],
                    'body' => "Your OTP is #" . $otp . " to verify and proceed.",
                ]);
                return true;
            } catch (\Throwable $e) {
                logger("sendOtpViaProvider Twilio error: " . $e->getMessage());
                return false;
            }
        }

        if ($provider === 'Termii') {
            require_once dirname(__FILE__) . '/src/Termii/Termii.php';
            try {
                $termii      = new \user_api\Termii();
                $companyName = $set['webname'];
                return (bool) $termii->sendSms(
                    $dialNumber,
                    "Your {$companyName} verification pin is {$otp}. Valid for 10 minutes, one-time use only."
                );
            } catch (\Throwable $e) {
                logger("sendOtpViaProvider Termii error: " . $e->getMessage());
                return false;
            }
        }

        logger("sendOtpViaProvider: unknown sms_type '{$provider}'");
        return false;
    }
}
