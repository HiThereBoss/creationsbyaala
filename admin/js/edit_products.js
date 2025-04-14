// <?php
// include '../connect.php';

// $query = "SELECT * FROM products";
// $stmt = $dbh->prepare($query);
// $stmt->execute();

// $products = $stmt->fetchAll();

// ?>
// <script src="./js/edit_products.js"></script>
// <div class="action-container" id="edit-products-container">
//     <h3>Edit Products</h3>

//     <table id="products-table">
//         <thead>
//             <tr>
//                 <th>Product ID</th>
//                 <th>Name</th>
//                 <th>Category</th>
//                 <th>Available</th>
//                 <th>Price</th>
//                 <th>Processing Time</th>
//                 <th>Actions</th>
//             </tr>
//         </thead>
//         <tbody>
//             <?php foreach ($products as $product): ?>
//                 <tr data-product-id="<?= $product['product_id'] ?>">
//                     <td><?= $product['product_id'] ?></td>
//                     <td><?= htmlspecialchars($product['name']) ?></td>
//                     <td><?= htmlspecialchars($product['category']) ?></td>
//                     <td><?= $product['availability'] ? "Yes" : "No" ?></td>
//                     <td>$<?= number_format($product['price'], 2) ?></td>
//                     <td><?= htmlspecialchars($product['processing_time']) ?> hours</td>
//                     <td>
//                         <button class="edit-button">Edit</button>
//                         <button class="delete-button">Delete</button>
//                     </td>
//                 </tr>
//             <?php endforeach; ?>
//         </tbody>
//     </table>
//     <div id="edit-product-modal" class="modal" style="display: none;">
//         <div class="modal-content">
//             <span class="close-button">&times;</span>
//             <h3>Edit Product</h3>
//             <form id="edit-product-form" method="POST" action="update_product.php" enctype="multipart/form-data">
//                 <input type="hidden" name="product_id" id="product-id" value="" />
//                 <div class="input-container">
//                     <label for="edit-product-name">Product Name:</label>
//                     <input type="text" id="edit-product-name" name="product_name" required />

//                     <label for="availability">Available:</label>
//                     <input type="checkbox" id="availability" name="availability" value="1" />

//                     <label for="quick-description">Quick Description:</label>
//                     <input type="text" id="quick-description" name="quick_description" required />

//                     <label for="description">Description:</label>
//                     <textarea id="description" name="description" required></textarea>

//                     <label for="edit-product-category">Category:</label>
//                     <select id="edit-product-category" name="product_category" required>
//                         <option value="cake">Cake</option>
//                         <option value="bouquet">Bouquet</option>
//                     </select>

//                     <label for="edit-product-price">Product Price:</label>
//                     <input type="number" id="edit-product-price" name="product_price" step="0.01" min='0' required />

//                     <label for="edit-processing-time">Processing Time:</label>
//                     <input type="number" id="edit-processing-time" name="processing_time" min='0' required />
//                 </div>

//                 <button type="submit">Update Product</button>
//             </form>
//         </div>
//     </div>
// </div>

window.onload = () => {
    const editButtons = document.querySelectorAll('.edit-button');
    const deleteButtons = document.querySelectorAll('.delete-button');
    const modal = document.getElementById('edit-product-modal');
    const closeButton = document.querySelector('.close-button');
    const form = document.getElementById('edit-product-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission
    
        const formData = new FormData(this); // Collect form data
    
        fetch('update_product.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the product.');
            });
    });


    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const row = event.target.closest('tr');
            const productId = row.getAttribute('data-product-id');
            
            const url = '../categories/getProduct.php?productId=' + productId;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error); // Show error message
                        return;
                    }
                    const product = data;
                    document.getElementById('product-id').value = productId;
                    document.getElementById('edit-product-name').value = product['name'];
                    document.getElementById('availability').checked = product['availability'] == 1;
                    document.getElementById('quick-description').value = product['quick_description'];
                    document.getElementById('description').value = product['description'];
                    document.getElementById('edit-product-category').value = product['category']
                    document.getElementById('edit-product-price').value = product['price'];
                    document.getElementById('edit-processing-time').value = product['processing_time'];
                });

            modal.style.display = 'block';
        });
    });

    closeButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const row = event.target.closest('tr');
            const productId = row.getAttribute('data-product-id');

            if (confirm(`Are you sure you want to delete product ID ${productId}?`))
                deleteProduct(productId); // Call the delete function
        });
    });

    function deleteProduct(productId) {
    
        fetch('delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ product_id: productId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Show success message
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the product.');
            });
    }
}