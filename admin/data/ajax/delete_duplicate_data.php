<?php
session_start(); // session start
require_once('../../../include/config.php'); // include connection file

$admin_id  = $_SESSION['admin_id']; // session varibale for store admin id

$feed_id = $_POST['feed_id']; // store document id
$image_id = $_POST['image_id']; // store image name 
$imagePath = "../image/" . $image_id; // store complete path of the image

/* Query for fetch admin id  */
$verifyQuery = mysqli_query($conn,"SELECT fd_admin_id FROM tb_history WHERE fd_id = $feed_id AND fd_status = 0 AND fd_delete = 0");

$fetchRow = mysqli_fetch_assoc($verifyQuery);
$added_by = $fetchRow['fd_admin_id'];

/* Admin can delete only their own document */
if($admin_id == $added_by){

/* query for delete record  */
$deleteQuery = mysqli_query($conn,"UPDATE tb_history SET fd_delete = 1 WHERE fd_id = $feed_id");

if($deleteQuery){
    unlink($imagePath); // delete image from the folder
    echo 1;
}else{
    echo "Something went wrong!";
}
}
else{
    echo 0;
}
mysqli_close($conn); // connection close
?>