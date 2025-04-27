<?php

// Emre Bozkurt, 400555259

// Date created: 2025-04-15

// Auxilary script to add a new product to the database, including multiple images.
// It handles the file upload and database insertion, and redirects to the add 
// product page with a success message.
// Uploaded images are stored in the database under assets/images/product-images/,
// and their paths are stored in a database table.

// filepath: c:\xampp\htdocs\creationsbyaala\admin\upload_product.php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
    header("Location: ../");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    include '../connect.php';

    // Retrieve form data
    $productName = $_POST['product_name'] ?? '';
    $productCategory = $_POST['product_category'] ?? '';
    $quickDescription = $_POST['quick_description'] ?? '';
    $fullDescription = $_POST['product_description'] ?? '';
    $productPrice = $_POST['product_price'] ?? '';
    $processingTime = $_POST['processing_time'] ?? '';
    $imagePaths = []; // Array to store image paths

    // Handle multiple file uploads

    if (!isset($_FILES['product_images']) || $_FILES['product_images']['error'][0] === UPLOAD_ERR_NO_FILE) {
        $imagePaths[] = 'assets/images/cake.png';
    } else {
        $uploadDir = '../assets/images/product-images/'; // Directory to save the images

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Loop through each uploaded file
        foreach ($_FILES['product_images']['name'] as $key => $fileName) {
            $tmpName = $_FILES['product_images']['tmp_name'][$key];
            $error = $_FILES['product_images']['error'][$key];

            if ($error === UPLOAD_ERR_OK) {
                $uniqueFileName = uniqid() . '-' . basename($fileName); // Generate a unique file name
                $targetFilePath = $uploadDir . $uniqueFileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($tmpName, $targetFilePath)) {
                    $imagePaths[] = 'assets/images/product-images/' . $uniqueFileName; // Save relative path for database
                } else {
                    echo "<p>Failed to upload image: $fileName</p>";
                }
            } else {
                echo "<p>Error uploading file: $fileName</p>";
            }
        }
    }

    // Insert product into the database
    if ($productName && $productCategory && $quickDescription && $fullDescription && $productPrice && $processingTime && !empty($imagePaths)) {
        $query = "INSERT INTO products (`name`, category, quick_description, `description`, price, processing_time) 
                  VALUES (:name, :category, :quick_description, :full_description, :price, :processing_time)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':name', $productName);
        $stmt->bindParam(':category', $productCategory);
        $stmt->bindParam(':quick_description', $quickDescription);
        $stmt->bindParam(':full_description', $fullDescription);
        $stmt->bindParam(':price', $productPrice);
        $stmt->bindParam(':processing_time', $processingTime);

        if ($stmt->execute()) {
            $productId = $dbh->lastInsertId(); // Get the ID of the inserted product

            // Insert image paths into a separate table
            $imageQuery = "INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)";
            $imageStmt = $dbh->prepare($imageQuery);

            foreach ($imagePaths as $imagePath) {
                $imageStmt->bindParam(':product_id', $productId);
                $imageStmt->bindParam(':image_path', $imagePath);
                $imageStmt->execute();
            }

            header('Location: index.php?action=add_product&success=1'); // Redirect to the add product page with success message
        } else {
            echo "<p>Failed to add product. Please try again.</p>";
        }
    }
}
