<?php
class Review
{
    private $conn;
    private $userId;

    public function __construct(PDO $dbConnection, int $currentUserId) {
        $this->conn   = $dbConnection;
        $this->userId = $currentUserId;
    }

    public function create(int $productId, int $rating, string $text): array{
        $checkSql ="Select Available From Products Where Product_id = :pid"
    }
}

?>