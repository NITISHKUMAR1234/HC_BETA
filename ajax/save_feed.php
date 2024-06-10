<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file 

# SAVE CONTENT -----------
$feedId = $_POST['feedId'];
$feedType = $_POST['feedType'];
$userId = $_POST['userId'];

if(isset($feedId) && isset($feedType) && isset($userId)) {
    $query = "INSERT INTO tb_saved (`fdPostId`, `fdPostType`, `fdUserId`) VALUES ('$feedId', '$feedType','$userId')";
    if (@mysqli_query($conn,$query)) {
        echo "New record inserted successfully";
    } else {
        header('HTTP/1.0 404 Not Found', true, 404);
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
else {
    echo 'Failed';
}
# SAVE CONTENT END -----------


mysqli_close($conn); // connection close
?>