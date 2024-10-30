<?php 
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
date_default_timezone_set('Asia/Bangkok');

if (isset($_POST['myOrder']) && isset($_POST['myOrderIndex'])) {
    // Decode the JSON order data
    $myOrder = (array) json_decode($_POST['myOrder']);


    // Initialize variables
    $BorrowCode = time();
    $GuestID = $_SESSION['user_id'];
    // $role_id = $_POST['role_id'];

    // Set the DateBorrowed as the current date and time
    $DateBorrowed = date('Y-m-d H:i:s');

    // Set DateReturned to 15 days from now
    // $DateReturned = date('Y-m-d H:i:s', strtotime('+15 days', strtotime($DateBorrowed)));

    $Year = date('Y'); 
    $FullBorrowCode = $BorrowCode . '/' . $Year; 
    $YearCode = $Year;
    
    // Track success or failure for the entire operation
    $success = true;

    // Loop through orders and insert them into the database
    foreach ($myOrder as $orderGroup) {

            // Accessing order details
            $book_id = $orderGroup->book_id;
            $quantity = $orderGroup->quantity;

            // Insert order into database
            $insertQuery = "INSERT INTO tblborrow (GuestID, BookID, DateBorrowed, BorrowCode, FullBorrowCode, Quantity, YearCode) 
            VALUES ('$GuestID', '$book_id', '$DateBorrowed','$BorrowCode', '$FullBorrowCode', '$quantity', '$YearCode')";

            // Execute the query
            $borrow = $conn->query($insertQuery);

            // Check for errors during insertion
            if (!$borrow) {
                $success = false;
                echo "Error: " . $conn->error; // For debugging purposes
               
            }
        
    }

    // Handle success or error message after the loop
    if ($success) {
        $_SESSION['message'] = [
            'status' => 'success',
            'sms' => 'Order Successfully Placed: ' . $FullBorrowCode
        ];
        // Clear cart after successful order placement
        unset($_SESSION['orders'][$_SESSION['user_id']]); // Clear cart items
        header("Location: " . $burl . "/guest/index.php");
        exit();
    } else {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Error placing the order.'
        ];
        header("Location: " . $burl . "/guest/index.php");
        exit();
    }
}
?>
