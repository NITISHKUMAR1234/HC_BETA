<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file 

# DELETE SAVED FEED CONTENT -----------
$postId = $_POST['postId'];
$userId = $_POST['userId'];

if(isset($postId) && isset($userId)) {
    $query = "DELETE FROM tb_saved WHERE fdPostId='".$postId."' AND fdUserID='".$userId."';";
    if (@mysqli_query($conn,$query)) {
        echo "Delete Successfully";
    } else {
        header('HTTP/1.0 404 Not Found', true, 404);
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
else {
    header('HTTP/1.0 401 Unautherized', true, 401);
    echo 'Failed';
}
# DELETE SAVED FEED CONTENT END -----------

mysqli_close($conn); // connection close
?>