<?php
    $title = "user Page";
    $page = "users";
?>


<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php"); ?>
<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/slidebar.php"); ?>


<?php


    $users = $conn->query("SELECT 
    tbluser.*, 
    tblroles.Username AS user_name, 
    tblpermission.Name AS PermissionName
    FROM 
        tbluser
    LEFT JOIN 
        tblroles ON tblroles.UserID = tbluser.UserID
    LEFT JOIN 
        tblpermission ON tblpermission.PermissionID = tblroles.PermissionID
    WHERE tblroles.active=1;
    ");

    $permissions = $conn->query("SELECT * FROM tblpermission");
?>



<div class="p-2">
    <div class="card">
        <div class="card-header">

            <h5 style=" font-family: Moul;">ការគ្រប់គ្រងអ្នកប្រើប្រាស់</h5>

        </div>
        <div class="card-body">


            <?php

$user_id = $_GET['user_id'] ?? null;


        if ($user_id) {
            // Fetch user data from the database
            $query = "SELECT tbluser.*, tblroles.PermissionID AS role_id, tblroles.Username 
            FROM tbluser 
            LEFT JOIN tblroles ON tblroles.UserID = tbluser.UserID
            WHERE tbluser.UserID = $user_id";

            $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit;
    }
}



$isUpdating = isset($user_id) && !empty($user_id);

