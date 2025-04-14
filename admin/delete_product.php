<?php
// filepath: c:\xampp\htdocs\creationsbyaala\admin\delete_product.php

if (!isset($_SESSION)) {
    session_start();
}

// Ensure the user is an admin
if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
    header("Location: ../");
    exit();
}

// Include the database connection
include '../connect.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate the product ID
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
        exit();
    }

    try {
        // Delete the product from the database
        $query = "DELETE FROM products WHERE product_id = :product_id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            // Reset auto increment
            $query = "SELECT MAX(product_id) FROM products";
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $maxId = $stmt->fetchColumn();
            if ($maxId === false) {
                $maxId = 0; // If no products exist, set maxId to 0
            }

            $query = "ALTER TABLE products AUTO_INCREMENT = :max_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':max_id', $maxId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Delete the product images from the server
            // Fetch the image paths from the database
            $query = "SELECT image_path FROM product_images WHERE product_id = :product_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();

            $images = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            if ($images === false) {
                $images = []; // If no images found, set to empty array
            }

            // Loop through the images and delete them from the server
            foreach ($images as $image) {
                $imagePath = '../' . $image; // Adjust the path to the image file
                if (file_exists($imagePath) && $imagePath !== 'assets/images/cake.png') {
                    unlink($imagePath); // Delete the image file from the server
                }
            }

            // Delete the product images from the database
            $query = "DELETE FROM product_images WHERE product_id = :product_id";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete product images.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>