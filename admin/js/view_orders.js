// <?php

// if (!isset($_SESSION)) {
//     session_start();
// }

// if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
//     header("Location: ../");
//     exit();
// }

// include '../connect.php';

// $query = "SELECT * FROM orders";
// $stmt = $dbh->prepare($query);
// $stmt->execute();

// $orders = $stmt->fetchAll();

// ?>
// <script src="./js/view_orders.js"></script>
// <div class="action-container" id="view-orders-container">
//     <h3>View Orders</h3>

//     <table id="orders-table">
//         <thead>
//             <tr>
//                 <th>Order ID</th>
//                 <th>User ID</th>
//                 <th>Paid</th>
//                 <th>Purchase Date</th>
//             </tr>
//         </thead>
//         <tbody>
//             <?php foreach ($orders as $order): ?>
//                 <tr data-order-id="<?= $order['order_id'] ?>">
//                     <td><?= $order['order_id'] ?></td>
//                     <td><?= htmlspecialchars($order['userid']) ?></td>
//                     <td>$<?= number_format($order['purchase_price'], 2) ?></td>
//                     <td><?= htmlspecialchars($order['purchase_date']) ?> hours</td>
//                     <td>
//                         <button class="view-button">View</button>
//                     </td>
//                 </tr>
//             <?php endforeach; ?>
//         </tbody>
//     </table>
//     <div id="view-order-modal" class="modal" style="display: none;">
//         <div class="modal-content">
//             <span class="close-button">&times;</span>
//             <h3>Order Details</h3>
//             <div id="order-items-container">
//                 <table id="order-items-table">
//                     <thead>
//                         <tr>
//                             <th>Product ID</th>
//                             <th>Name</th>
//                             <th>Category</th>
//                             <th>Price</th>
//                             <th>Processing Time</th>
//                         </tr>
//                     </thead>
//                     <tbody id="order-items-body">
//                         <!-- Order items will be populated here via JavaScript -->
//                     </tbody>
//                 </table>
//                 <div id="order-total-container">
//                     <h4>Total: $<span id="order-total"></span></h4>
//                 </div>
//             </div>
//         </div>
//     </div>
// </div>

window.onload = () => {
    const viewButtons = document.querySelectorAll(".view-button");
    const modal = document.getElementById("view-order-modal");
    const closeBtn = document.querySelector(".close-button");
    
    viewButtons.forEach((button) => {
        button.addEventListener("click", () => {
        const orderRow = button.closest("tr");
        const orderId = orderRow.dataset.orderId;
    
        togglePopup(orderId);
        });
    });
    
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none"; // Hide the popup when the close button is clicked
    });
}

function togglePopup(orderId) {
    const modal = document.getElementById("view-order-modal");
    modal.style.display = "flex";
    
    // Add event listener to close the modal when clicking outside of it
    window.addEventListener("click", (event) => {
        window.removeEventListener("click", arguments.callee); // Remove the event listener after it's executed once
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
    
    // Fetch order details and populate the modal
    fetchOrderDetails(orderId);
}

function fetchOrderDetails(orderId) {
    const orderItemsBody = document.getElementById("order-items-body");
    const orderTotal = document.getElementById("order-total");
    
    // Clear previous order items
    orderItemsBody.innerHTML = "";
    
    // Fetch order details from the server
    fetch(`get_order_details.php?order_id=${orderId}`)
        .then((response) => response.json())
        .then((data) => {
            data.items.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.product_id}</td>
                    <td>${item.name}</td>
                    <td>${item.category}</td>
                    <td>$${parseFloat(item.price).toFixed(2)}</td>
                    <td>${item.processing_time} hours</td>
                `;
                orderItemsBody.appendChild(row);
            });
            orderTotal.innerText = parseFloat(data.total).toFixed(2);
        })
        .catch((error) => {
            console.error("Error fetching order details:", error);
        });
}