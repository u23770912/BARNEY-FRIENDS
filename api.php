
<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . '/ASS5/php/config.php'; 
require_once __DIR__ . '/ASS5/php/user.php'; 

$input = json_decode(file_get_contents("php://input"), true);

// Check for missing type
if (!isset($input['type'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Request type not specified"]);
    exit;
}

else if ($input['type'] === "Register") {
    try {
        $name = htmlspecialchars($input["name"]);
        $name      = trim($input['name'] ?? "");
        $surname   = trim($input['surname'] ?? "");
        $email     = trim($input['email'] ?? "");
        $password  = $input['password'] ?? "";

        if (empty($name) || empty($surname) || empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing required fields"]);
            exit;
        }

        $user = new User($db);
        $result = $user->register($name, $surname, $email, $password);

        if ($result['status'] === "success") {
            echo json_encode([
                "status" => "success",
                "timestamp" => round(microtime(true) * 1000),
                "data" => ["apikey" => $result['apikey']]
            ]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => $result['message']]);
        }
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Register failed", "details" => $e->getMessage()]);
        exit;
    }
}

else if ($input['type'] === "Login") {
    try {
        $email     = trim($input['email'] ?? "");
        $password  = $input['password'] ?? "";

        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing required fields"]);
            exit;
        }

        $user = new User($db);
        $result = $user->login( $email, $password);

        if ($result['status'] === "success") {

            echo json_encode([
                "status" => "success",
                "timestamp" => round(microtime(true) * 1000),
                "data" => [
                    "apikey" => $result['apikey'],
                    "userid" => $result['id'],
                    "name"   => $result['name']
                ]
            ]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => $result['message']]);
        }
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Login failed", "details" => $e->getMessage()]);
        exit;
    }
}

else if ($input['type'] === 'AddToWishlist') {
    try {
        $required = ['user_id', 'product_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        // Verify API key
        $stmt = $db->prepare("SELECT id FROM u23770912_users WHERE id = :user_id AND api_key = :api_key");
        $stmt->execute([
            ':user_id' => $input['user_id'],
            ':api_key' => $input['apikey']
        ]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception("Invalid API key or user ID", 401);
        }
        
        // Check if product exists
        $productCheck = $db->prepare("SELECT product_id FROM u23770912_products WHERE product_id = :product_id");
        $productCheck->execute([':product_id' => $input['product_id']]);
        
        if ($productCheck->rowCount() === 0) {
            throw new Exception("Product not found", 404);
        }
        
        // Check if already in wishlist
        $checkStmt = $db->prepare("SELECT product_id FROM u23770912_wishlist WHERE user_id = :user_id AND product_id = :product_id");
        $checkStmt->execute([
            ':user_id' => $input['user_id'],
            ':product_id' => $input['product_id']
        ]);
        
        if ($checkStmt->rowCount() > 0) {
            throw new Exception("Product already in wishlist", 409);
        }
        
        // Add to wishlist
        $insertStmt = $db->prepare("INSERT INTO u23770912_wishlist (user_id, product_id, add_date) VALUES (:user_id, :product_id, NOW())");
        $insertStmt->execute([
            ':user_id' => $input['user_id'],
            ':product_id' => $input['product_id']
        ]);
        
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Product added to wishlist'],
            'timestamp' => round(microtime(true) * 1000)
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error',
            'details' => $e->getMessage()
        ]);
    } catch (Exception $e) {
        http_response_code($e->getCode());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
    
else if ($input['type'] === 'RemoveFromWishlist') {
    try {
        $required = ['user_id', 'product_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        // Verify API key
        $stmt = $db->prepare("SELECT id FROM u23770912_users WHERE id = :user_id AND api_key = :api_key");
        $stmt->execute([
            ':user_id' => $input['user_id'],
            ':api_key' => $input['apikey']
        ]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception("Invalid API key or user ID", 401);
        }
        
        // Remove from wishlist
        $deleteStmt = $db->prepare("DELETE FROM u23770912_wishlist WHERE user_id = :user_id AND product_id = :product_id");
        $deleteStmt->execute([
            ':user_id' => $input['user_id'],
            ':product_id' => $input['product_id']
        ]);
        
        if ($deleteStmt->rowCount() === 0) {
            throw new Exception("Product not found in wishlist", 404);
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Product removed from wishlist'],
            'timestamp' => round(microtime(true) * 1000)
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error',
            'details' => $e->getMessage()
        ]);
    } catch (Exception $e) {
        http_response_code($e->getCode());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
    
else if ($input['type'] === 'GetWishlist') {
    try {
        $required = ['user_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }

        // Verify API key
        $authStmt = $db->prepare("SELECT id FROM u23770912_users WHERE id = :user_id AND api_key = :api_key");
        $authStmt->execute([
            ':user_id' => $input['user_id'],
            ':api_key' => $input['apikey']
        ]);

        if ($authStmt->rowCount() === 0) {
            throw new Exception("Invalid API key or user ID", 401);
        }

        // Get wishlist items
        $query = "
            SELECT 
                p.product_id,
                p.text AS name,
                p.description, 
                w.add_date
            FROM u23770912_wishlist w
            INNER JOIN u23770912_products p ON w.product_id = p.product_id
            WHERE w.user_id = :user_id
            ORDER BY w.add_date DESC
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([':user_id' => $input['user_id']]);
        
        $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'count' => count($wishlist),
                'items' => $wishlist
            ],
            'timestamp' => round(microtime(true) * 1000)
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error',
            'details' => $e->getMessage()
        ]);
    } catch (Exception $e) {
        http_response_code($e->getCode());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    exit;
}


else {
    http_response_code(400);
    echo json_encode(["status"=> "error","message"=> "Invalid Request Type..."]);
    exit;
}



?>
