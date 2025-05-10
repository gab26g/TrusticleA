<?php
// In a real application, this would destroy the session and redirect to login
// For this demo, we'll just redirect to the home page

// Redirect to home page
header("Location: login.php");
exit;
?>