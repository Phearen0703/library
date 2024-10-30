<?php
    $title = "Guests Page";
    $page = "guests";
?>


<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php"); ?>

<?php
    $guest = $conn -> query("SELECT * FROM tblguest");

?>


<div class="p-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-end">
                <div class="col">ផ្ទាំងគ្រប់គណនីភ្ញៀវ</div>

            </div>
        </div>
        <div class="card-body">
        <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th scope="col">ល.រ</th>
                        <th scope="col">ឈ្មោះ</th>
                        <th scope="col">ភេទ</th>
                        <th scope="col">ថ្ងៃខែឆ្នាំកំណើត</th>
                        <th scope="col">ស្ថានប័ន/អង្គភាព</th>
                        <th scope="col">ឋានៈ/តួនាទី</th>
                        <th scope="col">លេខទូរសព្ទទំនាក់ទំនង</th>
                        <th scope="col">ប្រភេទឯកសារ</th>
                        <th scope="col">ឯកសារ លេខ</th>
                        <th scope="col">ឈ្មោះគណនី</th>
                        <th class="bg-primary text-white">ប្រតិបត្តិការ</th>

                    </tr>


                </thead>
                <tbody>
                    <?php
                    $i = 0;
                 while($guests = $guest->fetch_object()) { ?>
                    <tr>
                        <th class="text-center" scope="row"><?php echo ++$i?></th>
                        <td><?php echo $guests->LastName .' '. $guests->FirstName?></td>
                        <td><?php echo $guests->Gender?></td>
                        <td><?php echo $guests->DoB?></td>
                        <td><?php echo $guests->Workplace?></td>
                        <td><?php echo $guests->Position?></td>
                        <td><?php echo $guests->Contacts?></td>
                        <td><?php echo $guests->DocumentType?></td>
                        <td><?php echo $guests->Docnum?></td>
                        <td><?php echo $guests->Username?></td>
                        <td>
                            <a href="<?php echo $burl . "/admin/guests/edit.php?book_id=" . $guests->GuestID ?>"
                                class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>

                            <a href="<?php echo $burl . "/admin/books/view.php?book_id=" . $guests->GuestID ?>"
                                class="btn btn-sm btn-success"><i class="fa-solid fa-eye"></i></a>
                        </td>

                    </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include("../layouts/footer.php");?>