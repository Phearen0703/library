<?php
// include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

// // Function to handle JSON responses
// function sendJsonResponse($data) {
//     header('Content-Type: application/json');
//     echo json_encode($data);
//     exit;
// }

// // Handle AJAX request to get subcategories
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getSubcategories') {
//     $catID = $_POST['CatID'];

//     if (!is_numeric($catID)) {
//         sendJsonResponse(['success' => false, 'message' => 'Invalid Category ID']);
//     }

//     $query = $conn->prepare("SELECT tblsubcategory.SubCatID, tblsubcategory.SubCatName, tblcategory.CatNum AS catnum 
//                              FROM tblsubcategory
//                              INNER JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
//                              WHERE tblsubcategory.CatID = ?");
//     $query->bind_param("i", $catID); // Binding the CatID
//     $query->execute();

//     $result = $query->get_result();
//     $subcategories = [];

//     while ($row = $result->fetch_assoc()) {
//         $subcategories[] = $row; // Fetch each row
//     }

//     // Return subcategories and catnum as JSON
//     sendJsonResponse(['success' => true, 'subcategories' => $subcategories]);
// }

// // Handle AJAX request to get book count for the selected subcategory or eBook
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getBookCount') {
//     $subCatID = $_POST['catID'];
//     $isEbook = isset($_POST['isEbook']) && $_POST['isEbook'] === 'true'; // Check if it's an e-book request

//     if (!is_numeric($subCatID)) {
//         sendJsonResponse(['error' => 'Invalid Subcategory ID']);
//     }

//     // Adjust query based on whether the request is for an e-book or regular book
//     $stmt = $conn->prepare($isEbook ? 
//         "SELECT MAX(EBookCode) AS LastBookCode FROM tblbook WHERE SubCatID = ?" :
//         "SELECT MAX(BookCode) AS LastBookCode FROM tblbook WHERE SubCatID = ?");
//     $stmt->bind_param("i", $subCatID);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_assoc();

//     // If there is no previous book code, start from 1
//     $lastBookCode = isset($data['LastBookCode']) ? intval($data['LastBookCode']) + 1 : 1;

//     sendJsonResponse(['count' => $lastBookCode]);
// }



include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

// Function to send JSON responses
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle AJAX request to get subcategories based on CatID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getSubcategories') {
    $catID = $_POST['CatID'];

    // Validate that CatID is numeric
    if (!is_numeric($catID)) {
        sendJsonResponse(['success' => false, 'message' => 'Invalid Category ID']);
    }

        // Query to fetch subcategories and associated catnum
        $query = $conn->prepare("
            SELECT tblsubcategory.*, 
            tblcategory.CatNum AS catnum, 
            (CAST(tblcategory.CatNum AS UNSIGNED) + CAST(tblsubcategory.SubCatNum AS UNSIGNED)) AS CountCode
            FROM tblsubcategory
            INNER JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
            WHERE tblsubcategory.CatID  = ?
    ");
    $query->bind_param("i", $catID); // Bind the CatID
    $query->execute();
    $result = $query->get_result();

    $subcategories = [];
    while ($row = $result->fetch_assoc()) {
        // Now you get all columns from tblsubcategory in each $row
        $subcategories[] = $row;
    }
    sendJsonResponse(['success' => true, 'subcategories' => $subcategories]);

    }

// Handle AJAX request to get the next book count (BookCode or EBookCode)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getBookCount') {
    $subCatID = $_POST['catID'];
    $isEbook = isset($_POST['isEbook']) && $_POST['isEbook'] === 'true'; // Check if it's an e-book request

    // Validate that SubCatID is numeric
    if (!is_numeric($subCatID)) {
        sendJsonResponse(['error' => 'Invalid Subcategory ID']);
    }

    // Choose query based on whether we're fetching for eBook or regular book
    $query = $isEbook ? 
        "SELECT MAX(EBookCode) AS LastBookCode FROM tblbook WHERE SubCatID = ?" :
        "SELECT MAX(BookCode) AS LastBookCode FROM tblbook WHERE SubCatID = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $subCatID);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Determine the next book code (start from 1 if no record found)
    $lastBookCode = isset($data['LastBookCode']) ? intval($data['LastBookCode']) + 1 : 1;

    // Send the book count as JSON
    sendJsonResponse(['count' => $lastBookCode]);
}



// Close the connection (optional but good practice)
$conn->close();

?>
