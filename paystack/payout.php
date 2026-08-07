<?php
/**
 * Admin auto-payout dispatcher (Paystack disbursement).
 *
 * Actions (POST/GET `action`):
 *   balance  -> available gateway balance
 *   banks    -> list of banks for account selection
 *   resolve  -> verify an account number + bank code, returns account name
 *   transfer -> create recipient + initiate transfer for a pending payout
 *   finalize -> submit OTP to complete a transfer that required one
 *
 * Admin/staff-guarded. Money movement guards: a payout must be 'pending' to be
 * transferred, and is flipped to 'processing' before the API call to prevent
 * double payment.
 */
require dirname(dirname(__FILE__)) . '/include/reconfig.php';
require dirname(dirname(__FILE__)) . '/include/estate.php';
require dirname(dirname(__FILE__)) . '/include/paystack_payout.php';
header('Content-type: application/json');

function payout_json($arr)
{
    echo json_encode($arr);
    exit;
}

// --- Auth guard: logged-in admin, or staff with payout Update permission ---
if (empty($_SESSION['restatename'])) {
    http_response_code(401);
    payout_json(['ok' => false, 'error' => 'Unauthorized']);
}
if (isset($_SESSION['stype']) && $_SESSION['stype'] === 'Staff' && ! in_array('Update', $payout_per ?? [], true)) {
    http_response_code(403);
    payout_json(['ok' => false, 'error' => 'You do not have permission to make payouts']);
}

$action     = $_REQUEST['action'] ?? '';
$gateway_id = (int) ($_REQUEST['gateway_id'] ?? 6); // Paystack = 6
$currency   = 'NGN';

$secret = paystack_secret_for_gateway($gateway_id);
if (! $secret) {
    payout_json(['ok' => false, 'error' => 'Selected gateway is not active or has no API key configured.']);
}

