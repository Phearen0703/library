<?php

include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
if(isset($_POST["book_id"])){
    $book_id = $_POST['book_id'];


    $book = $conn->query("SELECT * FROM tblbook WHERE BookID = '$book_id'");
    $book = $book->fetch_object();

 
    if(file_exists($_SERVER['DOCUMENT_ROOT'] ."/library/admin/". $book->filePath)){
        unlink($_SERVER['DOCUMENT_ROOT']."/library/admin/". $book->filePath);
    }
    if(file_exists($_SERVER['DOCUMENT_ROOT'] ."/library/admin/". $book->PDFFile)){
        unlink($_SERVER['DOCUMENT_ROOT']."/library/admin/". $book->PDFFile);
    }
   

    $conn->query("DELETE FROM tblbook WHERE `tblbook`.`BookID` = '$book_id'");

    $_SESSION['message'] = [
        'status'=> 'success',
        'sms'=> 'Delete Successfully'
    ];
}else{
    $_SESSION['message'] = [
        'status'=> 'error',
        'sms'=> 'Delete Not Successfully'
    ];
}
header('Location:'. $burl .'/admin/books/index.php');
?>