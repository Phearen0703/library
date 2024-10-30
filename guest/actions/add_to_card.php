
// include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

// $userId = $_SESSION['user_id'];

// if (isset($_POST['book_id'])) {
//     $bookId = $_POST['book_id'];

//     // Fetch the book details from the database
//     $book = $conn->query("SELECT * FROM tblbook WHERE BookID = '$bookId'")->fetch_object();

//     if ($book) {
//         // Initialize the orders array for the user if not set
//         if (!isset($_SESSION['orders'][$userId])) {
//             $_SESSION['orders'][$userId] = [];
//         }

//         // Check if the book is already in the user's cart
//         $found = false;
//         foreach ($_SESSION['orders'][$userId] as &$orderitem) {
//             if ($orderitem['book_id'] === $bookId) {
//                 $orderitem['quantity'] += 1; // Increment quantity
//                 $found = true;
//                 break;
//             }
//         }

//         // If the book is not found, add it to the cart
//         if (!$found) {
//             $_SESSION['orders'][$userId][] = [
//                 'book_id' => $bookId,
//                 'quantity' => 1,
//                 'title' => $book->BTitle,
//                 'author' => $book->BAuthor,
//                 'lang' => $book->LangID,
//                 'source' => $book->BSource,
//                 'published' => $book->BPublished,
//                 'pages' => $book->BPage,
//                 'code' => $book->BookCode,
//             ];

//             // Add success message to session
//             $_SESSION['message'] = [
//                 'status' => 'success',
//                 'sms' => 'សៀវភៅបានជ្រើសរើសជោគជ័យ'
//             ];
//             header('Location: ' . $burl . '/guest/index.php');
//             exit();
//         }

//         // Return a JSON response
//         echo json_encode(['success' => true, 'message' => $_SESSION['message']['sms']]);
//         exit();
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
//         exit();
//     }
// } else {
//     echo json_encode(['success' => false, 'message' => 'Invalid request.']);
//     exit();
// }




<?php

include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");

$response = ['success' => false, 'message' => ''];

// Check if required data is posted
if (isset($_POST['book_id']) && !empty($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $book_title = $_POST['book_title'];
    $book_author = $_POST['book_author'];
    $book_lang = $_POST['book_lang'];
    $book_source = $_POST['book_source'];
    $book_published = $_POST['book_published'];
    $book_page = $_POST['book_page'];
    $book_fullcode = $_POST['book_fullcode'];

    // Initialize orders array if it doesn't exist
    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }

    // Check if book already exists in orders
    $existingBook = array_filter($_SESSION['orders'], fn($item) => $item['book_id'] == $book_id);

    if (empty($existingBook)) {
        // Add new book to orders
        $_SESSION['orders'][] = [
            'book_id' => $book_id,
            'book_title' => $book_title,
            'book_author' => $book_author,
            'book_lang' => $book_lang,
            'book_source' => $book_source,
            'book_published' => $book_published,
            'book_page' => $book_page,
            'book_fullcode' => $book_fullcode,
            'quantity' => 1 // Initialize quantity to 1
        ];

        $_SESSION['message'] = [
            'status' => 'success',
            'sms' => 'Book added to cart successfully!'
        ];
    } else {
        $_SESSION['message'] = [
            'status' => 'warning',
            'sms' => 'This book is already in the cart.'
        ];
    }
} else {
    $_SESSION['message'] = [
        'status' => 'danger',
        'sms' => 'Incomplete book data. Please try again.'
    ];
}

// Redirect to index.php with the message
$burl = "http://localhost/library";
header("Location: " . $burl . "/guest/index.php?message=" . urlencode($_SESSION['message']['sms']));
exit();


?>