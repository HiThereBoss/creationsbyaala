<?php

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
        // User exists, now check password
        $password = password_hash($password, PASSWORD_BCRYPT);

        if (password_verify($password, $row['password'])) {
            // Password is correct
            $login = true;
            $_SESSION['userid'] = $userid;
            
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