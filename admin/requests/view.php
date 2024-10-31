<?php

$title = "Detail Page";
$page = "request";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php");
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php");


// Fetch detail details
$borrow_id = $_GET['borrow_id'];

        $query = "SELECT 
        tblborrow.*,
        tblbook.*,
        tblguest.*,
        tblsubcategory.SubCatName,
        tblcategory.CatName,
        tbllanguage.LangName
    FROM tblborrow 
    LEFT JOIN tblbook ON tblborrow.BookID = tblbook.BookID  
    LEFT JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID
    LEFT JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
    LEFT JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
    LEFT JOIN tblguest ON tblguest.GuestID = tblborrow.GuestID  
    WHERE tblborrow.BorrowCode = '$borrow_id'";




$details = $conn->query($query);

$com_del = $conn->query($query);


// Start output buffering to capture concatenated titles
        $firstTitle = '';
        $lastTitle = '';
        $count = 0;

        while ($row = $com_del->fetch_object()) {
            if ($count === 0) {
                $firstTitle = $row->BTitle;
            }
            $lastTitle = $row->BTitle;
            $count++;
        }

?>

<div class="p-4">
        <div class="col-12 mb-3 d-flex justify-content-between">
            <a href="<?php echo $burl . "/admin/requests/index.php?" ?>" class="btn btn-danger">
                <i class="fa-solid fa-rotate-left"></i> Back
            </a>
            <div>
            <!-- Button to open the modal -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal"
                    data-first-title="<?php echo htmlspecialchars($firstTitle); ?>"
                    data-last-title="<?php echo $count > 1 ? htmlspecialchars($lastTitle) : ''; ?>"
                    data-id="<?php echo $borrow_id; ?>">
                <i class="fa-solid fa-trash"></i> បដិសេធ
            </button>

            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#confirmAproveModal"
                    data-first-title="<?php echo htmlspecialchars($firstTitle); ?>"
                    data-last-title="<?php echo $count > 1 ? htmlspecialchars($lastTitle) : ''; ?>"
                    data-id="<?php echo $borrow_id; ?>">
                    <i class="fa-solid fa-check"></i> អនុម័ត
            </button>


            </div>
        </div>

    <div class="row">
        <div class="col">
            <div class="bg-white p-4 rounded">

            <?php while ($detail = $details->fetch_object()) { ?>
                    <div class="row">
                        <div class="col-10">
                            <div class="bg-white p-4 rounded">
                                <div class="m-1 p-1">ចំណងជើងលិខិត</div>
                                <?php if($detail->BTitle <> "") { ?>
                                <span class="badge bg-primary rounded-pill fs-6" ><?php echo $detail->BTitle ?> </span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col">
                            <?php if($detail->filePath <> "") { ?>
                            <img src="<?php echo "https://ad-lb.mef.gov.kh/"  . $detail->filePath; ?>" width="100"
                                class="img-thumbnail rounded" alt="Image" data-bs-toggle="modal"
                                data-bs-target="#imageModal" style="width: 100px; cursor: pointer;">
                            <?php } ?>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">អ្នកនិពន្ធ/ចងក្រង</div>
                        <?php if($detail->BAuthor <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->BAuthor ?></span>
                        <?php } ?>
                    </div>
                    <hr>


                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">ភាសា</div>
                        <?php if($detail->LangName <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->LangName ?></span>
                        <?php } ?>
                    </div>
                    <hr>

                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">សំណុំប្រភេទសៀវភៅ</div>
                        <?php if($detail->CatName <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->CatName ?></span>
                        <?php } ?>
                    </div>
                    <hr>

                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">ប្រភេទប្រភេទសៀវភៅ</div>
                        <?php if($detail->SubCatName <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->SubCatName ?></span>
                        <?php } ?>
                    </div>
                    <hr>

                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">ឆ្នាំបោះពុម្ព</div>
                        <?php if($detail->BPublished <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->BPublished ?></span>
                        <?php } ?>
                    </div>
                    <hr>

                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">ចំនួនទំព័រ</div>
                        <?php if($detail->BPage <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->BPage ?></span>
                        <?php } ?>
                    </div>
                    <hr>

                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">ចំនួនសៀវភៅ</div>
                        <?php if($detail->BStock <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->BStock ?></span>
                        <?php } ?>
                    </div>
                    <hr>



                    <div class="bg-white p-4 rounded">
                        <div class="m-1 p-1">កូដ</div>
                        <?php if($detail->FullCode <> "") { ?>
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->FullCode ?></span>
                        <?php } ?>
                    </div>
                    <hr>

                    <div class="m-1 p-1">PDF File</div>
                    <?php if($detail->PDFFile <> "") { ?>
                    <div class="bg-white p-4 rounded">
                        <span class="badge bg-primary rounded-pill fs-6"><?php echo $detail->PDFFile ?></span>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pdfModal"><i
                                class="fa-solid fa-eye"></i></button>
                    </div>
                    <?php } ?>
                    <hr style="height:5px;border:none;color:DodgerBlue;background-color:DodgerBlue;">


                <div class="row pt-3">
                    <div class="col">
                        <h6><i class="fa-solid fa-user"></i> ស្នើសុំខ្ចីដោយ</h6>
                        <hr>
                        <ul>

                            <li class="list-group-item py-2">ឈ្មោះ ៖
                                <?php echo $detail -> LastName. ' '.$detail -> FirstName ?></li>
                            <li class="list-group-item py-2">អង្គភាព ៖ <?php echo $detail -> Workplace ?></li>
                            <li class="list-group-item py-2">កាលបរិច្ឆេទ ៖ <?php echo $detail -> DateBorrowed ?></li>

                        </ul>

                    </div>

                </div>



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
                                    <img src="<?php echo "https://ad-lb.mef.gov.kh/" . $detail->filePath ?>" class="img-fluid" alt="Full Image">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    
                <?php } // End of while loop ?>


            </div>
        </div>
    </div>
</div>

<?php include("../layouts/footer.php"); ?>



<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">បញ្ជាក់ ការបដិសេធ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="itemName"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">មិនព្រម</button>
                <form action="<?php echo $burl . "/admin/requests/actions/delete.php"; ?>" method="POST" id="deleteForm">
                    <input type="hidden" name="borrow_id" id="borrow_id" value="<?php echo $borrow_id; ?>">
                    <button type="submit" class="btn btn-danger">បដិសេធ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script for delete confirmation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');

    confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var firstTitle = button.getAttribute('data-first-title');
        var lastTitle = button.getAttribute('data-last-title');

        
        var modalItemName = confirmDeleteModal.querySelector('#itemName');

        // Check if there's a last title
        if (lastTitle) {
            modalItemName.innerHTML = `តើអ្នកចង់បដិសេធ ៖ ${firstTitle}<br>តើអ្នកចង់បដិសេធ ៖ ${lastTitle}`;
        } else {
            modalItemName.innerHTML = `តើអ្នកចង់បដិសេធ ៖ ${firstTitle}`;
        }
    });
});

