<?php 
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: $burl/admin/auth/login.php");
        exit();
    }
    include($_SERVER['DOCUMENT_ROOT'] . "/library/protect.php");
    // Protect admin and user folders (only admin with role_id = 1 or user with role_id = 2)
    protect_admin_folder();
    
    // Fetch the user information for displaying in the header
    $user_id = $_SESSION['auth'];
    $users = $conn->query("SELECT tbluser.*, tblroles.RoleID 
        FROM tbluser 
        INNER JOIN tblroles ON tbluser.UserID = tblroles.UserID 
        WHERE tblroles.RoleID = '$user_id'")->fetch_object();
    ?>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <main style="font-family:  Siemreap;">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark min-vh-100"
                        style="width: 280px;">
                        <div class="text-center">
                            <img src="../Assets/image/logo.png" width="100px" alt="Logo">
                            <h5 class="pt-3 mx-3 text-white " style=" font-family: Moul">បណ្ណាល័យក្រសួង</h5>
                            <h5 class="pt-2 mx-3 text-white" style=" font-family: Moul">សេដ្ឋកិច្ចនិងហិរញ្ញវត្ថុ</h5>
                        </div>
                        <hr>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item my-3">
                                <a href="<?php echo $burl . "/admin/summary/" ?>"
                                    class="nav-link text-white <?php echo $page == 'summary' ? "active" : "" ?>">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#table"></use>
                                    </svg>
                                    ផ្ទៃគ្រប់គ្រងទិន្នន័យ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo $burl . "/admin/books/" ?>"
                                    class="nav-link text-white <?php echo $page == 'book' ? "active" : "" ?>"
                                    aria-current="page">
                                    <svg class="bi me-2" width="16" height="16">
                                    </svg>
                                    ការគ្រប់គ្រងសៀវភៅ
                                </a>
                            </li>
                            <li class="nav-item my-3">
                                <a href="<?php echo $burl . "/admin/users/" ?>"
                                    class="nav-link text-white <?php echo $page == 'users' ? "active" : "" ?>">
                                    <svg class="bi me-2" width="16" height="16">

                                    </svg>
                                    ការគ្រប់គ្រងអ្នកប្រើប្រាស់
                                </a>
                            </li>

                            <li class="nav-item my-3">
                                <a href="<?php echo $burl . "/admin/requests/" ?>"
                                    class="nav-link text-white <?php echo $page == 'request' ? "active" : "" ?>">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#grid"></use>
                                    </svg>
                                    សំណើរសុំខ្ចីសៀវភៅ
                                </a>
                            </li>
                            <li class="nav-item my-3">
                                <a href="<?php echo $burl . "/admin/borrow/" ?>"
                                    class="nav-link text-white <?php echo $page == 'borrow' ? "active" : "" ?>">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#people-circle"></use>
                                    </svg>
                                    ទទួលសងសៀវភៅ
                                </a>
                            </li>
                            <li class="nav-item my-3">
                                <a href="<?php echo $burl . "/admin/documents/" ?>"
                                    class="nav-link text-white <?php echo $page == 'documents' ? "active" : "" ?>">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#people-circle"></use>
                                    </svg>
                                    បញ្ជីរាយនាមលិខិត
                                </a>
                            </li>
                            <li class="nav-item my-3">
                                <a href="<?php echo $burl . "/admin/guests/" ?>"
                                     class="nav-link text-white <?php echo $page == 'guests' ? "active" : "" ?>">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#people-circle"></use>
                                    </svg>
                                    ផ្ទាំងគ្រប់គណនីភ្ញៀវ
                                </a>
                            </li>

                        </ul>
                        <hr>
                        <a href="<?php echo $burl . "/admin/auth/action_logout.php"?>" class="btn btn-danger"
                            style=" font-family: Moul;">ចាកចេញ</a>
                    </div>
                </main>

            </div>
        </nav>
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg rounded">
                <a class="sidebar-toggle js-sidebar-toggle px-4 text-decoration-none text-dark">
                    <i class="fa-solid fa-bars fs-3"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">

                        <a class="nav-link d-none d-sm-inline-block">
                            <img src="<?php echo $users->Image?>" class="avatar rounded me-4 " alt="Profile"
                                style="width: 60px; height: 60px; object-fit: cover;" />

                            <span class="text-dark fs-6 text-center border rounded-pill border-success px-3 py-2"
                                style="font-family:  Moul;"><?php echo $users->FirstName .'&nbsp&nbsp&nbsp'. $users->LastName ?></span>

                        </a>
                    </ul>
                </div>
            </nav>
            <?php 
    if (!isset($_SESSION['login']) || $_SESSION['login'] === false) {
        header('Location: ' . $burl . '/admin/auth/login.php');
    }

    ob_end_flush();
    ?>