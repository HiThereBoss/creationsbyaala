<!-- 
  Emre Bozkurt, 400555259

  Date created: 2025-04-15

  The landing page for the admin panel, only accessible to users with admin access.
  It allows the admin to add and edit products, and view current orders.
  The page includes links to the respective actions and loads the content 
  dynamically based on the selected action.
-->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
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

    <div id="admin-container">
        <h2>Admin Panel</h2>
        <div id="admin-actions">
            <a href="?action=add_product">Add Product</a>
            <a href="?action=edit_products">Edit Products</a>
            <a href="?action=view_orders">View Orders</a>
        </div>
        <div id="admin-content">
            <!-- Admin content will be loaded here based on the selected action -->
            <?php
            // Include the appropriate admin action file based on the user's selection
            if (isset($_GET['action'])) {
                $action = $_GET['action'];
                switch ($action) {
                    case 'add_product':
                        include 'add_product.php';

                        if (isset($_GET['success'])) {
                            echo "<p class='success-message'>Product added successfully!</p>";
                        }

                        break;
                    case 'view_orders':
                        include 'view_orders.php';
                        break;
                    case 'edit_products':
                        include 'edit_products.php';
                        break;
                    default:
                        echo "<p>Invalid action selected.</p>";
                }
            } else {
                echo "<p>Please select an action from the menu.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>