<?php
/**
 * Returns the list of banks (name + code) for the withdrawal form's bank picker.
 *
 * The app should show this list when a host requests a payout so it can send
 * back the selected bank's `bank_code` (which Paystack transfers require).
 * Sourced from the active Paystack gateway.
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/paystack_payout.php';
header('Content-type: text/json');

$gw = $rstate->query("SELECT id FROM tbl_payment_list WHERE id=6 AND status=1")->fetch_assoc();

if (! $gw) {
    echo json_encode([
        "ResponseCode" => "401",
        "Result"       => "false",
        "ResponseMsg"  => "Bank list unavailable.",
        "BankList"     => [],
    ]);
    exit;
}

$secret = paystack_secret_for_gateway(6);
$banks  = $secret ? paystack_list_banks($secret, 'NGN') : [];

echo json_encode([
    "ResponseCode" => "200",
    "Result"       => "true",
    "ResponseMsg"  => "Bank List Get Successfully!",
    "BankList"     => $banks,
]);
exit;
