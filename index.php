<?php
session_start(); // session is start
require_once('include/header.php'); // include header file
require_once('include/config.php'); // include connection file


$current_month = date('n'); // find current month
$current_year = date("Y"); // find current year

/* if month and year are not set in url */
if((!isset($_GET['month']) && !isset($_GET['year'])) && !isset($_GET['histroy_feed'])){
// $url = "index.php?month=".$current_month."&year=".$current_year;
// echo "<script>
// window.location.href = '$url'
// </script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
</html>

<div id="content">
    <style>
        .loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 999;
}

.spinner {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100px;
  height: 100px;
  border: 10px solid #f3f3f3;
  border-top: 10px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

    </style>
<!-- <div class="loader">
  <div class="spinner"></div>
</div> -->
</div>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Attach a click event handler to the button
            var xhr = new XMLHttpRequest();

                xhr.open("GET", "home-page.php?month=<?=$current_month?>&year=<?=$current_year?>", true);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // This function is called if the request is successful
                            document.getElementById("content").innerHTML = xhr.responseText;
                        } else {
                            // This function is called if the request encounters an error
                            document.getElementById("content").innerHTML = "Error loading data.";
                        }
                    }
                };

                xhr.send();
        });
    </script>
<?php
}
// Code to display page content here
else if(isset($_GET['histroy_feed'])){
require("histroy_feed.php");
}
else{
// set the timezone
date_default_timezone_set('UTC');

// set the month and year
if(isset($_GET['month']) && isset($_GET['year'])) {
$month = $_GET['month'];
$year = $_GET['year'];
} else {
$month = date('m');
$year = date('Y');
}
$newYear = $year;
$newMonth = $month;

if($month == 1){
$newMonth = 12;
$newYear = $year - 1;
}
else{
$newMonth--;
}
$nextYear = $year;
$nextmonth = $month;
if($month == 12){
$nextmonth = 1;
$nextYear = $year + 1;
}
else{
$nextmonth++;
}
$month;

// get the number of days in the month

$numDays = cal_days_in_month(CAL_GREGORIAN,$month, $year);

// create a date object for the first day of the month
$date = mktime(0, 0, 0, $month, 1, $year);

// get the day of the week for the first day of the month
$dayOfWeek = date('D', $date);

// create an array of days of the week
$daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

// determine the offset for the first day of the month
$offset = array_search($dayOfWeek, $daysOfWeek);

$previous_month = ($month - 1);
$next_month = ($month + 1);

// code by Nitish 29-06-23 start
$monthNumber = $month; // Replace with your desired month number
$currentMonth = date('F', mktime(0, 0, 0, $monthNumber, 1));

$today = date('d');
$monthYear = $currentMonth . " " . $year;

$currentMonth = date('m'); // get current month
$currentYear = '20'.date('y'); // get current year
$urlMonth = $_GET['month']; // get url month
if($urlMonth<10){ $newUrlMonth='0' .$urlMonth; }
else{
$newUrlMonth = $urlMonth;
}
$urlYear=$_GET['year'];  // get url year
?>
<!-- code for calender start -->
<section class="Calendardiv">
    <div class="container-fluid">
        <div class="row my-2 justify-content-center">
            <div class="col-12 mb-2">
                <div class="headerbanner">
                    <form method="POST" name="form" id="form">
                        <h1 class="currentMonth" id="currentMonth">
                            <?php echo $monthYear; ?>
                        </h1>
                        <p>Start learning with our institute</p>
                        <a class="btn btn-secondary previousBtn" name="previousBtn" id="previousBtn"
                            href="index.php?month=<?php echo $newMonth; ?>&year=<?php echo $newYear; ?>"><b>Previous</b></a>
                        <?php
                            if(!isset($_SESSION['loginuseremail'])){
                                echo '<a class="btn w3-indigo loginBtn" name="loginBtn" id="loginBtn"
                                    href="user_login.php"><b>Login</b></a>';
                                ?>
                        <?php
                            }
                            else{
                                echo '<a class="btn btn-danger logoutBtn" name="logoutBtn" id="logoutBtn"
                                onclick="logoutpopup()"><b>Logout</b></a>';
                            }
                            ?>
                        <a class="btn nextBtn" name="nextBtn" id="nextBtn"
                            href="index.php?month=<?php echo $nextmonth; ?>&year=<?php echo $nextYear; ?>"><b>Next</b></a>
                    </form>
                    <img src="Image/banner.png" alt="" class="img-fluid">
                </div>
            </div>
            <?php
            for($i = 1; $i <= $numDays; $i++) {
                 $com_date=$year."-".$month."-".$i;

                 $date=mktime(0, 0,0,$month, $i, $year);
                 $dayOfWeek=date('D', $date); /* code for fetch calender details start */ //

                $calenderQuery=mysqli_query($conn,"SELECT fdTitle,fdSubTitle,fdImg FROM `tb_calender`
                WHERE DAY(`fdDate`)='$i' AND MONTH(`fdDate`)='$newUrlMonth' AND YEAR(`fdDate`)='$urlYear' AND fdStatus=0
                AND fdDelete=0");
                if(mysqli_num_rows($calenderQuery)> 0){
                $calenderRow = mysqli_fetch_assoc($calenderQuery);
                $maxTitleLength = 40;
                $caltitle = $calenderRow['fdTitle'];
                if (strlen($caltitle) > $maxTitleLength) {
                $title = substr($caltitle, 0, $maxTitleLength) . "..";
                }else{
                $title = $caltitle;
                }

                $maxSubTitleLength = 20;
                $cal_sub_title = $calenderRow['fdSubTitle'];

                if (strlen($cal_sub_title) > $maxSubTitleLength) {
                $sub_title = substr($cal_sub_title, 0, $maxSubTitleLength) . "..";
                }else{
                $sub_title = $cal_sub_title;
                }

                if($calenderRow['fdImg']){
                $img = $calenderRow['fdImg'];
                }
                } else{
                $histroyQuery=mysqli_query($conn,"SELECT fd_title, fd_sub_title,fd_discription,fd_img FROM `tb_history` WHERE
                DAY(`tb_calender_date`) = '$i' AND MONTH(`tb_calender_date`) = '$newUrlMonth' AND `fd_status`=0 AND
                `fd_delete`=0 ORDER BY `fd_id` DESC LIMIT 1");

                if(mysqli_num_rows($histroyQuery) > 0){
                $histroyRow = mysqli_fetch_assoc($histroyQuery);

                $histroy_title_length = 40;
                $histroy_title = $histroyRow['fd_title'];
                $histroy_disc = $histroyRow['fd_discription']; // store discription

                if (strlen($histroy_title) > $histroy_title_length) {
                $title = substr($histroy_title, 0, $histroy_title_length) . "..";
                }
                else{
                $title = $histroy_title;
                }
                $histroy_sub_title_length = 20;
                $histroy_sub_title = $histroyRow['fd_sub_title'];

                if (strlen($histroy_sub_title) > $histroy_title_length) {
                $sub_title = substr($histroy_sub_title, 0, $histroy_sub_title_length) . "..";
                }
                else{
                $sub_title = $histroy_sub_title;
                }
                if($histroyRow['fd_img']){
                $img = $histroyRow['fd_img'];
                }
                }
            }
                /* code for fetch calender details end */

                /* code for highlight today card */

                if(($today==$i) && ($newUrlMonth==$currentMonth) && ($urlYear==$currentYear))
                {
                echo '<div id="calcard" class="cardcal mx-md-2 my-md-2 my-3">
                    <span>'.$i.'</span>
                    <p>'.$dayOfWeek.'</p>';
                    echo '<h5 class="title" onload = "setTextColorBasedOnBackground(this);">'.$title.'</h5>

                    <p class="carddesc">'.$sub_title.'</p>';

                    if (file_exists("admin/data/calender_image/" . $img) && $calenderRow['fdImg']){
                    echo '
                    <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$caltitle.'&discription='.$histroy_disc.'&image=admin/data/calender_image/'.$img.'"><img class="img-fluid lodeImgs"
                            data-img='.$i.' onload="changeDivColor(this)"
                            src="admin/data/calender_image/'.$img.'"></a>';

                    }
                    else if(file_exists("admin/data/image/" . $img) && $histroyRow['fd_img']){
                    echo '
                    <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$histroy_title.'&discription='.$histroy_disc.'&image=admin/data/image/'.$img.'"><img class="img-fluid lodeImgs"
                            data-img='.$i.' onload="changeDivColor(this)" src="admin/data/image/'.$img.'"></a>';
                    }
                    else{
                    echo '<button class="btnsync"><a
                            href="index.php?histroy_feed&date='.$com_date.'">Explore</a></button>';
                    }
                    echo '
                </div>';
                }
                else{
                echo '<div id="cardcal_'.$i.'" class="cardcal mx-md-2 my-md-2 my-3">
                    <span>'.$i.'</span>
                    <p>'.$dayOfWeek.'</p>
                    <h5 class="title" onload = "setTextColorBasedOnBackground(this);">'.$title.'</h5>
                    <p id="festiveImg" class="carddesc">'.$sub_title.'</p>';
                    if (file_exists("admin/data/calender_image/" . $img) && $calenderRow['fdImg']){
                        echo '
                        <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$caltitle.'&discription='.$histroy_disc.'&image=admin/data/calender_image/'.$img.'"><img class="img-fluid lodeImgs"
                                data-img='.$i.' onload="changeDivColor(this)"
                                src="admin/data/calender_image/'.$img.'"></a>';

                        }
                    else if(file_exists("admin/data/image/".$img) && $histroyRow['fd_img']){
                    echo '
                    <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$histroy_title.'&discription='.$histroy_disc.'&image=admin/data/image/'.$img.'"><img class="img-fluid lodeImgs"
                            data-img='.$i.' onload="changeDivColor(this)" src="admin/data/image/'.$img.'"></a>';

                    }else{
                    echo '<button class="btnsync"><a
                            href="index.php?histroy_feed&date='.$com_date.'">Explore</a></button>';
                    }
                    echo '
                </div>';
                }
                unset($dayOfWeek);
                }
                ?>
            <!--  include external js file -->
            <script src="JS/script.js"></script>
            <?php
            mysqli_close($conn);
            }
            ?>
        </div>
    </div>
</section>
<!-- code for calender end -->
<script>
// Get all the cards
var cards = document.getElementsByClassName("cardcal");

// Iterate over each card
for (var i = 0; i < cards.length; i++) {
    var card = cards[i];
    var backgroundColor = getComputedStyle(card).backgroundColor;

    // Compare the background color with predefined colors
    if (isDarkColor(backgroundColor)) {
        card.style.color = "white"; // Set white text color for dark background
    } else if (isGreyColor(backgroundColor) || isWhiteColor(backgroundColor) || isYellowColor(backgroundColor)) {
        card.style.color = "black"; // Set black text color for grey, white, or yellow background
    }
}

// Helper functions to check color conditions
function isDarkColor(color) {
    // You can implement your own logic to determine if the color is dark
    // Here's a simple example considering colors with high luminance as light colors
    var luminance = calculateLuminance(color);
    return luminance < 0.5;
}

function isGreyColor(color) {
    // You can implement your own logic to determine if the color is grey
    // Here's a simple example considering colors with close RGB values as grey
    var rgb = getRGB(color);
    var threshold = 20; // Adjust the threshold value as per your requirement
    return Math.abs(rgb.r - rgb.g) < threshold && Math.abs(rgb.g - rgb.b) < threshold && Math.abs(rgb.b - rgb.r) <
        threshold;
}

function isWhiteColor(color) {
    return color === "white";
}

function isYellowColor(color) {
    return color === "yellow";
}

function calculateLuminance(color) {
    // Calculate the luminance of the color
    // You can implement your own algorithm or use external libraries for accurate luminance calculations
    // Here's a simple example considering the color as a hexadecimal RGB value
    var rgb = hexToRGB(color);
    return (0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b) / 255;
}

function getRGB(color) {
    // Parse the color string and extract the RGB values
    // You can modify this function to handle different color representations
    var regex = /^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/;
    var matches = color.match(regex);

    if (matches) {
        return {
            r: parseInt(matches[1], 10),
            g: parseInt(matches[2], 10),
            b: parseInt(matches[3], 10)
        };
    }

    return null;
}

function hexToRGB(color) {
    // Convert a hexadecimal color string to RGB values
    // You can modify this function to handle different color representations
    var regex = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i;
    var matches = color.match(regex);

    if (matches) {
        return {
            r: parseInt(matches[1], 16),
            g: parseInt(matches[2], 16),
            b: parseInt(matches[3], 16)
        };
    }

    return null;
}

function logoutpopup(){
    Swal.fire({
  title: 'Logout Successfuly',
  text: 'You are successfully logged out from the Page',
  icon: 'Success',
})
// href="logout.php";
window.location.href = "logout.php";

}
</script>
</body>

</html>