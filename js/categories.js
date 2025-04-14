window.onload = () => {
  // Support for sequential fade in animation based off HTML order
  const products = document.querySelectorAll(".product");
  const modal = document.getElementById("product-modal");
  const closeBtn = document.getElementById("close-button");

  products.forEach((product, index) => {
    product.style.setProperty("--index", index); // Dynamically set the index
    product.classList.add("animate"); // Add the animation class after window load so the whole animation can be seen

    // Add event listener to each product for click event, providing their respective IDs to the popup
    // This will be used to fetch and show the product details in the popup window
    product.addEventListener("click", () => {
      console.log("Product clicked");

      const id = product.id; // Product ID is stored in the id attribute

      togglePopup(id); // Show the popup window when clicked
    });
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none"; // Hide the popup when the close button is clicked
  });
};

function togglePopup(id) {
  const modal = document.getElementById("product-modal");
  modal.style.display = "flex";

  // Add event listener to close the modal when clicking outside of it
  window.addEventListener("click", (event) => {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  });

  // Initialize the "Add to Cart" button
  const addToCartBtn = document.getElementById("add-to-cart");
  addToCartBtn.addEventListener("click", () => {
    addToCartBtn.removeEventListener("click", arguments.callee);
    addToCartBtn.innerText = "Added to Cart"; // Change button text after adding to cart
    addToCartBtn.classList.add("added-to-cart"); // Add a class to change the button style
    addToCartBtn.setAttribute("disabled", "true"); // Disable the button after adding to cart

    setTimeout(() => {
      addToCartBtn.innerText = "Add to Cart"; // Reset button text after 2 seconds
      addToCartBtn.classList.remove("added-to-cart"); // Remove the class to reset style
      addToCartBtn.removeAttribute("disabled"); // Re-enable the button
    }, 2000); // Reset after 2 seconds

    addToCart(id); // Call the function to add the product to the cart
  });

  // Initialize all elements to loading state
  document.getElementById("product-name").innerText = "Loading...";
  document.getElementById("product-price").innerText = "Loading...";
  document.getElementById("product-description").innerText = "Loading...";
  document.getElementById("selected-image").src =
    "../../assets/images/loading.gif"; // Show loading image

  const imageContainer = document.getElementById("thumbnail-images");
  imageContainer.innerHTML = ""; // Clear existing thumbnails

  const url = "../getProduct.php?productId=" + id + "&getImages=true";
  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      // Populate the popup with product details
      document.getElementById("product-name").innerText = data.name;
      document.getElementById("selected-image").src = "../../" + data.images[0];
      document.getElementById("product-price").innerText =
        "the price: $" + data.price;
      document.getElementById("product-description").innerText =
        data.description;

      data.images.forEach((image, index) => {
        const thumbnail = document.createElement("img");
        thumbnail.src = "../../" + image;
        thumbnail.classList.add("thumbnail-image");
        thumbnail.setAttribute("data-index", index); // Store the index in a data attribute
        thumbnail.addEventListener("click", () => {
          document.getElementById("selected-image").src = "../../" + image; // Update the main image when a thumbnail is clicked
        });
        imageContainer.appendChild(thumbnail);
      });
    })
    .catch((error) => {
      console.error("Error fetching product details:", error);
    });
}

function addToCart(productId) {
  // Logic to add the product to the cart
  const url = "../../cart/add_to_cart.php?id=" + productId;

  fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);
      // Update the cart UI if necessary
    });

  console.log(`Product ${productId} added to cart`);
}
