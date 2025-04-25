// Tiya Jathan, 
// Date created: 2025-03-25
//
// Handles the printing process for the receipt page.
// This includes creating a hidden iframe to load the receipt content and trigger the print dialog.

function closePrint() {
  document.body.removeChild(this.__container__);
}

function setPrint() {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}

function printReceipt(orderId) {
  var oHiddFrame = document.createElement("iframe");
  oHiddFrame.onload = setPrint;
  oHiddFrame.style.visibility = "hidden";
  oHiddFrame.style.position = "fixed";
  oHiddFrame.style.right = "0";
  oHiddFrame.style.bottom = "0";
  oHiddFrame.src = "receipt_for_print.php?orderid=" + orderId;
  document.body.appendChild(oHiddFrame);
}
