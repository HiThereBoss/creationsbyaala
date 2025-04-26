
// Emre Bozkurt, 400555259
// Sreyo Biswas, 400566085
// Date created: 2025-03-25
//
// Handles the cart functionality for the shopping cart page.
// This includes fetching the cart items through AJAX, displaying them, 
// and handling the checkout process.



const cartChangedEvent = new Event("cartChanged");

var cart = [];

window.onload = () => {
    initialize_cart();
    initialize_buttons();

    // Add event listener for cart changed event
    document.addEventListener("cartChanged", () => {
        console.log("Cart has been changed!");
        
        updateOrderSummary();
    });
}

function updateOrderSummary() {
    let totalPrice = 0.0;

    for (const item of Object.values(cart)) {
        if (item) { // Check if item is not null
            totalPrice += parseFloat(item.price); // Assuming item has a price property
        }
    }

    const subtotal = document.getElementById("subtotal");
    subtotal.textContent = "Subtotal: $" + totalPrice.toFixed(2);

    const tax = document.getElementById("tax");
    const taxRate = 0.13; // Assuming a tax rate of 13%
    const taxAmount = totalPrice * taxRate;
    tax.textContent = "Tax: $" + taxAmount.toFixed(2); // Assuming a tax rate of 13%

    const total = document.getElementById("total");
    total.textContent = "Total: $" + (totalPrice + taxAmount).toFixed(2); // Total with tax

    const checkout = document.getElementById("checkout-button");
    if (cart == null || Object.keys(cart).length == 0) {
        checkout.setAttribute("disabled", "true"); // Disable the button if cart is empty
        return;
    }
    checkout.removeAttribute("disabled"); // Enable the button if cart is not empty
}

function initialize_cart() {
    const url = "../cart/get_cart.php";

    fetch(url, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            // Assuming data is an array of cart items
            const cartItemsList = document.getElementById("cart-items-list");
            data.forEach(item => {
                const cartElement = createCartElement(item);
                cart[item.uid] = item // Add item to cart array
                cartItemsList.appendChild(cartElement);
            });

            document.dispatchEvent(cartChangedEvent); // Dispatch event after cart is initialized
        })

}

function createCartElement(item) {
    // <div class="cart-item">
    //     <img src="../assets/images/cake.png" alt="Cake Image">
    //     <div class="item-content">
    //         <div class="item-details">
    //             <h3>Cake Name</h3>
    //             <p>Price: $20.00</p>
    //         </div>
    //         <div class="item-actions">
    //             <button class="remove-item">Remove</button>
    //         </div>
    //     </div>
    // </div>

    // item = [
    //     'id' => $product['product_id'],
    //     'name' => $product['name'],
    //     'category' => $product['category'],
    //     'price' => $product['price'],
    //     'quick_description' => $product['quick_description'],
    //     'processing_time' => $product['processing_time'],
    // ];

    const cartItem = document.createElement("div");
    cartItem.classList.add("cart-item");
    cartItem.id = item.uid;
    cartItem.setAttribute("product-id", item.id);

    const img = document.createElement("img");
    img.src = "../" + item.images[0];
    img.alt = item.name;
    cartItem.appendChild(img);

    const itemContent = document.createElement("div");
    itemContent.classList.add("item-content");
    cartItem.appendChild(itemContent);

    const itemDetails = document.createElement("div"); 
    itemDetails.classList.add("item-details");
    itemContent.appendChild(itemDetails);

    const itemName = document.createElement("h3");
    itemName.textContent = item.name;
    itemDetails.appendChild(itemName);

    const itemPrice = document.createElement("p");
    itemPrice.textContent = "Price: $" + item.price;
    itemDetails.appendChild(itemPrice);

    const itemActions = document.createElement("div");
    itemActions.classList.add("item-actions");
    itemContent.appendChild(itemActions);

    const removeButton = document.createElement("button");
    removeButton.classList.add("remove-item");
    removeButton.textContent = "Remove";
    removeButton.addEventListener("click", () => {
        removeFromCart(item.uid, cartItem);
    });
    itemActions.appendChild(removeButton);

    return cartItem;
}

function removeFromCart(uid, cartItem) {
    const url = "../cart/remove_from_cart.php?uid=" + uid;

    fetch(url, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log(data);

            cartItem.remove(); // Remove the item from the cart UI
            delete cart[uid]; // Remove item from internal cart array

            document.dispatchEvent(cartChangedEvent);
        })
        .catch(error => {
            console.error("Error:", error);
        });
}

function initialize_buttons() {
    const checkout = document.getElementById("checkout-button");

    checkout.addEventListener("click", () => {
        // No need to send post parameters, purchase page must retrieve cart information from session.
        window.location.href = "../purchase/";
    });
}