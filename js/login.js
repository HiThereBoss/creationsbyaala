// Emre Bozkurt, 400555259
// Date created: 2025-03-25
//
// Fetches user input from the login form and sends it to the server for verification.
// This includes handling the response and displaying error messages if needed.

window.onload = () => {
    const loginForm = document.getElementById("login-form");
    const errorMessage = document.getElementById("error-message");

    loginForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const rememberMe = document.getElementById("remember-me").checked;

        let url = "verify.php?userid=" + username + "&password=" + password + "&remember=" + rememberMe;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log(data)
                if (data.success) {
                    window.location.href = "../"; // Redirect to home page on success
                } else {
                    errorMessage.textContent = data.message; // Show error message
                }
            })
            .catch(error => {
                console.error("Error:", error);
                errorMessage.textContent = "An error occurred. Please try again."; // Show generic error message
            });
    });
}