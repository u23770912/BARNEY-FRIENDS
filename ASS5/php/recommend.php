<?php
class Recommender {
   private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function recommendForUser(int $userId, int $limit = 5): array {
        // 1) Get top 3 preferences
        $prefStmt = $this->conn->prepare("
          SELECT brand_id, price_range
            FROM user_preferences
           WHERE user_id = :uid
        ORDER BY count DESC
           LIMIT 3
        ");
        $prefStmt->execute([':uid'=>$userId]);
        $prefs = $prefStmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($prefs)) {
            return []; // no history yet
        }

        // 2) Build dynamic WHERE clauses
        $where = [];
        $params = [':uid'=>$userId];
        foreach ($prefs as $i => $p) {
            $where[] = "(p.brand_id = :bid{$i} AND
                         CASE
                           WHEN p.price < 50  THEN 'low'
                           WHEN p.price < 150 THEN 'mid'
                           ELSE 'high'
                         END = :range{$i})";
            $params[":bid{$i}"]   = $p['brand_id'];
            $params[":range{$i}"] = $p['price_range'];
        }
        $whereSql = implode(' OR ', $where);

        // 3) Final query
        $sql = "
          SELECT p.*, AVG(r.rating) AS avg_rating
            FROM product p
           LEFT JOIN reviews r ON r.product_id = p.product_id
           WHERE {$whereSql}
           GROUP BY p.product_id
           ORDER BY avg_rating DESC
           LIMIT :lim
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logUserPreference(int $userId, int $brandId, float $price): void {
        // Determine price bucket
        if ($price < 1500)      $range = 'low';
        elseif ($price < 2500) $range = 'mid';
        else                  $range = 'high';

        // Upsert into user_preferences
        $sql = "
          INSERT INTO user_preferences (user_id, brand_id, price_range, count)
          VALUES (:uid, :bid, :range, 1)
          ON DUPLICATE KEY UPDATE count = count + 1
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
          ':uid'   => $userId,
          ':bid'   => $brandId,
          ':range' => $range
        ]);
    }
}
