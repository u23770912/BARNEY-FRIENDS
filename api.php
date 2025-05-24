<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . '/ASS5/php/config.php';
require_once __DIR__ . '/ASS5/php/user.php';
require_once __DIR__ . '/ASS5/php/review.php';

// Initialize database connection using PDO
$database = Database::getInstance();
$db = $database->getConnection();

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
        // ===== Add to Wishlist =====
        $required = ['user_id', 'product_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        // Verify API key - Using PDO
        $stmt = $db->prepare("SELECT id FROM users WHERE id = ? AND api_key = ?");
        $stmt->execute([$input['user_id'], $input['apikey']]);
        if ($stmt->rowCount() === 0) {
            throw new Exception("Invalid API key or user ID", 401);
        }
        
        // Check if product exists - Using PDO
        $productCheck = $db->prepare("SELECT product_id FROM product WHERE product_id = ?");
        $productCheck->execute([$input['product_id']]);
        if ($productCheck->rowCount() === 0) {
            throw new Exception("Product not found", 404);
        }
        
        // Check if already in wishlist - Using PDO
        $checkStmt = $db->prepare("SELECT product_id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $checkStmt->execute([$input['user_id'], $input['product_id']]);
        if ($checkStmt->rowCount() > 0) {
            throw new Exception("Product already in wishlist", 409);
        }
        
        // Add to wishlist - Using PDO
        $insertStmt = $db->prepare("INSERT INTO wishlist (user_id, product_id, add_date) VALUES (?, ?, NOW())");
        $insertStmt->execute([$input['user_id'], $input['product_id']]);
        
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Product added to wishlist'],
            'timestamp' => round(microtime(true) * 1000)
        ]);
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => round(microtime(true) * 1000)
        ]);
    }
} 
    
else if ($input['type'] === 'RemoveFromWishlist') {
    try {
        // ===== Remove from Wishlist =====
        $required = ['user_id', 'product_id', 'apikey'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                throw new Exception("Missing field: $field", 400);
            }
        }
        
        // Verify API key - Using PDO
        $stmt = $db->prepare("SELECT id FROM users WHERE id = ? AND api_key = ?");
        $stmt->execute([$input['user_id'], $input['apikey']]);
        if ($stmt->rowCount() === 0) {
            throw new Exception("Invalid API key or user ID", 401);
        }
        
        // Remove from wishlist - Using PDO
        $stmt = $db->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$input['user_id'], $input['product_id']]);
        if ($stmt->rowCount() === 0) {
            throw new Exception("Product not found in wishlist", 404);
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => ['message' => 'Product removed from wishlist'],
            'timestamp' => round(microtime(true) * 1000)
        ]);
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => round(microtime(true) * 1000)
        ]);
    }
}
    
else if ($input['type'] === 'GetWishlist') {
    try {
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

        // Verify API key - Using PDO
        $authStmt = $db->prepare("SELECT id FROM users WHERE id = ? AND api_key = ?");
        $authStmt->execute([$input['user_id'], $input['apikey']]);
        
        if ($authStmt->rowCount() === 0) {
            error_log("[GetWishlist] Invalid credentials for user_id: " . $input['user_id']);
            throw new Exception("Invalid API key or user ID", 401);
        }
        
        error_log("[GetWishlist] Authentication successful");

        // Get wishlist items - Using PDO
        $query = "
            SELECT 
                p.product_id,
                p.description, 
                w.add_date
            FROM wishlist w
            INNER JOIN product p ON w.product_id = p.product_id
            WHERE w.user_id = ?
            ORDER BY w.add_date DESC
        ";
        
        error_log("[GetWishlist] Main query: " . str_replace("\n", " ", $query));
        
        $stmt = $db->prepare($query);
        $stmt->execute([$input['user_id']]);
        $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
    } catch (Exception $e) {
        error_log("[GetWishlist] Error: " . $e->getMessage());
        http_response_code($e->getCode() ?: 500);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => round(microtime(true) * 1000)
        ]);
    }
}

else if (in_array($input['type'], ["CreateReview","GetByProduct","GetByUser","UpdateReview","DeleteReview"], true))
{
    // 1) Authenticate
    $userId = $input['user_id'] ?? null;
    $apiKey = $input['apikey']  ?? '';
    if (! $userId) {
        http_response_code(422);
        echo json_encode([
            "status"  => "error",
            "message" => "user_id and apikey are required"
        ]);
        exit;
    }

    $authStmt = $db->prepare("SELECT id FROM users WHERE id = ? AND api_key = ?");
    $authStmt->execute([$userId, $apiKey]);

    if ($authStmt->rowCount() === 0) {
        http_response_code(401);
        echo json_encode([
            "status"  => "error",
            "message" => "Invalid API key or user ID"
        ]);
        exit;
    }

    $svc = new Review($db, (int)$userId);

    // 2) Dispatch
    switch ($input['type']) {
        case "CreateReview":
            $pid    = $input['product_id'] ?? null;
            $rating = $input['rating']    ?? null;
            $text   = trim($input['text'] ?? '');

            if (!$pid || !is_numeric($rating) || $rating < 1 || $rating > 5 || $text === '') {
                http_response_code(422);
                echo json_encode([
                    "status"  => "error",
                    "message" => "product_id, rating (1-5) and non-empty text are required"
                ]);
                exit;
            }

            $res = $svc->create($pid, (int)$rating, $text);
            http_response_code($res['status'] === 'success' ? 201 : 400);
            echo json_encode($res);
            break;

        case "GetByProduct":
            $pid = $input['product_id'] ?? null;
            if (! $pid) {
                http_response_code(422);
                echo json_encode([
                    "status"  => "error",
                    "message" => "product_id is required"
                ]);
                exit;
            }
            echo json_encode($svc->getByProduct((int)$pid));
            break;

        case "GetByUser":
            echo json_encode($svc->getByUser());
            break;

        case "UpdateReview":
            $rid    = $input['review_id'] ?? null;
            $rating = $input['rating']    ?? null;
            $text   = trim($input['text'] ?? '');

            if (! $rid || ! is_numeric($rating) || $rating < 1 || $rating > 5 || $text === '') {
                http_response_code(422);
                echo json_encode([
                    "status"  => "error",
                    "message" => "review_id, rating (1-5) and non-empty text are required"
                ]);
                exit;
            }

            echo json_encode($svc->update((int)$rid, (int)$rating, $text));
            break;

        case "DeleteReview":
            $rid = $input['review_id'] ?? null;
            if (! $rid) {
                http_response_code(422);
                echo json_encode([
                    "status"  => "error",
                    "message" => "review_id is required"
                ]);
                exit;
            }
            echo json_encode($svc->delete((int)$rid));
            break;
    }

    exit;
}
 
else {
    http_response_code(400);
    echo json_encode(["status"=> "error","message"=> "Invalid Request Type..."]);
    exit;
}

?>