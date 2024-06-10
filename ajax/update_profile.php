<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file

if($_SESSION['loginuseremail'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/user_login.php`;
    </script>';
    die();
}
$user_email = $_SESSION['loginuseremail'];

if(isset($_FILES['image'])){
    $filename = $_FILES['image']['name'];   
    $file_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];  
    
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_extension = array("jpg","jpeg","png","gif","webp","JPG","JPEG","PNG","GIF","WEBP"); 

    if($file_size > 2097152){
        echo 1;
    }
    else if(in_array($ext,$valid_extension)){
        $newName = md5(microtime()).'.'.$ext; 
        if(move_uploaded_file($tempname,"../Image/$newName")){
            $updateQuery = "UPDATE tb_users SET fd_profile_image = '$newName' WHERE fd_email = '$user_email'";
            $runQuery = mysqli_query($conn,$updateQuery);           

            if($runQuery){
                echo 2;
            }
            else{
                echo 3;
            }
        }
    }  
}

if(!isset($_FILES['image'])){
/* variable declaration */
$firstname = $_POST['fname'];
$lastname = $_POST['lname'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];
$channel = $_POST['channel'];
$address = $_POST['address'];
$country = $_POST['country_name'];
$state = $_POST['sname'];
$city = $_POST['city_name'];
$zip = $_POST['zip'];

/* upadate query */
$updateQuery = mysqli_query($conn,"UPDATE tb_users SET fd_fname = '$firstname',fd_lname = '$lastname',fd_Dob = '$dob',fd_contactno = '$phone',fd_channel = '$channel',fd_address = '$address',fd_country = '$country',fd_state = '$state',fd_city = '$city',fd_zip = '$zip' WHERE fd_email = '$user_email'");

/* if query run successfully */
if($updateQuery){
    echo 1;
}
/* if query does not run */
else{
    echo 0;
}
}
?>