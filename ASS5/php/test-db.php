<?php
require __DIR__ . '/config.php';

header('Content-Type: application/json');

$db = Database::getInstance()->getConnection();

echo json_encode([
    'status' => 'success',
    'msg'    => 'Connected to DB: ' . getenv('DB_NAME')
]);
