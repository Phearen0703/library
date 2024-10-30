<?php

$title = "Edit Book Page";
$page = "book";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php");
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php");

$category = $conn->query("SELECT * FROM tblcategory");
$language = $conn->query("SELECT * FROM tbllanguage");

// Fetch book details
$book_id = $_GET['book_id'];
$query = "SELECT b.*, sc.SubCatID, sc.SubCatName, c.CatID, c.CatName 
          FROM tblbook b
          INNER JOIN tblsubcategory sc ON b.SubCatID = sc.SubCatID
          INNER JOIN tblcategory c ON sc.CatID = c.CatID
          WHERE b.BookID = '$book_id'";
$books = $conn->query($query);
$book = $books->fetch_object();

?>
<div class="p-2">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Book</h4>
        </div>

        <div class="card-body">
        <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php"); ?>

            <div class="col-12 mb-3">
                <a href="<?php echo $burl . "/admin/books/index.php"; ?>" class="btn btn-danger"><i
                        class="fa-solid fa-rotate-left"></i> Back</a>
            </div>

            <form action="<?php echo $burl . "/admin/books/actions/edit.php"; ?>" class="needs-validation" novalidate
                method="post" enctype="multipart/form-data" id="bookForm">
                <div class="row">
                    <div class="col">
                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book->BookID);?>">
                        <!-- Book Title -->
                        <div class="mb-3">
                        <label for="name">ចំណងជើង (សៀវភៅ/ឯកសារ)</label>
                            <input type="text" id="BTitle" name="BTitle" class="form-control"
                                value="<?php echo htmlspecialchars($book->BTitle); ?>" required>
                            <div class="invalid-feedback">
                                Please enter a book title.
                            </div>
                        </div>

                        <!-- Author -->
                        <div class="mb-3">
                            <label for="price">អ្នកនិពន្ធ/រៀបចំ</label>
                            <input type="text" id="BAuthor" name="BAuthor" class="form-control"
                                value="<?php echo htmlspecialchars($book->BAuthor); ?>" required>
                            <div class="invalid-feedback">
                                Please enter the author's name.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price">ប្រភព</label>
                            <input type="text" id="BSource" name="BSource" class="form-control"
                            value="<?php echo htmlspecialchars($book->BSource); ?>" required>
                            <div class="invalid-feedback">
                                សូបបញ្ចូលប្រភព
                            </div>
                        </div>

                        <!-- Language -->
                        <div class="mb-3">
                        <label for="BLang">ភាសា</label>
                            <div class="input-group">
                                <select name="BLang" class="form-select" required>
                                    <option selected disabled value="">Please select a language</option>
                                    <?php while($languages = $language->fetch_object()) { ?>
                                    <option value="<?php echo $languages->LangID; ?>"
                                        <?php echo $book->LangID == $languages->LangID ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($languages->LangName); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="mb-3">
                            <label for="categoryDropdown">សំណុំប្រភេទសៀវភៅ</label>
                            <div class="input-group">
                                <select name="MBtype" id="categoryDropdown" class="form-select" required>
                                    <option selected disabled>Choose a category</option>
                                    <?php while($categorys = $category->fetch_object()) { ?>
                                    <option value="<?php echo $categorys->CatID; ?>"
                                        <?php echo $categorys->CatID == $book->CatID ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categorys->CatName); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Subcategory Dropdown -->
                        <div class="mb-3">
                            <label for="subcategoryDropdown">ប្រភេទសៀវភៅ</label>
                            <div class="input-group">
                                <select name="SBType" id="subcategoryDropdown" class="form-select" required>
                                    <option selected disabled>Choose a subcategory</option>
                                    <!-- Dynamically populate based on selected category -->
                                    <option value="<?php echo $book->SubCatID; ?>" selected>
                                        <?php echo htmlspecialchars($book->SubCatName); ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Book Image -->
                        <div class="mb-3 d-flex justify-content-center">
                            <?php if (!empty($book->filePath)): ?>
                            <img src="<?php echo $burl."/admin/". $book->filePath; ?>" alt="Book Image"
                                class="rounded" width="180">
                            <?php endif; ?>
                        </div>
                    </div>
      
                    <div class="col">
                        <!-- Year Published -->
                        <div class="mb-3">
                            <label for="BPublished">ឆ្នាំបោះពុម្ព</label>
                            <input type="text" id="BPublished" name="BPublished" class="form-control"
                                value="<?php echo htmlspecialchars($book->BPublished); ?>" required>
                            <div class="invalid-feedback">
                                Please enter the year published.
                            </div>
                        </div>

                        <!-- Number of Pages -->
                        <div class="mb-3">
                            <label for="BPage">ទំព័រ</label>
                            <input type="text" id="BPage" name="BPage" class="form-control"
                                value="<?php echo htmlspecialchars($book->BPage); ?>" required>
                            <div class="invalid-feedback">
                                Please enter the number of pages.
                            </div>
                        </div>

                        <!-- Book Stock -->
                        <div class="mb-3">
                            <label for="BStock">ចំនួន</label>
                            <input type="text" id="BStock" name="BStock" class="form-control"
                                value="<?php echo htmlspecialchars($book->BStock); ?>" required>
                            <div class="invalid-feedback">
                                Please enter the stock quantity.
                            </div>
                        </div>

                        <!-- Book Price -->
                        <div class="mb-3">
                            <label for="BPrice">តម្លៃ</label>
                            <input type="text" id="BPrice" name="BPrice" class="form-control"
                                value="<?php echo htmlspecialchars($book->BPrice); ?>" required>
                            <div class="invalid-feedback">
                                Please enter the book price.
                            </div>
                        </div>

                        <!-- E-book Checkbox -->
                        <div class="row mb-3">
                            <div class="col-2 pt-4">
                                <label for="E_book">E_book</label>
                                <input class="form-check-input" type="checkbox" id="E_book" name="E_book" value="1"
                                    <?php echo !empty($book->EBookCode) && $book->EBookCode >= 1 ? 'checked' : ''; ?>>
                            </div>
                            <div class="col">
                                <label for="FullCode">កូដ</label>
                                <input type="text" id="FullCode" name="FullCode" class="form-control"
                                    value="<?php echo htmlspecialchars($book->FullCode); ?>">


                                <!-- Add the book code textbox -->
                                <input type="hidden" id="BookCode" name="BookCode" readonly />
                            </div>
                        </div>




                        <!-- Upload Image -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="photo">Book Photo</label>
                                <input type="file" accept="image/*" id="photo" name="photo" class="form-control">
                            </div>

                            <!-- PDF Upload -->
                            <div class="col">
                                <label for="pdf_file">PDF File</label>
                                
                                <div class="input-group">
                                <input type="file" id="pdf_file" name="pdf_file" class="form-control">
                                <?php if (!empty($book->PDFFile)): ?>
                                <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pdfModal"><i class="fa-solid fa-eye"></i></a>
                                <?php endif; ?>
                                </div>  
                            </div>
                        </div>

                        <div class="mb-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                                កែប្រែ</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/footer.php"); ?>

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
//  document.addEventListener('DOMContentLoaded', function () {
//     const eBookCheckbox = document.getElementById('E_book');
//     const subcategoryDropdown = document.getElementById('subcategoryDropdown');
//     const fullCodeTextbox = document.getElementById('FullCode');
//     const bookCodeTextbox = document.getElementById('BookCode');
//     const categoryDropdown = document.getElementById('categoryDropdown');

