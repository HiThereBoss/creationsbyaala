<!-- 
  Sreyo Biswas,
  Emre Bozkurt, 400555259

  Date created: 2025-03-15

  Fetches product information from the database based on the product ID passed 
  as a GET parameter. If the product is found, it returns the product details as JSON.
  If the product is not found, it returns an error message. 
-->
<?php

    include '../connect.php';
    $productId = filter_input(INPUT_GET, 'productId', FILTER_VALIDATE_INT);
    $getImages = filter_input(INPUT_GET, 'getImages', FILTER_VALIDATE_BOOLEAN);

    if($productId) {
        $query = "SELECT * FROM products WHERE product_id = :productId";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        
        $product = $stmt->fetch();
        
        if ($product) {
            if ($getImages) {
                $query = "SELECT image_path FROM product_images WHERE product_id = :productId";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':productId', $productId);
                $stmt->execute();
                $images = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                $product['images'] = $images;
            }
            // Return product details as JSON
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Product not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid product ID']);
    }


    
?>