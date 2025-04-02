<?php

session_start();

$uid = filter_input(INPUT_GET, 'uid');

if (!$uid) {
    echo json_encode("Invalid product ID");
    exit;
}

if (!isset($_SESSION['cart'])) {
    echo json_encode("Cart is empty");
    exit;
}

if (!isset($_SESSION['cart'][$uid])) {
    echo json_encode("Product not found in cart");
    exit;
}

// Remove the product from the cart
$_SESSION['cart'][$uid] = null;

echo json_encode("Product removed from cart successfully");