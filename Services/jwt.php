<?php

namespace SECRET;

require_once __DIR__ . '/config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class SECRET {

    private string $JWT_ALGO;
    private int $JWT_EXP_SECONDS;

    public function __construct() {
        $this->JWT_ALGO        = JWT_ALGO;
        $this->JWT_EXP_SECONDS = JWT_EXP_SECONDS;
    }

    /**
     * توليد Access Token
     */
    public function generateAccessToken(array $user, string $JWT_SECRET): string {
        if (empty($user['id'])) {
            throw new \InvalidArgumentException("User ID is required", 400);
        }

        $now = time();
        $payload = [
            "iss"  => "CMS",
            "iat"  => $now,
            "exp"  => $now + $this->JWT_EXP_SECONDS,
            "sub"  => (int) $user['id'],
            "data" => [
                "name"     => $user['name']     ?? null,
                "email"    => $user['email']    ?? null,
                "admin" => $user['admin'] ?? 0,
            ]
        ];
        return JWT::encode($payload, $JWT_SECRET, $this->JWT_ALGO);
    }

    /**
     * التحقق من Access Token
     * يُرجع stdClass عند النجاح، أو null عند الفشل
     */
    public function validateAccessToken(string $token, string $JWT_SECRET): ?\stdClass {
        try {
            return JWT::decode($token, new Key($JWT_SECRET, $this->JWT_ALGO));
        } catch (ExpiredException $e) {
            return null;  // Token منتهي الصلاحية
        } catch (SignatureInvalidException $e) {
            return null;  // توقيع خاطئ
        } catch (\UnexpectedValueException $e) {
            return null;  // Token مشوه
        } catch (\Exception $e) {
            error_log("JWT validation error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * توليد Refresh Token عشوائي آمن
     */
    public function generateRefreshToken(): string {
        return bin2hex(random_bytes(64));  // 128 حرف hex
    }
}