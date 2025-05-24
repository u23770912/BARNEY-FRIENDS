<?php
// require_once __DIR__ . '/../../vendor/autoload.php';
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();
class Database {
    private static $instance = null;
    private $conn;

    private $host;
    private $dbname;
    private $username;
    private $password;

    private function __construct() {
        // Pull from the environment
        $this->host     = getenv('DB_HOST');
        $this->dbname   = getenv('DB_NAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Make sure errors are returned in JSON if this is an API
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
