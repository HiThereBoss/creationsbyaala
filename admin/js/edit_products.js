// Emre Bozkurt, 400555259
// Date created: 2025-04-15
//
// Handles deleting and updating products functionality using AJAX requests.
// Also handles the modal form pop-up, populating it with the product's data for editing.

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