<?php 

include($_SERVER['DOCUMENT_ROOT']."/library/config.php");


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to get user details and role for Admin and Regular Users
    $user = $conn->query("SELECT tblroles.*, tblpermission.PermissionID 
    FROM tblroles
    INNER JOIN tblpermission ON tblroles.PermissionID = tblpermission.PermissionID
    WHERE Username = '$username' AND Password = '$password' AND active = '1'");
        $users = $user->fetch_object();

        if ($users && $password === $users->Password) {
            // Set session data based on role
            $_SESSION['login'] = true;
            $_SESSION['role_id'] = $users->PermissionID; // Store role in session
            $_SESSION['user_id'] = $users->UserID;
            $_SESSION['username'] = $users->Username;
            $_SESSION['auth'] = $users->RoleID;

            // Redirect based on role
            if ($users->PermissionID == 1) { // Admin role
                $_SESSION['message'] = ['status' => 'success', 'sms' => 'Welcome Admin!'];
                header('Location:' . $burl . '/admin/summary/index.php');
            } elseif ($users->PermissionID == 2) { // Regular User
                $_SESSION['message'] = ['status' => 'success', 'sms' => 'Welcome User!'];
                header('Location:' . $burl . '/admin/summary/index.php');
        }
        exit();
    } else {
        // Check if guest exists in tblguest
        $guest = $conn->query("SELECT * FROM tblguest WHERE Username = '$username' AND Password = '$password'");
        $guests = $guest->fetch_object();


        if ($guests) { // Use password_verify for guests as well
            // Set session data for guest
            $_SESSION['login'] = true;
            $_SESSION['role_id'] = '3'; // Assuming 3 represents the Guest role
            $_SESSION['user_id'] = $guests->GuestID;
            $_SESSION['username'] = $guests->Username;
            $_SESSION['auth'] = $guests->GuestID;; // Differentiating guests from other users

            // Redirect to guest page
            $_SESSION['message'] = ['status' => 'success', 'sms' => 'Welcome Guest!'];
            header('Location:' . $burl . '/guest/index.php');
            exit();
        } else {
            // Invalid credentials
            $_SESSION['message'] = ['status' => 'error', 'sms' => 'Invalid username or password'];
            header('Location:' . $burl . '/admin/auth/login.php');
            exit();
        }
    }
}

?>
<!-- 966446OUDOM -->
<!-- Oudommef -->