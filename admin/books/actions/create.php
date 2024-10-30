<?php
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
date_default_timezone_set('Asia/Bangkok');
if (isset($_POST['BTitle']) && isset($_FILES['photo']) && isset($_POST['BAuthor']) && isset($_POST['BLang']) && isset($_POST['MBtype']) && isset($_POST['SBType']) && isset($_POST['BPublished'])) {

    $BTitle = $_POST['BTitle'];
    $BAuthor = $_POST['BAuthor'];
    $BLang = $_POST['BLang'];
    $MBtype = $_POST['MBtype'];
    $SBType = $_POST['SBType'];
    $BPublished = $_POST['BPublished'];
    $BPage = $_POST['BPage'];
    $BSource = $_POST['BSource'];
    $BStock = $_POST['BStock'];
    $BPrice = $_POST['BPrice'];
    $E_book = isset($_POST['E_book']) ? 1 : 0; // 1 if E_book is checked, otherwise 0
    $BookCode = $_POST['BookCode']; // This is the code that will be used for either BookCode or EBookCode
    $FullCode = $_POST['FullCode'];

    $photo = $_FILES['photo'];
    $pdf_file = $_FILES['pdf_file'];
    $created_at = date('Y-m-d H:i:s');
    $RoleID = $_SESSION['auth']; 

    $category = $conn->query("SELECT * FROM tblcategory WHERE tblcategory.CatID = '$MBtype'")->fetch_object();
    $categorys = $category->CatNum;

    // Validate required fields
    if (empty($BTitle) || empty($BAuthor) || empty($SBType) || empty($BLang) || empty($FullCode)) {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Validation Error: Required fields are missing.'
        ];
        header('Location: ' . $burl . '/admin/books/create.php');
        exit();
    }

    // Check for file upload errors
    if ($photo['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Photo upload error: ' . $photo['error']
        ];
        header('Location: ' . $burl . '/admin/books/create.php');
        exit();
    }

    // Validate file types
    $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
    $allowed_pdf_types = ['application/pdf'];

    if (!in_array($photo['type'], $allowed_image_types)) {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Invalid photo format. Only JPEG, PNG, or GIF allowed.'
        ];
        header('Location: ' . $burl . '/admin/books/create.php');
        exit();
    }

// Handle file upload for photo
$photo_name = $FullCode . '.jpg'; // Assuming .jpg or .png based on your earlier code
// $photo_path = "/library/admin/assets/images/cover/" . $photo_name;
// $photo_full_path = $_SERVER['DOCUMENT_ROOT'] . $photo_path;

$photo_path = "Assets/Icons/".$categorys ."/". $photo_name;
$photo_full_path = $_SERVER['DOCUMENT_ROOT'] ."/library/admin/". $photo_path;




if (move_uploaded_file($photo['tmp_name'], $photo_full_path)) {
    // Handle file upload for PDF, but only if a file was uploaded
    if ($pdf_file['error'] !== UPLOAD_ERR_NO_FILE) {
        $pdf_name = $FullCode . '.pdf';
        // $pdf_path = "/library/admin/assets/PDF/" . $pdf_name;
        // $pdf_full_path = $_SERVER['DOCUMENT_ROOT'] . $pdf_path;
        $pdf_path = "Assets/PDF/".$categorys."/" . $pdf_name;
        $pdf_full_path = $_SERVER['DOCUMENT_ROOT'] ."/library/admin/". $pdf_path;

        if (!file_exists(dirname($pdf_full_path))) {
            mkdir(dirname($$categorys), 0755, true);
        }

        if (move_uploaded_file($pdf_file['tmp_name'], $pdf_full_path)) {
            $pdf_success = true;
        } else {
            $_SESSION['message'] = [
                'status' => 'error',
                'sms' => 'Failed to upload PDF file.'
            ];
            header('Location: ' . $burl . '/admin/books/create.php');
            exit();
        }
    } else {
        $pdf_success = false; // No PDF uploaded
    }

    // Insert into the database
    $pdf_path = $pdf_success ? $pdf_path : NULL; // Set PDF path as NULL if no PDF was uploaded
    if ($E_book) {
        // Insert into EBookCode if it's an eBook
        $conn->query("INSERT INTO tblbook (BTitle, BAuthor, BPublished, BRegistered,BSource, BPage, BStock, BPrice, SubCatID, LangID, FullCode, EBookCode, RoleID, filePath, PDFFile) 
                                    VALUES ('$BTitle', '$BAuthor', '$BPublished', '$created_at','$BSource', '$BPage', '$BStock', '$BPrice', '$SBType', '$BLang','$FullCode', '$BookCode', '$RoleID', '$photo_path', '$pdf_path')");
    } else {
        // // Insert into BookCode for regular books

        $conn->query("INSERT INTO tblbook (BTitle, BAuthor, BPublished, BRegistered,BSource, BPage, BStock, BPrice, SubCatID, LangID, BookCode, FullCode, RoleID, filePath, PDFFile) 
                                        VALUES ('$BTitle', '$BAuthor', '$BPublished', '$created_at','$BSource', '$BPage', '$BStock', '$BPrice', '$SBType', '$BLang', '$BookCode', '$FullCode', '$RoleID', '$photo_path', '$pdf_path')");

    }
    $_SESSION['message'] = [
        'status' => 'success',
        'sms' => 'Book added successfully.'
    ];
} else {
    $_SESSION['message'] = [
        'status' => 'error',
        'sms' => 'Failed to upload photo.'
    ];
    }
}

header('Location: ' . $burl . '/admin/books/create.php');
exit();
?>
