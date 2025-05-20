<?php
class Order {
    private $conn;
    public function __construct(PDO $dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createOrder($userId, $totalAmount, $status = 'Pending') {
        $sql = "INSERT INTO u23770912_orders (user_id, placed_at, total_amount, status) \
               VALUES (:user_id, NOW(), :total_amount, :status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return (int)$this->conn->lastInsertId();
    }

    public function addOrderItem($orderId, $productId, $quantity) {
        $sql = "INSERT INTO u23770912_order_products (order_id, product_id, quantity) \
               VALUES (:order_id, :product_id, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function placeOrder($userId, array $cartItems, $deliveryDate, $cartModel = null) {
        // Calculate total amount
        $total = 0.0;
        foreach ($cartItems as $item) {
            $total += $item['final_price'] * $item['quantity'];
        }
        // Create order
        $orderId = $this->createOrder($userId, $total);
        if (!$orderId) {
            return false;
        }
        // Add items
        foreach ($cartItems as $item) {
            $this->addOrderItem($orderId, $item['id'], $item['quantity']);
        }
        // Clear cart if provided
        if ($cartModel !== null) {
            $cartModel->clear($userId);
        }

        return $orderId;
    }
    public function clear($userId) {
        $sql = "DELETE FROM u23770912_cart WHERE user_id = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $userId);
        return $stmt->execute();
    }

    public function details(int $orderId): array {
        $sql = "SELECT op.product_id, p.title, p.image_url, op.quantity, op.quantity * p.final_price AS subtotal
                FROM u23770912_order_products op
                JOIN u23770912_products p ON p.id = op.product_id
                WHERE op.order_id = :oid";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':oid', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listByUser(int $userId): array {
        $sql = "SELECT id, placed_at, total_amount, status
                FROM u23770912_orders
                WHERE user_id = :uid
                ORDER BY placed_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row;
        }
        return $orders;
    }

    
}




?>
