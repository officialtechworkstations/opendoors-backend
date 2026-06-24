<?php
/**
 * Paystack redirects here when the user taps "Cancel Payment" on the checkout
 * page (configured via metadata.cancel_action in paystack/index.php).
 *
 * The app's webview watches for this URL — the moment it navigates here, the
 * app knows the transaction was cancelled and can close the checkout. Mirrors
 * the success-side behaviour of callback.php, but no verification is needed.
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
header('Content-type: application/json');

// Paystack appends the transaction reference (reference / trxref) on redirect.
$reference = $_GET['reference'] ?? ($_GET['trxref'] ?? '');

echo json_encode([
    "status"    => "cancelled",
    "reference" => $reference,
    "message"   => "Transaction was Cancelled",
]);
exit;
