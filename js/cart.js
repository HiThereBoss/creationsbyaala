window.onload = () => {
    initialize_cart();
    initialize_buttons();
}

function initialize_cart() {

}

function initialize_buttons() {
    const checkout = document.getElementById("checkout-button");

    checkout.addEventListener("click", () => {
        // No need to send post parameters, purchase page must retrieve cart information from session.
        window.location.href = "../purchase/";
    });
}