<?php
    $title = "View Book";
    $page = "View Book";
    include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php");

    include($_SERVER['DOCUMENT_ROOT'] . "/library/protect.php");
    // protect_guest_folder();

    protect_guest_folder();
    
    
    

    echo $_SESSION['user_id'];

    // Fetch categories
    $categoryQuery = "SELECT * FROM tblcategory";
    $categoryResult = $conn->query($categoryQuery);

    // Initialize subcategories array
    $subcategories = [];

    // Fetch all subcategories and group them by category
    $subcategoryQuery = "SELECT * FROM tblsubcategory";
    $subcategoryResult = $conn->query($subcategoryQuery);

    while ($row = $subcategoryResult->fetch_assoc()) {
        $subcategories[$row['CatID']][] = $row;
    }

    // Set the number of books per page
    $per_page = 12;

    // Get the current page number from the query string; default is 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);
    $start_page = ($page - 1) * $per_page;

    // Initialize search term, filters, and conditions
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $searchCondition = $categoryFilter = $subcategoryFilter = "";

    // Get category and subcategory from the query string
    $categoryParam = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    $subCategoryParam = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : 0;

    // If a search term is provided, build the search condition
    if ($search !== '') {
        $search = preg_replace('/\s+/', '', $search); // Remove whitespace
        $search = $conn->real_escape_string($search); // Escape special characters
        $search = str_replace('-', ' - ', $search); // Escape hyphen specifically

        // Build the search condition
        $searchCondition = " AND (tblbook.BTitle LIKE '%$search%' OR tblbook.FullCode LIKE '%$search%')";
    }

    // If a subcategory is selected, we also find its associated category
    if ($subCategoryParam != 0) {
        $categoryQueryForSubCat = "SELECT CatID FROM tblsubcategory WHERE SubCatID = $subCategoryParam";
        $categoryResultForSubCat = $conn->query($categoryQueryForSubCat);
        if ($categoryResultForSubCat->num_rows > 0) {
            $categoryRowForSubCat = $categoryResultForSubCat->fetch_assoc();
            $categoryParam = $categoryRowForSubCat['CatID'];
        }
    }

    // If category is selected
    if ($categoryParam != 0) {
        $categoryFilter = " AND tblsubcategory.CatID = $categoryParam";
    }

    // If subcategory is selected
    if ($subCategoryParam != 0) {
        $subcategoryFilter = " AND tblbook.SubCatID = $subCategoryParam";
    }

    // Query to count books matching the search and filters
    $CountBookResult = $conn->query("
        SELECT COUNT(*) AS total, tbllanguage.LangName as BLang 
        FROM tblbook
        INNER JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID 
        LEFT JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID 
        LEFT JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID 
        WHERE tblbook.BookCode > 0
        $searchCondition
        $categoryFilter
        $subcategoryFilter
    ");
    $CountBook = $CountBookResult->fetch_object();
    $totalPage = ceil($CountBook->total / $per_page);

    // Final Query including filters and search condition
    $bookQuery = "
        SELECT tblbook.*, 
            tblcategory.CatName AS main_type, 
            tblsubcategory.SubCatName AS sub_type, 
            tbllanguage.LangName AS Blang
        FROM tblbook
        LEFT JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
        LEFT JOIN tblsubcategory ON tblbook.SubCatID = tblsubcategory.SubCatID 
        LEFT JOIN tblcategory ON tblsubcategory.CatID = tblcategory.CatID
        WHERE tblbook.BookCode > 0
        $searchCondition
        $categoryFilter
        $subcategoryFilter
        LIMIT $per_page OFFSET $start_page
    ";
    $book = $conn->query($bookQuery);

    // Handle query errors (optional)
    if (!$book) {
        die("Database query failed: " . $conn->error);
    }
    $user_id = $_SESSION['auth'];
    $users = $conn->query("SELECT * FROM tblguest WHERE GuestID = '$user_id'")->fetch_object();
?>

<div class="container-fluid">
    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg bg-success sticky-top ">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarScroll">
                <div class="col-12  col-lg-4">
                    <form class="d-flex" role="search" method="GET" action="index.php">
                        <div class="input-group">
                            <input type="search" id="searchInput" name="search" value="<?php echo $search ?>"
                                placeholder="ស្វែងរកសៀវភៅតាមរយៈ ចំណងជើង លេខកូដ និងអ្នកនិពន្ធ" class="form-control">
                            <input type="hidden" name="category" value="<?php echo $categoryParam; ?>">
                            <input type="hidden" name="subcategory" value="<?php echo $subCategoryParam; ?>">
                            <button class="btn btn-danger me-2" type="submit"><i
                                    class="fa-solid fa-magnifying-glass"></i> ស្វែងរក</button>
                        </div>
                    </form>

                </div>
                <div class="col-12  col-lg-4">
                    <h4 class="my-2 text-center text-white">បណ្ណាល័យក្រសួងសេដ្ឋកិច្ច និងហិរញ្ញវត្ថុ</h4>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">

                        <a class="nav-link d-none d-sm-inline-block">
                            <img src="<?php echo "https://ad-lb.mef.gov.kh/Assets/Users/" . $users->GuestID . "/pf.png"?>" class="avatar rounded me-4 " alt="Profile"
                                style="width: 60px; height: 60px; object-fit: cover;" />

                            <span class="text-white fs-6 text-center border rounded-pill border-primary px-3 py-2"
                                style="font-family:  Moul;"><?php echo $users->FirstName .'&nbsp&nbsp&nbsp'. $users->LastName ?></span>

                        </a>
                    </ul>
                    <a class="btn btn-danger" href="<?php echo $burl . "/admin/auth/action_logout.php"?>">ចាកចេញ</a>
                </div>
            </div>
        </div>
    </nav>

    <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>

    <main class="row mt-3">
        <!-- Sidebar (Categories) -->
        <aside class="col-12 col-md-3 col-lg-2 pt-3">
            <div class="list-group">
                <!-- Highlight "All Books" when no category is selected -->
                <a href="javascript:void(0)"
                    class="list-group-item list-group-item-action py-4 <?php echo ($categoryParam == 0) ? 'active' : ''; ?>"
                    onclick="selectCategory(0)">
                    សៀវភៅទាំងអស់
                </a>

                <?php while ($category = $categoryResult->fetch_assoc()): ?>
                <div class="dropdown">
                    <a href="javascript:void(0)"
                        class="list-group-item list-group-item-action py-4 <?php echo ($categoryParam == $category['CatID']) ? 'active' : ''; ?>"
                        data-bs-toggle="dropdown" onclick="selectCategory(<?php echo $category['CatID']; ?>)">
                        <?php echo $category['CatName']; ?>
                        <i class="fas fa-angle-right float-end"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (isset($subcategories[$category['CatID']])): ?>
                        <?php foreach ($subcategories[$category['CatID']] as $subcategory): ?>
                        <li>
                            <a href="javascript:void(0)"
                                class="dropdown-item <?php echo ($subCategoryParam == $subcategory['SubCatID']) ? 'active' : ''; ?>"
                                onclick="selectSubcategory(<?php echo $subcategory['SubCatID']; ?>)">
                                <?php echo $subcategory['SubCatName']; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <li><a class="dropdown-item" href="#">No subcategories</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endwhile; ?>
            </div>
        </aside>


        <!-- Books Section -->
        <div class="col-12 col-md-9 col-lg-10">
            <div class="row">
                <?php while ($row = $book->fetch_assoc()) { ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-2 d-flex align-items-stretch mt-4">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#bookModal"
                        data-book-title="<?php echo $row['BTitle']; ?>" data-book-id="<?php echo $row['BookID']; ?>"
                        data-book-author="<?php echo $row['BAuthor']; ?>" data-book-lang="<?php echo $row['Blang']; ?>"
                        data-book-source="<?php echo $row['BSource']; ?>"
                        data-book-published="<?php echo $row['BPublished']; ?>"
                        data-book-fullcode="<?php echo $row['FullCode']; ?>"
                        data-book-page="<?php echo $row['BPage']; ?>"
                        data-book-cover="<?php echo 'https://ad-lb.mef.gov.kh/' . $row['filePath']; ?>">
                        <div class="card">
                            <img src="<?php echo 'https://ad-lb.mef.gov.kh/' . $row['filePath']; ?>" class="full-image"
                                alt="Book Cover">
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
 
            <!-- Pagination Section -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-start mt-3">
                    <!-- Previous Button -->
                    <li class="page-item <?php echo $page - 1 == 0 ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo $burl . "/guest/index.php?page=" . ($page - 1) . "&search=" . urlencode($search) . "&category=" . $categoryParam . "&subcategory=" . $subCategoryParam; ?>">Previous</a>
                    </li>

                    <?php
                        if ($totalPage <= 7) { 
                            for ($i = 1; $i <= $totalPage; $i++) { ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo $burl . "/guest/index.php?page=" . $i . "&search=" . urlencode($search) . "&category=" . $categoryParam . "&subcategory=" . $subCategoryParam; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php }
                        } else {
                            if ($page > 3) {
                                echo '<li class="page-item"><a class="page-link" href="' . $burl . '/guest/index.php?page=1&search=' . urlencode($search) . '&category=' . $categoryParam . '&subcategory=' . $subCategoryParam . '">1</a></li>';
                                echo '<li class="page-item"><a class="page-link" href="' . $burl . '/guest/index.php?page=2&search=' . urlencode($search) . '&category=' . $categoryParam . '&subcategory=' . $subCategoryParam . '">2</a></li>';
                                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                            }
                            for ($i = max(1, $page - 1); $i <= min($page + 1, $totalPage); $i++) { ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo $burl . "/guest/index.php?page=" . $i . "&search=" . urlencode($search) . "&category=" . $categoryParam . "&subcategory=" . $subCategoryParam; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php }

                            if ($page < $totalPage - 2) {
                                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                                echo '<li class="page-item"><a class="page-link" href="' . $burl . '/guest/index.php?page=' . ($totalPage - 1) . '&search=' . urlencode($search) . '&category=' . $categoryParam . '&subcategory=' . $subCategoryParam . '">' . ($totalPage - 1) . '</a></li>';
                                echo '<li class="page-item"><a class="page-link" href="' . $burl . '/guest/index.php?page=' . $totalPage . '&search=' . urlencode($search) . '&category=' . $categoryParam . '&subcategory=' . $subCategoryParam . '">' . $totalPage . '</a></li>';
                            }
                        }
                     ?>

                    <!-- Next Button -->
                    <li class="page-item <?php echo $page + 1 > $totalPage ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo $burl . "/guest/index.php?page=" . ($page + 1) . "&search=" . urlencode($search) . "&category=" . $categoryParam . "&subcategory=" . $subCategoryParam; ?>">Next</a>
                    </li>
                </ul>

            </nav>
        </div>
    </main>
</div>


<!-- Button trigger modal -->
        <!-- <a href="#" class="btn position-fixed bottom-0 end-0 mb-3 me-3" data-bs-toggle="modal"
            data-bs-target="#staticBackdrop" style="background-color: #ff7c89; padding: 10px;">
                    មើលសៀវភៅដែលបានជ្រើសរើស
        </a> -->





        <!-- Button to view selected books, initially hidden -->
        <a id="view-selected-books" href="#" class="btn position-fixed bottom-0 end-0 mb-3 me-3 d-none" data-bs-toggle="modal"
        data-bs-target="#staticBackdrop" style="background-color: #ff7c89; padding: 10px;">
            មើលសៀវភៅដែលបានជ្រើសរើស
        </a>

<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/footer.php"); ?>
<!-- Script to handle category/subcategory selection -->
<script>
function selectCategory(catID) {
    const url = new URL(window.location.href);
    url.searchParams.set('category', catID);
    url.searchParams.delete('subcategory'); // Remove subcategory filter when switching categories
    window.location.href = url.toString();
}

function selectSubcategory(subCatID) {
    const url = new URL(window.location.href);
    url.searchParams.set('subcategory', subCatID);
    window.location.href = url.toString();
}

function selectCategory(catId) {
    const search = "<?php echo urlencode($search); ?>"; // Current search term
    const burl = "<?php echo $burl; ?>"; // Your base URL

    // Redirect to page 1 with the selected category
    window.location.href = `${burl}/guest/index.php?page=1&search=${search}&category=${catId}`;
}

function selectSubcategory(subCatId) {
    const category = "<?php echo $categoryParam; ?>"; // Current selected category
    const search = "<?php echo urlencode($search); ?>"; // Current search term
    const burl = "<?php echo $burl; ?>"; // Your base URL

    // Redirect to page 1 with the selected subcategory
    window.location.href =
        `${burl}/guest/index.php?page=1&search=${search}&category=${category}&subcategory=${subCatId}`;
}


document.addEventListener('DOMContentLoaded', function() {
    // Modal and Cart button elements
    const bookModal = document.getElementById('bookModal');
    const cartButton = document.getElementById('cartButton'); // Cart button outside modal
    const addToCartForm = document.getElementById('add-to-cart-form'); // Form for adding to cart
    const addToCartBtn = document.getElementById('add-to-cart-btn'); // Add to Cart button inside modal

    // Check if the cart button should be visible on page load (based on localStorage)
    if (localStorage.getItem('bookInCart') === 'true') {
        cartButton.style.display = 'block';
    }

    // Event listener to populate modal when it's triggered
    bookModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Button that triggered the modal

        // Fetch book data from the button's data attributes
        var bookTitle = button.getAttribute('data-book-title');
        var bookId = button.getAttribute('data-book-id');
        var bookAuthor = button.getAttribute('data-book-author');
        var bookLang = button.getAttribute('data-book-lang');
        var bookSource = button.getAttribute('data-book-source');
        var bookPublished = button.getAttribute('data-book-published');
        var bookFullCode = button.getAttribute('data-book-fullcode');
        var bookPage = button.getAttribute('data-book-page');
        var bookCover = button.getAttribute('data-book-cover');

        // Populate modal form fields
        document.getElementById('modal-book-title').value = bookTitle;
        document.getElementById('modal-book-id').value = bookId; // Hidden input
        document.getElementById('modal-book-author').value = bookAuthor;
        document.getElementById('modal-book-lang').value = bookLang;
        document.getElementById('modal-book-source').value = bookSource;
        document.getElementById('modal-book-published').value = bookPublished;
        document.getElementById('modal-book-fullcode').value = bookFullCode;
        document.getElementById('modal-book-page').value = bookPage;
        document.getElementById('modal-book-cover').src = bookCover; // Assuming this is an img element
    });

    // Event listener for the Add to Cart button inside the modal
    // addToCartForm.addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent default form submission

    //     // Log form data for debugging
    //     console.log('Form data:', {
    //         book_id: document.getElementById('modal-book-id').value,
    //         book_title: document.getElementById('modal-book-title').value,
    //         book_author: document.getElementById('modal-book-author').value,
    //         book_lang: document.getElementById('modal-book-lang').value,
    //         book_source: document.getElementById('modal-book-source').value,
    //         book_published: document.getElementById('modal-book-published').value,
    //         book_fullcode: document.getElementById('modal-book-fullcode').value,
    //         book_page: document.getElementById('modal-book-page').value,
    //     });

    //     // Submit the form data using Fetch API (if you prefer AJAX)
    //     fetch(addToCartForm.action, {
    //         method: 'POST',
    //         body: new FormData(addToCartForm),
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         console.log('Success:', data);
    //         if (data.success) {
    //             cartButton.style.display = 'block'; // Show the Cart button
    //             localStorage.setItem('bookInCart', 'true'); // Save cart state in localStorage
    //             document.getElementById('cart-message').innerText = "Book added to cart successfully!";
    //         } else {
    //             // Handle errors here
    //             console.error('Error adding book to cart:', data.message);
    //             document.getElementById('cart-message').innerText = "Failed to add book to cart.";
    //         }
    //     })
    //     .catch((error) => {
    //         console.error('Error:', error);
    //         document.getElementById('cart-message').innerText = "An error occurred.";
    //     });
    // });
});
</script>



