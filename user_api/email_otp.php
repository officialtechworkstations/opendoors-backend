<?php

require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';

header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input') , true);

$email = $data['email'];
if ($email == '') {
    $returnArr = [
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "The email field is required",
    ];
} else {
    $to  = $email; // email address to send OTP
    $otp = rand(111111, 999999);

    try {
        $msg_sent = sendOutgoingEmail($to, "Your OpenDoors One-Time Password (OTP)", '
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:480px; background-color:#ffffff; border-radius:6px;">
                <tbody>
                    <tr>
                        <td style="padding:0 30px 20px; color:#555555; font-size:14px; line-height:1.6;">
                            <p style="margin:0 0 15px;">Hello,</p>

                            <p style="margin:0 0 20px;">
                                Use the One-Time Password (OTP) below to complete your verification.
                                This code is valid for a limited time.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin:30px 0;">
                                <tbody>
                                    <tr>
                                        <td align="center">
                                            <span style="display:inline-block; padding:15px 30px; font-size:24px; letter-spacing:4px; font-weight:bold; background-color:#f0f2f5; color:#111111; border-radius:4px;">
                                                '.$otp.'
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p style="margin:20px 0 0;">
                                If you did not request this code, please ignore this email or contact support immediately.
                            </p>

                            <p style="margin-top:30px;">
                                Regards,<br>
                                <strong>OpenDoors Team</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 30px; font-size:12px; color:#999999; text-align:center;">
                            This is an automated message. Please do not reply.
                        </td>
                    </tr>
                </tbody>
            </table>');

        if ($msg_sent) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result"       => "true",
                "ResponseMsg"  => "OTP sent successfully",
                "otp"          => $otp,
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "401",
                "Result"       => "false",
                "ResponseMsg"  => "Error while sending the OTP at this time. Please try again or contact Tech support for help",
            ];
        }
    } catch (Exception $e) {
        // Handle exceptions such as invalid number, message failures, etc.
        $returnArr = [
            "ResponseCode" => "401",
            "Result"       => "false",
            "ResponseMsg"  => "We could not send the OTP at this time. Please try again or contact Tech support for help",
        ];

        logger("Error sending OTP to {$email}: " . $e->getMessage());
        // Log the error or take other necessary actions
    }

}

echo json_encode($returnArr);
exit;