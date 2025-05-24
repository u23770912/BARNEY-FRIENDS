<?php
// Load environment variables
//require_once __DIR__ . '/../../../vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
// $dotenv->load();

class Database {
    private static $instance = null;
    private $conn;
    
    // Fixed: host should be the server hostname, not the phpMyAdmin URL
    private $host = "wheatley.cs.up.ac.za"; // or "wheatley.cs.up.ac.za" if connecting remotely
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

?>