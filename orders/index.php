<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: ../login");
    exit();
}

include '../connect.php';

$query = "SELECT * FROM orders WHERE userid = :userid";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':userid', $_SESSION['userid']);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($orders as $key => $order) {
    $orderId = $order['order_id'];
    $query = "SELECT * FROM order_items WHERE order_id = :order_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();
    
    $orders[$key]['items'] = [];
    foreach ($stmt->fetchAll() as $item) {
        $query = "SELECT * FROM products WHERE product_id = :product_id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch();
        $orders[$key]['items'][] = $product;
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/orders.css">
</head>

<body>
    <header id="header-container">
        <div class="logo-container">
            <nav class="logo">
                <img src="../assets/images/cake.png" alt="" />
            </nav>
        </div>

        <nav class="header-nav">
            <div id="left-nav">
                <a href="../">home</a>
                <a href="../categories/cakes/">cakes & bouquets</a>
            </div>

            <div id="right-nav">
                <!-- Logged in state handling -->
                <?php if (isset($_SESSION['access']) && $_SESSION['access'] === 'admin'): ?>
                    <a href="../admin">admin</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['userid'])): ?>
                    <a href="../orders/">orders</a>
                    <a href="../logout">logout</a>
                <?php else: ?>
                    <a href="../login">login</a>
                <?php endif; ?>
                <a id="cart-icon-container" href="../cart"><svg
                        id="cart-icon"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 32 32"
                        fill="currentColor"
                        width="40px"
                        height="40px">
                        <g id="food_cart" data-name="food cart">
                            <path
                                d="M29,17a1,1,0,0,0,1-1V14a1,1,0,0,0-1-1H27v-.79a2.19,2.19,0,0,0-1.09-1.89A6.5,6.5,0,0,0,20,4V3a1,1,0,0,0-2,0V4a6.29,6.29,0,0,0-2.47.72,1,1,0,0,0,.94,1.76A4.51,4.51,0,0,1,18.58,6h.84a4.51,4.51,0,0,1,4.48,4H14.1a4.37,4.37,0,0,1,.47-1.54,1,1,0,0,0-.44-1.35,1,1,0,0,0-1.34.43,6.53,6.53,0,0,0-.7,2.78A2.19,2.19,0,0,0,11,12.21V13H8.72L7,7.68A1,1,0,0,0,6,7H3.33a1,1,0,0,0,0,2h2L7,14.16v5.61a2.24,2.24,0,0,0,1,1.86V26a1,1,0,0,0,1,1h1.05a2.73,2.73,0,0,0-.05.5,2.5,2.5,0,0,0,5,0A2.73,2.73,0,0,0,15,27h7.1a2.73,2.73,0,0,0-.05.5,2.5,2.5,0,0,0,5,0A2.73,2.73,0,0,0,27,27H28a1,1,0,0,0,1-1V21.63a2.24,2.24,0,0,0,1-1.86,1,1,0,1,0-2,0,.23.23,0,0,1-.23.23h-.89a1,1,0,0,0-.92.6.76.76,0,0,1-.46.44,1.07,1.07,0,0,1-1.1-.54,1,1,0,0,0-.93-.5,1,1,0,0,0-.86.61.81.81,0,0,1-.46.43,1.07,1.07,0,0,1-1.1-.53,1,1,0,0,0-.93-.51,1,1,0,0,0-.86.6.83.83,0,0,1-.46.44,1.09,1.09,0,0,1-1.11-.54,1,1,0,0,0-.93-.5,1,1,0,0,0-.86.61.74.74,0,0,1-.46.43,1.07,1.07,0,0,1-1.1-.53,1,1,0,0,0-.92-.51,1,1,0,0,0-.87.6.79.79,0,0,1-.46.44A1.07,1.07,0,0,1,11,20.5a1,1,0,0,0-.87-.5H9.23A.23.23,0,0,1,9,19.77V15H28v1A1,1,0,0,0,29,17ZM13,27.5a.5.5,0,1,1-.5-.5A.5.5,0,0,1,13,27.5Zm12,0a.5.5,0,1,1-.5-.5A.5.5,0,0,1,25,27.5ZM12.62,23a2.67,2.67,0,0,0,.91-.45,3.06,3.06,0,0,0,1.72.55A2.55,2.55,0,0,0,16,23a2.5,2.5,0,0,0,.91-.45,3,3,0,0,0,2.44.45,2.5,2.5,0,0,0,.91-.45,3,3,0,0,0,2.44.45,2.55,2.55,0,0,0,.92-.45A2.94,2.94,0,0,0,26,23a2.42,2.42,0,0,0,1-.51V25H10V22.37A3,3,0,0,0,12.62,23ZM13,13v-.79a.21.21,0,0,1,.21-.21H24.79a.21.21,0,0,1,.21.21V13Z" />
                        </g>
                    </svg>
                </a>
            </div>
        </nav>
    </header>

    <h2>Order History</h2>

    <div id="orders-container">
        <?php 
        // if (empty($orders))
        //     echo "<p>No orders found.</p>";
        // else {
        //     foreach ($orders as $order) {
        //         echo "<div class='order-entry'>";
        //         echo "<div class='order-details'>";
        //         echo "<h3 class='order-title'>Order #" . htmlspecialchars($order['order_id']) . "</h3>";
        //         echo "<p class='order-meta'>Purchase Date: " . htmlspecialchars($order['purchase_date']) . "</p>";
        //         echo "<p class='order-meta'>Total Price: $" . number_format($order['purchase_price'], 2) . "</p>";
        //         echo "</div>";
        //         echo "<div class='order-items'>";
        //         foreach ($order['items'] as $item) {
        //             echo "<div class='order-item'>";
        //             echo "<h4 class='item-name'>" . htmlspecialchars($item['name']) . "</h4>";
        //             echo "<p class='item-meta'>Category: " . htmlspecialchars($item['category']) . "</p>";
        //             echo "<p class='item-meta'>Price: $" . number_format($item['price'], 2) . "</p>";
        //             echo "<p class='item-meta'>Processing Time: " . htmlspecialchars($item['processing_time']) . " hours</p>";
        //             echo "</div>";
        //         }
        //         echo "</div>";
        //         echo "</div>";
        //     }
        // }
        ?>
        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-entry">
                    <div class="order-details">
                        <h3 class="order-title">Order #<?php echo htmlspecialchars($order['order_id']); ?></h3>
                        <p class="order-meta">Purchase Date: <?php echo htmlspecialchars($order['purchase_date']); ?></p>
                        <p class="order-meta">Total Price: $<?php echo number_format($order['purchase_price'], 2); ?></p>
                    </div>
                    <div class="order-items">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="order-item">
                                <h4 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p class="item-meta">Category: <?php echo htmlspecialchars($item['category']); ?></p>
                                <p class="item-meta">Price: $<?php echo number_format($item['price'], 2); ?></p>
                                <p class="item-meta">Processing Time: <?php echo htmlspecialchars($item['processing_time']); ?> hours</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="receipt-button" onclick="window.location.href='../receipt/?orderid=<?php echo $order['order_id']; ?>'">View Receipt</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</body>

</html>