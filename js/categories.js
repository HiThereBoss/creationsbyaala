window.onload = () => {
  
  // Support for sequential fade in animation based off HTML order
  // OnClick handling
  const products = document.querySelectorAll(".product");
  products.forEach((product, index) => {
    product.style.setProperty("--index", index); // Dynamically set the index
    product.classList.add("animate"); // Add the animation class after window load so the whole animation can be seen

    product.addEventListener("click", () => {
        console.log("Product clicked");
        // Popup logic, use AJAX to fetch the product details
    });


  });
};
