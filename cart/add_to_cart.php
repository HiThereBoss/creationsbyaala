<?php

session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode($id);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Append the product ID to the cart
$_SESSION['cart'][uniqid()] = $id;

echo json_encode("Product added to cart successfully");

?>
