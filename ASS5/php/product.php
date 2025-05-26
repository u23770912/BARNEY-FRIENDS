<?php
class Product {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    public function getAllProducts(
        string $search   = '',
        string $sortKey  = 'product_id',
        string $order    = 'ASC',
        int    $limit    = 10
    ): array {
        // 1) Sort whitelist
        $sortMap = [
          'product_id'   => 'p.product_id',
          'brand'        => 'b.brand_name',
          'brand_name'   => 'b.brand_name',
          'retailer'     => 'r.retailer_name',
          'retailer_name'=> 'r.retailer_name',
          'availability' => 'p.availability',
          'price'        => 'pr.price'
        ];
        if (!isset($sortMap[$sortKey])) {
            throw new InvalidArgumentException("Invalid sort field: $sortKey");
        }
        $sortExpr = $sortMap[$sortKey];
        $order    = (strtoupper($order) === 'DESC') ? 'DESC' : 'ASC';

        // 2) Base query with joins
        $sql = "
          SELECT
            p.product_id,
            p.brand_id,
            p.description,
            p.availability,
            p.retailer_id,
            pr.price,
            b.brand_name,
            r.retailer_name
          FROM product p
          LEFT JOIN (
            SELECT product_id, MIN(price) AS price
            FROM price
            GROUP BY product_id
          ) pr ON pr.product_id = p.product_id
          LEFT JOIN brand    b ON b.brand_id     = p.brand_id
          LEFT JOIN retailer r ON r.retailer_id  = p.retailer_id
        ";

        $params = [];
        if ($search !== '') {
            $sql .= "
              WHERE (
                p.description LIKE :search
                OR b.brand_name    LIKE :search
                OR r.retailer_name LIKE :search
              )
            ";
            $params[':search'] = "%{$search}%";
        }

        $sql .= " ORDER BY {$sortExpr} {$order} LIMIT :limit";

        // 3) Execute base query
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($products)) {
            return [];
        }

        // 4) Batch-fetch images & prices
        $ids = array_column($products, 'product_id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Images
        $imgStmt = $this->conn->prepare(
          "SELECT * FROM image WHERE product_id IN ($placeholders)"
        );
        $imgStmt->execute($ids);
        $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
        $imgsByProduct = [];
        foreach ($images as $img) {
            $imgsByProduct[$img['product_id']][] = $img;
        }

        // Prices
        $priceStmt = $this->conn->prepare(
          "SELECT * FROM price WHERE product_id IN ($placeholders)"
        );
        $priceStmt->execute($ids);
        $allPrices = $priceStmt->fetchAll(PDO::FETCH_ASSOC);
        $pricesByProduct = [];
        foreach ($allPrices as $pr) {
            $pricesByProduct[$pr['product_id']][] = $pr;
        }

        // 5) Attach to each product
        foreach ($products as &$prod) {
            $pid = $prod['product_id'];
            $prod['images'] = $imgsByProduct[$pid]   ?? [];
            $prod['prices'] = $pricesByProduct[$pid] ?? [];
        }
        unset($prod);

        return $products;
    }
}
