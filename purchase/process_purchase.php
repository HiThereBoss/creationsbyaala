<!-- <form id="purchaseForm" action="process_purchase.php" method="POST">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>

        <label for="card">Card Number:</label>
        <input type="text" id="card" name="card" pattern="\d{16}" required>

        <label for="exp">Expiration Date:</label>
        <input type="month" id="exp" name="exp" required>

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" pattern="\d{3}" required>

        <label for="tip">Tip Percentage:</label>
        <select id="tip" name="tip">
            <option value="0">0%</option>
            <option value="10">10%</option>
            <option value="15">15%</option>
            <option value="20">20%</option>
        </select>

        <button type="submit">Submit</button>
    </form> -->
<?php
// Process the purchase form submission

session_start();

include '../connect.php';

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone');
$card = filter_input(INPUT_POST, 'card');
$exp = filter_input(INPUT_POST, 'exp');
$cvv = filter_input(INPUT_POST, 'cvv');
$tip = filter_input(INPUT_POST, 'tip', FILTER_VALIDATE_INT);

if (!$name || !$email || !$phone || !$card || !$exp || !$cvv || $tip === false) {
    die('Invalid input. Please fill in all fields correctly.');
}

$userid = $_SESSION['userid'] ?? null; // Get the user ID from the session

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die('Your cart is empty. Please add items to your cart before proceeding to checkout.');
}

$items = [];
$total = 0;
foreach ($cart as $uID => $pID) {
    $query = "SELECT * FROM products WHERE product_id = :id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id', $pID);
    if (!$stmt->execute()) {
        die('Error fetching product: ' . implode(', ', $stmt->errorInfo()));
    }
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($cart); // Debugging line to check the item fetched
    $total += $item['price'];
    $items[] = $item;
}

$taxrate = 0.13; // Example tax rate of 13%
$total += $total * ($tip / 100); // Add tip to the total
$total += $total * $taxrate; // Add tax to the total

$total = number_format($total, 2, '.', ''); // Format total to 2 decimal places
$tip = number_format($total * ($tip / 100), 2, '.', ''); // Format tip amount to 2 decimal places

// Insert the order into the database
$query = "INSERT INTO orders (userid, purchase_date, purchase_price) VALUES (:userid, NOW(), :total)";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':userid', $userid);
$stmt->bindParam(':total', $total);
if (!$stmt->execute()) {
    die('Error inserting order: ' . implode(', ', $stmt->errorInfo()));
}
$orderId = $dbh->lastInsertId(); // Get the last inserted order ID

foreach ($items as $item) {
    $query = "INSERT INTO order_items (order_id, product_id, price) VALUES (:order_id, :product_id, :price)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':order_id', $orderId);
    $stmt->bindParam(':product_id', $item['product_id']);
    $stmt->bindParam(':price', $item['price']);
    if (!$stmt->execute()) {
        die('Error inserting order item: ' . implode(', ', $stmt->errorInfo()));
    }
}

// Clear the cart after successful purchase
unset($_SESSION['cart']);

// Redirect to the order confirmation page
header('Location: ../receipt/?orderid=' . $orderId);
