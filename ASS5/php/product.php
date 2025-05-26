<?php
class Product {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    /**
     * Fetch a page of products with optional fuzzy search across description,
     * brand name and retailer name; sorting; and limit.
     *
     * @param string $search   The search term (empty = no filter)
     * @param string $sortKey  One of: product_id, brand, retailer, availability, price
     * @param string $order    ASC or DESC
     * @param int    $limit    Max rows to return
     * @return array           List of products as associative arrays
     */
    public function getAllProducts(
        string $search   = '',
        string $sortKey  = 'product_id',
        string $order    = 'ASC'

    ): array {
        // 1) Whitelist & map sort keys
        $sortMap = [
          'product_id'   => 'p.product_id',
          'brand'        => 'b.name',
          'retailer'     => 'r.name',
          'availability' => 'p.availability',
          'price'        => 'pr.price'
        ];
        if (! isset($sortMap[$sortKey])) {
            throw new InvalidArgumentException("Invalid sort field: $sortKey");
        }
        $sortExpr = $sortMap[$sortKey];
        $order    = (strtoupper($order) === 'DESC') ? 'DESC' : 'ASC';

        // 2) Build base SQL with joins
        $sql = "
          SELECT
            p.product_id,
            p.brand_id,
            p.description,
            p.availability,
            p.retailer_id,
            pr.price,
            b.brand_name    AS brand_name,
            r.retailer_name    AS retailer_name
          FROM product p
          LEFT JOIN (
            SELECT product_id, MIN(price) AS price
            FROM price pr
            GROUP BY product_id
          ) pr ON pr.product_id = p.product_id
          LEFT JOIN brand b ON b.brand_id    = p.brand_id
          LEFT JOIN retailer r ON r.retailer_id = p.retailer_id
        ";

        $params = [];
        // 3) Add fuzzy search if provided
        if ($search !== '') {
            $sql .= "
              WHERE (
                p.description LIKE :search
                OR b.brand_name       LIKE :search
                OR r.retailer_name    LIKE :search
              )
            ";
            $params[':search'] = "%{$search}%";
        }

        // 4) Append ORDER BY and LIMIT
        $sql .= " ORDER BY {$sortExpr} {$order} LIMIT :limit";

        // 5) Prepare, bind, execute
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->bindValue(PDO::PARAM_INT);
        $stmt->execute();

        // 6) Fetch all products
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
