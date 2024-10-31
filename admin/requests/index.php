<?php
    $title = "request Page";
    $page = "request";
?>

<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php"); ?>

<?php
    // Query to get all borrow requests along with book titles and user details
    $request = $conn->query("SELECT tblborrow.*, tblbook.BTitle, tblbook.FullCode, tblguest.FirstName, tblguest.LastName
        FROM tblborrow
        LEFT JOIN tblbook ON tblbook.BookID = tblborrow.BookID
        LEFT JOIN tblguest ON tblguest.GuestID = tblborrow.GuestID
        WHERE tblborrow.RoleID IS NULL");
?>

<div class="p-2">
    <div class="card">
        <div class="card-header">
            <h4 style=" font-family: Moul;">សំណើខ្ចីសៀវភៅ</h4>
        </div>
    </div>

    <div class="container-fluid mt-3">

    <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>

        <?php
        $previousBorrowCode = null;  // Store the previous BorrowCode
        $bookTitles = [];  // Array to accumulate book titles for the same BorrowCode

        while ($requests = $request->fetch_object()) {
            if ($previousBorrowCode !== null && $previousBorrowCode !== $requests->BorrowCode) {
                // Output the merged card for the previous BorrowCode
                ?>
                <div class="container-fluid">
                    <a href="<?php echo $burl . "/admin/requests/view.php?borrow_id=" . $previousBorrowID ?>" class="card mb-3 card-hover" style="border-radius: 10px; text-decoration: none;">
                        <div class="row g-0">
                            <div class="col-md-10">
                                <div class="card-body">
                                    <!-- Display the book titles with numbering -->
                                    <p class="card-text">
                                        <?php 
                                        foreach ($bookTitles as $index => $title) {
                                            echo ($index + 1) . '. ' . htmlspecialchars($title) . '<br>';
                                        }
                                        ?>
                                    </p>

                                    <p class="card-text">
                                        <small class="text-muted">អ្នកស្នើសុំ ៖ <?php echo htmlspecialchars($previousLastName . " " . $previousFirstName); ?></small><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($previousDateBorrowed); ?></small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-2 text-end align-self-center px-3">
                                <span class="badge bg-success">ស្នើសុំខ្ចី</span>
                                <span class="badge bg-warning">រងចាំពិនិត្យ</span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
                // Reset the titles for the next BorrowCode
                $bookTitles = [];
            }

            // Accumulate book titles for the current BorrowCode
            $bookTitles[] = $requests->BTitle;

            // Store the current BorrowCode data for the next iteration
            $previousBorrowCode = $requests->BorrowCode;
            $previousBorrowID = $requests->BorrowCode;
            $previousLastName = $requests->LastName;
            $previousFirstName = $requests->FirstName;
            $previousDateBorrowed = $requests->DateBorrowed;
        }

        // Output the last card (for the final BorrowCode)
        if (!empty($bookTitles)) {
            ?>
            <div class="container-fluid">
                <a href="<?php echo $burl . "/admin/requests/view.php?borrow_id=" . $previousBorrowID ?>" class="card mb-3 card-hover" style="border-radius: 10px; text-decoration: none;">
                    <div class="row g-0">
                        <div class="col-md-10">
                            <div class="card-body">
                                <!-- Display the book titles with numbering -->
                                <p class="card-text">
                                    <?php 
                                    foreach ($bookTitles as $index => $title) {
                                        echo ($index + 1) . '. ' . htmlspecialchars($title) . '<br>';
                                    }
                                    ?>
                                </p>

                                <p class="card-text">
                                    <small class="text-muted">អ្នកស្នើសុំ ៖ <?php echo htmlspecialchars($previousLastName . " " . $previousFirstName); ?></small><br>
                                    <small class="text-muted">កាលបរិច្ឆេទ ៖ <?php echo htmlspecialchars($previousDateBorrowed); ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-2 text-end align-self-center px-3">
                            <span class="badge bg-success">ស្នើសុំខ្ចី</span>
                            <span class="badge bg-warning">រងចាំពិនិត្យ</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php include("../layouts/footer.php"); ?>