?>
            <form
                action="<?php echo $burl . ($isUpdating ? "/admin/users/actions/edit.php" : "/admin/users/actions/create.php"); ?>"
                class="needs-validation" id="myForm"  novalidate method="post" enctype="multipart/form-data">
                <div class="row">

                    <div class="col-6">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="firsname">នាមត្រកូល</label>
                                <input type="text" id="firsname" name="firstname"
                                    value="<?= htmlspecialchars($user['FirstName'] ?? '') ?>" class="form-control"
                                    required>
                            </div>
                            <div class="col">
                                <label for="lastname">នាមខ្លួន</label>
                                <input type="text" id="lastname" name="lastname"
                                    value="<?= htmlspecialchars($user['LastName'] ?? '') ?>" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="gender">ភេទ</label>
                                <select name="gender" class="form-select" required>
                                    <option disabled selected>សូមជ្រើសរើសភេទ</option>
                                    <option value="ប្រុស"
                                        <?= isset($user['Gender']) && $user['Gender'] == 'ប្រុស' ? 'selected' : '' ?>>
                                        ប្រុស</option>
                                    <option value="ស្រី"
                                        <?= isset($user['Gender']) && $user['Gender'] == 'ស្រី' ? 'selected' : '' ?>>
                                        ស្រី</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="Dob">ថ្ងៃខែឆ្នាំកំណើត</label>
                                <input type="date" id="Dob" name="Dob"
                                    value="<?= htmlspecialchars($user['Dob'] ?? '') ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="workplace">ស្ថាប័ន/អង្គភាព</label>
                            <input type="text" id="workplace" name="workplace"
                                value="<?= htmlspecialchars($user['WorkPlace'] ?? '') ?>" class="form-control" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="position">តួនាទី</label>
                                <input type="text" id="position" name="position"
                                    value="<?= htmlspecialchars($user['Position'] ?? '') ?>" class="form-control"
                                    required>
                            </div>
                            <div class="col">
                                <label for="NPhone">លេខទំនាក់ទំនង</label>
                                <input type="text" id="NPhone" name="NPhone"
                                    value="<?= htmlspecialchars($user['Contacts'] ?? '') ?>" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="DocType">បណ្ណសម្គាល់ខ្លួន</label>
                                <select name="DocType" class="form-select" required>
                                    <option disabled selected>សូមជ្រើសបណ្ណសម្គាល់ខ្លួន</option>
                                    <option value="អត្តសញ្ញាណបណ្ណ"
                                        <?= isset($user['DocumentType']) && $user['DocumentType'] === 'អត្តសញ្ញាណបណ្ណ' ? 'selected' : '' ?>>
                                        អត្តសញ្ញាណបណ្ណ
                                    </option>
                                    <option value="លិខិតឆ្លងដែន"
                                        <?= isset($user['DocumentType']) && $user['DocumentType'] === 'លិខិតឆ្លងដែន' ? 'selected' : '' ?>>
                                        លិខិតឆ្លងដែន
                                    </option>
                                    <option value="កាតសម្គាល់ខ្លួន"
                                        <?= isset($user['DocumentType']) && $user['DocumentType'] === 'កាតសម្គាល់ខ្លួន' ? 'selected' : '' ?>>
                                        កាតសម្គាល់ខ្លួន
                                    </option>
                                    <option value="កាតសិស្ស"
                                        <?= isset($user['DocumentType']) && $user['DocumentType'] === 'កាតសិស្ស' ? 'selected' : '' ?>>
                                        កាតសិស្ស
                                    </option>
                                </select>

                            </div>
                            <div class="col">
                                <label for="DocNum">លេខ</label>
                                <input type="text" id="DocNum" name="DocNum"
                                    value="<?= htmlspecialchars($user['DocNum'] ?? '') ?>" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="mb-3">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username"
                                value="<?= htmlspecialchars($user['Username'] ?? '') ?>" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" value="" class="form-control"
                                <?= !$isUpdating ? 'required' : '' ?>>
                            <?php if ($isUpdating): ?>
                            <small class="text-muted">Leave blank to keep the current password.</small>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select name="role" class="form-select" required>
                                <option selected value="">Please Select</option>
                                <?php while($permission = $permissions->fetch_object()) { ?>
                                <option value="<?php echo $permission->PermissionID; ?>"
                                    <?php echo isset($user['role_id']) && $user['role_id'] == $permission->PermissionID ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($permission->Name); ?>
                                </option>
                                <?php } ?>
                            </select>



                        </div>

                        <div class="mb-3">
                            <label for="photo">Photo</label>
                            <input type="file" accept="image/*" id="photo" name="photo" class="form-control"
                                <?= !$isUpdating ? 'required' : '' ?>>
                            <?php if ($isUpdating && !empty($user['Image'])): ?>
                            <br>
                            <img src="<?=$user['Image'] ?>" class="rounded border border-primary" alt="Current Photo"
                                width="100">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mb-5 mt-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?= $isUpdating ? 'កែប្រែព័ត៌មានអ្នកប្រើប្រាស់' : 'បង្កើតអ្នកប្រើប្រាស់ថ្មី'; ?>
                    </button>

                    <a href="<?php echo $burl . "/admin/users/" ?>" class="btn btn-danger"><i class="fa-solid fa-eraser"></i> សម្អាត</a>
                </div>



                <?php if ($isUpdating): ?>
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                <?php endif; ?>
            </form>





            <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>


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
                        <th scope="col">ឈ្មោះគណនី</th>
                        <th scope="col">សិទ្ធិ</th>
                        <th scope="col">Action</th>

                    </tr>


                </thead>
                <tbody>
                    <?php
                    $i = 0;
                 while($user = $users->fetch_object()) { ?>
                    <tr>
                        <th class="text-center" scope="row"><?php echo ++$i?></th>
                        <td><?php echo $user->LastName .' '. $user->FirstName?></td>
                        <td><?php echo $user->Gender?></td>
                        <td><?php echo $user->Dob?></td>
                        <td><?php echo $user->WorkPlace?></td>
                        <td><?php echo $user->Position?></td>
                        <td><?php echo $user->Contacts?></td>
                        <td><?php echo $user->user_name?></td>
                        <td><?php echo $user->PermissionName?></td>
                        <td class="text-center">
                        <?php if ($_SESSION['role_id'] == 1): // Only show if the user is an admin ?>
                        <a href="<?php echo $burl . "/admin/users/index.php?user_id=" . $user->UserID ?>"
                        class="btn btn-success"><i class="fa-solid fa-pen"></i> កែប្រែ</a>

                        <a href="<?php echo $burl . "/admin/users/actions/delete.php?user_id=" . $user->UserID ?>"
                        class="btn btn-danger"><i class="fa-solid fa-trash"></i> លុប</a>
                    <?php endif; ?>

                        </td>

                    </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>
    </div>
</div>





<?php include("../layouts/footer.php");?>
