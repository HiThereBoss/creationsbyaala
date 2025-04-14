<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
    header("Location: ../");
    exit();
}
?>

<div class="action-container">
    <h3>Add Product</h3>
    <form id="add-product-form" method="POST" action="upload_product.php" enctype="multipart/form-data">
        <div class="input-container">
            <div id="left">
                <label for="product-name">Product Name:</label>
                <input type="text" id="product-name" name="product_name" required />

                <label for="product-category">Category:</label>
                <select id="product-category" name="product_category" required>
                    <option value="cake">Cake</option>
                    <option value="bouquet">Bouquet</option>
                </select>
                <label for="product-price">Product Price:</label>
                <input type="number" id="product-price" name="product_price" step="0.01" min='0' required />

                <label for="processing-time">Processing Time:</label>
                <input type="number" id="processing-time" name="processing_time" min='0' required />
            </div>

            <div id="right">
                <label for="product-quick-description">Quick Description:</label>
                <input type="text" id="quick-description" name="quick_description" required title="Shown in gallery view only" />

                <label for="product-description">Full Description:</label>
                <textarea id="product-description" name="product_description" required></textarea>

                <label for="product-image">Product Images:</label>
                <input type="file" class="product-images" name="product_images[]" accept=".jpg, .jpeg, .png, .gif" multiple />
            </div>
        </div>
        <button type="submit">Add Product</button>
    </form>
</div>