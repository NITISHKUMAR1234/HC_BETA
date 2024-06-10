<?php
session_start(); // Session starts
require_once('../../../include/config.php'); // Include connection file
$admin_id = $_SESSION['admin_id']; // session variable for admin id

/* if title and date both are not selected */
if((!isset($_POST['day']) && !isset($_POST['month']) && !isset($_POST['title']) && !isset($_POST['disc'])) || ($_POST['day'] == "0" && $_POST['month'] == "0" && $_POST['title'] == "0" && $_POST['disc'] == "0")){

/* query for get all data */
$query = "SELECT t1.fd_id,t1.fd_admin_id,t1.tb_calender_date,t1.fd_img,t1.fd_event_type,t1.fd_title,t1.fd_sub_title,t1.fd_country_ID,t1.fd_discription,t2.fd_email FROM tb_history AS t1 INNER JOIN tb_admin AS t2 ON t1.fd_admin_id = t2.fd_id WHERE t1.fd_status = 0 AND t1.fd_delete = 0 ORDER BY t1.fd_id DESC LIMIT 30";

$res = mysqli_query($conn,$query) or die(mysqli_error($conn));
$count = mysqli_num_rows($res);
$data = array();
while($row = mysqli_fetch_array($res)){
    array_push($data,$row);
}
echo json_encode($data);
}
else{
$day = $_POST['day']; // store day
$month = $_POST['month']; // store month
$disc = $_POST['disc'];

/* store the value of day */
$newDay = ($day == 0) ? "" : "AND DAY(t1.tb_calender_date) = '$day'";

/* store the value of month */
$newMonth = ($month == 0) ? "" : "AND MONTH(t1.tb_calender_date) = '$month'";

$title = $_POST['title']; // store title
$newTitle = ($title == "0") ? "" : "AND t1.fd_title = '$title'";
$newDisc = ($disc == "0") ? "" : "AND t1.fd_discription = '$disc'";

/* query for get all data according the day and month wise */
$sql = "SELECT t1.fd_id,t1.fd_admin_id,t1.tb_calender_date,t1.fd_img,t1.fd_event_type,t1.fd_title,t1.fd_sub_title,t1.fd_country_ID,t1.fd_discription,t2.fd_email FROM tb_history AS t1 INNER JOIN tb_admin AS t2 ON t1.fd_admin_id = t2.fd_id WHERE t1.fd_status = 0 AND t1.fd_delete = 0 $newDay $newMonth $newTitle $newDisc LIMIT 30";

$res = mysqli_query($conn,$sql);
$count = mysqli_num_rows($res);
$feedData = array();
while($row = mysqli_fetch_array($res)){
array_push($feedData,$row);
}
echo json_encode($feedData);
}

mysqli_close($conn); // connection close
?>