<?php
// config.php at C:\Users\socce\Documents\COS221\ASS5\ASS5\php\config.php

// 1) Load Composer’s autoloader from C:\Users\socce\Documents\COS221\ASS5\vendor\autoload.php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// 2) Tell phpdotenv to load the .env from C:\Users\socce\Documents\COS221\ASS5\.env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $host     = $_ENV['DB_HOST'];
        $dbname   = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        try {
            $this->conn = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $username,
                $password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => 'error',
                'message' => 'Database connection error'
            ]);
            exit;
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->conn;
    }
}
