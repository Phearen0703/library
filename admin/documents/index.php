<?php
    $title = "Documents Page";
    $page = "documents";
?>


<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php"); ?>




<div class="pt-4">
    <div class="card">
        <div class="card-header">
           <div class="d-flex flex-end">
            <div class="col">ប្រភេទសំណើ</div>
            <form action="" method="get">
                    <div class="mb-2 col-3">
                        <div class="input-group">
                            <input type="hidden" name="page" value="">
                            <input type="search" name="search" class="form-control" value="">
                        </div>
                    </div>
                </form>

           </div>
        </div>
        <div class="card-body">

            <div class="row">
               

                <div class="container d-flex justify-content-between flex-wrap">
                    <div class="col-4 p-2">
                        <div class="card bg-primary p-4">
                            <div class="row text-center">
                                <p class="text-white" style="font-family: Siemreap;"> ចំនួនសៀវភៅក្នុងប្រព័ន្ធ</p>
                                <p class="text-white fs-2" style="font-family:  Moul;"> 500</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-4 p-2">
                        <div class="card bg-primary p-4">
                            <div class="row text-center">
                                <p class="text-white" style="font-family: Siemreap;">ចំនួនអ្នកចូលទស្សានក្នុងបណ្ណាល័យ</p>
                                <p class="text-white fs-2" style="font-family:  Moul;">500</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 p-2">
                        <div class="card bg-primary p-4">
                            <div class="row text-center">
                                <p class="text-white" style="font-family: Siemreap;"> ចំនួនអ្នកចូលទស្សានក្នុងគេហទំព័រ</p>
                                <p class="text-white fs-2" style="font-family:  Moul;"> 500</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 p-2">
                        <div class="card bg-primary p-4">
                            <div class="row text-center">
                                <p class="text-white" style="font-family: Siemreap;"> ចំនួនអ្នកចូលអាន E-Book</p>
                                <p class="text-white fs-2" style="font-family:  Moul;"> 500</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <?php include("../layouts/footer.php");?>