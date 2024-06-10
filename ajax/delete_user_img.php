<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file

if($_SESSION['loginuseremail'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/user_login.php`;
    </script>';
    die();
}

if(isset($_POST['user_email'])){
    $user_email = $_POST['user_email']; // user email    
   
    $sql = mysqli_query($conn,"SELECT fd_profile_image FROM tb_users WHERE fd_email = '$user_email'");
    if(mysqli_num_rows($sql) > 0){        
        $row = mysqli_fetch_assoc($sql);
        $filename = $row['fd_profile_image']; // fetch user image
       
        if($filename){
            if(file_exists("../Image/" . $filename)){
        // Delete the image file from the folder
        unlink("../Image/". $filename);
            }
            
    // Delete the image record from the database
    $result = mysqli_query($conn, "UPDATE tb_users SET fd_profile_image = '' WHERE fd_profile_image = '$filename'");
    if($result){
        echo 1;
    }else{
        echo "Error!";
    }
    } 
} 
else{
    echo "Not Exist!";
}  
}

// Close the database connection
mysqli_close($conn);
?>