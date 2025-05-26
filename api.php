<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . '/ASS5/php/config.php';
require_once __DIR__ . '/ASS5/php/user.php'; 
require_once __DIR__ . '/ASS5/php/product.php';
require_once __DIR__ . '/ASS5/php/user.php';
require_once __DIR__ . '/ASS5/php/review.php';


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
                "data" => [
                    "apikey" => $result['user']['apikey'],
                    "userid" => $result['user']['id'],
                    "name"   => $result['user']['name']
                ]
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
                    "apikey" => $result['user']['apikey'],
                    "userid" => $result['user']['id'],
                    "name"   => $result['user']['name']
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
        // Get basic product info
        $query = "
            SELECT 
                p.product_id,
                p.description,
                p.availability AS product_availability,
                b.brand_id,
                b.brand_name,
                i.url_1 AS image_url_1,
                i.url_2 AS image_url_2,
                i.url_3 AS image_url_3
            FROM 
                product p
            JOIN 
                brand b ON p.brand_id = b.brand_id
            LEFT JOIN 
                image i ON p.product_id = i.product_id
            ORDER BY 
                p.product_id
        ";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get all prices with retailer info
        $priceQuery = "
            SELECT 
                pr.product_id,
                pr.price_id,
                pr.retailer_id,
                r.retailer_name,
                pr.price,
                pr.availability
            FROM 
                price pr
            JOIN 
                retailer r ON pr.retailer_id = r.retailer_id
        ";
        $priceStmt = $db->prepare($priceQuery);
        $priceStmt->execute();
        $allPrices = $priceStmt->fetchAll(PDO::FETCH_ASSOC);

        // Organize prices by product_id
        $pricesByProduct = [];
        foreach ($allPrices as $price) {
            $pricesByProduct[$price['product_id']][] = [
                'price_id' => $price['price_id'],
                'retailer_id' => $price['retailer_id'],
                'retailer_name' => $price['retailer_name'],
                'price' => (float)$price['price'],
                'availability' => (int)$price['availability']
            ];
        }

        // Combine products with their prices
        $formattedProducts = array_map(function($product) use ($pricesByProduct) {
            $product['prices'] = $pricesByProduct[$product['product_id']] ?? [];
            
            $product['images'] = array_filter([
                $product['image_url_1'],
                $product['image_url_2'],
                $product['image_url_3']
            ]);
            
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

else if ($input['type'] === 'GetAllProducts') {
    try {
        // 1) Read & sanitize inputs
        $search = trim($input['search'] ?? '');
        $sort   = $input['sort']  ?? 'product_id';
        $order  = strtoupper($input['order'] ?? 'ASC');
        $limit  = isset($input['limit']) ? (int)$input['limit'] : 10;

        // 2) Validate sort field & order
        $allowedSortFields = ['product_id','brand_id','availability','retailer_id'];
        if (!in_array($sort, $allowedSortFields, true)) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Invalid sort field']);
            exit;
        }
        $order = ($order === 'DESC') ? 'DESC' : 'ASC';

        // 3) Build search array for fuzzy matching
        $searchArr = [];
        if ($search !== '') {
            $searchArr['description'] = $search;
        }

        // 4) Fetch products
        $productService = new Product($db);
        $products = $productService->getProducts(
            $searchArr,
            $sort,
            $order,
            $limit,
            ['*'],
            true
        );

        // If no products, return early
        if (empty($products)) {
            echo json_encode(['status'=>'success','products'=>[]]);
            exit;
        }

        // 5) Extract IDs
        $ids = array_column($products, 'product_id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // 6) Batch-fetch images
        $imgStmt = $db->prepare(
          "SELECT * FROM image WHERE product_id IN ($placeholders)"
        );
        $imgStmt->execute($ids);
        $allImages = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
        // group by product_id
        $imagesByProduct = [];
        foreach ($allImages as $img) {
            $imagesByProduct[$img['product_id']][] = $img;
        }

        // 7) Batch-fetch prices
        $priceStmt = $db->prepare(
          "SELECT * FROM price WHERE product_id IN ($placeholders)"
        );
        $priceStmt->execute($ids);
        $allPrices = $priceStmt->fetchAll(PDO::FETCH_ASSOC);
        // group by product_id
        $pricesByProduct = [];
        foreach ($allPrices as $pr) {
            $pricesByProduct[$pr['product_id']][] = $pr;
        }

        // 8) Attach images & prices to each product
        foreach ($products as &$product) {
            $pid = $product['product_id'];
            $product['images'] = $imagesByProduct[$pid] ?? [];
            $product['prices'] = $pricesByProduct[$pid] ?? [];
        }
        unset($product); // break reference

        // 9) Return results
        echo json_encode([
            'status'   => 'success',
            'products' => $products
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

else if ($input['type'] === "GetProduct") {
    $product_id = $input["product_id"];
    $api_key    = $input["apikey"];

    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Missing product_id']);
        exit;
    }

    // 1) Fetch the product
    $stmt = $db->prepare(
      "SELECT product_id, brand_id, description, availability, retailer_id
       FROM product
       WHERE product_id = :pid
       LIMIT 1"
    );
    $stmt->execute([':pid' => $product_id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['status'=>'error','message'=>'Product not found']);
        exit;
    }
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2) Fetch all images for that product
    $imgStmt = $db->prepare(
      "SELECT *
       FROM image
       WHERE product_id = :pid"
    );
    $imgStmt->execute([':pid' => $product_id]);
    $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

    // 3) Fetch all prices for that product
    $priceStmt = $db->prepare(
      "SELECT *
       FROM price
       WHERE product_id = :pid"
    );
    $priceStmt->execute([':pid' => $product_id]);
    $prices = $priceStmt->fetchAll(PDO::FETCH_ASSOC);

    // 4) Return combined response
    echo json_encode([
        'status'   => 'success',
        'product'  => $product,
        'images'   => $images,
        'prices'   => $prices
    ]);
}


else if ($input['type'] === "GetReviews"){
    $product_id = $input["product_id"];
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
    $product_id = $input["product_id"];
    $api_key = $input["apikey"];
    if (!$product_id || !$api_key) {
      http_response_code(400);
      echo json_encode(['status'=>'error','message'=>'Missing product_id or api_key']);
      exit;
    }
    // First lookup user_id by api_key
    $u = $db->prepare("SELECT id FROM users WHERE api_key = :key");
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
