<!-- 
  Sreyo Biswas,
  Emre Bozkurt, 400555259

  Date created: 2025-03-15

  Fetches all products from the database, and displays them in a grid format.
  Each product has a thumbnail image, name, and quick description. 
  Clicking on a product opens a modal with more details, and the option to add it to the cart.
  The modal also displays all images associated with the product.
-->

<?php

include '../../connect.php';

session_start(); // Start the session

// Select all cake products from the database
$query = "SELECT * FROM products WHERE category = 'cake' AND availability = 1";
$stmt = $dbh->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll();

if (!$products) {
  echo "<h1>No products found</h1>";
  exit;
}

// Fetch images for each product
foreach ($products as &$product) {
  $query = "SELECT image_path FROM product_images WHERE product_id = :product_id";
  $stmt = $dbh->prepare($query);
  $stmt->bindParam(':product_id', $product['product_id'], PDO::PARAM_INT);
  $stmt->execute();
  $images = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  $product['images'] = $images; // Store images in the product array
}
unset($product); // Unset the reference to avoid issues later
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cakes</title>
  <link rel="stylesheet" href="../../css/style.css" />
  <link rel="stylesheet" href="../../css/categories.css" />
  <script src="../../js/categories.js"></script>
</head>

<body>
  <header id="header-container">
    <div class="logo-container">
      <nav class="logo">
        <img src="../../assets/images/cake.png" alt="" />
      </nav>
    </div>

    <nav class="header-nav">
      <div id="left-nav">
        <a href="../../">home</a>
        <a href="./">cakes & bouquets</a>
      </div>

      <div id="right-nav">
        <!-- Logged in state handling -->
        <?php if (isset($_SESSION['access']) && $_SESSION['access'] === 'admin'): ?>
          <a href="../../admin">admin</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['userid'])): ?>
          <a href="../../orders/">orders</a>
          <a href="../../logout">logout</a>
        <?php else: ?>
          <a href="../../login">login</a>
        <?php endif; ?>
        <a id="cart-icon-container" href="../../cart"><svg
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

  <div class="main-container">
    <div class="products-container">
      <?php

      foreach ($products as $product) {
        echo "<div class='product' id='{$product['product_id']}'>";
        echo "<div class='image-container'>";

        if (empty($product['images'])) {
          $product['images'][0] = 'assets/images/cake.png'; // Default image if none found
        }
        // User the first image for the product
        echo "<img src='../../{$product['images'][0]}' alt='Cake' />";
        echo "</div>";
        echo "<div class='info-container'>";
        echo "<h3>{$product['name']}</h3>";
        echo "<p>{$product['quick_description']}</p>";
        echo "</div>";
        echo "</div>";
      }

      ?>

    </div>
  </div>

  <div id="product-modal" class="modal" style="display: none;">
    <div class="modal-content">
      <span id="close-button">&times;</span>
      <div id="thumbnail-container">
        <div id="thumbnail-images">
          <!-- Thumbnail images will be inserted here dynamically -->
        </div>
      </div>

      <div id="selected-image-container">
        <img id="selected-image" src="" alt="Cake" />
      </div>


      <div id="text-container">
        <h3 id="product-name">Product Name</h3>
        <p id="product-description">Product Description</p>
        <p id="product-price">Price: $XX.XX</p>
        <button id="add-to-cart">Add to Cart</button>
      </div>
    </div>
  </div>
</body>

</html>