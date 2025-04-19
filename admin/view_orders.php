<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['access']) || $_SESSION['access'] !== 'admin') {
    header("Location: ../");
    exit();
}

include '../connect.php';

$query = "SELECT * FROM orders";
$stmt = $dbh->prepare($query);
$stmt->execute();

$orders = $stmt->fetchAll();

?>
<script src="./js/view_orders.js"></script>
<div class="action-container" id="view-orders-container">
    <h3>View Orders</h3>

    <table id="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Paid</th>
                <th>Purchase Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr data-order-id="<?= $order['order_id'] ?>">
                    <td><?= $order['order_id'] ?></td>
                    <td><?= $order['userid'] != NULL ? htmlspecialchars($order['userid']) : "guest" ?></td>
                    <td>$<?= number_format($order['purchase_price'], 2) ?></td>
                    <td><?= htmlspecialchars($order['purchase_date']) ?></td>
                    <td>
                        <button class="view-button">View</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="view-order-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3>Order Details</h3>
            <div id="order-items-container">
                <table id="order-items-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Processing Time</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-body">
                        <!-- Order items will be populated here via JavaScript -->
                    </tbody>
                </table>
                <div id="order-total-container">
                    <h4>Total: $<span id="order-total"></span></h4>
                </div>
            </div>
        </div>
    </div>
</div>