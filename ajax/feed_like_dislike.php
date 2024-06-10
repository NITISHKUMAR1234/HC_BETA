<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file
// check feed date
$currDate = $_POST['currDate']; // get url date,month and year
$month = $_POST['month'];

$date = $_POST['date'];

$user_email = $_POST['user_email'];
/* code for like start */
if(isset($_POST['likeBtn'])){    
  
    // check if user has already liked/disliked the post
    
    $result = mysqli_query($conn,"SELECT fd_like,fd_dislike FROM tb_history WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'");
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);       
        $count_like = $row['fd_like'] + 1;
        $dislike = $row['fd_dislike'];       
        $decrease_dislike = $row['fd_dislike'] - 1;

      // if user already liked/disliked, then update the status
      $query = "UPDATE tb_history SET fd_like = '$count_like' WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'";
      $runQuery = mysqli_query($conn,$query); 
      
      /* code for insert user id and its like or dislike into the tb_like details table */
    
      $checkLike = "SELECT fd_user_id FROM tb_like_dlike_details WHERE fd_user_id = '$user_email' AND MONTH(`fd_his_date`) = '$month' AND DAY(`fd_his_date`) = '$currDate'"; 
      $res = mysqli_query($conn,$checkLike);  
      
      if(mysqli_num_rows($res) > 0){
  
        $updateLikeQuery = "UPDATE tb_like_dlike_details SET fdStatus = '1' WHERE fd_user_id = '$user_email' AND MONTH(`fd_his_date`) = '$month' AND DAY(`fd_his_date`) = '$currDate'";
        $runQuery = mysqli_query($conn,$updateLikeQuery);
        
        if($dislike != '0'){
        $decQuery = mysqli_query($conn,"UPDATE tb_history SET fd_dislike = '$decrease_dislike' WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'");
        }
      }else{
      $likeQuery = "INSERT INTO `tb_like_dlike_details` (`fd_his_date`,`fd_user_id`,`fdStatus`,`fd_created_date`) VALUES ('$date','$user_email','1',now())";    
   
      $runQuery = mysqli_query($conn,$likeQuery);
      }      
    } 
    
    $sql = mysqli_query($conn,"SELECT fd_like,fd_dislike FROM tb_history WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'");
    $likeDislikeRow = mysqli_fetch_assoc($sql);
    $totalLike = $likeDislikeRow['fd_like'];
    $totalDislike = $likeDislikeRow['fd_dislike'];
    
    $response = array('like_count' => $totalLike,'dislike_count' => $totalDislike);
    echo json_encode($response);
    mysqli_close($conn);
    }
    
    /* code for like end */
    
    /* code for dislike start */

    if(isset($_POST['dislikeBtn'])){  
       
        // check if user has already liked/disliked the post
        $result = mysqli_query($conn,"SELECT fd_like,fd_dislike FROM tb_history WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'");
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $count_dislike = $row['fd_dislike'] + 1;
            $like = $row['fd_like'];
            $decrease_like = $row['fd_like'] - 1;
          // user already liked/disliked, so update the status
          $query = "UPDATE tb_history SET fd_dislike = '$count_dislike' WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'";
          $runQuery = mysqli_query($conn,$query);    
    
          $ckeckDislike = mysqli_query($conn,"SELECT fd_user_id FROM tb_like_dlike_details WHERE fd_user_id = '$user_email' AND MONTH(`fd_his_date`) = '$month' AND DAY(`fd_his_date`) = '$currDate'");
    
      if(mysqli_num_rows($ckeckDislike) > 0){
        $updateLikeQuery = "UPDATE tb_like_dlike_details SET fdStatus = '2' WHERE fd_user_id = '$user_email' AND MONTH(`fd_his_date`) = '$month' AND DAY(`fd_his_date`) = '$currDate'";
        
        $runQuery = mysqli_query($conn,$updateLikeQuery);
    
        if($like !='0'){
        $decQuery = mysqli_query($conn,"UPDATE tb_history SET fd_like = '$decrease_like' WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'");   
        }
      }else{
      $likeQuery = "INSERT INTO `tb_like_dlike_details` (`fd_his_date`,`fd_user_id`,`fdStatus`,`fd_created_date`) VALUES ('$currDate','$user_email','2',now())";
    
      $runQuery = mysqli_query($conn,$likeQuery);
      }  
        } 
        $sql = mysqli_query($conn,"SELECT fd_like,fd_dislike FROM tb_history WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate'");
        $likeDislikeRow = mysqli_fetch_assoc($sql);
        $totalLike = $likeDislikeRow['fd_like'];
        $totalDislike = $likeDislikeRow['fd_dislike'];
    
        $response = array('like_count' => $totalLike,'dislike_count' => $totalDislike);
        echo json_encode($response);
    }
    
    /* code for dislike end */ 
?>