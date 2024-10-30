<?php
// Start output buffering to prevent accidental output
ob_start();

// Include config file (ensure no output is in config.php)
include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve form data and sanitize input
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $gender = trim($_POST['gender']);
    $Dob = trim($_POST['Dob']);
    $workplace = trim($_POST['workplace']);
    $position = trim($_POST['position']);
    $NPhone = trim($_POST['NPhone']);
    $DocType = trim($_POST['DocType']);
    $DocNum = trim($_POST['DocNum']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Secure password hashing
    $role = trim($_POST['role']);
    $created_by =  $_SESSION['auth'];

    $photo = $_FILES['photo'];

    // Check if all required fields are filled
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($Dob) || empty($workplace) || empty($position) || empty($NPhone) || empty($DocType) || empty($DocNum) || empty($username) || empty($password) || empty($role)) {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Validation Error: All fields are required'
        ];
    } elseif (!isset($photo) || $photo['error'] !== UPLOAD_ERR_OK) {
        // Check if the photo is uploaded without errors
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'File upload error: Please upload a valid photo'
        ];
    } else {
        // Move the uploaded file to the target directory
        $path = "/library/admin/assets/image/users/" . time() . "_" . basename($photo['name']);
        move_uploaded_file($photo['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . $path);

        // Insert into tbluser table
        $query = "INSERT INTO tbluser (DocumentType, DocNum, FirstName, LastName, Gender, Dob, WorkPlace, Position, Contacts, Image) 
                  VALUES ('$DocType', '$DocNum', '$firstname', '$lastname', '$gender', '$Dob', '$workplace', '$position', '$NPhone', '$path')";

        if ($conn->query($query)) {
            // Get the last inserted user ID
            $userID = $conn->insert_id;

            // Insert into tblroles table
            $roleQuery = "INSERT INTO tblroles (Username, Password, UserID, PermissionID, created_by, active) 
                          VALUES ('$username', '$password', '$userID', '$role', '$created_by','1')";

            if ($conn->query($roleQuery)) {
                $_SESSION['message'] = [
                    'status' => 'success',
                    'sms' => 'Insert Successfully'
                ];
            } else {
                $_SESSION['message'] = [
                    'status' => 'error',
                    'sms' => 'Error inserting role data'
                ];
            }
        } else {
            $_SESSION['message'] = [
                'status' => 'error',
                'sms' => 'Error inserting user data'
            ];
        }
    }

    // Redirect to the index page after processing
    header('Location: ' . $burl . '/admin/users/index.php');
    exit(); // Stop script after redirect
}

// End output buffering
ob_end_flush();
?>