switch ($action) {

    case 'balance':
        $bal = paystack_available_balance($secret, $currency);
        if (! $bal['ok']) {
            payout_json(['ok' => false, 'error' => $bal['error']]);
        }
        payout_json(['ok' => true, 'balance' => $bal['balance'], 'currency' => $currency]);
        break;

    case 'banks':
        $banks = paystack_list_banks($secret, $currency);
        payout_json(['ok' => true, 'banks' => $banks]);
        break;

    case 'resolve':
        $account_number = trim($_REQUEST['account_number'] ?? '');
        $bank_code      = trim($_REQUEST['bank_code'] ?? '');
        if ($account_number === '' || $bank_code === '') {
            payout_json(['ok' => false, 'error' => 'Account number and bank are required.']);
        }
        $res = paystack_resolve_account($secret, $account_number, $bank_code);
        if (! $res['ok']) {
            payout_json(['ok' => false, 'error' => $res['error'] ?: 'Could not verify this account.']);
        }
        payout_json(['ok' => true, 'account_name' => $res['data']['account_name'] ?? '']);
        break;

    case 'transfer':
        $payout_id      = (int) ($_REQUEST['payout_id'] ?? 0);
        $account_number = trim($_REQUEST['account_number'] ?? '');
        $bank_code      = trim($_REQUEST['bank_code'] ?? '');
        if (! $payout_id || $account_number === '' || $bank_code === '') {
            payout_json(['ok' => false, 'error' => 'Missing payout, account or bank.']);
        }

        $payout = $rstate->query("SELECT * FROM payout_setting WHERE id=" . $payout_id)->fetch_assoc();
        if (! $payout) {
            payout_json(['ok' => false, 'error' => 'Payout request not found.']);
        }
        if ($payout['status'] !== 'pending') {
            payout_json(['ok' => false, 'error' => 'This payout is already ' . $payout['status'] . '.']);
        }

        $amount = (float) $payout['amt'];

        // 1) Balance check.
        $bal = paystack_available_balance($secret, $currency);
        if (! $bal['ok']) {
            payout_json(['ok' => false, 'error' => $bal['error']]);
        }
        if ($bal['balance'] < $amount) {
            payout_json(['ok' => false, 'error' => 'Insufficient gateway balance. Available: ' . number_format($bal['balance'], 2) . ' ' . $currency]);
        }

        // 2) Verify account (integrity — confirm the name Paystack has on file).
        $resolve = paystack_resolve_account($secret, $account_number, $bank_code);
        if (! $resolve['ok']) {
            payout_json(['ok' => false, 'error' => $resolve['error'] ?: 'Could not verify the destination account.']);
        }
        $account_name = $resolve['data']['account_name'] ?? ($payout['acc_name'] ?: 'OpenDoors Host');

        // 3) Create recipient.
        $recip = paystack_create_recipient($secret, $account_name, $account_number, $bank_code, $currency);
        if (! $recip['ok'] || empty($recip['data']['recipient_code'])) {
            payout_json(['ok' => false, 'error' => $recip['error'] ?: 'Could not create transfer recipient.']);
        }
        $recipient_code = $recip['data']['recipient_code'];

        // Guard against double payment before the money-moving call.
        $reference = 'OPD-' . $payout_id . '-' . uniqid();
        $rstate->query("UPDATE payout_setting SET status='processing', payout_mode='auto', gateway_id=" . $gateway_id . ", transaction_id='" . $rstate->real_escape_string($reference) . "' WHERE id=" . $payout_id . " AND status='pending'");

        // 4) Initiate transfer.
        $tr = paystack_initiate_transfer($secret, $amount, $recipient_code, 'OpenDoors host payout #' . $payout_id, $reference);
        $respJson = $rstate->real_escape_string(json_encode($tr['raw']));

        if (! $tr['ok']) {
            // Roll back to pending so the admin can retry.
            $rstate->query("UPDATE payout_setting SET status='pending', gateway_response='" . $respJson . "' WHERE id=" . $payout_id);
            payout_json(['ok' => false, 'error' => $tr['error'] ?: 'Transfer failed.']);
        }

        $status        = $tr['data']['status'] ?? '';
        $transfer_code = $tr['data']['transfer_code'] ?? '';

        if ($status === 'otp') {
            $rstate->query("UPDATE payout_setting SET status='processing', transaction_id='" . $rstate->real_escape_string($transfer_code) . "', gateway_response='" . $respJson . "' WHERE id=" . $payout_id);
            payout_json(['ok' => true, 'need_otp' => true, 'transfer_code' => $transfer_code, 'message' => 'Enter the OTP Paystack sent to complete this transfer.']);
        }

        // success / pending / received -> treat as sent; Paystack settles async.
        $today = date('Y-m-d');
        $rstate->query("UPDATE payout_setting SET status='completed', payout_mode='auto', gateway_id=" . $gateway_id . ", transaction_id='" . $rstate->real_escape_string($tr['data']['reference'] ?? $reference) . "', paid_amount=" . $amount . ", paid_date='" . $today . "', gateway_response='" . $respJson . "' WHERE id=" . $payout_id);
        payout_json(['ok' => true, 'done' => true, 'message' => 'Transfer ' . ($status ?: 'sent') . ' successfully.']);
        break;

    case 'finalize':
        $payout_id     = (int) ($_REQUEST['payout_id'] ?? 0);
        $transfer_code = trim($_REQUEST['transfer_code'] ?? '');
        $otp           = trim($_REQUEST['otp'] ?? '');
        if (! $payout_id || $transfer_code === '' || $otp === '') {
            payout_json(['ok' => false, 'error' => 'Missing transfer or OTP.']);
        }

        $payout = $rstate->query("SELECT * FROM payout_setting WHERE id=" . $payout_id)->fetch_assoc();
        if (! $payout || $payout['status'] !== 'processing') {
            payout_json(['ok' => false, 'error' => 'This transfer is not awaiting an OTP.']);
        }

        $fin      = paystack_finalize_transfer($secret, $transfer_code, $otp);
        $respJson = $rstate->real_escape_string(json_encode($fin['raw']));

        if (! $fin['ok']) {
            $rstate->query("UPDATE payout_setting SET gateway_response='" . $respJson . "' WHERE id=" . $payout_id);
            payout_json(['ok' => false, 'error' => $fin['error'] ?: 'OTP verification failed.']);
        }

        $today = date('Y-m-d');
        $rstate->query("UPDATE payout_setting SET status='completed', payout_mode='auto', gateway_id=" . $gateway_id . ", transaction_id='" . $rstate->real_escape_string($fin['data']['reference'] ?? $transfer_code) . "', paid_amount=" . (float) $payout['amt'] . ", paid_date='" . $today . "', gateway_response='" . $respJson . "' WHERE id=" . $payout_id);
        payout_json(['ok' => true, 'done' => true, 'message' => 'Transfer completed successfully.']);
        break;

    default:
        payout_json(['ok' => false, 'error' => 'Unknown action.']);
}
