// Emre Bozkurt, 400555259
// Date created: 2025-04-15
//
// Uses AJAX to fetch order details from the server and display them as 
// rows in a table.
// ALso handles populating the modal pop-up for viewing order details when the
// associated button is clicked.

window.onload = () => {
    const viewButtons = document.querySelectorAll(".view-button");
    const modal = document.getElementById("view-order-modal");
    const closeBtn = document.querySelector(".close-button");
    
    viewButtons.forEach((button) => {
        button.addEventListener("click", () => {
        const orderRow = button.closest("tr");
        const orderId = orderRow.dataset.orderId;
    
        togglePopup(orderId);
        });
    });
    
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none"; // Hide the popup when the close button is clicked
    });
}

function togglePopup(orderId) {
    const modal = document.getElementById("view-order-modal");
    modal.style.display = "flex";
    
    // Add event listener to close the modal when clicking outside of it
    window.addEventListener("click", (event) => {
        window.removeEventListener("click", arguments.callee); // Remove the event listener after it's executed once
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
    
    // Fetch order details and populate the modal
    fetchOrderDetails(orderId);
}

function fetchOrderDetails(orderId) {
    const orderItemsBody = document.getElementById("order-items-body");
    const orderTotal = document.getElementById("order-total");
    
    // Clear previous order items
    orderItemsBody.innerHTML = "";
    
    // Fetch order details from the server
    fetch(`get_order_details.php?order_id=${orderId}`)
        .then((response) => response.json())
        .then((data) => {
            data.items.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.product_id}</td>
                    <td>${item.name}</td>
                    <td>${item.category}</td>
                    <td>$${parseFloat(item.price).toFixed(2)}</td>
                    <td>${item.processing_time} hours</td>
                `;
                orderItemsBody.appendChild(row);
            });
            orderTotal.innerText = parseFloat(data.total).toFixed(2);
        })
        .catch((error) => {
            console.error("Error fetching order details:", error);
        });
}