<?php
session_start();
require_once('../../../include/config.php');

date_default_timezone_set('Asia/Kolkata');

$admin_email  = $_SESSION['email'];
$admin_id  = $_SESSION['admin_id'];

/* code for insert data start */
if(isset($_POST['submitBtn'])){
    $date = $_POST['date'];
    $event = $_POST['event'];

    $filename = $_FILES['image']['name'];   
    $file_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];  

    $title = addslashes($_POST['title']);
    $sub_title = addslashes($_POST['sub_title']);
    $country_id = $_POST['country_name'];
    $disc = addslashes($_POST['discription']);
    $currentDateTime = date('Y-m-d H:i:s');

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_extension = array("jpg","jpeg","png","gif","webp","JPG","JPEG","PNG","GIF","WEBP");    
    
    /* Verify duplicate data  */
    $duplicateData = mysqli_query($conn,"SELECT fd_title,fd_sub_title FROM tb_history WHERE fd_title = '$title' AND fd_sub_title = '$sub_title' AND tb_calender_date = '$date' AND fd_status = 0 AND fd_delete = 0");

    /* Search for duplicate data */
    if(mysqli_num_rows($duplicateData) > 0){
        echo 0;
    }else{

    if($file_size > 2097152){
        echo 1;
    }
    else if(in_array($ext,$valid_extension)){
        $newName = md5(microtime()).'.'.$ext; 
        if(move_uploaded_file($tempname,"../image/$newName")){
            $insertQuery = "INSERT INTO `tb_history`(`tb_calender_date`,`fd_admin_id`,`fd_event_type`,`fd_img`,`fd_title`,`fd_sub_title`,`fd_country_ID`,`fd_discription`,`fd_uploaded_on`) VALUES ('$date',$admin_id,'$event','$newName','$title','$sub_title','$country_id','$disc','$currentDateTime')";             
           
            $runQuery = mysqli_query($conn,$insertQuery);           

            if($runQuery){
                echo 2;
            }
            else{
                echo 3;
            }
        }
    }else{
        echo "Something went wrong!";
    }
}
}
/* code for insert data end */

/* code for update data start */
if(isset($_POST['updateBtn'])){ 
    $update = $_POST['updateBtn'];  
    $img = $_POST['feedImg']; 
    $filename = $_FILES['feedImg']['name']; 
    $file_size = $_FILES['feedImg']['size'];
    $tempname = $_FILES['feedImg']['tmp_name']; 
    $date = $_POST['updateDate'];
    $event = $_POST['updateEvent'];
    $title = addslashes($_POST['updateTitle']); 
    $sub_title = addslashes($_POST['updatesub_title']); 
    $update_country_id = $_POST['update_country_id'];
    $disc = addslashes($_POST['updateDisc']); 
    
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_extension = array("jpg","jpeg","png","gif","webp","JPG","JPEG","PNG","GIF","WEBP");    

    $verifyQuery = mysqli_query($conn,"SELECT fd_admin_id FROM tb_history WHERE fd_id = $update");    
    $fetchId = mysqli_fetch_assoc($verifyQuery);   
    
    $added_by = $fetchId['fd_admin_id'];
  if($admin_id == $added_by){
  if($filename !=''){
    
    if($file_size > 2097152){
                echo 0;
            }
            else if(in_array($ext,$valid_extension)){
                        $newName = md5(microtime()).'.'.$ext; 
                        if(move_uploaded_file($tempname,"../image/$newName")){
                            $updateQuery = "UPDATE `tb_history` SET `fd_admin_id` = '$admin_id',`tb_calender_date`='$date',`fd_event_type`='$event',`fd_img`='$newName',`fd_title`='$title',`fd_sub_title`='$sub_title',   `fd_country_ID` = '$update_country_id',`fd_discription`='$disc' WHERE fd_id='$update'";        
                            $runQuery = mysqli_query($conn,$updateQuery);                            
                
                            if($runQuery){
                                echo 1;
                            }
                            else{
                                echo 2;
                            }
                        }
                    }
                    else{
                        echo "Something went wrong!";
                    }   
  }
  else{
    $updateQuery = "UPDATE `tb_history` SET `fd_admin_id` = '$admin_id',`tb_calender_date`='$date',`fd_event_type`='$event',`fd_title`='$title',`fd_sub_title`='$sub_title',`fd_country_ID` = '$update_country_id',`fd_discription`='$disc' WHERE fd_id='$update'";  

            $runQuery = mysqli_query($conn,$updateQuery);           

            if($runQuery){
                echo 1;
            }
            else{
                echo 2;
            }
  }
}else{
    echo 3;
}
  }

/* code for update data end */
mysqli_close($conn); // connection close
?>