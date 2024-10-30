<?php

$title = " create book Page";
$page = "book";

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files before any output
include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php");


include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php");



$category = $conn->query("SELECT * FROM tblcategory");
$language = $conn->query("SELECT * FROM tbllanguage");


?>

<div class="p-2">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">បង្កើតសៀវភៅ</h4>
        </div>

        <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>


        <div class="card-body">

            <div class="col-12 mb-3">
                <a href="<?php echo $burl . "/admin/books/index.php?"?>" class="btn btn-danger"><i
                        class="fa-solid fa-rotate-left"></i> Back</a>
            </div>

            <form action="<?php echo $burl . "/admin/books/actions/create.php"; ?>" class="needs-validation" novalidate
                method="post" enctype="multipart/form-data" id="bookForm">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="name">ចំណងជើង (សៀវភៅ/ឯកសារ)</label>
                            <input type="text" id="BTitle" name="BTitle" class="form-control ">
                            <div class="invalid-feedback">
                                សូបបញ្ចូលចំណងជើង
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price">អ្នកនិពន្ធ/រៀបចំ</label>
                            <input type="text" id="BAuthor" name="BAuthor" class="form-control">
                            <div class="invalid-feedback">
                                សូបបញ្ចូលអ្នកនិពន្ធ
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="price">ប្រភព</label>
                            <!-- <input type="text" id="BSource" name="BSource" class="form-control"> -->
                            <div class="input-group">
                                <select name="BSource" class="form-select" required>
                                    <option selected disabled value="">សូមជ្រើសរើស</option>
                                    <option value="អំណោយ">អំណោយ</option>
                                    <option value="ទំនិញ">ទំនិញ</option>
                                    <option value="អនឡាញ">អនឡាញ</option>
                                </select>
                            </div>
                            <div class="invalid-feedback">
                                សូបបញ្ចូលប្រភព
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="BLang">ភាសា</label>
                            <div class="input-group">
                                <select name="BLang" class="form-select" required>
                                    <option selected disabled value="">សូមជ្រើសរើស</option>
                                    <?php while($languages = $language->fetch_object()) { ?>
                                    <option value="<?php echo $languages->LangID ?>"><?php echo $languages->LangName ?>
                                    </option>

                                    <?php } ?>
                                </select>
                                <a class="btn btn-primary" href=""><i class="fa-solid fa-plus"></i></a>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="MBType">សំណុំប្រភេទសៀវភៅ</label>
                            <div class="input-group">
                                <select name="MBtype" id="categoryDropdown" class="form-select">
                                    <option selected disabled value="">សូមជ្រើសរើស</option>
                                    <?php while($categorys = $category->fetch_object()) { ?>
                                    <option value="<?php echo $categorys->CatID ?>"
                                        data-catnum="<?php echo $categorys->CatNum ?>">
                                        <?php echo $categorys->CatName ?>
                                    </option>

                                    <?php } ?>
                                </select>
                                <a class="btn btn-primary" href=""><i class="fa-solid fa-plus"></i></a>
                            </div>
                        </div>

                        <!-- Subcategory Dropdown -->
                        <div class="mb-3">
                            <label for="subcategoryDropdown">ប្រភេទសៀវភៅ</label>
                            <div class="input-group">
                                <select name="SBType" id="subcategoryDropdown" class="form-select">
                                    <option selected disabled value="">សូមជ្រើសរើស</option>
                                </select>
                                <a class="btn btn-primary" href=""><i class="fa-solid fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="mb-3">
                            <label for="BPublished">ឆ្នាំបោះពុម្ព</label>
                            <input type="text" id="BPublished" name="BPublished" class="form-control">
                            <div class="invalid-feedback">
                                សូបបញ្ចូលឆ្នាំបោះពុម្ព
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="BPage">ទំព័រ</label>
                            <input type="text" id="BPage" name="BPage" class="form-control">
                            <div class="invalid-feedback">
                                សូបបញ្ចូលទំព័រ
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="BStock">ចំនួន</label>
                            <input type="text" id="BStock" name="BStock" class="form-control">
                            <div class="invalid-feedback">
                                សូបបញ្ចូលចំនួនសៀវភៅ
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="BPrice">តម្លៃ</label>
                            <input type="text" id="BPrice" name="BPrice" class="form-control">
                            <div class="invalid-feedback">
                                សូបបញ្ចូលតម្លៃ
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-2 pt-4">
                                <label for="E_book">E-book</label>
                                <input class="form-check-input p-2 mx-3 border rounded-pill border-success"
                                    type="checkbox" id="E_book" name="E_book">
                            </div>
                            <div class="col">
                                <label for="FullCode">កូដ</label>
                                <input type="text" id="FullCode" name="FullCode" class="form-control">
                            </div>
                            <div class="col">
                                <!-- Add the book code textbox -->
                                <input type="hidden" id="BookCode" name="BookCode" readonly />

                            </div>
                        </div>
                        <div class=" row mb-3">
                            <div class="col">
                                <label for="photo">រូបភាពក្របសៀវភៅ</label>
                                <input type="file" accept="image/*" id="photo" name="photo" class="form-control">
                                <div class="invalid-feedback">
                                    សូបបញ្ចូលក្របសៀវភៅ
                                </div>
                            </div>
                            <div class="col">
                                <label for="photo">PDF File</label>
                                <input type="file" id="pdf_file" name="pdf_file" class="form-control">
                            </div>
                        </div>


                        <div class="mb-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                                បញ្ចូល</button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>
