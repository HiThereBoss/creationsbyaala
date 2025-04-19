<?php

session_start();

if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
    header("Location: ../");
    exit();
}

include '../connect.php';

$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if (!$orderId) {
    echo json_encode(['error' => 'Invalid order ID']);
    exit;
}

$query = "SELECT * FROM orders WHERE order_id = :orderId";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    echo json_encode(['error' => 'Order not found']);
    exit;
}
$total = $order['purchase_price'];

$query = "SELECT * FROM order_items WHERE order_id = :orderId";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
$stmt->execute();

$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$orderItems) {
    echo json_encode(['error' => 'No items found for this order']);
    exit;
}

foreach ($orderItems as &$item) {
    $query = "SELECT `name`, `category`, `price`, `processing_time` FROM products WHERE product_id = :productId";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':productId', $item['product_id'], PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        $item['name'] = htmlspecialchars($product['name']);
        $item['category'] = htmlspecialchars($product['category']);
        $item['price'] = number_format($product['price'], 2, '.', '');
        $item['processing_time'] = htmlspecialchars($product['processing_time']);
    }
}


$response = [
    'items' => $orderItems,
    'total' => $total,
];

echo json_encode($response);
?>