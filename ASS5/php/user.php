<?php

require_once __DIR__ . '/config.php';

class User {
    private $conn;
    
    public function __construct(PDO $dbConnection) {
        $this->conn = $dbConnection;
    }
    
    public function register(string $name, string $surname, string $email, string $password): array {
        // 1) Validate email
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }
        // 2) Validate password (min 9 chars, upper+lower+digit+special)
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            return ['status' => 'error', 'message' => 'Password does not meet requirements'];
        }
        
        // 3) Check for existing email
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return ['status' => 'error', 'message' => 'Email already exists'];
        }
                    
        // 4) Generate salt & hash
        $salt           = bin2hex(random_bytes(16));             // 32 hex chars
        $hashedPassword = hash('sha256', $password . $salt);      // SHA-256 + salt
        
        // 5) Generate API key
        $apikey = bin2hex(random_bytes(16));                      // 32 hex chars
        
        // 6) Insert user
        $insertSql  = "INSERT INTO users (name, surname, email, password, salt, api_key)
                       VALUES (:name, :surname, :email, :password, :salt, :api_key)";
        $insertStmt = $this->conn->prepare($insertSql);
        $insertStmt->bindParam(':name',     $name);
        $insertStmt->bindParam(':surname',  $surname);
        $insertStmt->bindParam(':email',    $email);
        $insertStmt->bindParam(':password', $hashedPassword);
        $insertStmt->bindParam(':salt',     $salt);
        $insertStmt->bindParam(':api_key',  $apikey);
        
        try {
            $insertStmt->execute();
            $userId = $this->conn->lastInsertId();
            
            return [
                'status'  => 'success',
                'message' => 'Registration successful',
                'user'    => [
                    'id'       => $userId,
                    'name'     => $name,
                    'surname'  => $surname,
                    'email'    => $email,
                    'apikey'  => $apikey
                ]
            ];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function login(string $email, string $password): array {
        // 1) Validate inputs
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
            return ['status' => 'error', 'message' => 'Password does not meet requirements'];
        }
    
        // 2) Fetch stored hash, salt, and user info
        $sql = "SELECT id, name, surname,
                       password AS stored_hash,
                       salt,
                       api_key
                FROM users
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
    
        // 3) Compare hashes safely
        if (!hash_equals($row['stored_hash'], $checkHash)) {
            return ['status' => 'error', 'message' => 'Incorrect password'];
        }
    
        // 4) Success!
        return [
            'status'  => 'success',
            'message' => 'Login successful',
            'user'    => [
                'id'       => $row['id'],
                'name'     => $row['name'],
                'surname'  => $row['surname'],
                'email'    => $email,
                'api_key'  => $row['api_key']
            ]
        ];
    }
}
