window.onload = () => {
    const registerForm = document.getElementById("login-form");
    const errorMessage = document.getElementById("error-message");

    registerForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;
        const verifyPassword = document.getElementById("verify-password").value;
        const rememberMe = document.getElementById("remember-me").checked;

        if (password !== verifyPassword) {
            errorMessage.textContent = "Passwords do not match!"; // Show error message for password mismatch
            return;
        }

        let url = "add_user.php?userid=" + username + "&password=" + password + "&remember=" + rememberMe;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if (data.success) {
                    window.location.href = "../../"; // Redirect to home page on success
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