<?php
include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the hidden input field
    $user_id = $_POST['user_id'];

    // Get the updated data from the form submission
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $gender = trim($_POST['gender']);
    $Dob = trim($_POST['Dob']);
    $workplace = trim($_POST['workplace']);
    $position = trim($_POST['position']);
    $NPhone = trim($_POST['NPhone']);
    $DocType = trim($_POST['DocType']);
    $DocNum = trim($_POST['DocNum']);
    $role = trim($_POST['role']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Secure password hashing
    $photo = $_FILES['photo'];

    // Check if all fields are filled (validation)
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($Dob) || empty($workplace) || empty($position) || empty($NPhone) || empty($DocType) || empty($DocNum)) {
        $_SESSION['message'] = ['status' => 'error', 'sms' => 'Validation Error: All fields are required'];
    } else {
        // Retrieve the current image from the database
        $imageQuery = "SELECT Image FROM tbluser WHERE UserID = $user_id";
        $result = $conn->query($imageQuery);
        $currentImage = $result->fetch_assoc()['Image'];

        // Handle file upload
        if (isset($photo) && $photo['error'] === UPLOAD_ERR_OK) {
            $path = "/library/admin/assets/image/users/" . time() . "_" . basename($photo['name']);
            move_uploaded_file($photo['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . $path);
        } else {
            $path = $currentImage; // Keep existing image if no new image is uploaded
        }

        // Update the user data in the tbluser table
        $query = "UPDATE tbluser SET 
            FirstName = '$firstname', 
            LastName = '$lastname', 
            Gender = '$gender', 
            Dob = '$Dob',
            WorkPlace = '$workplace', 
            Position = '$position', 
            Contacts = '$NPhone', 
            DocumentType = '$DocType', 
            DocNum = '$DocNum', 
            Image = '$path' 
            WHERE UserID = $user_id";

        if ($conn->query($query)) {
            // Also update the user's role in the tblroles table
            $roleQuery = "UPDATE tblroles SET 
                PermissionID = '$role' 
                -- Username = '$username'
                -- Password = '$password'
                WHERE UserID = $user_id";

            if ($conn->query($roleQuery)) {
                $_SESSION['message'] = ['status' => 'success', 'sms' => 'User updated successfully'];
            } else {
                $_SESSION['message'] = ['status' => 'error', 'sms' => 'Error updating user role'];
            }
        } else {
            $_SESSION['message'] = ['status' => 'error', 'sms' => 'Error updating user'];
        }
    }

    // Redirect to index page after updating
    header('Location: ' . $burl . '/admin/users/index.php');

}
?>
