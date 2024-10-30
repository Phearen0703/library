<?php
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null; // Use null coalescing to avoid undefined index notice
    $book_id = $_POST['book_id'] ?? null;

    // Check if orders exist in the session
    if (!isset($_SESSION['orders'])) {
        echo json_encode(['success' => false, 'message' => 'No items in the cart']);
        exit;
    }

    switch ($action) {
        case 'increase':
            foreach ($_SESSION['orders'] as &$orderGroup) {
                foreach ($orderGroup as &$orderitem) {
                    if ($orderitem['book_id'] == $book_id) {
                        $orderitem['quantity'] += 1;
                        echo json_encode(['success' => true]);
                        exit;
                    }
                }
            }
            break;

        case 'decrease':
            foreach ($_SESSION['orders'] as &$orderGroup) {
                foreach ($orderGroup as &$orderitem) {
                    if ($orderitem['book_id'] == $book_id && $orderitem['quantity'] > 1) {
                        $orderitem['quantity'] -= 1;
                        echo json_encode(['success' => true]);
                        exit;
                    }
                }
            }
            break;

        case 'delete':
            foreach ($_SESSION['orders'] as &$orderGroup) {
                foreach ($orderGroup as $orderKey => $orderitem) {
                    if ($orderitem['book_id'] == $book_id) {
                        unset($orderGroup[$orderKey]);
                        echo json_encode(['success' => true]);
                        exit;
                    }
                }
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
    }

    // If no action was performed
    echo json_encode(['success' => false, 'message' => 'Book not found in cart']);
    exit;
}




// include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

// $userId = $_SESSION['user_id'] ?? null;

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
//         }
//         $_SESSION['message'] = [
//             'status' => 'success',
//             'sms' => 'Book added to cart successfully!'
//         ];
//         header('Location: ' . $burl . '/guest/index.php'); // Redirect on invalid request
//         exit();
//     } else {
//         $_SESSION['message'] = [
//             'status' => 'error',
//             'sms' => 'Invalid book ID.'
//         ];
//         header('Location: ' . $burl . '/guest/index.php'); // Redirect on invalid request
//     exit();
//     }
// } else {
//     $_SESSION['message'] = [
//         'status' => 'error',
//         'sms' => 'Invalid request.'
//     ];
//     header('Location: ' . $burl . '/guest/index.php'); // Redirect on invalid request
//     exit();
// }

?>