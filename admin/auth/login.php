<?php
    $title = "Login";
?>

<?php
 include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/header.php");

    // if($_SESSION['login']==true){
    //     header('Location:' .$burl .'/admin/summary/index.php');
    // }
    if($_SESSION['login']==true){
         // Check user permission and redirect based on their role
        if ($_SESSION['role_id'] == 1) { // Admin role
            header('Location: ' . $burl . '/admin/summary/index.php');
            exit();
        } elseif ($_SESSION['role_id'] == 2) { // Regular user
            header('Location: ' . $burl . '/admin/summary/index.php');
            exit();
        } elseif ($_SESSION['role_id'] == 3) { // Guest role
            header('Location: ' . $burl . '/guest/index.php');
            exit();
        }
    }

?>
<style>
body {
    background-image: url('../Assets/image/Cover.jpg');
    background-repeat: no-repeat;
    overflow-y: hidden;

}
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <img src="../assets/image/logo.png" alt="Ministry Logo" class="img-fluid mb-3" style="max-width: 150px;">
        <h1 class="text-white" style=" font-family: Moul">បណ្ណាល័យ ក្រសួងសេដ្ឋកិច្ច និងហិរញ្ញវត្ថុ</h1>
        <h3 class="mb-3 pt-3 text-dark">សូមស្វាគមន៍!</h3>


        <form action="<?php echo $burl . "/admin/auth/action_login.php" ?>" method="post" class="w-100 needs-validation"  novalidate
            style="max-width: 400px; margin: 0 auto;">
            <div class="py-2">
                <input type="text" name="username" class="form-control p-2" placeholder="ឈ្មោះគណនី" required>
            </div>
            <div class="py-2">
                <input type="password" name="password" class="form-control p-2" placeholder="លេខសម្ងាត់" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block p-2">ចូលប្រើប្រាស់</button>
        </form>
            <br>
        <?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/sms.php");?>

    </div>
</div>
<footer class="vh-100">
    <div class="text-center">
        <p class="text-white">© 2021 រក្សាសិទ្ធិដោយនាយកដ្ឋានបណ្ណសារនៃក្រសួងសេដ្ឋកិច្ច និងហិរញ្ញវត្ថុ</p>
    </div>
</footer>

<?php include($_SERVER['DOCUMENT_ROOT']."/library/admin/layouts/footer.php"); ?>