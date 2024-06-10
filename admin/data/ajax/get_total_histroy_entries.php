<?php
session_start(); // session start
require_once('../../../include/config.php'); // include connection file
$admin_id = $_SESSION['admin_id']; // session variable for admin id
$total_entry = 0; // Used as a counter variable

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d',strtotime("today")); // find current date

/* code for fetch total numbers of entries */

/* query for count total numbers of entries */
$query = mysqli_query($conn,"SELECT tb_calender_date,fd_event_type,fd_img,fd_title,fd_sub_title,fd_country_ID,fd_discription FROM `tb_history` WHERE `fd_admin_id` = $admin_id AND
fd_status = 0 AND fd_delete = 0");

if(mysqli_num_rows($query) > 0){
while($countRow = mysqli_fetch_assoc($query)){
    $title = $countRow['fd_title'];
    $sub_title = $countRow['fd_sub_title'];
    $country_id = $countRow['fd_country_ID'];
    $event_type = $countRow['fd_event_type'];
    $historical_date = $countRow['tb_calender_date'];
    $historical_img = $countRow['fd_img'];
    $discription = $countRow['fd_discription'];

    if(!empty($title) && !empty($sub_title) && !empty($country_id) && !empty($event_type) && !empty($historical_date) && !empty($historical_img) && !empty($discription)){
        $total_entry++;
    }
}
}

/* code for count total numbers of entries per day */

/* query for count today entry */
$sql = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `admin_id` FROM `tb_history` WHERE `fd_admin_id` = $admin_id AND
date(fd_uploaded_on) = '$today' AND fd_status = 0 AND fd_delete = 0");
if(mysqli_num_rows($sql) > 0){
while($countData = mysqli_fetch_assoc($sql)){
$today_entry = $countData['admin_id'];
}
}else{
echo $today_entry = "No Data Available!";
}

/* count duplicate entries */

$add_duplicate = 0; // used for sum duplicate entry
$counter = 0; // used as a counter variable

/* query for count duplicate entry */
$duplicateQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_admin_id =
$admin_id AND date(fd_uploaded_on) = '$today' AND fd_status = 0 AND fd_delete = 0 GROUP BY
fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1");

if(mysqli_num_rows($duplicateQuery) > 0){
while($countDuplicateRow = mysqli_fetch_assoc($duplicateQuery)){
$duplicate_row = $countDuplicateRow['total_duplicate'];
$add_duplicate += $duplicate_row ;
$counter += 1;
}
}

$total_duplicate = $add_duplicate - $counter; // find total duplicate entry

/* create array variable to store multiples values */
$response = array("total_entry" => $total_entry,"today_entry" => $today_entry,"duplicate_entry" => $total_duplicate);
echo json_encode($response);

mysqli_close($conn); // connection close
?>