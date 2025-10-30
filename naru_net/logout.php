<?php
session_start(); // Ensure session is started to destroy it
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to login page after logout
exit();
?>