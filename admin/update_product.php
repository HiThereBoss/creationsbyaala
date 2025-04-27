<?php

// Emre Bozkurt, 400555259

// Date created: 2025-04-15

// Updates the existing product based on the passed input, which is taken from
// the edit products modal form. The form details are passed via AJAX to this script.

// filepath: c:\xampp\htdocs\creationsbyaala\admin\update_product.php

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
    // Retrieve and sanitize form data
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $productName = filter_input(INPUT_POST, 'product_name');
    $availability = isset($_POST['availability']) ? 1 : 0; // Checkbox value
    $quickDescription = filter_input(INPUT_POST, 'quick_description');
    $description = filter_input(INPUT_POST, 'description');
    $productCategory = filter_input(INPUT_POST, 'product_category');
    $productPrice = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
    $processingTime = filter_input(INPUT_POST, 'processing_time', FILTER_VALIDATE_INT);

    // Validate required fields
    if (!$productId || !$productName || !$quickDescription || !$description || !$productCategory || !$productPrice || !$processingTime) {
        echo json_encode(['success' => false, 'message' => 'Invalid input. Please fill out all required fields.']);
        exit();
    }

    try {
        // Update the product in the database
        $query = "UPDATE products 
                  SET name = :name, 
                      availability = :availability, 
                      quick_description = :quick_description, 
                      description = :description, 
                      category = :category, 
                      price = :price, 
                      processing_time = :processing_time 
                  WHERE product_id = :product_id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':name', $productName);
        $stmt->bindParam(':availability', $availability, PDO::PARAM_INT);
        $stmt->bindParam(':quick_description', $quickDescription);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $productCategory);
        $stmt->bindParam(':price', $productPrice);
        $stmt->bindParam(':processing_time', $processingTime, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update product.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>