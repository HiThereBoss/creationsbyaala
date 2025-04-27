<?php

// Emre Bozkurt, 400555259
// Sreyo Biswas, 400566085

// Date created: 2025-04-15

// Displays a list of products in a table format, allowing the admin
// to edit or delete products. A modal is used for editing product details, as
// all of its fields can be displayed in the larger form.

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
    header("Location: ../");
    exit();
}

include '../connect.php';

$query = "SELECT * FROM products";
$stmt = $dbh->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll();

?>
<script src="./js/edit_products.js"></script>
<div class="action-container" id="edit-products-container">
    <h3>Edit Products</h3>

    <table id="products-table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Available</th>
                <th>Price</th>
                <th>Processing Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr data-product-id="<?= $product['product_id'] ?>">
                    <td><?= $product['product_id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                    <td><?= $product['availability'] ? "Yes" : "No" ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['processing_time']) ?> hours</td>
                    <td>
                        <button class="edit-button">Edit</button>
                        <button class="delete-button">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="edit-product-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Edit Product</h3>
            <form id="edit-product-form" method="POST" action="update_product.php" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="product-id" value="" />
                <div class="input-container">
                    <label for="edit-product-name">Product Name:</label>
                    <input type="text" id="edit-product-name" name="product_name" required />

                    <div id="availability-container">
                        <label for="availability">Available:</label>
                        <input type="checkbox" id="availability" name="availability" value="1" />
                    </div>
                    

                    <label for="quick-description">Quick Description:</label>
                    <input type="text" id="quick-description" name="quick_description" required />

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>

                    <label for="edit-product-category">Category:</label>
                    <select id="edit-product-category" name="product_category" required>
                        <option value="cake">Cake</option>
                        <option value="bouquet">Bouquet</option>
                    </select>

                    <label for="edit-product-price">Product Price:</label>
                    <input type="number" id="edit-product-price" name="product_price" step="0.01" min='0' required />

                    <label for="edit-processing-time">Processing Time:</label>
                    <input type="number" id="edit-processing-time" name="processing_time" min='0' required />
                </div>

                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>
</div>