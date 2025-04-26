<!-- 
  Tiya Jathan, 
  Emre Bozkurt, 400555259

  Date created: 2025-03-15

  Logs the user out of the application by destroying the session 
  and redirecting to the home page.
-->
<?php

session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the home page
header("Location: ../");

?>