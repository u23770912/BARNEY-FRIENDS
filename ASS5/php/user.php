<?php
class User {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    
    public function register($name, $surname, $email, $password) {
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            return ['status' => 'error', 'message' => 'Password does not meet requirements'];
        }
        
        // Check if the email already exists
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return ['status' => 'error', 'message' => 'Email already exists'];
        }
                    
        // Generate a dynamic salt (ensure salt is longer than 10 characters)
        $salt = bin2hex(random_bytes(16)); // 32 hex characters
        
        // Hash the password using SHA-256 combined with the salt (Blowfish not allowed)
        $hashedPassword = hash('sha256', $password . $salt);
        
        // Generate a unique API key (at least 10 alphanumeric characters)
        $apiKey = bin2hex(random_bytes(8)); // 16 hex characters
        
        // Insert the new user into the database
        $insertQuery = "INSERT INTO users (name, surname, email, password, salt, api_key) 
                        VALUES (:name, :surname, :email, :password, :salt, :api_key)";
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bindParam(':name', $name);
        $insertStmt->bindParam(':surname', $surname);
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':password', $hashedPassword);
        $insertStmt->bindParam(':salt', $salt);
        $insertStmt->bindParam(':api_key', $apiKey);
        
        if ($insertStmt->execute()) {
            return ['status' => 'success', 'apikey' => $apiKey];
        } else {
            return ['status' => 'error', 'message' => 'Failed to register user'];
        }
    }
    public function login(string $email, string $password): array {
        // 1) Basic server‑side validation
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            return ['status' => 'error', 'message' => 'Password does not meet requirements'];
        }
    
        $sql = "SELECT id, password AS stored_hash, salt, api_key
                FROM u23770912_users
                WHERE email = :email
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            return ['status' => 'error', 'message' => 'Email not found'];
        }
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $checkHash = hash('sha256', $password . $row['salt']);
    
        if (!hash_equals($row['stored_hash'], $checkHash)) {
            // hash_equals prevents timing attacks
            return ['status' => 'error', 'message' => 'Incorrect password'];
        }
    
        return [
            'status'  => 'success',
            'apikey'  => $row['api_key'],
            'name'    => $row['name'],
            'id'      => $row['id']
        ];
    }
    
}
?>
