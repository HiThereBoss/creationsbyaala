<?php

// Tiya Jathan, 400589788

// Date created: 2025-04-12

// Generates a copy of the receipt for printing purposes, with slightly
// modified structure.

session_start();

$ok = true;
$message = '';

$orderid = filter_input(INPUT_GET, 'orderid', FILTER_VALIDATE_INT);

if (!$orderid) {
    header('Location: ../');
    exit;
}

include '../connect.php';

$query = "SELECT * FROM orders WHERE order_id = :orderid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':orderid', $orderid, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    $ok = false;
    $message = 'Order not found.';
}

if ((isset($_SESSION['access']) && $_SESSION['access'] !== 'admin') && (isset($_SESSION['userid']) && $_SESSION['userid'] !== $order['userid'])) {
    $ok = false;
    $message = 'You do not have permission to view this order.';
}

if ($ok) {
    $total = $order['purchase_price'];

    $orderId = $order['order_id'];
    $query = "SELECT * FROM order_items WHERE order_id = :order_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    $order['items'] = [];
    foreach ($stmt->fetchAll() as $item) {
        $query = "SELECT * FROM products WHERE product_id = :product_id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch();
        $order['items'][] = $product;
    }

    $subtotal = 0;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/receipt.css">
</head>

<body>
    <div class="print-container">
        <?php if (!$ok): ?>
            <div class="error-message-container">
                <p class="error-message"><?php echo $message; ?></p>
            </div>
        <?php
            echo "</body>";
            echo "</html>";
            exit;
        endif;
        ?>

        <div class="receipt-container">
            <h3>Items Ordered:</h3>
            <div id="order-items"></div>
            <?php foreach ($order['items'] as $item): ?>
                <div class="order-item-container">
                    <div class="order-item-details">
                        <h4><?php echo $item['name']; ?></h4>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                </div>
                <?php $subtotal += $item['price']; ?>
                <div class="order-item-separator"></div>
            <?php endforeach; ?>
            <p><strong>Subtotal:</strong> <span id="total"><?php echo "$$subtotal"; ?></span></p>
            <p><strong>Tax (13%):</strong> <span id="total"><?php $tax = $subtotal * 0.13;
                                                            echo "$" . number_format($tax, 2); ?></span></p>
            <p><strong>Tip:</strong> <span id="total"><?php echo "$" . ($total - $tax - $subtotal); ?></span></p>
            <p><strong>Total:</strong> <span id="total"><?php echo "$$total"; ?></span></p>
        </div>
    </div>

</body>

</html>