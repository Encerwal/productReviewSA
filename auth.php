<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Optional: Function to require login and redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}
?>
