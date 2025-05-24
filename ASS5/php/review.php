<?php
class Review
{
    private $conn;
    private $userId;

    public function __construct(PDO $dbConnection, int $currentUserId) {
        $this->conn   = $dbConnection;
        $this->userId = $currentUserId;
    }

    public function create(int $productId, int $rating, string $text)
    {
        // 1) Check availability
        $sqlStmt   = "SELECT Available FROM Product WHERE Product_ID = :pid";
        $checkStmt = $this->conn->prepare($sqlStmt);
        $checkStmt->bindParam(':pid', $productId, PDO::PARAM_INT);
        $checkStmt->execute();
        $row = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !(bool)$row['Available']) {
            return [
                "status"  => "error",
                "message" => "Cannot review: product not found or not available"
            ];
        }

        // 2) Insert review
        $newSql = "INSERT INTO Review (Product_ID, User_ID, Rating, Text, review_date)
                   VALUES (:pid, :uid, :rating, :text, :rdate)";
        $stmt = $this->conn->prepare($newSql);
        $stmt->bindParam(':pid',    $productId, PDO::PARAM_INT);
        $stmt->bindParam(':uid',    $this->userId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':text',   $text,   PDO::PARAM_STR);
        $stmt->bindValue(':rdate', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        if ($stmt->execute()) {
            return [
                "status"    => "success",
                "review_id" => $this->conn->lastInsertId(),
                "posted_at" => date('c')
            ];
        }

        return [
            "status"  => "error",
            "message" => "Failed to create review"
        ];
    }

    public function getByProduct(int $productId)
    {
        $sql = "SELECT r.Review_ID, r.Product_ID, r.User_ID, r.Rating, r.Text, r.review_date AS Posted_At
                FROM Review r
                JOIN u23770912_users u ON u.User_ID = r.User_ID
                WHERE r.Product_ID = :pid
                ORDER BY r.Posted_At DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pid', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return [
            "status" => "success",
            "data"   => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function getByUser()
    {
        $sql = "SELECT Review_ID, Product_ID, Rating, Text, Posted_At
                FROM Review
                WHERE User_ID = :uid
                ORDER BY Posted_At DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $this->userId, PDO::PARAM_INT);
        $stmt->execute();

        return [
            "status" => "success",
            "data"   => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function update(int $reviewId, int $rating, string $text)
    {
        $sql = "UPDATE Review
                SET Rating = :rating, Text = :text
                WHERE Review_ID = :rid
                  AND User_ID   = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':text',   $text,   PDO::PARAM_STR);
        $stmt->bindParam(':rid',    $reviewId, PDO::PARAM_INT);
        $stmt->bindParam(':uid',    $this->userId, PDO::PARAM_INT);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return [
                "status"  => "success",
                "message" => "Review updated"
            ];
        }

        return [
            "status"  => "error",
            "message" => "Unauthorized or no change made"
        ];
    }

    public function delete(int $reviewId)
    {
        $sql = "DELETE FROM Review
                WHERE Review_ID = :rid
                  AND User_ID   = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':rid', $reviewId, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $this->userId, PDO::PARAM_INT);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return [
                "status"  => "success",
                "message" => "Review deleted"
            ];
        }

        return [
            "status"  => "error",
            "message" => "Unauthorized or not found"
        ];
    }
}
?>
