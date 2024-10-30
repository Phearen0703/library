<?php
    $title = "book Page";
    $page = "book";
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php"); ?>

<?php
date_default_timezone_set('Asia/Bangkok');
$per_page = 100;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Ensure $page is at least 1 to avoid negative offsets
$page = max(1, $page);
$start_page = ($page - 1) * $per_page;

$CountBook = 0;
$totalPage = 0;
$search = "";
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'ASC';
$keyOrder = isset($_GET['keyOrder']) ? $_GET['keyOrder'] : 'tblbook.FullCode';



$from = isset($_GET['from']) ? date('Y-m-d',strtotime($_GET['from'])) : date('Y');
$to = isset($_GET['to']) ? date('Y-m-d H:i:s',strtotime($_GET['to'])) : date('Y-m-d H:i:s');




// Sanitize and trim the search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $search = preg_replace('/\s+/', '', $search);

    // Ensure $search is properly escaped to prevent SQL injection
    $search = $conn->real_escape_string($search);

    // Escape the hyphen specifically
    $search = str_replace('-', ' - ', $search);


    // Build the search condition
    $searchCondition = "AND tblbook.BTitle LIKE '%$search%'
                        OR tblbook.FullCode LIKE '%$search%'
                        OR tbluser.FirstName LIKE '%$search%'
                        OR tbluser.LastName LIKE '%$search%'
                        ";
                        
} else {
    $searchCondition = "";
}

                            
// Count total books
$CountBook = $conn->query("SELECT COUNT(*) AS total FROM tblbook
                            LEFT JOIN tblroles ON tblbook.RoleID = tblroles.RoleID
                            LEFT JOIN tbluser ON tblroles.UserID = tbluser.UserID
                            LEFT JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
                            LEFT JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID
                            LEFT JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
                            WHERE BRegistered >= '$from' AND BRegistered <= '$to'
                            $searchCondition")->fetch_object();
$totalPage = ceil($CountBook->total / $per_page);

// Fetch books with pagination
$books = $conn->query("SELECT tblbook.*,   
                       tblroles.RoleID as user_id, 
                       tbluser.FirstName, 
                       tbluser.LastName,
                       tbllanguage.LangName as lang_name,
                       tblcategory.CatName as main_type,
                       tblsubcategory.SubCatName as sub_type
                       FROM tblbook
                       LEFT JOIN tblroles ON tblbook.RoleID = tblroles.RoleID
                       LEFT JOIN tbluser ON tblroles.UserID = tbluser.UserID
                       LEFT JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
                       LEFT JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID
                       LEFT JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
                       WHERE BRegistered >= '$from' AND BRegistered <= '$to'
                       $searchCondition
                       ORDER BY $keyOrder $orderBy
                       LIMIT $per_page OFFSET $start_page");

$orderBy = $orderBy === 'ASC' ? 'DESC' : 'ASC';

?>



<div class="p-2">
    <div class="card">
        <div class="card-header">
            <h4 style=" font-family: Moul;">ការគ្រប់គ្រងសៀវភៅ</h4>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12 mb-3">
                    <a href="<?php echo $burl  . "/admin/books/create.php"?>" class="btn btn-success"><i
                            class="fa-solid fa-plus"></i> បង្កើតសៀវភៅថ្មី</a>
                </div>
            </div>

            <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>


            <form action="<?php echo $burl . '/admin/books/index.php' ?>" method="get">
                <div class="row g-3 d-flex align-items-center flex-lg-nowrap">
                    <!-- Search Input (Full width on larger screens) -->
                    <div class="col-lg-3">
                        <div class="input-group">
                            <input type="hidden" name="page" value="">
                            <input type="search" name="search" class="form-control" value="<?php echo $search ?>"
                                placeholder="Search">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-magnifying-glass"></i> ស្វែងរក
                            </button>
                        </div>
                    </div>

                    <!-- Date Inputs and Buttons (Full width on larger screens) -->
                    <div class="col-lg-9 d-flex align-items-center flex-lg-nowrap align-items-center">
                        <!-- From Date -->
                        <div class="col-md-6 col-lg-4 mb-2 mb-lg-0 flex-grow-1">
                            <div class="input-group px-1 me-lg-3 mb-2 mb-lg-0 flex-grow-1">
                                <label for="from" class="input-group-text bg-dark text-white">ចាប់ផ្ដើម</label>
                                <input type="date" name="from" value="<?php echo $from ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-2 mb-lg-0 flex-grow-1">
                            <div class="input-group px-1 me-lg-3 mb-2 mb-lg-0 flex-grow-1">
                                <label for="to" class="input-group-text bg-dark text-white">បញ្ចប់</label>
                                <input type="datetime-local" name="to" value="<?php echo $to ?>" class="form-control">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end flex-grow-1">
                            <button type="submit" class="btn btn-primary mx-2 rounded" style="font-family: Siemreap;">
                                យល់បព្រម
                            </button>
                            <a href="<?php echo $burl . '/admin/books/actions/export.php?from=' . $from . '&to=' . $to . '&keyOrder=' . $keyOrder . '&orderBy=' . $orderBy . '&searchCondition=' . urlencode($searchCondition); ?>"
                                class="btn btn-success mx-2 rounded">
                                ទាញយកជា Excel
                            </a>



                        </div>
                    </div>
                </div>
            </form>



            <table class="table mt-3 table-sm table-bordered text-center">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th class="col-2">ចំណងជើង</th>
                        <th class="col-1">អ្នកនិពន្ធ/ចងក្រង</th>
                        <th>ប្រភព</th>
                        <th>ភាសា</th>
                        <th class="col-1">ប្រភេទ</th>
                        <th>ឆ្នាំបោះពុម្ព</th>
                        <th>ទំព័រ</th>
                        <th>ចំនួន</th>
                        <th>តម្លៃ</th>
                        <th>កាលបរិច្ឆេទបញ្ចូល</th>
                        <th>កូដសៀបភៅ<a
                                href="<?php echo $burl . '/admin/books/index.php?search=' .$search . '&orderBy=' . $orderBy; ?>"
                                class="sort float-end mx-3"><i class="fa-solid fa-sort"></i></a></th>
                        <th>អ្នកប្របញ្ចូល</th>
                        <th class="bg-primary text-white">ប្រតិបត្តិការ</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $i = $start_page ?>
                    <?php while($book = $books->fetch_object()) { ?>
                    <tr>
                        <th class="align-middle"><?php echo ++$i ?></th>
                        <td class="text-start"><?php echo $book -> BTitle ?></td>
                        <td class="text-start"><?php echo $book -> BAuthor ?></td>
                        <td><?php echo $book -> BSource ?></td>
                        <td><?php echo $book -> lang_name ?></td>
                        <td><?php echo $book -> main_type . "<br>" . $book->sub_type?></td>
                        <td><?php echo $book -> BPublished ?></td>
                        <td><?php echo $book -> BPage ?></td>
                        <td><?php echo $book -> BStock ?></td>
                        <td><?php echo $book -> BPrice ?></td>
                        <td><?php echo $book -> BRegistered ?></td>
                        <td><?php echo $book -> FullCode ?></td>
                        <td><?php echo $book -> LastName . " " .$book -> FirstName?></td>
                        <td>
                            <a href="<?php echo $burl . "/admin/books/edit.php?book_id=" . $book->BookID ?>"
                                class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal" data-name="<?php echo $book->BTitle; ?>"
                                data-id="<?php echo $book->BookID; ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <a href="<?php echo $burl . "/admin/books/view.php?book_id=" . $book->BookID ?>"
                                class="btn btn-sm btn-success"><i class="fa-solid fa-eye"></i></a>
                        </td>

                    </tr>

                    <?php } ?>


                </tbody>
            </table>

            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <li class="page-item <?php echo $page - 1 == 0 ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="<?php echo $burl . "/admin/books/index.php?page=" . ($page - 1); ?>">Previous</a>
                            </li>

                            <?php
                if ($totalPage <= 7) { 
                    // If there are less than 7 total pages, show all
                    for ($i = 1; $i <= $totalPage; $i++) { ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="<?php echo $burl . "/admin/books/index.php?page=" . $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php }
                } else {
                    // Show the first two pages
                    if ($page > 3) {
                        echo '<li class="page-item"><a class="page-link" href="' . $burl . '/admin/books/index.php?page=1">1</a></li>';
                        echo '<li class="page-item"><a class="page-link" href="' . $burl . '/admin/books/index.php?page=2">2</a></li>';
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    }

                    // Show the surrounding pages (current, one before, one after)
                    for ($i = max(1, $page - 1); $i <= min($page + 1, $totalPage); $i++) { ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="<?php echo $burl . "/admin/books/index.php?page=" . $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php }

                    // Show last two pages
                    if ($page < $totalPage - 2) {
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                        echo '<li class="page-item"><a class="page-link" href="' . $burl . '/admin/books/index.php?page=' . ($totalPage - 1) . '">' . ($totalPage - 1) . '</a></li>';
                        echo '<li class="page-item"><a class="page-link" href="' . $burl . '/admin/books/index.php?page=' . $totalPage . '">' . $totalPage . '</a></li>';
                    }
                }
                ?>

                            <!-- Next Button -->
                            <li class="page-item <?php echo $page + 1 > $totalPage ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                    href="<?php echo $burl . "/admin/books/index.php?page=" . ($page + 1); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">បញ្ជាក់ ការលុប</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                តើអ្នកចង់លុប ៖ <strong id="itemName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">មិនព្រម</button>
                <form action="<?php echo $burl . "/admin/books/actions/delete.php" ?>" method="POST" id="deleteForm">
                    <input type="hidden" name="book_id" id="book_id" value="">
                    <button type="submit" class="btn btn-danger">លុប</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');

    confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var bookTitle = button.getAttribute('data-name'); // Extract book title
        var bookId = button.getAttribute('data-id'); // Extract book ID

        // Update the modal content
        var modalItemName = confirmDeleteModal.querySelector('#itemName');
        var modalItemId = confirmDeleteModal.querySelector('#book_id');

        modalItemName.textContent = bookTitle; // Set the book title in the modal
        modalItemId.value = bookId; // Set the book ID in the hidden input
    });
});
</script>

<?php include("../layouts/footer.php");?>