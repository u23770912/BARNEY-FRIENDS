<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . '/ASS5/php/config.php';
require_once __DIR__ . '/ASS5/php/user.php'; 

// Initialize database connection using PDO
$database = Database::getInstance();
$db = $database->getConnection();

$input = json_decode(file_get_contents("php://input"), true);

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;
$api_key    = $_GET['api_key']    ?? null;

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

else if ($input['type'] === 'GetProducts') {
    try {
        // Get all products with their details
        $query = "
            SELECT 
                p.product_id,
                p.description,
                p.availability AS product_availability,
                b.brand_id,
                b.brand_name,
                i.url_1 AS image_url_1,
                i.url_2 AS image_url_2,
                i.url_3 AS image_url_3,
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'price_id', pr.price_id,
                        'retailer_id', pr.retailer_id,
                        'retailer_name', r.retailer_name,
                        'price', pr.price,
                        'availability', pr.availability
                    )
                ) AS prices
            FROM 
                products p
            JOIN 
                1234_brand b ON p.brand_id = b.brand_id
            LEFT JOIN 
                1234_image i ON p.product_id = i.product_id
            LEFT JOIN 
                1234_price pr ON p.product_id = pr.product_id
            LEFT JOIN 
                retailer r ON pr.retailer_id = r.retailer_id
            GROUP BY 
                p.product_id
            ORDER BY 
                p.product_id
        ";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process the results to format prices as JSON arrays
        $formattedProducts = array_map(function($product) {
            // Convert the GROUP_CONCAT prices string to an array
            $product['prices'] = $product['prices'] ? 
                array_map('json_decode', explode(',', $product['prices'])) : 
                [];
            
            // Create an array of non-null image URLs
            $product['images'] = array_filter([
                $product['image_url_1'],
                $product['image_url_2'],
                $product['image_url_3']
            ]);
            
            // Remove the individual image URL fields
            unset($product['image_url_1'], $product['image_url_2'], $product['image_url_3']);
            
            return $product;
        }, $products);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'count' => count($formattedProducts),
                'products' => $formattedProducts
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
    }
    exit;
}

else if ($input['type'] === "GetProduct") {
    if (!$product_id) {
      http_response_code(400);
      echo json_encode(['status'=>'error','message'=>'Missing product_id']);
      exit;
    }
    $stmt = $db->prepare(
      "SELECT product_id, brand_id, description, availability, retailer_id
       FROM products
       WHERE product_id = :pid
       LIMIT 1"
    );
    $stmt->execute([':pid'=>$product_id]);
    if ($stmt->rowCount()===0) {
      http_response_code(404);
      echo json_encode(['status'=>'error','message'=>'Product not found']);
      exit;
    }
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['status'=>'success','product'=>$product]);  
}

else if ($input['type'] === "GetReviews"){
    if (!$product_id) {
      http_response_code(400);
      echo json_encode(['status'=>'error','message'=>'Missing product_id']);
      exit;
    }
    $stmt = $db->prepare(
      "SELECT r.review_id, r.product_id, r.user_id, r.text, r.rating, r.review_date,
              u.name, u.surname
       FROM reviews r
       JOIN users u ON u.id = r.user_id
       WHERE r.product_id = :pid
       ORDER BY r.review_date DESC"
    );
    $stmt->execute([':pid'=>$product_id]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status'=>'success','reviews'=>$reviews]);
}

else if ($input['type'] === "GetUserReview"){
    if (!$product_id || !$api_key) {
      http_response_code(400);
      echo json_encode(['status'=>'error','message'=>'Missing product_id or api_key']);
      exit;
    }
    // First lookup user_id by api_key
    $u = $db->prepare("SELECT id FROM users WHERE api_key = :key LIMIT 1");
    $u->execute([':key'=>$api_key]);
    if ($u->rowCount()===0) {
      http_response_code(401);
      echo json_encode(['status'=>'error','message'=>'Invalid API key']);
      exit;
    }
    $user_row = $u->fetch(PDO::FETCH_ASSOC);
    // Now fetch their review
    $stmt = $db->prepare(
      "SELECT review_id, product_id, user_id, text, rating, review_date
       FROM reviews
       WHERE product_id = :pid
         AND user_id    = :uid
       LIMIT 1"
    );
    $stmt->execute([
      ':pid'=> $product_id,
      ':uid'=> $user_row['id']
    ]);
    if ($stmt->rowCount()===0) {
      http_response_code(404);
      echo json_encode(['status'=>'error','message'=>'No review found for this user/product']);
      exit;
    }
    $review = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['status'=>'success','review'=>$review]);
}

else if (in_array($input['type'], ["CreateReview","GetByProduct","GetByUser","UpdateReview","DeleteReview"], true))
{
    // 1) Authenticate
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    $auth = new User($db);
    if (!$auth->authenticateApiKey($apiKey)) {
        http_response_code(401);
        echo json_encode([
            "status"  => "error",
            "message" => "Unauthorized"
        ]);
        exit;
    }
    $currentUserId = $auth->getUserId();
    $svc = new Review($db, $currentUserId);

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
