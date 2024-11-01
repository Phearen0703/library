<?php
date_default_timezone_set('Asia/Bangkok');
include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");

if (isset($_POST['borrow_code'])) {
    // Convert BorrowID to an integer
    $BorrowCode = $_POST['borrow_code'];
    $RoleID = $_SESSION['auth'];
    $DateApproved = date('Y-m-d H:i:s');

    $getBorrow = $conn->query("SELECT * FROM `tblborrow` ORDER BY `tblborrow`.`BorrowCode` = $BorrowCode")->fetch_object();

    $BorrowID = $getBorrow->BorrowID;
    var_dump($BorrowID);
    echo '</br>';
    echo $BRID;
    die();

    // Check if BorrowID exists in tblborrow
    $checkBorrowIDQuery = "SELECT * FROM tblborrow WHERE BorrowID = $BorrowID";
    $result = $conn->query($checkBorrowIDQuery);

    if ($result->num_rows == 0) {
        // BorrowID does not exist
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'BorrowID does not exist in tblborrow.'
        ];
        header('Location: ' . $burl . '/admin/requests/index.php');
        exit();
    }

    // Continue with the rest of the code if BorrowID exists...
    // Fetch user information
    $getUserQuery = "SELECT tbluser.*, tblroles.PermissionID AS role_id, tblroles.Username 
                     FROM tbluser 
                     LEFT JOIN tblroles ON tblroles.UserID = tbluser.UserID
                     WHERE tblroles.RoleID = '$RoleID'";
    $getUser = $conn->query($getUserQuery);

    if ($getUser) {
        $user = $getUser->fetch_object();
        $Fullname = $user->LastName . ' ' . $user->FirstName;

        // Insert into tblapproval
        $approveQuery = "INSERT INTO tblapproval (RoleID, DateApproved, BorrowID) 
                         VALUES ('$RoleID', '$DateApproved', $BorrowID)";
        if ($conn->query($approveQuery)) {
            // Insert into tblreturn
            $returnQuery = "INSERT INTO tblreturn (RoleId, BorrowID, Fullname) 
                            VALUES ('$RoleID', $BorrowID, '$Fullname')";
            if ($conn->query($returnQuery)) {
                $_SESSION['message'] = [
                    'status' => 'success',
                    'sms' => 'អនុម័តសំណើបានជោគជ័យ'
                ];
            } else {
                $_SESSION['message'] = [
                    'status' => 'error',
                    'sms' => 'ប្រព័ន្ធមានបញ្ហា (tblreturn Insert Failed): ' . $conn->error
                ];
            }
        } else {
            $_SESSION['message'] = [
                'status' => 'error',
                'sms' => 'ប្រព័ន្ធមានបញ្ហា (tblapproval Insert Failed): ' . $conn->error
            ];
        }
    } else {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'ប្រព័ន្ធមានបញ្ហា (User Fetch Failed): ' . $conn->error
        ];
    }

    // Redirect back to the requests page
    header('Location: ' . $burl . '/admin/requests/index.php');
    exit();
}
?>
