<?php

namespace DataBase;

use PDO;

require_once __DIR__ . '/../Services/config.php';

class DataBase{
    private $conn;
    private $username;
    private $dsn;
    private $password;

    private function __construct()
    {
        try{
            $this->username = defined('DB_USER') ? DB_USER : "root";
            $this->password = defined('DB_PASS') ? DB_PASS : "root";
            $host = defined('DB_HOST') ? DB_HOST : "localhost";
            $dbName = defined('DB_NAME') ? DB_NAME : "";
            $this->dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(\PDOException $e){
            $this->conn = null;
            http_response_code(500);
            header("Content-Type: application/json");
            error_log($e->getMessage());
            echo json_encode([
                "success" => false,
                "message" => "ERROR SERVER"
            ]);
            exit;
        }
    }
    public function GetConnect()
    {
        return $this->conn ?? null;
    }
    private function CloseConnect(){
        $this->conn = null;
    }
}