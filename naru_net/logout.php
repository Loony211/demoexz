<?php
<<<<<<< HEAD
session_start();
session_unset();
session_destroy();
header("Location: index.php");
=======
session_start(); // Ensure session is started to destroy it
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to login page after logout
>>>>>>> 6b7f57f35a2cad7bff364e68e715a2b13b175b73
exit();
?>