<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/footer.php"); ?>

<?php

function findBookIndex($orders, $book_id) {
    foreach ($orders as $index => $order) {
        if (isset($order['book_id']) && $order['book_id'] == $book_id) {
            return $index;
        }
    }
    return -1;
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['orders']) || !is_array($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

// Define messages
$messages = [
    'added' => 'Book added to cart.',
    'decreased' => 'Book quantity decreased.',
    'removed' => 'Book removed from cart.',
    'not_found' => 'Book not found in cart.',
];

// Handle POST requests for adding, decreasing, or deleting books
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input to prevent XSS and other issues
    $book_id = isset($_POST['book_id']) ? htmlspecialchars($_POST['book_id']) : null;

    // Add a book to the cart
    if ($book_id) {
        $bookIndex = findBookIndex($_SESSION['orders'], $book_id);
        if ($bookIndex >= 0) {
            $_SESSION['orders'][$bookIndex]['quantity'] += 1;
            $message = $messages['added'];
        } else {
            $_SESSION['orders'][] = [
                'book_id' => $book_id,
                'quantity' => 1
            ];
            $message = $messages['added'];
        }
    }

    // Decrease the quantity of a book
    if (isset($_POST['decrease_book_id'])) {
        $decrease_book_id = htmlspecialchars($_POST['decrease_book_id']);
        $bookIndex = findBookIndex($_SESSION['orders'], $decrease_book_id);
        
        if ($bookIndex >= 0) {
            $_SESSION['orders'][$bookIndex]['quantity'] -= 1;
            if ($_SESSION['orders'][$bookIndex]['quantity'] <= 0) {
                array_splice($_SESSION['orders'], $bookIndex, 1);
                $message = $messages['removed'];
            } else {
                $message = $messages['decreased'];
            }
        } else {
            $message = $messages['not_found'];
        }
    }

    // Delete a book completely from the cart
    if (isset($_POST['delete_book_id'])) {
        $delete_book_id = htmlspecialchars($_POST['delete_book_id']);
        $bookIndex = findBookIndex($_SESSION['orders'], $delete_book_id);
        
        if ($bookIndex >= 0) {
            array_splice($_SESSION['orders'], $bookIndex, 1);
            $message = $messages['removed'];
        } else {
            $message = $messages['not_found'];
        }
    }

    // Redirect to avoid form resubmission with a success message
    header('Location: ' . $_SERVER['PHP_SELF'] . '?message=' . urlencode($message));
    exit;
}

