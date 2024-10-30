<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

// Function to handle JSON responses
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle AJAX request to get subcategories
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getSubcategories') {
    $catID = $_POST['CatID'];

    if (!is_numeric($catID)) {
        sendJsonResponse(['success' => false, 'message' => 'Invalid Category ID']);
    }

    $query = $conn->prepare("
        SELECT tblsubcategory.*, 
            tblcategory.CatNum AS catnum, 
            (CAST(tblcategory.CatNum AS UNSIGNED) + CAST(tblsubcategory.SubCatNum AS UNSIGNED)) AS CountCode
        FROM tblsubcategory
        INNER JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
        WHERE tblsubcategory.CatID = ?
    ");
    $query->bind_param("i", $catID);
    $query->execute();

    $result = $query->get_result();
    $subcategories = [];

    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row;
    }

    sendJsonResponse(['success' => true, 'subcategories' => $subcategories]);
}

// Handle AJAX request to get FullCode
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'getFullCode') {
    $subCatID = $_POST['SubCatID'];
    $isEbook = isset($_POST['isEbook']) && $_POST['isEbook'] === 'true'; 

    if (!is_numeric($subCatID)) {
        sendJsonResponse(['error' => 'Invalid Subcategory ID']);
    }

    $stmt = $conn->prepare($isEbook ? 
        "SELECT MAX(EBookCode) AS LastBookCode FROM tblbook WHERE SubCatID = ?" :
        "SELECT MAX(BookCode) AS LastBookCode FROM tblbook WHERE SubCatID = ?");
    $stmt->bind_param("i", $subCatID);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $lastBookCode = isset($data['LastBookCode']) ? intval($data['LastBookCode']) + 1 : 1;

   // Fetch CatNum and SubCatNum for the selected subcategory
$catNumQuery = $conn->prepare("
SELECT tblsubcategory.SubCatNum, tblcategory.CatNum
FROM tblsubcategory
INNER JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
WHERE tblsubcategory.SubCatID = ?
");
$catNumQuery->bind_param("i", $subCatID);
$catNumQuery->execute();
$catNumResult = $catNumQuery->get_result();
$catNumData = $catNumResult->fetch_assoc();

$catNum = isset($catNumData['CatNum']) ? intval($catNumData['CatNum']) : 0;
$subCatNum = isset($catNumData['SubCatNum']) ? intval($catNumData['SubCatNum']) : 0;

// Calculate the sum
$sumNum = $catNum + $subCatNum;

// Generate FullCode
$fullCode = $isEbook ? "E{$sumNum} - " . str_pad($lastBookCode, 3, '0', STR_PAD_LEFT) 
                 : "{$sumNum} - " . str_pad($lastBookCode, 3, '0', STR_PAD_LEFT);

sendJsonResponse(['fullCode' => $fullCode, 'count' => $lastBookCode]);

}
?>

