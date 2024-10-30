<?php
// ob_start();
// session_start();

// $base_url = "http://localhost/";
// $project_path = "/library";
// $burl = $base_url . $project_path;

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "libraryproject";

// $_SESSION['login'] = (isset($_SESSION['login']) && $_SESSION['login'] == true) ? true : false;

// // Create a new MySQLi connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check for connection errors
// if ($conn->connect_error) {
//     echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
//     exit;
// }



ob_start();
session_start();

// Define base URLs
$base_url = "http://localhost";
$project_path = "/library";
$burl = $base_url . $project_path;

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "libraryproject";

// Check if user is logged in, set login session to false if not
$_SESSION['login'] = isset($_SESSION['login']) && $_SESSION['login'] == true ? true : false;

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    // You can log errors and provide a user-friendly message instead of JSON response in production
    die('Connection failed: ' . $conn->connect_error);
}

// Protect pages (This could be used across your admin/guest pages as well)
function protect_page($required_role) {
    global $burl;
    // If the user is not logged in, redirect to login page
    if (!$_SESSION['login']) {
        header('Location: ' . $burl . '/admin/auth/login.php');
        exit();
    }

    // Check if the user role matches the required role
    if ($_SESSION['auth'] != $required_role) {
        // Redirect to a "no permission" or "access denied" page
        header('Location: ' . $burl . '/no-permission.php');
        exit();
    }
}





?>
