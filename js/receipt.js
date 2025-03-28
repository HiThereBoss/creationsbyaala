document.addEventListener("DOMContentLoaded", function () {
    generateReceipt();
});

function generateReceipt() {
    let orderData = JSON.parse(localStorage.getItem("cakeOrder")) || {
        items: [
            { name: "Chocolate Cake", price: 20 },
            { name: "Strawberry Topping", price: 5 }
        ],
        tip: 3
    };

    let subtotal = orderData.items.reduce((acc, item) => acc + item.price, 0);
    let tax = subtotal * 0.10;
    let total = subtotal + tax + orderData.tip;

    let receiptDetails = document.getElementById("receipt-details");
    receiptDetails.innerHTML = orderData.items.map(item => 
        `<p>${item.name}: $${item.price.toFixed(2)}</p>`
    ).join("");

    document.getElementById("subtotal").textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById("tax").textContent = `$${tax.toFixed(2)}`;
    document.getElementById("tip").textContent = `$${orderData.tip.toFixed(2)}`;
    document.getElementById("total").textContent = `$${total.toFixed(2)}`;
}

function printReceipt() {
    window.print();
}

function saveAsPDF() {
    html2canvas(document.getElementById("receipt")).then(canvas => {
        let imgData = canvas.toDataURL("image/png");
        let pdf = new jsPDF();
        pdf.addImage(imgData, "PNG", 10, 10);
        pdf.save("receipt.pdf");
    });
}

function saveAsImage() {
    html2canvas(document.getElementById("receipt")).then(canvas => {
        let link = document.createElement("a");
        link.href = canvas.toDataURL();
        link.download = "receipt.png";
        link.click();
    });
}