?>


<!-- Extra Large Modal Structure -->

<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">ព័ត៌មានរបស់សៀវភៅ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="modal-book-cover" src="" alt="Book Cover" class="img-fluid">
                    </div>
                    <div class="col-md-8">
                        <form id="bookForm" action="<?php echo $burl . "/guest/index.php" ?>" method="POST">
                            <div class="mb-3">
                                <label for="modal-book-title" class="form-label"><strong>ចំណងជើងសៀវភៅ / ឯកសារ:</strong></label>
                                <input type="text" class="form-control" id="modal-book-title" name="book_title" required>
                            </div>

                            <input type="hidden" id="modal-book-id" name="book_id">

                            <div class="mb-3">
                                <label for="modal-book-author" class="form-label"><strong>អ្នកនិពន្ធ / ចងក្រង:</strong></label>
                                <input type="text" class="form-control" id="modal-book-author" name="book_author" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-book-lang" class="form-label"><strong>ភាសា:</strong></label>
                                <input type="text" class="form-control" id="modal-book-lang" name="book_lang" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-book-source" class="form-label"><strong>ប្រភព:</strong></label>
                                <input type="text" class="form-control" id="modal-book-source" name="book_source" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-book-published" class="form-label"><strong>ឆ្នាំបោះពុម្ព:</strong></label>
                                <input type="text" class="form-control" id="modal-book-published" name="book_published" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-book-page" class="form-label"><strong>ទំព័រ:</strong></label>
                                <input type="number" class="form-control" id="modal-book-page" name="book_page" required>
                            </div>

                            <div class="mb-3">
                                <label for="modal-book-fullcode" class="form-label"><strong>លេខកូដសៀវភៅ:</strong></label>
                                <input type="text" class="form-control" id="modal-book-fullcode" name="book_fullcode" required>
                            </div>

                        </form>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="add-to-cart-form" action="" method="POST">
                    <input type="hidden" name="book_id" id="modal-book-id">
                    <button type="submit" id="add-to-cart-btn" class="btn btn-info">Add to Cart</button>
                </form>
                <!-- Div to display the success message -->
                <div id="cart-message" class="mt-2 text-success"></div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.getElementById('add-to-cart-form');
    const cartMessage = document.getElementById('cart-message');

    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(addToCartForm);

            // Send the form data using fetch or XMLHttpRequest
            fetch('<?php echo $burl . "/guest/index.php"; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Show success message in the cart-message div
                cartMessage.textContent = 'Book added to cart successfully!';
            })
            .catch(error => {
                console.error('Error:', error);
                cartMessage.textContent = 'Error adding book to cart.';
            });
        });
    }
});
// Function to open the modal with book details
function openBookModal(book) {
    document.getElementById('modal-book-cover').src = book.cover; // Set book cover image
    document.getElementById('modal-book-title').value = book.title; // Set book title
    document.getElementById('modal-book-author').value = book.author; // Set book author
    document.getElementById('modal-book-lang').value = book.language; // Set book language
    document.getElementById('modal-book-source').value = book.source; // Set book source
    document.getElementById('modal-book-published').value = book.published; // Set published year
    document.getElementById('modal-book-page').value = book.pages; // Set page number
    document.getElementById('modal-book-fullcode').value = book.fullcode; // Set full code
    document.getElementById('modal-book-id').value = book.id; // Set book ID

    // Show the modal
    const bookModal = new bootstrap.Modal(document.getElementById('bookModal'));
    bookModal.show();
}

