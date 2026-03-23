<?php 
namespace Model\User;
use DataBase\DataBase;
use SECRET\SECRET;
use PDO;
use PDOException;
use Refresh\Refresh;

class Users {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new DataBase();
        $this->conn = $this->db->GetConnect();
        if (!$this->conn) {
            throw new \Exception('Connect Failed', 500);
        }
    }

    public function signUp(array $data){
        try {
            $checkStmt = $this->conn->prepare("SELECT id FROM user WHERE email = :email");
            $checkStmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
            $checkStmt->execute();
            $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                throw new \Exception("Email Is Already Exist", 409);
            }

            $stmt = $this->conn->prepare("INSERT INTO user (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
            $stmt->execute();
            return json_encode([
                    "success" => true,
                    "message" => "User Created Successfully"]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
    public function login(array $data){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($data['password'], $user['password'])) {
                throw new \Exception("Password Or Email Is Not Correct", 401);
            }

            $secret = new SECRET();
            $accessToken = $secret->generateAccessToken($user, JWT_SECRET);  // ← تصحيح: خزن الـ return string

            $refresh = new Refresh();
            $refreshToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 7 * 24 * 60 * 60);  // ← تصحيح: حوّل لـ string تاريخ
            $created = $refresh->createRefreshToken($refreshToken, $user['id'], $expiresAt);
            if (!$created) {
                throw new \Exception("Failed to create refresh token", 500);
            }

            return json_encode([
                "accessToken" => $accessToken,
                "refreshToken" => $refreshToken
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception("ERROR SERVER", 500);
        }
    }
}
