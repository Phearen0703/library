<?php
    date_default_timezone_set('Asia/Bangkok');
    include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
    if(isset($_POST['borrow_id'])){
        $BorrowID = $_POST['borrow_id'];
        $RoleID = $_SESSION['auth'];
        $DateApproved = date('Y-m-d H:i:s');
        
    }

?>