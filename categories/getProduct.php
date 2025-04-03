<?php

    include '../../connect.php';
    $productId = filter_input(INPUT_GET, 'productId', FILTER_VALIDATE_INT);

    if($productId) {
        $query = "SELECT * FROM products WHERE id = :productId";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        
        $product = $stmt->fetch();
        
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Product not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid product ID']);
    }


    
?>