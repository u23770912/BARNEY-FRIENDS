
<?php
session_start();
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
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
        // ===== Add to Wishlist =====
        $required = ['user_id', 'product_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        // Verify API key - Updated for new users table
        $stmt = $db->prepare("SELECT id FROM u23770912_users WHERE id = ? AND api_key = ?");
        if ($stmt === false) throw new Exception("Database prepare error: " . $db->error, 500);
        $stmt->bind_param("is", $input['user_id'], $input['apikey']);
        if (!$stmt->execute()) throw new Exception("Database error", 500);
        if ($stmt->get_result()->num_rows === 0) throw new Exception("Invalid API key or user ID", 401);
        
        // Check if product exists - Updated for product table
        $productCheck = $db->prepare("SELECT product_id FROM product WHERE product_id = ?");
        if ($productCheck === false) throw new Exception("Database prepare error: " . $db->error, 500);
        $productCheck->bind_param("i", $input['product_id']);
        $productCheck->execute();
        if ($productCheck->get_result()->num_rows === 0) throw new Exception("Product not found", 404);
        
        // Check if already in wishlist - Updated for new wishlist structure
        $checkStmt = $db->prepare("SELECT product_id FROM wishlist WHERE user_id = ? AND product_id = ?");
        if ($checkStmt === false) throw new Exception("Database prepare error: " . $db->error, 500);
        $checkStmt->bind_param("ii", $input['user_id'], $input['product_id']);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) throw new Exception("Product already in wishlist", 409);
        
        // Add to wishlist - Updated for new wishlist structure
        $insertStmt = $db->prepare("INSERT INTO wishlist (user_id, product_id, add_date) VALUES (?, ?, NOW())");
        if ($insertStmt === false) throw new Exception("Database prepare error: " . $db->error, 500);
        $insertStmt->bind_param("ii", $input['user_id'], $input['product_id']);
        if (!$insertStmt->execute()) throw new Exception("Failed to add to wishlist", 500);
        
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Product added to wishlist'],
            'timestamp' => round(microtime(true) * 1000)
        ]);

} 
    
else if ($input['type'] === 'RemoveFromWishlist') {
        // ===== Remove from Wishlist =====
        $required = ['user_id', 'product_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        // Verify API key - Updated for new users table
        $stmt = $db->prepare("SELECT id FROM u23770912_users WHERE id = ? AND api_key = ?");
        if ($stmt === false) throw new Exception("Database prepare error: " . $db->error, 500);
        $stmt->bind_param("is", $input['user_id'], $input['apikey']);
        if (!$stmt->execute()) throw new Exception("Database error", 500);
        if ($stmt->get_result()->num_rows === 0) throw new Exception("Invalid API key or user ID", 401);
        
        // Remove from wishlist - Updated for new wishlist structure
        $stmt = $db->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        if ($stmt === false) throw new Exception("Database prepare error: " . $db->error, 500);
        $stmt->bind_param("ii", $input['user_id'], $input['product_id']);
        $stmt->execute();
        if ($stmt->affected_rows === 0) throw new Exception("Product not found in wishlist", 404);
        
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Product removed from wishlist'],
            'timestamp' => round(microtime(true) * 1000)
        ]);

}
    
else if ($input['type'] === 'GetWishlist') {
        error_log("[GetWishlist] Starting request processing");
        
        // Validate required fields
        $required = ['user_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                error_log("[GetWishlist] Missing required field: $field");
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        error_log("[GetWishlist] Fields validated");

        // Verify API key - Updated for new users table
        $authQuery = "SELECT id FROM u23770912_users WHERE id = ? AND api_key = ?";
        error_log("[GetWishlist] Auth query: $authQuery");
        
        $authStmt = $db->prepare($authQuery);
        if ($authStmt === false) {
            $error = $db->error;
            error_log("[GetWishlist] Auth prepare failed: " . $error);
            throw new Exception("Database preparation failed: " . $error, 500);
        }
        
        error_log("[GetWishlist] Auth statement prepared");
        
        $authStmt->bind_param("is", $input['user_id'], $input['apikey']);
        if (!$authStmt->execute()) {
            $error = $authStmt->error;
            error_log("[GetWishlist] Auth execute failed: " . $error);
            throw new Exception("Database execution failed: " . $error, 500);
        }
        
        error_log("[GetWishlist] Auth query executed");
        
        if ($authStmt->get_result()->num_rows === 0) {
            error_log("[GetWishlist] Invalid credentials for user_id: " . $input['user_id']);
            throw new Exception("Invalid API key or user ID", 401);
        }
        
        error_log("[GetWishlist] Authentication successful");

        // Get wishlist items - Updated for new table structure
        $query = "
            SELECT 
                p.product_id,
                p.text AS name,
                p.description, 
                w.add_date
            FROM wishlist w
            INNER JOIN product p ON w.product_id = p.product_id
            WHERE w.user_id = ?
            ORDER BY w.add_date DESC
        ";
        
        error_log("[GetWishlist] Main query: " . str_replace("\n", " ", $query));
        
        $stmt = $db->prepare($query);
        if ($stmt === false) {
            $error = $db->error;
            error_log("[GetWishlist] Main prepare failed: " . $error);
            throw new Exception("Database preparation failed: " . $error, 500);
        }
        
        error_log("[GetWishlist] Main statement prepared");
        
        $stmt->bind_param("i", $input['user_id']);
        if (!$stmt->execute()) {
            $error = $stmt->error;
            error_log("[GetWishlist] Main execute failed: " . $error);
            throw new Exception("Database execution failed: " . $error, 500);
        }
        
        error_log("[GetWishlist] Main query executed");
        
        $result = $stmt->get_result();
        $wishlist = [];
        
        while ($row = $result->fetch_assoc()) {
            $wishlist[] = $row;
        }
        
        error_log("[GetWishlist] Found " . count($wishlist) . " items");
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'count' => count($wishlist),
                'items' => $wishlist
            ],
            'timestamp' => round(microtime(true) * 1000)
        ]);
        
        error_log("[GetWishlist] Request completed successfully");
}


else {
    http_response_code(400);
    echo json_encode(["status"=> "error","message"=> "Invalid Request Type..."]);
    exit;
}



?>
