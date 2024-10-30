<?php

$title = "Detail Page";
$page = "book";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php");
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php");


// Fetch book details
    $book_id = $_GET['book_id'];
// Update_by
    $update_by = $conn->query("SELECT tblbook_history.*, 
        tbluser.FirstName,
        tbluser.LastName,
        tbluser.WorkPlace
    FROM tblbook_history
    INNER JOIN tblroles ON tblbook_history.edited_by = tblroles.RoleID
    INNER JOIN tbluser ON tblroles.UserID = tbluser.UserID
    WHERE tblbook_history.book_id = '$book_id'");


    $query = "SELECT tblbook.*, 
        tblsubcategory.SubCatID, 
        tblsubcategory.SubCatName, 
        tblcategory.CatID, 
        tblcategory.CatName, 
        tbllanguage.LangName,
        tbluser.FirstName,
        tbluser.LastName,
        tbluser.WorkPlace,
        tblbook_history.edited_by
    FROM tblbook
    INNER JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID
    INNER JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
    INNER JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
    INNER JOIN tblroles ON tblbook.RoleID = tblroles.RoleID
    INNER JOIN tbluser ON tblroles.UserID = tbluser.UserID
    LEFT JOIN tblbook_history ON tblbook.BookID = tblbook_history.book_id
    WHERE tblbook.BookID = '$book_id'";
$books = $conn->query($query);
$book = $books->fetch_object();

?>
<div class="p-4">
    <div class="col-12 mb-3">
        <a href="<?php echo $burl . "/admin/books/index.php?"?>" class="btn btn-danger"><i
                class="fa-solid fa-rotate-left"></i> Back</a>
    </div>
    <div class="row">
        <div class="col">
            <div class="bg-white p-4 rounded">

                <div class="row">
                    <div class="col-10">
                        <div class="bg-white p-4 rounded">
                            <div class="m-1 p-1">ចំណងជើងលិខិត</div>
                            <?php if($book->BTitle <> "") { ?>
                            <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BTitle ?></span>
                           
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col">
                        <?php if($book->filePath <>"") {?>
                        <img src="<?php echo $burl."/admin/". $book->filePath; ?>" width="100"
                            class="img-thumbnail rounded" alt="Image" data-bs-toggle="modal"
                            data-bs-target="#imageModal" style="width: 100px; cursor: pointer;">
                        <?php }?>
                    </div>
                </div>
                <hr>

                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">អ្នកនិពន្ធ/ចងក្រង</div>
                    <?php if($book->BAuthor <> "") { ?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BAuthor ?></span>

                </div>
                <hr>
                <?php } ?>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">ប្រភព</div>
                    <?php if($book->BSource <> "") { ?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BSource ?></span>

                </div>
                <hr>
                <?php } ?>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">ភាសា</div>
                    <?php if($book->LangName <> "") { ?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->LangName ?></span>

                </div>
                <hr>
                <?php } ?>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">សំណុំប្រភេទសៀវភៅ</div>
                    <?php if($book->CatName <> "") { ?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->CatName ?></span>

                </div>
                <hr>
                <?php } ?>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">ប្រភេទប្រភេទសៀវភៅ</div>
                    <?php if($book->SubCatName <> ""){?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->SubCatName ?></span>

                    <hr>
                    <?php }?>
                </div>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">ឆ្នាំបោះពុម្ព</div>
                    <?php if($book->BPublished <> ""){?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BPublished ?></span>
                    <hr>
                    <?php }?>
                </div>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">ចំនួនទំព័រ</div>
                    <?php if($book->BPage <> ""){?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BPage ?></span>
                    <hr>
                    <?php }?>
                </div>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">ចំនួនសៀវភៅ</div>
                    <?php if($book->BStock <> ""){?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BStock ?></span>

                    <hr>
                    <?php }?>
                </div>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">តម្លៃ</div>
                    <?php if($book->BPrice <> ""){?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->BPrice ?></span>

                    <hr>
                    <?php }?>
                </div>
                <div class="bg-white p-4 rounded">
                    <div class="m-1 p-1">កូដ</div>
                    <?php if($book->FullCode <> ""){?>
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->FullCode ?></span>

                    <hr>
                    <?php }?>
                </div>
                <div class="m-1 p-1">PDF File</div>
                <?php if($book->PDFFile <> "") { ?>
                <div class="bg-white p-4 rounded">
                    <span class="badge bg-primary rounded-pill fs-6"><?php echo $book->PDFFile ?></span>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pdfModal"><i
                            class="fa-solid fa-eye"></i></button>

                </div>

                <?php } ?>
                <hr style="height:5px;border:none;color:DodgerBlue;background-color:DodgerBlue;">
                <div class="row pt-3">
                    <div class="col">
                        <h6><i class="fa-solid fa-user"></i> បង្កើតដោយ</h6>
                        <hr>
                        <ul>

                            <li class="list-group-item py-2">ឈ្មោះ ៖
                                <?php echo $book -> FirstName .' '.$book -> LastName ?></li>
                            <li class="list-group-item py-2">អង្គភាព ៖ <?php echo $book -> WorkPlace ?></li>
                            <li class="list-group-item py-2">កាលបរិច្ឆេទ ៖ <?php echo $book -> BRegistered ?></li>

                        </ul>

                    </div>


                    <?php if($book->edited_by <> ""){?>
                    <div class="col">
                        <h6><i class="fa-solid fa-user"></i> កែប្រែដោយ</h6>
                        <hr>
                        <?php while($updated_by=$update_by->fetch_object()){?>
                        <ul>

                            <li class="list-group-item py-2">ឈ្មោះ ៖
                                <?php echo $updated_by -> FirstName .' '.$updated_by -> LastName ?></li>
                            <li class="list-group-item py-2">អង្គភាព ៖ <?php echo $updated_by -> WorkPlace ?></li>
                            <li class="list-group-item py-2">កាលបរិច្ឆេទ ៖ <?php echo $updated_by -> edited_at ?></li>

                        </ul>

                        <?php } ?>
                    </div>
                    <?php }?>

                </div>

            </div>
        </div>
    </div>
</div>



<?php include("../layouts/footer.php");?>


<!-- Modal -->
<div class="modal fade" id="imageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Cover Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="<?php echo $burl."/admin/".$book->filePath ?>" class="img-fluid" alt="Full Image">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal PDF -->
<!-- <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">PDF Viewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfIframe" src="" width="100%" height="600px" style="border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> -->


<!-- Draggable Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="draggableModal">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="pdfModalLabel">PDF Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <iframe id="pdfIframe" src="" width="100%" height="600px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var myModal = new bootstrap.Modal(document.getElementById('pdfModal'));

    // For debugging: output the URL to the console
    var pdfUrl = "<?php echo $burl."/admin/".  $book->PDFFile; ?>";
    console.log('PDF URL:', pdfUrl);

    document.getElementById('pdfModal').addEventListener('show.bs.modal', function() {
        var iframe = document.getElementById('pdfIframe');
        iframe.src = pdfUrl; // Set the src to the internal PDF path
    });

    document.getElementById('pdfModal').addEventListener('hide.bs.modal', function() {
        var iframe = document.getElementById('pdfIframe');
        iframe.src = ""; // Clear the iframe src to stop PDF loading
    });
});


// Make modal draggable
const modal = document.getElementById('draggableModal');
const header = document.getElementById('modalHeader');

header.addEventListener('mousedown', function(e) {
    const offsetX = e.clientX - modal.getBoundingClientRect().left;
    const offsetY = e.clientY - modal.getBoundingClientRect().top;

    function onMouseMove(e) {
        modal.style.left = `${e.clientX - offsetX}px`;
        modal.style.top = `${e.clientY - offsetY}px`;
    }

    function onMouseUp() {
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
    }

    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
});
</script>