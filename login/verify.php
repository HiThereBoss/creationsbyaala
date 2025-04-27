<?php

// Emre Bozkurt, 400555259

// Date created: 2025-02-15

// Called upon through AJAX from the login page, it verifies the given
// credentials against the database and sets the session variables.

header('Content-Type: application/json');

include "../connect.php";
session_start();

$userid = filter_input(INPUT_GET, 'userid');
$password = filter_input(INPUT_GET, 'password');
$remember = filter_input(INPUT_GET, 'remember', FILTER_VALIDATE_BOOLEAN);

$success = false;
$message = "";
if ($userid && $password) {
    $stmt = $dbh->prepare("SELECT * FROM users WHERE userid = :userid");
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        if (password_verify($password, $row['password'])) {
            // Password is correct
            $success = true;
            $_SESSION['userid'] = $userid;
            $_SESSION['access'] = $row['role'];
            
            if ($remember) {
                setcookie('userid', $userid, time() + (86400 * 30), "/"); // 86400 = 1 day
            }
        } else {
            // Password is incorrect
            $success = false;
            $message = "Incorrect password.";
        }
    }
    else {
        // User does not exist
        $success = false;
        $message = "User does not exist.";
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