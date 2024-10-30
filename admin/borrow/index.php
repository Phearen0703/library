<?php
    $title = "Borrow Page";
    $page = "borrow";
?>


<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php"); ?>




<div class="pt-4">
    <div class="card">
        <div class="card-header">
            <div class="card-header">
                <h4 style=" font-family: Moul;">ទទួលសងសៀវភៅ</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3 text-center">
                    <div class="card">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>លេខលិខិត</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>510</td>
                                    <td><a href="#" class="btn btn-success">ទទួល</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>511</td>
                                    <td><a href="#" class="btn btn-success">ទទួល</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="container row text-center">
                            <div class="col-4">
                                <div class="card text-center">
                                    <img src="https://image.freshnewsasia.com/2017/117/fn-2017-12-14-16-10-05-1.jpg" alt="សំណើលិខិត" width="300px",hight="300px">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="container">
                                    <div class="row p-2">
                                       <div class="col-3">
                                       <div class="card text-center">
                                        <img src="https://image.freshnewsasia.com/2017/117/fn-2017-12-14-16-10-05-1.jpg" alt="សំណើលិខិត" width="150px",hight="150px">
                                        </div>
                                    </div>
                                    <div class="col-3 pt-2">
                                    </div>
                                        <div class="card">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSx3WDGxA4PtH0hZHQFK8HllNLzl5SGL5PBOA&s" alt="សំណើលិខិត" width="300px",hight="150px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                <img src="https://image.freshnewsasia.com/2017/117/fn-2017-12-14-16-10-05-1.jpg" alt="សំណើលិខិត" width="300px",hight="300px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br>
    <div class="card">
    <table class="table text-center">
                <thead>
                    <tr>
                        <th>លេខលិខិត</th>
                        <th>ចំណងជើងសៀវភៅ/លិខិត</th>
                        <th>កូដសៀវភៅ</th>
                        <th>ឈ្មោះអ្នកខ្ចី</th>
                        <th>កាលបរិច្ឆេទ</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>ផ្កាស្រពោន</td>
                        <td>001</td>
                        <td>ជួប ភារិន្ទ</td>
                        <td>07/08/2024</td>
                        <td>
                            <a href="#" class="btn btn-warning">ខ្ចីបន្ដ</a>
                            <a href="#" class="btn btn-success">សងសៀវភៅ</a>
                        </td>
                    </tr>
                </tbody>
             </table>

    </div>
</div>


<?php include("../layouts/footer.php");?>