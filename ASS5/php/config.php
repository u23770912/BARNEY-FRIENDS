<?php

$envPath = __DIR__ . '/../.env'; 
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

class Database {

    private static $instance = null;
    private $conn;
    private $host = 'wheatley.cs.up.ac.za';
    private $dbname = 'u23770912';
    private $username = 'u23770912';
    private $password = 'UGJ5F5F5KPEZ7E455QF6JWBZVTPWR73K';

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", 
                                  $this->username, 
                                  $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create a database connection instance
$dbInstance = Database::getInstance();
$db = $dbInstance->getConnection();
?>
