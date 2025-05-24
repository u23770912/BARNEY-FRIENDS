<?php
// Load environment variables
//require_once __DIR__ . '/../../../vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
// $dotenv->load();

class Database {
    private static $instance = null;
    private $conn;
    
    private $host = "https://wheatley.cs.up.ac.za/phpmyadmin/";
    private $dbname = "u23976072_COS221Ass5";
    private $username = "u23976072"; 
    private $password = "LYCUW3YGLIB7THQGRWU2N5WHX6WBOMIC";

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
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