</script>

<!-- Modal for Selected Books -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="<?php echo $burl . '/guest/actions/store.php'; ?>" method="POST">
            <input type="hidden" name="myOrder" value='<?php echo json_encode($_SESSION['orders']); ?>'>
            <input type="hidden" name="myOrderIndex" value="<?php echo $index; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Selected Books</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th>លុប</th>
                            <th>ល.រ</th>
                            <th>ចំណងជើងសៀវភៅ /
                            ឯកសារ</th>
                            <th>អ្នកនិពន្ធ /
                            ចងក្រង</th>
                            <th>ភាសា</th>
                            <th>ប្រភព</th>
                            <th>ឆ្នាំបោះពុម្ព</th>
                            <th>ទំព័រ</th>
                            <th>កូដសៀវភៅ</th>
                            <th>ចំនួន</th>
                            </tr>
                        </thead>
                        <tbody id="order-items">
                            <?php
                            if (!empty($_SESSION['orders'])) {
                                $i = 1;
                                foreach ($_SESSION['orders'] as $key => $orderGroup) {
                                    foreach ($orderGroup as $orderitem) {
                                        if (isset($orderitem['book_id'])) {
                                            $book_id = $orderitem['book_id'];

                                            // Fetch book details from the database
                                            $getBook = $conn->query("SELECT tblbook.*, tbllanguage.LangName AS Blang
                                                FROM tblbook
                                                INNER JOIN tbllanguage ON tblbook.LangID = tbllanguage.LangID
                                                WHERE tblbook.BookID = '$book_id'")->fetch_object();
                                            
                                            if ($getBook) {
                            ?>
                                                <tr data-book-id="<?php echo $book_id; ?>" data-stock="<?php echo $getBook->BStock; ?>">
                                                <input type="hidden" name="book_id" value='<?php echo htmlspecialchars($getBook->BookID); ?>'>
                                                <input type="hidden" name="role_id" value='<?php echo htmlspecialchars($getBook->RoleID); ?>'>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm delete-item" title="Remove from cart">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->BTitle); ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->BAuthor); ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->Blang); ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->BSource); ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->BPublished); ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->BPage); ?></td>
                                                    <td><?php echo htmlspecialchars($getBook->FullCode);; ?></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col text-end">
                                                                <button class="btn btn-danger btn-sm decrease-qty" <?php echo ($orderitem['quantity'] <= 1) ? 'disabled' : ''; ?>>-</button>
                                                                <span><?php echo $orderitem['quantity']; ?></span>
                                                                <button class="btn btn-success btn-sm increase-qty">+</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                            <?php
                                            } else {
                                                echo "<tr><td colspan='10'>Error: Book not found in database</td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10'>Error: Book ID not set in order item</td></tr>";
                                        }
                                    }
                                }
                            } else {
                                echo "<tr><td colspan='10'>Your cart is empty</td></tr>";
                                // Hide the button if the cart is empty
                                echo "<script>document.getElementById('view-selected-books').classList.add('d-none');</script>";
                            }

                            
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">ស្នើសុំខ្ចីសៀវភៅ</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
$(document).ready(function() {
    // Function to update button visibility based on the cart items
    function updateCartButtonVisibility() {
        const viewButton = $('#view-selected-books');
        const orderItemsCount = $('#order-items tr').length; // Count rows excluding header

        if (orderItemsCount > 1) { // More than 1 means there are items (1 for the header)
            viewButton.removeClass('d-none');
        } else {
            viewButton.addClass('d-none');
        }
    }

    // Increase Quantity
    $('#order-items').on('click', '.increase-qty', function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var bookId = row.data('book-id');
        
        var quantityElement = row.find('span');
        var currentQuantity = parseInt(quantityElement.text());
        var stock = row.data('stock'); // Available stock

        if (currentQuantity < stock) {
            $.post('<?php echo $burl . "/guest/actions/handle_view_modal.php"; ?>', 
            { action: 'increase', book_id: bookId }, 
            function(response) {
                if (response.success) {
                    var newQuantity = currentQuantity + 1;
                    quantityElement.text(newQuantity);
                    row.find('.decrease-qty').prop('disabled', false);
                    updateCartButtonVisibility(); // Update visibility of the view button
                } else {
                    alert(response.message);
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error:", textStatus, errorThrown);
            });
        } else {
            $('#alertMessage').text("ចំនួនសៀវភៅក្នងបណ្ណាល័យមិនគ្រប់គ្រាន់");
            $('#customAlert').modal('show'); // Show alert modal
        }
    });

    // Decrease Quantity
    $('#order-items').on('click', '.decrease-qty', function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var bookId = row.data('book-id');
        
        var quantityElement = row.find('span');
        var currentQuantity = parseInt(quantityElement.text());

        if (currentQuantity > 1) {
            $.post('<?php echo $burl . "/guest/actions/handle_view_modal.php"; ?>', 
            { action: 'decrease', book_id: bookId }, 
            function(response) {
                if (response.success) {
                    var newQuantity = currentQuantity - 1;
                    quantityElement.text(newQuantity);
                    if (newQuantity <= 1) {
                        row.find('.decrease-qty').prop('disabled', true);
                    }
                    updateCartButtonVisibility(); // Update visibility of the view button
                } else {
                    alert(response.message);
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error:", textStatus, errorThrown);
            });
        }
    });

    // Delete Item
    $('#order-items').on('click', '.delete-item', function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var bookId = row.data('book-id');

        $.post('<?php echo $burl . "/guest/actions/handle_view_modal.php"; ?>', 
        { action: 'delete', book_id: bookId }, 
        function(response) {
            if (response.success) {
                row.remove();
                updateCartButtonVisibility(); // Update visibility of the view button
            } else {
                alert(response.message);
            }
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus, errorThrown);
        });
    });

    // Initial visibility check for the cart button
    updateCartButtonVisibility();
});
</script>




<!-- Custom Alert Modal -->
<div class="modal fade" id="customAlert" tabindex="-1" aria-labelledby="customAlertLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="customAlertLabel">ជូនដំណឹង</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="alertMessage">
                <!-- Dynamic message goes here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">បិទ</button>
            </div>
        </div>
    </div>
</div>

