<?php

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
?> 

<?php

include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

$userId = $_SESSION['user_id'] ?? null;

if (isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Fetch the book details from the database
    $book = $conn->query("SELECT * FROM tblbook WHERE BookID = '$bookId'")->fetch_object();

    if ($book) {
        // Initialize the orders array for the user if not set
        if (!isset($_SESSION['orders'][$userId])) {
            $_SESSION['orders'][$userId] = [];
        }

        // Check if the book is already in the user's cart
        $found = false;
        foreach ($_SESSION['orders'][$userId] as &$orderitem) {
            if ($orderitem['book_id'] === $bookId) {
                $orderitem['quantity'] += 1; // Increment quantity
                $found = true;
                break;
            }
        }

        // If the book is not found, add it to the cart
        if (!$found) {
            $_SESSION['orders'][$userId][] = [
                'book_id' => $bookId,
                'quantity' => 1,
                'title' => $book->BTitle,
                'author' => $book->BAuthor,
                'lang' => $book->LangID,
                'source' => $book->BSource,
                'published' => $book->BPublished,
                'pages' => $book->BPage,
                'code' => $book->BookCode,
            ];
        }
        $_SESSION['message'] = [
            'status' => 'success',
            'sms' => 'Book added to cart successfully!'
        ];
        header('Location: ' . $burl . '/guest/index.php'); // Redirect on invalid request
        exit();
    } else {
        $_SESSION['message'] = [
            'status' => 'error',
            'sms' => 'Invalid book ID.'
        ];
        header('Location: ' . $burl . '/guest/index.php'); // Redirect on invalid request
    exit();
    }
} else {
    $_SESSION['message'] = [
        'status' => 'error',
        'sms' => 'Invalid request.'
    ];
    header('Location: ' . $burl . '/guest/index.php'); // Redirect on invalid request
    exit();
}
?>
