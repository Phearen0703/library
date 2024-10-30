<?php
    $title = "Dashboard";
    $page = "summary";
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php");?>


<?php
$from = isset($_GET['from']) ? date('Y-m-d',strtotime($_GET['from'])) : date('Y');
$to = isset($_GET['to']) ? date('Y-m-d',strtotime($_GET['to'])) : date('Y-m-d');

$total_book = $conn->query("SELECT COUNT(*) AS total FROM tblbook WHERE BRegistered >= '$from' AND BRegistered <= '$to'")->fetch_object()->total;
$total_book_count = $conn->query("SELECT COUNT(*) AS total FROM tblbookcounts WHERE dateViewed >= '$from' AND dateViewed <= '$to'")->fetch_object()->total;


?>

<div class="pt-3">
    <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>
</div>

<main class="content">
    <div class="container-fluid p-0">

        <div class="card">
            <div class="card-header">
                <h4 style=" font-family: Moul;">ការគ្រប់គ្រងទិន្នន័យ</h4>
            </div>
            <div class="card-body">

                <div class="row">
                    <form action="" method="get">
                        <div class="row m-auto">
                            <div class="col-md-3 mb-3">
                                <label for="startDate" class="form-label" style="font-family:  Siemreap;">ចាប់ផ្តើម</label>
                                <input type="date" name="from" value="<?php echo $from ?>" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="endDate" class="form-label" style="font-family:  Siemreap;">បញ្ចប់</label>
                                <input type="date" name="to" value="<?php echo $to ?>"  class="form-control">
                            </div>
                            <div class="col-md-6 mb-3 m-auto">
                               <button type="submit" class="btn btn-primary " style="font-family:  Siemreap;">យល់បព្រម</button>
                            </div>
                            
                        </div>
                        
                    </form>
                    <div class="row p-3">
                        <div class="col-lg-4 col-md-6 col-12 p-3">
                            <div class="p-5 bg-primary rounded text-center">
                                <p class="text-white fs-5" style="font-family:  Siemreap;">ចំនួនសៀវភៅក្នុងប្រព័ន្ធ ៖</p>
                                <p class="text-white fs-5" style="font-family:  Siemreap;"><?php echo $total_book ?></p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12 p-3">
                            <div class="p-5 bg-success rounded text-center">
                                <p class="text-white fs-5" style="font-family:  Siemreap;"> ចំនួនសៀវភៅដែលទស្សនាក្នុងគេហទំព័រ ៖ </p>
                                <p class="text-white fs-5" style="font-family:  Siemreap;"> <?php echo $total_book_count ?> </p>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12 p-3">
                            <div class="p-5 bg-danger rounded">
                                <p class="text-white fs-5" style="font-family:  Siemreap;"
                                    style="font-family: Siemreap;"> ចំនួនអ្នកចូលទស្សានក្នុងគេហទំព័រ : 500</p>
                                </h1>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12 p-3">
                            <div class="p-5 bg-warning rounded">
                                <p class="text-white fs-5" style="font-family:  Siemreap;"
                                    style="font-family: Siemreap;"> ចំនួនអ្នកចូលអាន E-Book : 500</p>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




        <?php include("../layouts/footer.php");?>