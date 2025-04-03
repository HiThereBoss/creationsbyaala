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
        
        togglePopup(id); // Show the popup window when clicked

        // Popup logic, use AJAX to fetch the product details
    });


  });
};

function togglePopup(id){
  const popupForm = document.getElementById("form-container");
  popupForm.style.display = "flex";

  const addToCartBtn = document.getElementById("add-to-cart");
  addToCartBtn.addEventListener("click", () => {
    addToCartBtn.removeEventListener("click", arguments.callee);
    addToCart(id); // Call the function to add the product to the cart
  });

  const url = '../getProduct.php?id='+id;
  fetch(url)
  .then(response => response.json())
  .then(data => {
    console.log(data);
    // Populate the popup with product details
    document.getElementById("product-name").innerText = data.name;
    document.getElementById("product-price").innerText = "the price: $" + data.price;
    document.getElementById("product-description").innerText = data.description;
  })
  .catch(error => {
    console.error('Error fetching product details:', error);
  });
}

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