<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Check if user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: $burl/admin/auth/login.php");
    exit();
}



// Protect admin folder (only allow PermissionID 1 or 2)
function protect_admin_folder() {
    // Ensure that the user is either an admin (role_id = '1') or a regular user (role_id = '2')
    if (!isset($_SESSION['role_id']) || ($_SESSION['role_id'] !== '1' && $_SESSION['role_id'] !== '2')) {
        // Redirect to a "no permission" page if the user does not have access
        header("Location: /library/admin/auth/login.php");
        exit();
    }
}


// Protect guest folder (only guests with PermissionID = 3)
function protect_guest_folder() {
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] !== '3') { // Guest role
        // Redirect to login page if not a guest
        header("Location: /library/admin/auth/login.php");
        exit();
    }
}
?>
