<?php
// Getting items from cart

session_start();
include '../connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// each item is [(a unique id)] -> productID

$products = [];
foreach ($_SESSION['cart'] as $uid => $pID) {
    $query = "SELECT * FROM products WHERE product_id = :id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $pID);
    $stmt->execute();
    $product = $stmt->fetch();
    if (!$product) {
        continue; // Skip if product not found
    }
    $products[] = [
        'uid' => $uid,
        'id' => $product['product_id'],
        'name' => $product['name'],
        'category' => $product['category'],
        'price' => $product['price'],
        'quick_description' => $product['quick_description'],
        'processing_time' => $product['processing_time'],
    ];
}

// Return the products as a JSON response
echo json_encode($products);