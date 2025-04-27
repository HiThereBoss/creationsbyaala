<?php

// Emre Bozkurt, 400555259

// Date created: 2025-03-15

// Removes a product from the user's cart stored in the session.

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
unset($_SESSION['cart'][$uid]);

echo json_encode("Product removed from cart successfully");