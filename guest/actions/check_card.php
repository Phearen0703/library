<?php
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
$userId = $_SESSION['user_id'] ?? null;

$cartHasItems = false;

if (isset($_SESSION['orders']) && count($_SESSION['orders']) > 0) {
    $cartHasItems = true;
}

echo json_encode(['cartHasItems' => $cartHasItems]);
?>
