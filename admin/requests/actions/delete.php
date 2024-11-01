<?php 
include($_SERVER['DOCUMENT_ROOT']."/library/config.php");
        if(isset($_POST['borrow_code'])){
            $borrow_code = $_POST['borrow_code'];


            $del_borrow = $conn ->query("DELETE FROM `tblborrow` WHERE `tblborrow`.`BorrowCode` = $borrow_code");
            if($del_borrow){
                $_SESSION['message'] = [
                    'status'=> 'success',
                    'sms'=> 'បដិសេធសំណើសុំខ្ចីសៀវភៅបានជោគជ័យ'
                ];
            }else{
                $_SESSION['message'] = [
                    'status'=> 'error',
                    'sms'=> 'មិនអាចបដិសេធ! មានបញ្ហាបច្ចេកទេ'
                ];
            }
            header('Location:'. $burl .'/admin/requests/index.php');
        }
?>