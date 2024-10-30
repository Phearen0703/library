<?php
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
$userId = $_SESSION['user_id'] ?? null;

$cartHasItems = false;

if ($userId && isset($_SESSION['orders'][$userId])) {
    $cartHasItems = count($_SESSION['orders'][$userId]) > 0;
}

echo json_encode(['cartHasItems' => $cartHasItems]);
?>
