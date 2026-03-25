<?php
namespace Refresh;
require_once __DIR__ . '/jwt.php';
use DataBase\DataBase;
use PDO;
use PDOException;

class Refresh {
    private $db;
    private $conn;
    private $token;
    private $userId;
    private $expiresAt = 7 * 24 * 60 * 60;

    public function __construct() {
        $this->db = new DataBase();
        $this->conn = $this->db->GetConnect();
        if (!$this->conn) {
            throw new \Exception('Connect Failed', 500);
        }
    }

    public function createRefreshToken(string $token, int $userId, string $expiresAt): bool {
        $this->token = $token;
        $this->userId = $userId;
        $this->expiresAt = $expiresAt;
        try {
            $stmt = $this->conn->prepare("INSERT INTO refresh_tokens (token, user_id, expires_at) VALUES (:token, :user_id, :expires_at)");
            $stmt->bindParam(':token', $this->token, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $this->userId, PDO::PARAM_INT);
            $stmt->bindParam(':expires_at', $this->expiresAt, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;  // ← تصحيح: return bool بدل echo
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }

    public function validateRefreshToken(string $token): array|false {
        $this->token = $token;
        try {
            $stmt = $this->conn->prepare("SELECT * FROM refresh_tokens WHERE token = :token AND expires_at > NOW()");
            $stmt->bindParam(':token', $this->token, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? ['user_id' => (int)$result['user_id']] : false; // 
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }

    // إضافة: دالة لتنظيف الـ expired tokens (استخدمها في cron job)
    public function cleanExpiredTokens(): int {
        try {
            $stmt = $this->conn->prepare("DELETE FROM refresh_tokens WHERE expires_at <= NOW()");
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
}