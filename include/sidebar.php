<?php
session_start();
$admin_id = $_SESSION['admin_id'];
?>
<!--- external css for sidebar -->
<link href="../../include/css/sidebar.css" rel="stylesheet">
<style type="text/css">
.title {
    color: black;
}

.sub_title {
    color: red;
}
</style>
<!-- code for top navbar start -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between float-left">
        <a href="index.php" class="logo d-flex align-items-center">
            <img src="../../Image/logo.jpeg" alt="Calendar Admin">
            <b class="title">CAL</b><b class="sub_title">ENDAR</b>
        </a>
        <!-- <a href="index.php" class="navbar-brand"><img src="Image/logo.jpeg" alt="CALENDER"><b class="title">CAL</b><b
            class="sub_title">ENDAR</b></a> -->
    </div><!-- End Logo -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown mr-3">
                <b><i class="fa fa-bars toggle-sidebar-btn" aria-hidden="true"></i></b>
            </li>
        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<!-- code for top navbar end -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <div class="logbg titles w3-large w3-center w3-margin-bottom w3-margin-top w3-padding w3-white">
        <span class=""><b>TINLY<sup class="w3-small">IN</sup></b></span>
    </div>
    <hr class="titles">
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link" id="home" href="index.php">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="upload_histroy" href="index.php?upload_histroy">
                <i class="fa fa-rss" aria-hidden="true"></i>
                <span>Upload Histroy Data</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="calender_data" href="index.php?calender_data">
                <i class="fa fa-calendar" aria-hidden="true"></i>
                <span>Upload Calender Data</span>
            </a>
        </li>       
        <li class="nav-item">
            <a class="nav-link" id="my_duplicate" href="index.php?myduplicate">
            <i class="fa fa-repeat" aria-hidden="true"></i>
                <span>My Duplicate Entries</span>
            </a>
        </li>
        <?php                        
        if($admin_id == 1 || $admin_id == 2){
            ?>
        <li class="nav-item">
            <a class="nav-link" id="entry_details" href="index.php?entry_details">
                <i class="fa fa-database" aria-hidden="true"></i>
                <span>Entry Details</span>
            </a>
        </li>
        <?php
        }   
        if($admin_id == 1 || $admin_id == 2){
            ?>
        <li class="nav-item" id="entry_details_graph">
            <a class="nav-link" href="index.php?entry_details_graph">
                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                <span>Entry Details Graph</span>
            </a>
        </li>
        <?php
        }
        ?>
        <li class="nav-item">
            <a class="nav-link" id="logout" href="../logout.php">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                <span>Logout</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <span>Help</span>
            </a>
        </li><!-- End Dashboard Nav -->

    </ul>
</aside><!-- End Sidebar-->

<!-- Template Main JS File -->
<script src="../../include/js/main.js"></script>