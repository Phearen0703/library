<?php
include($_SERVER['DOCUMENT_ROOT'] . "/library/config.php");


$response = ['success' => false, 'message' => ''];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $book_id = $_POST['book_id'] ?? null;

    if ($action === 'delete' && $book_id !== null) {
        // Ensure the book ID exists in the cart session data
        foreach ($_SESSION['orders'] as $key => $orderitem) {
            if (isset($orderitem['book_id']) && $orderitem['book_id'] == $book_id) {
                unset($_SESSION['orders'][$key]); // Remove item from session
                $response['success'] = true;
                break;
            }
        }

        // If not found, send an error message
        if (!$response['success']) {
            $response['message'] = 'Error: Book not found in cart';
        }
    } else {
        $response['message'] = 'Error: Invalid action or book ID';
    }
} else {
    $response['message'] = 'Error: Action not specified';
}

echo json_encode($response);
?>
