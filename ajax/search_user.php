<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file 

$search_user = $_POST['searchInput']; // Store serached user email
$user = '%'.$search_user.'%';
/* Query for find user */
$findUser = mysqli_query($conn,"SELECT fd_email FROM tb_users WHERE fd_email LIKE '$user'");

if(mysqli_num_rows($findUser) > 0){
    while($fetchUserEmail = mysqli_fetch_assoc($findUser)){
        $user_email = $fetchUserEmail['fd_email'];   
        echo '<li>'.$user_email.'</li>';         
    }
}else{
    echo '<li> No data found!😥 </li>';
}

mysqli_close($conn); // connection close
?>