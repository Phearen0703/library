<?php

include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
if(isset($_GET["user_id"])){
    $user_id = $_GET['user_id'];


    $user = $conn->query("SELECT * FROM tbluser WHERE UserID = '$user_id'");
    $user = $user->fetch_object();
    if(file_exists($_SERVER['DOCUMENT_ROOT'] . $user->photo)){
        unlink($_SERVER['DOCUMENT_ROOT'] . $user->photo);
    }
   


    $conn->query("UPDATE tblroles SET active = 0 WHERE UserID  = '$user_id'");

    $_SESSION['message'] = [
        'status'=> 'success',
        'sms'=> 'លុប អ្នកប្រើប្រាស់បានជោគជ័យ'
    ];
}else{
    $_SESSION['message'] = [
        'status'=> 'error',
        'sms'=> 'មិនអាចលុបអ្នកប្រើប្រាស់បានទេ!'
    ];
}
header('Location:'. $burl .'/admin/users/index.php');
?>