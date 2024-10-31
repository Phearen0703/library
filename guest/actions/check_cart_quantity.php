<?php

include($_SERVER['DOCUMENT_ROOT']."/library/config.php");

$userId = $_SESSION['user_id'] ?? null;
$itemLimitReached = false;

if ($userId && isset($_SESSION['orders'][$userId])) {
    $itemLimitReached = count($_SESSION['orders'][$userId]) >= 2;
}

echo json_encode(['itemLimitReached' => $itemLimitReached]);
?>