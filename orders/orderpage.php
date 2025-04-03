<?php
// orders.php
// This file serves as a visual prototype for the "My Orders" page.
// It currently outputs HTML/CSS/JavaScript, and can later be extended
// to include more advanced PHP server-side logic.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Orders</title>
  <style>
    /* Base styling and color palette */
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #007BFF;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
    /* Container for order cards */
    .orders-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 20px;
    }
    .order-card {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin: 10px;
      padding: 20px;
      width: 300px;
      box-shadow: 0 2px 4px rgb(0, 0, 0);
    }
    .order-card h3 {
      margin-top: 0;
    }
    .order-details {
      margin: 10px 0;
    }
    .order-button {
      background-color: #28a745;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 3px;
      cursor: pointer;
    }
    /* Modal styling for pop-ups */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0, 0, 0);
    }
    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
      border-radius: 5px;
    }
    .close-modal {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    /* Purchase form styling */
    #purchaseFormSection {
      background: #fff;
      border-top: 2px solid #007BFF;
      padding: 20px;
      margin-top: 20px;
    }
    #purchaseForm div {
      margin-bottom: 15px;
    }
    #purchaseForm label {
      display: block;
      margin-bottom: 5px;
    }
    #purchaseForm input {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
    }
    #purchaseForm button {
      background-color: #007BFF;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 3px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <h1>My Orders</h1>
  </header>

  <!-- Container for order cards -->
  <div class="orders-container" id="ordersContainer">
    <!-- Order cards will be generated dynamically -->
  </div>

  <!-- Modal for order details -->
  <div id="modalOrder" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <h2>Order Details</h2>
      <div id="modalContent">
        <!-- Order details loaded from JavaScript -->
      </div>
    </div>
  </div>

  <!-- Purchase form for adding new orders -->
  <section id="purchaseFormSection">
    <h2>Purchase Products</h2>
    <form id="purchaseForm">
      <div>
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" required>
      </div>
      <div>
        <label for="purchaseDate">Date Purchased:</label>
        <input type="date" id="purchaseDate" name="purchaseDate" required>
      </div>
      <div>
        <label for="amountPaid">Amount Paid:</label>
        <input type="number" id="amountPaid" name="amountPaid" step="0.01" required>
      </div>
      <button type="submit">Add Order</button>
    </form>
  </section>

  <script>
    // JavaScript logic for modal and localStorage

    const ordersContainer = document.getElementById('ordersContainer');
    const modal = document.getElementById('modalOrder');
    const modalContent = document.getElementById('modalContent');
    const purchaseForm = document.getElementById('purchaseForm');

    // Open modal to display order details
    function openModal(orderId) {
      // This stub can be extended to retrieve and show detailed order info
      modalContent.innerHTML = '<p>Details for ' + orderId + '</p>';
      modal.style.display = 'block';
    }

    // Close the modal
    function closeModal() {
      modal.style.display = 'none';
    }

    // Add order to localStorage and update display
    function addOrder(order) {
      let orders = JSON.parse(localStorage.getItem('orders')) || [];
      orders.push(order);
      localStorage.setItem('orders', JSON.stringify(orders));
      displayOrders();
    }

    // Render orders from localStorage onto the page
    function displayOrders() {
      let orders = JSON.parse(localStorage.getItem('orders')) || [];
      ordersContainer.innerHTML = '';
      orders.forEach((order, index) => {
        let card = document.createElement('div'); /// Create a new order card
        card.className = 'order-card';
        card.innerHTML = `
          <h3>Order #${index + 1}</h3>
          <div class="order-details">
            <p><strong>Date:</strong> ${order.date}</p>
            <p><strong>Products:</strong> ${order.products}</p>
            <p><strong>Amount:</strong> $${order.amount}</p>
          </div>
          <button class="order-button" onclick="openModal('order${index + 1}')">View Details</button>
        `;
        ordersContainer.appendChild(card);
      });
    }

    // Handle form submission for a new order
    purchaseForm.addEventListener('submit', function(e) { // Prevent default form submission
      e.preventDefault();
      const productName = document.getElementById('productName').value;
      const purchaseDate = document.getElementById('purchaseDate').value;
      const amountPaid = document.getElementById('amountPaid').value;
      
      // Create order object
      const order = { 
        products: productName,
        date: purchaseDate,
        amount: parseFloat(amountPaid).toFixed(2)
      };
      
      addOrder(order);
      purchaseForm.reset(); // Reset the form after submission
    });

    // Load orders when the page is ready
    document.addEventListener('DOMContentLoaded', displayOrders);
  </script>
</body>
</html>
