<?php
include($_SERVER['DOCUMENT_ROOT']."/library/config.php"); // Include your configuration file

session_start(); // Ensure the session is started

// Set a logout success message
$_SESSION['message'] = [
    'status' => 'warning',
    'sms' => 'ចាកចេញ បានជោគជ័យ', // "Successfully logged out"
];

// Clear session variables
unset($_SESSION['orders']); // Clear the cart if it exists
unset($_SESSION['user_id']); // Clear user ID
$_SESSION['login'] = false; // Set login status to false
$_SESSION['auth'] = 0; // Reset authentication status

// Destroy the session (optional, depending on your needs)
session_destroy(); 

// Redirect to the login page
header('Location: ' . $burl . '/admin/auth/login.php');
exit(); // Ensure no further code is executed
?>