//     let previousCategoryID = subcategoryDropdown.value;
//     let previousIsEbook = eBookCheckbox.checked;

//     function updateFullCode() {
//         const selectedOption = subcategoryDropdown.options[subcategoryDropdown.selectedIndex];
//         const selectedCategoryID = selectedOption ? selectedOption.value : '';

//         const isEbook = eBookCheckbox.checked;

//         if (selectedCategoryID !== previousCategoryID || isEbook !== previousIsEbook) {
//             fetch('<?php echo $burl . "/admin/books/back_end/create.php" ?>', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/x-www-form-urlencoded'
//                 },
//                 body: new URLSearchParams({
//                     action: 'getFullCode',
//                     SubCatID: selectedCategoryID,
//                     isEbook: isEbook
//                 })
//             })
//             .then(response => {
//                 if (!response.ok) {
//                     throw new Error(`Network response was not ok: ${response.statusText}`);
//                 }
//                 return response.text(); // Get raw response for debugging
//             })
//             .then(text => {
//                 console.log('Raw response for FullCode:', text); // Log raw response
//                 try {
//                     const data = JSON.parse(text); // Try parsing JSON response
//                     console.log('Parsed JSON for FullCode:', data); // Log parsed JSON
//                     if (data.fullCode) {
//                         fullCodeTextbox.value = data.fullCode;
//                         bookCodeTextbox.value = data.count;
//                         previousCategoryID = selectedCategoryID;
//                         previousIsEbook = isEbook;
//                     } else {
//                         console.error('FullCode not returned from server:', data);
//                     }
//                 } catch (error) {
//                     console.error('Error parsing JSON response for FullCode:', text); // Log parsing error
//                 }
//             })
//             .catch(error => console.error('Error fetching FullCode:', error));
//         }
//     }

//     function loadSubcategories() {
//         const categoryId = categoryDropdown.value;

