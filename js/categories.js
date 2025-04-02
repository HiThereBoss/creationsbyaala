window.onload = () => {
  
  // Support for sequential fade in animation based off HTML order
  // OnClick handling
  const products = document.querySelectorAll(".product");
  products.forEach((product, index) => {
    product.style.setProperty("--index", index); // Dynamically set the index
    product.classList.add("animate"); // Add the animation class after window load so the whole animation can be seen

    product.addEventListener("click", () => {
        console.log("Product clicked");
        
        const id = product.id; // Product ID is stored in the id attribute

        // Temporary for testing, should be done in the popup window
        addToCart(id);

        // Popup logic, use AJAX to fetch the product details
    });


  });
};

function addToCart(productId) {
  // Logic to add the product to the cart
  const url = '../../cart/add_to_cart.php?id='+productId;
  
  fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  })
    .then(response => response.json())
    .then(data => {
      console.log('Success:', data);
      // Update the cart UI if necessary
    })
  
  console.log(`Product ${productId} added to cart`);
}