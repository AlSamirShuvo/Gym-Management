<?php
// Start the session
session_start();

// Destroy the session to log out the user
session_destroy();
session_start();

// Redirect to the login page after logout
header("Location: login2.php");
exit;
?>