</script>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="confirmAproveModal" tabindex="-1" aria-labelledby="confirmAproveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmAproveModalLabel">បញ្ជាក់ អនុមត់</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="itemName"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">មិនព្រម</button>
                <form action="<?php echo $burl . '/admin/requests/actions/aprove.php'; ?>" method="POST" id="approveForm">
                    <input type="hidden" name="borrow_id" id="borrow_id" value="<?php echo $borrow_id; ?>">
                    <button type="submit" class="btn btn-danger">អនុម័ត</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script for approval confirmation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var confirmAproveModal = document.getElementById('confirmAproveModal');

    confirmAproveModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var firstTitle = button.getAttribute('data-first-title');
        var lastTitle = button.getAttribute('data-last-title');

        var modalItemName = confirmAproveModal.querySelector('#itemName');

        // Check if there's a last title
        if (lastTitle) {
            modalItemName.innerHTML = `តើអ្នកចង់អនុមត់ ៖ ${firstTitle}<br>តើអ្នកចង់អនុមត់ ៖ ${lastTitle}`;
        } else {
            modalItemName.innerHTML = `តើអ្នកចង់អនុមត់ ៖ ${firstTitle}`;
        }
    });
});
</script>



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






    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var myModal = new bootstrap.Modal(document.getElementById('pdfModal'));

                            // For debugging: output the URL to the console
                            var pdfUrl = "<?php echo "https://ad-lb.mef.gov.kh/".  $detail->PDFFile; ?>";
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