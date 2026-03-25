<?php
namespace Middleware\Refresh;

use SECRET\SECRET;
use Refresh\Refresh;

class TokenService {

    private SECRET $jwt;
    private Refresh $refresh;
    private string $secret;

    public function __construct(string $secret) {
        $this->jwt = new SECRET();
        $this->refresh = new Refresh();
        $this->secret = $secret;
    }

    /**
     * تجديد Access Token باستخدام Refresh Token
     */
    public function refreshAccessToken(string $refreshToken): array {

        $result = $this->refresh->validateRefreshToken($refreshToken);

        if (!$result) {
            throw new \Exception("Invalid or expired refresh token", 401);
        }

        $userId = $result['user_id'];

        // هنا يفضل جلب بيانات المستخدم كاملة من الداتابيز
        // لكن للبساطة نستخدم الحد الأدنى
        $user = [
            "id"    => $userId,
            "admin" => 0       // يمكنك جلبها من DB
        ];

        $accessToken = $this->jwt->generateAccessToken($user, $this->secret);

        return [
            "access_token" => $accessToken,
            "token_type"   => "Bearer",
            "expires_in"   => JWT_EXP_SECONDS
        ];
    }
}