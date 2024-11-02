<?php
date_default_timezone_set('Asia/Bangkok');
include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");

if (isset($_POST['borrow_code'])) {
    // Convert BorrowCode to an integer
    $BorrowCode = intval($_POST['borrow_code']);
    $RoleID = $_SESSION['auth'];
    $DateApproved = date('Y-m-d H:i:s');

    // Fetch all BorrowID associated with the BorrowCode
    $getBorrowQuery = "SELECT * FROM tblborrow WHERE BorrowCode = $BorrowCode";
    $borrowResult = $conn->query($getBorrowQuery);

    if ($borrowResult->num_rows == 0) {
        // If no BorrowID is found, set an error message and redirect
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'BorrowID does not exist in tblborrow.'
        ];
        header('Location: ' . $burl . '/admin/requests/index.php');
        exit();
    }

    // Fetch user information
    $getUserQuery = "SELECT tbluser.*, tblroles.PermissionID AS role_id, tblroles.Username 
                     FROM tbluser 
                     LEFT JOIN tblroles ON tblroles.UserID = tbluser.UserID
                     WHERE tblroles.RoleID = '$RoleID'";
    $getUser = $conn->query($getUserQuery);

    if ($getUser) {
        $user = $getUser->fetch_object();
        $Fullname = $user->LastName . ' ' . $user->FirstName;

        // Iterate through each book associated with the BorrowCode
        $allInsertsSuccessful = true; // Track if all inserts are successful

        while ($borrowRow = $borrowResult->fetch_object()) {
            $BorrowID = $borrowRow->BorrowID;

            // Insert into tblapproval
            $approveQuery = "INSERT INTO tblapproval (RoleID, DateApproved, BorrowID) 
                             VALUES ('$RoleID', '$DateApproved', $BorrowID)";
            if (!$conn->query($approveQuery)) {
                $allInsertsSuccessful = false;
                break;
            }

            // Insert into tblreturn
            $returnQuery = "INSERT INTO tblreturn (RoleId, BorrowID, Fullname) 
                            VALUES ('$RoleID', $BorrowID, '$Fullname')";
            if (!$conn->query($returnQuery)) {
                $allInsertsSuccessful = false;
                break;
            }

            // Update tblborrow to set RoleId and DateReturned
            $updateQuery = "UPDATE tblborrow 
                            SET RoleId = '$RoleID' 
                            WHERE BorrowCode = $BorrowCode";
            if (!$conn->query($updateQuery)) {
                $allInsertsSuccessful = false;
                break;
            }
        }

        if ($allInsertsSuccessful) {
            $_SESSION['message'] = [
                'status' => 'success',
                'sms' => 'អនុម័តសំណើបានជោគជ័យ'
            ];
        } else {
            $_SESSION['message'] = [
                'status' => 'error',
                'sms' => 'ប្រព័ន្ធមានបញ្ហា: ' . $conn->error
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
