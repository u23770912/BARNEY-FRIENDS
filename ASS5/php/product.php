<?php
class Product {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }
    public function getProducts($search = [], $sort = 'id', $order = 'ASC', $limit = 10, $returnFields = ['*'], $fuzzy = true) {
        // If wildcard is provided, select all fields.
        if (in_array('*', $returnFields)) {
            $fields = '*';
        } else {
            $fields = implode(", ", $returnFields);
        }

        $sql = "SELECT $fields FROM product";

        $conditions = [];
        $params = [];
        foreach ($search as $column => $value) {
            if ($fuzzy) {
                $conditions[] = "$column LIKE :$column";
                $params[":$column"] = "%" . $value . "%";
            } else {
                $conditions[] = "$column = :$column";
                $params[":$column"] = $value;
            }
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        
        $sql .= " ORDER BY $sort $order LIMIT :limit";

        try {
            $stmt = $this->conn->prepare($sql);

            foreach ($params as $placeholder => $value) {
                $stmt->bindValue($placeholder, $value);
            }
            $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);

            $stmt->execute();

            $products = [];

            if ($fields === '*') {
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $boundColumns = [];
                foreach ($returnFields as $field) {
                    $boundColumns[$field] = null;
                    $stmt->bindColumn($field, $boundColumns[$field]);
                }
                while ($stmt->fetch(PDO::FETCH_BOUND)) {
                    $row = [];
                    foreach ($boundColumns as $field => $value) {
                        $row[$field] = $value;
                    }
                    $products[] = $row;
                }
            }
            return $products;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
}
?>
