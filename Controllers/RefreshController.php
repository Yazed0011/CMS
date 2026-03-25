<?php
namespace Controllers\Auth;

use Middleware\Refresh\TokenService;

class RefreshController {

    public function refresh() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['refresh_token'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Refresh token required"]);
            exit;
        }

        try {
            $tokenService = new TokenService(JWT_SECRET);
            $result = $tokenService->refreshAccessToken($input['refresh_token']);

            echo json_encode([
                "success" => true,
                "message" => "Token refreshed successfully",
                "data"    => $result
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode() ?: 401);
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}