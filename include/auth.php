<?php
/**
 * include/auth.php
 *
 * JWT Bearer token authentication middleware for protected API endpoints.
 *
 * Usage (at the top of any protected endpoint):
 *
 *   require dirname(__DIR__) . '/include/auth.php';
 *   $auth_uid = requireAuth();   // exits with 401 if token is missing/invalid
 *
 * Token format:
 *   Authorization: Bearer <jwt>
 *
 * JWT claims:
 *   - sub  : (int) user ID
 *   - iat  : issued-at timestamp
 *   - exp  : expiry timestamp
 *
 * The JWT is signed with HS256 using JWT_SECRET from .env.
 *
 * Backward-compatibility (transition period):
 *   requireAuth($legacyUid) — if no Bearer header is present AND a legacy uid
 *   is provided, it is returned as-is after strict integer validation.
 *   This allows mobile clients to migrate at their own pace.
 */

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (! function_exists('issueToken')) {
    /**
     * Create and persist a signed JWT for the given user.
     *
     * @param  int $uid
     * @return array ['token' => string, 'expires_in' => int]
     */
    function issueToken(int $uid): array
    {
        global $rstate;

        $secret = getConfig('JWT_SECRET');
        $ttl = (int) getConfig('JWT_TTL_SECONDS');
        $now = time();
        $payload = [
            'sub' => $uid,
            'iat' => $now,
            'exp' => $now + $ttl,
        ];

        $token = JWT::encode($payload, $secret, 'HS256');

        // Persist hash for optional revocation checks
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', $now + $ttl);
        $created = date('Y-m-d H:i:s', $now);
        $rstate->query(
            "INSERT INTO tbl_auth_tokens (uid, token_hash, expires_at, created_at)
            VALUES (" . intval($uid) . ", '" . $rstate->real_escape_string($hash) . "', '$expires', '$created')"
        );

        return ['token' => $token, 'expires_in' => $ttl];
    }
}

if (! function_exists('requireAuth')) {
    /**
     * Verify the Bearer token from the Authorization header.
     *
     * Returns the authenticated uid (int) on success.
     * Calls errorResponse() and exits on failure.
     *
     * @param  int|string $legacyUid  Optional uid from request body (transition mode).
     *                                Used only when no Bearer header is present.
     * @return int                    Authenticated user ID
     */
    function requireAuth($legacyUid = null): int
    {
        global $rstate;

        // 1. Try Bearer token first
        $authHeader = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if (empty($authHeader) && function_exists('getallheaders')) {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }

        if (! empty($authHeader) && stripos($authHeader, 'Bearer ') === 0) {
            $rawToken = substr($authHeader, 7);
            $secret = getConfig('JWT_SECRET');

            try {
                $decoded = (array) JWT::decode($rawToken, new Key($secret, 'HS256'));
            } catch (\Throwable $e) {
                http_response_code(401);
                errorResponse('Invalid or expired token.', 401, 'AUTH_TOKEN_INVALID');
                exit;
            }

            $uid = (int)($decoded['sub'] ?? 0);
            if ($uid <= 0) {
                http_response_code(401);
                errorResponse('Invalid token subject.', 401, 'AUTH_TOKEN_INVALID');
                exit;
            }

            // Optional: check token has not been revoked
            $hash = hash('sha256', $rawToken);
            $check = $rstate->query(
                "SELECT id FROM tbl_auth_tokens WHERE token_hash = '"
                . $rstate->real_escape_string($hash) . "' AND expires_at > NOW() LIMIT 1"
            );
            if (! $check || $check->num_rows === 0) {
                http_response_code(401);
                errorResponse('Token has been revoked or expired.', 401, 'AUTH_TOKEN_REVOKED');
                exit;
            }

            return $uid;
        }

        // 2. Fallback: legacy uid from request body (transition mode)
        if ($legacyUid !== null) {
            $uid = (int)$legacyUid;
            if ($uid > 0) {
                return $uid;
            }
        }

        // 3. No valid credential
        http_response_code(401);
        errorResponse('Authentication required. Provide a Bearer token.', 401, 'AUTH_MISSING');
        exit;
    }
}

if (! function_exists('invalidateToken')) {
    /**
     * Delete a token from tbl_auth_tokens to force logout.
     *
     * @param string $rawToken The raw JWT string
     */
    function invalidateToken(string $rawToken): void
    {
        global $rstate;
        $hash = hash('sha256', $rawToken);
        $rstate->query(
            "DELETE FROM tbl_auth_tokens WHERE token_hash = '"
            . $rstate->real_escape_string($hash) . "'"
        );
    }
}

if (! function_exists('optionalAuth')) {
    /**
     * Verify the Bearer token from the Authorization header if present.
     * Returns the authenticated uid (int) on success, or 0 if missing/invalid.
     */
    function optionalAuth($legacyUid = null): int
    {
        global $rstate;

        $authHeader = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if (empty($authHeader) && function_exists('getallheaders')) {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }

        if (! empty($authHeader) && stripos($authHeader, 'Bearer ') === 0) {
            $rawToken = substr($authHeader, 7);
            $secret = getConfig('JWT_SECRET');

            try {
                $decoded = (array) \Firebase\JWT\JWT::decode($rawToken, new \Firebase\JWT\Key($secret, 'HS256'));
            } catch (\Throwable $e) {
                return 0;
            }

            $uid = (int)($decoded['sub'] ?? 0);
            if ($uid <= 0) {
                return 0;
            }

            $hash = hash('sha256', $rawToken);
            $check = $rstate->query(
                "SELECT id FROM tbl_auth_tokens WHERE token_hash = '"
                . $rstate->real_escape_string($hash) . "' AND expires_at > NOW() LIMIT 1"
            );
            if (! $check || $check->num_rows === 0) {
                return 0;
            }

            return $uid;
        }

        if ($legacyUid !== null) {
            $uid = (int)$legacyUid;
            if ($uid > 0) {
                return $uid;
            }
        }

        return 0;
    }
}
