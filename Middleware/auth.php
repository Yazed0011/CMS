<?php

namespace Middleware\Auth;
require_once __DIR__ . "/../Services/jwt.php";

use SECRET\SECRET;

class AUTH {

    private SECRET $decoder;

    public function __construct() {
        $this->decoder = new SECRET();
    }

    /**
     * يقرأ الـ Authorization header ويتحقق من الـ Access Token
     * يُرجع بيانات المستخدم عند النجاح، أو يرمي Exception
     */
    public function handle(): array {
        $headers     = getallheaders();
        $authHeader  = $headers['Authorization'] ?? $headers['authorization'] ?? "";

        if (!str_starts_with($authHeader, "Bearer ")) {
            throw new \Exception("Unauthorized: missing or invalid Authorization header", 401);
        }

        $token   = substr($authHeader, 7);
        $decoded = $this->decoder->validateAccessToken($token, JWT_SECRET);

        if (!$decoded) {
            throw new \Exception("Invalid or expired token", 401);
            exit;
        }

        // data مخزنة كـ stdObject في JWT — نقرأها صح
        return [
            "id"       => (int) ($decoded->sub              ?? 0),
            "name"     => $decoded->data->name              ?? null,
            "email"    => $decoded->data->email             ?? null,
            "is_admin" => (int) ($decoded->data->is_admin   ?? 0),
        ];
    }
}