//         fetch('<?php echo $burl . "/admin/books/back_end/create.php" ?>', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             },
//             body: new URLSearchParams({
//                 action: 'getSubcategories',
//                 CatID: categoryId
//             })
//         })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error(`Network response was not ok: ${response.statusText}`);
//             }
//             return response.text(); // Get raw response for debugging
//         })
//         .then(text => {
//             console.log('Raw response for subcategories:', text); // Log raw response
//             try {
//                 const data = JSON.parse(text); // Parse JSON response
//                 console.log('Parsed JSON for subcategories:', data); // Log parsed JSON
//                 if (data.success) {
//                     subcategoryDropdown.innerHTML = '';
//                     data.subcategories.forEach(subcategory => {
//                         let option = document.createElement('option');
//                         option.value = subcategory.SubCatID;
//                         option.textContent = subcategory.SubCatName;
//                         option.setAttribute('data-catnum', subcategory.catnum);
//                         subcategoryDropdown.appendChild(option);
//                     });
//                     subcategoryDropdown.dispatchEvent(new Event('change'));
//                 } else {
//                     console.error('Failed to retrieve subcategories:', data.message || 'No message provided');
//                 }
//             } catch (error) {
//                 console.error('Error parsing JSON response for subcategories:', text); // Log parsing error
//             }
//         })
//         .catch(error => console.error('Error fetching subcategories:', error));
//     }

//     categoryDropdown.addEventListener('change', loadSubcategories);
//     subcategoryDropdown.addEventListener('change', updateFullCode);
//     eBookCheckbox.addEventListener('change', updateFullCode);

//     loadSubcategories();
// });

document.addEventListener('DOMContentLoaded', function () {
    const eBookCheckbox = document.getElementById('E_book');
    const subcategoryDropdown = document.getElementById('subcategoryDropdown');
    const fullCodeTextbox = document.getElementById('FullCode');
    const bookCodeTextbox = document.getElementById('BookCode');
    const categoryDropdown = document.getElementById('categoryDropdown');

    let previousCategoryID = subcategoryDropdown.value;
    let previousIsEbook = eBookCheckbox.checked;

    function updateFullCode() {
        const selectedOption = subcategoryDropdown.options[subcategoryDropdown.selectedIndex];
        const selectedCategoryID = selectedOption ? selectedOption.value : '';
        const isEbook = eBookCheckbox.checked;

        if (selectedCategoryID !== previousCategoryID || isEbook !== previousIsEbook) {
            fetch('<?php echo $burl . "/admin/books/back_end/edit.php" ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'getFullCode',
                    SubCatID: selectedCategoryID,
                    isEbook: isEbook
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.statusText}`);
                }
                return response.text();
            })
            .then(text => {
                // console.log('Raw response for FullCode:', text);
                try {
                    const data = JSON.parse(text);
                    // console.log('Parsed JSON for FullCode:', data);
                    if (data.fullCode && data.count) {
                        fullCodeTextbox.value = data.fullCode;
                        bookCodeTextbox.value = data.count;
                        previousCategoryID = selectedCategoryID;
                        previousIsEbook = isEbook;
                    } else {
                        console.error('FullCode or count missing from response:', data);
                    }
                } catch (error) {
                    console.error('Error parsing JSON response for FullCode:', text);
                }
            })
            .catch(error => console.error('Error fetching FullCode:', error));
        }
    }

    function loadSubcategories() {
        const categoryId = categoryDropdown.value;

        fetch('<?php echo $burl . "/admin/books/back_end/edit.php" ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                action: 'getSubcategories',
                CatID: categoryId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.text();
        })
        .then(text => {
            // console.log('Raw response for subcategories:', text);
            try {
                const data = JSON.parse(text);
                // console.log('Parsed JSON for subcategories:', data);
                if (data.success) {
                    subcategoryDropdown.innerHTML = '';
                    data.subcategories.forEach(subcategory => {
                        let option = document.createElement('option');
                        option.value = subcategory.SubCatID;
                        option.textContent = subcategory.SubCatName;
                        option.setAttribute('data-catnum', subcategory.catnum);
                        subcategoryDropdown.appendChild(option);
                    });
                    subcategoryDropdown.dispatchEvent(new Event('change'));
                } else {
                    console.error('Failed to retrieve subcategories:', data.message || 'No message provided');
                }
            } catch (error) {
                console.error('Error parsing JSON response for subcategories:', text);
            }
        })
        .catch(error => console.error('Error fetching subcategories:', error));
    }

    categoryDropdown.addEventListener('change', loadSubcategories);
    subcategoryDropdown.addEventListener('change', updateFullCode);
    eBookCheckbox.addEventListener('change', updateFullCode);

    loadSubcategories();
});










//view PDF

document.addEventListener('DOMContentLoaded', function() {
    var myModal = new bootstrap.Modal(document.getElementById('pdfModal'));

    // For debugging: output the URL to the console
    var pdfUrl = "<?php echo 'https://ad-lb.mef.gov.kh/' . $book->PDFFile; ?>";
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