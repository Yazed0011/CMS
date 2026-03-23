<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

if (!defined('JWT_SECRET')) {
    define("JWT_SECRET", $_ENV['JWT_SECRET'] ?? null);
}
if (!defined('JWT_ALGO')) {
    define("JWT_ALGO", $_ENV['JWT_ALGO'] ?? "HS256");
}
if (!defined('JWT_EXP_SECONDS')) {
    define("JWT_EXP_SECONDS", (int)($_ENV['JWT_EXP_SECONDS'] ?? 3600));
}

if (!defined('DB_HOST')) {
    define("DB_HOST", $_ENV['DB_HOST'] ?? "localhost");
}
if (!defined('DB_NAME')) {
    define("DB_NAME", $_ENV['DB_NAME'] ?? "cms");
}
if (!defined('DB_USER')) {
    define("DB_USER", $_ENV['DB_USER'] ?? "root");
}
if (!defined('DB_PASS')) {
    define("DB_PASS", $_ENV['DB_PASS'] ?? "root");
}

if (empty(constant('JWT_SECRET'))) {
    if (PHP_SAPI !== 'cli') {
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode([
            "success" => false,
            "message" => "The Dotenv file can't read .env (JWT_SECRET missing)"
        ]);
    }
    exit;
}