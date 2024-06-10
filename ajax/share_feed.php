<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file 

# SHARE CONTENT -----------
$feedId = $_POST['feedId'];
$feedType = $_POST['feedType'];
$sender = $_POST['sender'];
$reciver = $_POST['reciver'];

if(isset($feedId) && isset($feedType) && isset($reciver) && isset($sender)) {
    $query = 'INSERT INTO tb_shared (fdSender, fdRecever, fdPostId, fdPostType) VALUES ("'.$sender.'", "'.$reciver.'", '.$feedId.', '.$feedType.');';
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
# SHARE CONTENT END -----------

mysqli_close($conn); // connection close
?>