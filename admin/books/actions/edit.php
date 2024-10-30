<?php
include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $BTitle = $_POST['BTitle'];
    $BAuthor = $_POST['BAuthor'];
    $BLang = $_POST['BLang'];
    $MBtype = $_POST['MBtype'];
    $SBType = $_POST['SBType'];
    $BPublished = $_POST['BPublished'];
    $BSource = $_POST['BSource'];
    $BPage = $_POST['BPage'];
    $BStock = $_POST['BStock'];
    $BPrice = $_POST['BPrice'];
    $E_book = isset($_POST['E_book']) ? 1 : 0;
    $BookCode = $_POST['BookCode'];
    $FullCode = $_POST['FullCode'];

    $photo = $_FILES['photo'];
    $pdf_file = $_FILES['pdf_file'];
    $RoleID = $_SESSION['auth'];
    $edited_at = date('Y-m-d H:i:s');

    $book_id = $_POST['book_id'];

    // Fetch the existing book data
    $query = $conn->query("SELECT * FROM tblbook WHERE BookID = $book_id");
    $book = $query->fetch_assoc();

    $category = $conn->query("SELECT * FROM tblcategory WHERE tblcategory.CatID = '$MBtype'")->fetch_object();
    $categorys = $category->CatNum;

    if (!$book) {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Book not found.'
        ];
        header('Location: ' . $burl . '/admin/books/index.php');
        exit();
    }

    // Validate required fields
    if (empty($BTitle) || empty($BAuthor) || empty($SBType) || empty($BLang) || empty($FullCode)) {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Validation Error: Required fields are missing.'
        ];
        header('Location: ' . $burl . '/admin/books/edit.php?book_id=' . $book_id);
        exit();
    }

    // Handle file uploads
    $photo_path = $book['filePath']; // Default to existing photo
    if ($photo['error'] === UPLOAD_ERR_OK) {
        $photo_name = $FullCode . '.jpg'; 
        $photo_path = "Assets/Icons/".$categorys ."/". $photo_name;
        $photo_full_path = $_SERVER['DOCUMENT_ROOT'] ."/library/admin/". $photo_path;

        if (!file_exists(dirname($photo_full_path))) {
            mkdir(dirname($photo_full_path), 0755, true);
        }

        if (!move_uploaded_file($photo['tmp_name'], $photo_full_path)) {
            $_SESSION['message'] = [
                'status' => 'error',
                'sms' => 'Failed to upload photo.'
            ];
            header('Location: ' . $burl . '/admin/books/edit.php?book_id=' . $book_id);
            exit();
        }
    }

    // Handle PDF upload
    $pdf_path = $book['PDFFile']; // Default to existing PDF
    if ($pdf_file['error'] === UPLOAD_ERR_OK) {
        $pdf_name = $FullCode . '.pdf';
        // $pdf_path = "/library/admin/assets/PDF/" . $pdf_name;
        // $pdf_full_path = $_SERVER['DOCUMENT_ROOT'] . $pdf_path;
        $pdf_path = "Assets/PDF/" .$categorys ."/" . $pdf_name;
        $pdf_full_path = $_SERVER['DOCUMENT_ROOT'] ."/library/admin/". $pdf_path;

        if (!file_exists(dirname($pdf_full_path))) {
            mkdir(dirname($pdf_full_path), 0755, true);
        }

        if (!move_uploaded_file($pdf_file['tmp_name'], $pdf_full_path)) {
            $_SESSION['message'] = [
                'status' => 'error',
                'sms' => 'Failed to upload PDF file.'
            ];
            header('Location: ' . $burl . '/admin/books/edit.php?book_id=' . $book_id);
            exit();
        }
    }

    // Update the database
    if ($E_book) {
        $conn->query("UPDATE tblbook SET BTitle = '$BTitle', BAuthor = '$BAuthor', BPublished = '$BPublished', BSource = '$BSource',
                      BPage = '$BPage', BStock = '$BStock', BPrice = '$BPrice', SubCatID = '$SBType', 
                      LangID = '$BLang', FullCode = '$FullCode', EBookCode = '$BookCode', RoleID = '$RoleID', 
                      filePath = '$photo_path', PDFFile = '$pdf_path' WHERE BookID = $book_id");
    } else {
        $conn->query("UPDATE tblbook SET BTitle = '$BTitle', BAuthor = '$BAuthor', BPublished = '$BPublished', BSource = '$BSource',
                      BPage = '$BPage', BStock = '$BStock', BPrice = '$BPrice', SubCatID = '$SBType', 
                      LangID = '$BLang', BookCode = '$BookCode', FullCode = '$FullCode', RoleID = '$RoleID', 
                      filePath = '$photo_path', PDFFile = '$pdf_path' WHERE BookID = $book_id");
    }
    $conn->query("INSERT INTO tblbook_history (book_id, edited_by, edited_at) VALUES ('$book_id', '$RoleID', '$edited_at')");

    $_SESSION['message'] = [
        'status' => 'success',
        'sms' => 'Book updated successfully.'
    ];
    header('Location: ' . $burl . '/admin/books/edit.php?book_id=' . $book_id);
    exit();
}

?>