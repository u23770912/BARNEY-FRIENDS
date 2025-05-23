
<?php
session_start();
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//ini_set('display_errors', 0);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header("Content-Type: application/json");

include_once "COS216/ASS5/php/config.php";
include_once "COS216/ASS5/php/user.php";
include_once "COS216/ASS5/php/review.php";

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
        $user_type = trim($input['user_type'] ?? "");

        if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($user_type)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Missing required fields"]);
            exit;
        }

        $user = new User($db);
        $result = $user->register($name, $surname, $email, $password, $user_type);

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
                    "user_type"   => $result['usertype'],
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
                    "message" => "product_id, rating (1–5) and non-empty text are required"
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
    echo json_encode(["status"=> "error","message"=> "Invalid Request Type"]);
    exit;
}



?>
