<!-- 
  Emre Bozkurt, 400555259

  Date created: 2025-03-15

  Creates a new user in the database and logs them in automatically.
  It handles the registration process, including password hashing and session management.
-->
<?php

header('Content-Type: application/json');

include "../../connect.php";
session_start();

$userid = filter_input(INPUT_GET, 'userid');
$password = filter_input(INPUT_GET, 'password');
$remember = filter_input(INPUT_GET, 'remember', FILTER_VALIDATE_BOOLEAN);

$success = false;
$message = "";

if ($userid && $password) {
    $stmt = $dbh->prepare("INSERT INTO users (userid, password) VALUES (:userid, :password)");
    $stmt->bindParam(':userid', $userid);
    $password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // User created successfully
        $success = true;
        $message = "User registered successfully.";
        $_SESSION['userid'] = $userid;
        
        if ($remember) {
            setcookie('userid', $userid, time() + (86400 * 30), "/"); // 86400 = 1 day
        }
    } else {
        // Failed to create user
        $success = false;
        $message = "Failed to register user. Please try again.";
    }
}
else {
    $success = false;
    $message = "Please enter a valid username and password.";
}

echo json_encode([
    'success' => $success,
    'message' => $message,
    'remember' => $remember
]);

?>