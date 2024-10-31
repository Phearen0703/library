<?php 

include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");
date_default_timezone_set('Asia/Bangkok');

if (isset($_POST['myOrder']) && isset($_POST['myOrderIndex'])) {
    // Decode the JSON order data
    $myOrder = json_decode($_POST['myOrder'], true); // Decode to associative array

    // Initialize variables
    $BorrowCode = time();
    $GuestID = $_SESSION['user_id'];
    $DateBorrowed = date('Y-m-d H:i:s');
    $Year = date('Y'); 
    $FullBorrowCode = $BorrowCode . '/' . $Year; 
    $YearCode = $Year;
    
    // Track success or failure for the entire operation
    $success = true;

    // Loop through orders and insert them into the database
    foreach ($myOrder as $orderGroup) {
        $book_id = $orderGroup['book_id'];
        $quantity = $orderGroup['quantity'];

        // Insert order into the database
        $insertQuery = "INSERT INTO tblborrow (GuestID, BookID, DateBorrowed, BorrowCode, FullBorrowCode, Quantity, YearCode) 
                        VALUES ('$GuestID', '$book_id', '$DateBorrowed', '$BorrowCode', '$FullBorrowCode', '$quantity', '$YearCode')";

        if (!$conn->query($insertQuery)) {
            $success = false;
            echo "Error: " . $conn->error; // For debugging purposes
            break;
        }
    }

    // Debug output: Check session data before clearing
    echo "<pre>Session Data Before Clearing: ";
    print_r($_SESSION['orders']);
    echo "</pre>";

    // Handle success or error message after the loop
    if ($success) {
        $_SESSION['message'] = [
            'status' => 'success',
            'sms' => 'ស្នើសុំខ្ចីសៀវភៅបានជោគជ័យ សូមទំនាក់ទំនងទៅបណ្ណានុរក្ស'
        ];

        // Clear the cart after successful order placement
        if (isset($_SESSION['orders'])) {
            unset($_SESSION['orders']); // Clear cart items
           
        } else {
            echo "Cart for user not found"; // Debugging if cart not found
        }

        // Redirect to the guest index page
        header("Location: " . $burl . "/guest/index.php");
        exit();
    } else {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'ប្រព័ន្ធមានបញ្ហា សូមទំនាក់ទំនងទៅបណ្ណានុរក្ស'
        ];
        header("Location: " . $burl . "/guest/index.php");
        exit();
    }
}
?>
