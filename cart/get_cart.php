<!-- 
  Emre Bozkurt, 400555259

  Date created: 2025-03-15

  Fetches all products from the user's cart stored in the session.
  Each product is fetched from the database using its product ID.
  Returns a properly formatted JSON response with product details.
-->
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

    // Fetch images for the product
    $query = "SELECT image_path FROM product_images WHERE product_id = :product_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':product_id', $pID, PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    $products[] = [
        'uid' => $uid,
        'id' => $product['product_id'],
        'name' => $product['name'],
        'category' => $product['category'],
        'price' => $product['price'],
        'quick_description' => $product['quick_description'],
        'processing_time' => $product['processing_time'],
        'images' => $images,
    ];
}

// Return the products as a JSON response
echo json_encode($products);