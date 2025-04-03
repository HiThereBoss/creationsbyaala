<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CreationsByAala - Purchase</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/purchase.css">
    <script defer src="../js/purchase.js"></script>
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

    <h1>Purchase</h1>

    <form id="purchaseForm" action="../receipt/">
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
    </form>
</body>

</html>