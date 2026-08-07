<?php
/**
 * Paystack Transfers helpers for admin auto-payout (disbursement).
 *
 * Flow: check balance -> resolve/verify bank account -> create transfer
 * recipient -> initiate transfer -> (finalize with OTP if required).
 *
 * Every function returns: ['ok'=>bool, 'data'=>mixed, 'error'=>?string, 'raw'=>array].
 * The Paystack secret key is read from tbl_payment_list.attributes (CSV index 1),
 * the same slot the checkout flow uses.
 */

if (! function_exists('paystack_secret_for_gateway')) {
    function paystack_secret_for_gateway($gateway_id)
    {
        global $rstate;
        $row = $rstate->query(
            "SELECT attributes FROM tbl_payment_list WHERE id=" . (int) $gateway_id . " AND status=1"
        )->fetch_assoc();

        if (! $row) {
            return null;
        }
        $parts = explode(',', $row['attributes']);
        return isset($parts[1]) ? trim($parts[1]) : null;
    }
}

if (! function_exists('paystack_request')) {
    function paystack_request($secret, $method, $path, $payload = null)
    {
        $ch      = curl_init();
        $headers = [
            "Authorization: Bearer " . $secret,
            "Cache-Control: no-cache",
        ];
        $opts = [
            CURLOPT_URL            => "https://api.paystack.co" . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => $method,
        ];
        if ($payload !== null) {
            $headers[]                 = "Content-Type: application/json";
            $opts[CURLOPT_POSTFIELDS]  = json_encode($payload);
        }
        $opts[CURLOPT_HTTPHEADER] = $headers;
        curl_setopt_array($ch, $opts);

        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return ['ok' => false, 'http' => 0, 'data' => null, 'error' => $err ?: 'Network error', 'raw' => []];
        }

        $json = json_decode($body, true);
        $ok   = ($code >= 200 && $code < 300) && ! empty($json['status']);

        return [
            'ok'    => $ok,
            'http'  => $code,
            'data'  => $json['data'] ?? null,
            'error' => $ok ? null : ($json['message'] ?? 'Paystack error'),
            'raw'   => is_array($json) ? $json : [],
        ];
    }
}

if (! function_exists('paystack_available_balance')) {
    /** Returns the available balance (in major units) for a currency, or null. */
    function paystack_available_balance($secret, $currency = 'NGN')
    {
        $res = paystack_request($secret, 'GET', '/balance');
        if (! $res['ok'] || ! is_array($res['data'])) {
            return ['ok' => false, 'error' => $res['error'] ?: 'Could not read balance'];
        }
        foreach ($res['data'] as $bal) {
            if (strtoupper($bal['currency'] ?? '') === strtoupper($currency)) {
                return ['ok' => true, 'balance' => ($bal['balance'] / 100), 'kobo' => (int) $bal['balance']];
            }
        }
        return ['ok' => true, 'balance' => 0, 'kobo' => 0];
    }
}

if (! function_exists('paystack_list_banks')) {
    /** Paginates GET /bank for a currency and returns [['name'=>..,'code'=>..], ...]. */
    function paystack_list_banks($secret, $currency = 'NGN')
    {
        $banks = [];
        $page  = 1;
        do {
            $res = paystack_request($secret, 'GET', "/bank?currency={$currency}&perPage=100&page={$page}");
            if (! $res['ok'] || ! is_array($res['data'])) {
                break;
            }
            foreach ($res['data'] as $b) {
                if (! empty($b['code'])) {
                    $banks[] = ['name' => $b['name'], 'code' => $b['code']];
                }
            }
            $count = count($res['data']);
            $page++;
        } while ($count === 100 && $page <= 10);

        return $banks;
    }
}

if (! function_exists('paystack_resolve_account')) {
    function paystack_resolve_account($secret, $account_number, $bank_code)
    {
        $res = paystack_request(
            $secret,
            'GET',
            '/bank/resolve?account_number=' . rawurlencode($account_number) . '&bank_code=' . rawurlencode($bank_code)
        );
        return $res;
    }
}

if (! function_exists('paystack_create_recipient')) {
    function paystack_create_recipient($secret, $name, $account_number, $bank_code, $currency = 'NGN')
    {
        return paystack_request($secret, 'POST', '/transferrecipient', [
            'type'           => 'nuban',
            'name'           => $name,
            'account_number' => $account_number,
            'bank_code'      => $bank_code,
            'currency'       => $currency,
        ]);
    }
}

if (! function_exists('paystack_initiate_transfer')) {
    function paystack_initiate_transfer($secret, $amount_major, $recipient_code, $reason, $reference)
    {
        return paystack_request($secret, 'POST', '/transfer', [
            'source'    => 'balance',
            'amount'    => (int) round($amount_major * 100), // kobo
            'recipient' => $recipient_code,
            'reason'    => $reason,
            'reference' => $reference,
        ]);
    }
}

if (! function_exists('paystack_finalize_transfer')) {
    function paystack_finalize_transfer($secret, $transfer_code, $otp)
    {
        return paystack_request($secret, 'POST', '/transfer/finalize_transfer', [
            'transfer_code' => $transfer_code,
            'otp'           => $otp,
        ]);
    }
}
