
<?php

// Emre Bozkurt, 400555259

// Date created: 2025-03-15

// Adds a product to the user's cart which is an array stored in the session.
// The array stores product IDs as values, and the keys are unique identifiers generated using uniqid().

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