</div>




<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/footer.php"); ?>



<script>
// Get references to the necessary elements
const bookCodeTextbox = document.getElementById('BookCode');

// Listen for category dropdown changes
document.getElementById('categoryDropdown').addEventListener('change', function() {
    var categoryId = this.value;

    // Fetch subcategories for the selected category
    fetch('<?php echo $burl . "/admin/books/back_end/create.php" ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=getSubcategories&CatID=${categoryId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let subcategoryDropdown = document.getElementById('subcategoryDropdown');
                subcategoryDropdown.innerHTML = ''; // Clear previous options

                // Populate the dropdown with subcategories
                data.subcategories.forEach(function(subcategory) {
                    let option = document.createElement('option');
                    option.value = subcategory.SubCatID;
                    option.textContent = subcategory.SubCatName;
                    option.setAttribute('data-catnum', subcategory.catnum); // Set catnum
                    option.setAttribute('data-subcatnum', subcategory.SubCatNum); // Set SubCatNum
                    subcategoryDropdown.appendChild(option);
                });

                // Automatically trigger change event for subcategory
                subcategoryDropdown.dispatchEvent(new Event('change'));
            } else {
                console.error('Failed to retrieve subcategories');
            }
        })
        .catch(error => console.error('Error fetching subcategories:', error));
});

// Generate full code when subcategory or eBook checkbox changes
const subcategoryDropdown = document.getElementById('subcategoryDropdown');
const eBookCheckbox = document.getElementById('E_book');
const fullCodeTextbox = document.getElementById('FullCode');

function updateFullCode() {
    const selectedOption = subcategoryDropdown.options[subcategoryDropdown.selectedIndex];
    const selectedCategoryNum = parseInt(selectedOption.getAttribute('data-catnum'), 10) || 0; // Ensure numeric value
    const selectedSubCategoryNum = parseInt(selectedOption.getAttribute('data-subcatnum'), 10) ||
    0; // Ensure numeric value
    const selectedCategoryID = selectedOption.value; // Subcategory ID

    if (selectedCategoryNum && selectedSubCategoryNum && selectedCategoryID) {
        // Fetch book count for the selected subcategory
        fetch('<?php echo $burl . "/admin/books/back_end/create.php" ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=getBookCount&catID=${selectedCategoryID}&isEbook=${eBookCheckbox.checked}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) {
                    const count = String(data.count).padStart(4,
                    '00'); // Pad the count with leading zeros (to 3 digits)
                    let code = eBookCheckbox.checked ?
                        `E${selectedCategoryNum + selectedSubCategoryNum} - ${count}` :
                        `${selectedCategoryNum + selectedSubCategoryNum} - ${count}`;

                    // Set FullCode and BookCode
                    fullCodeTextbox.value = code;
                    bookCodeTextbox.value = count;
                } else {
                    console.error('Book count not returned from server.');
                }
            })
            .catch(error => console.error('Error fetching book count:', error));
    } else {
        fullCodeTextbox.value = ''; // Clear FullCode if no valid subcategory selected
        bookCodeTextbox.value = ''; // Clear BookCode as well
    }
}

// Listen for changes in subcategory and eBook checkbox
subcategoryDropdown.addEventListener('change', updateFullCode);
eBookCheckbox.addEventListener('change', updateFullCode);
</script>