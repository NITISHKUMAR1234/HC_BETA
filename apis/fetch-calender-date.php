<?php

$baseDir = "/www/wwwroot/ONESPECT.IN.NET/Calendar/beta/";

session_start(); // session is start
require_once($baseDir.'include/config.php'); // include connection file

// set the timezone
date_default_timezone_set('UTC');


// set the month and year
if(isset($_GET['month']) && isset($_GET['year'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];
} else {
    $month = date('m');
    $year = date('Y');
}
    $newYear = $year;
    $newMonth = $month;
    
    if($month == 1){
    $newMonth = 12;
    $newYear = $year - 1;
}
    else{
    $newMonth--;
}
    $nextYear = $year;
    $nextmonth = $month;
if($month == 12){
    $nextmonth = 1;
    $nextYear = $year + 1;
}
else{
    $nextmonth++;
}
$month;
    
// get the number of days in the month

$numDays = cal_days_in_month(CAL_GREGORIAN,$month, $year);

// create a date object for the first day of the month
$date = mktime(0, 0, 0, $month, 1, $year);

// get the day of the week for the first day of the month
$dayOfWeek = date('D', $date);

// create an array of days of the week
$daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

// determine the offset for the first day of the month
$offset = array_search($dayOfWeek, $daysOfWeek);

$previous_month = ($month - 1);
$next_month = ($month + 1);

// code by Nitish 29-06-23 start
$monthNumber = $month; // Replace with your desired month number
$currentMonth = date('F', mktime(0, 0, 0, $monthNumber, 1));

$today = date('d');
$monthYear = $currentMonth . " " . $year;

$currentMonth = date('m'); // get current month
$currentYear = '20'.date('y'); // get current year
$urlMonth = $_GET['month']; // get url month
if($urlMonth<10){ $newUrlMonth='0' .$urlMonth; }
else{
$newUrlMonth = $urlMonth;
} 
$urlYear=$_GET['year'];  // get url year

for($i = 1; $i <= $numDays; $i++) {
    $com_date=$year."-".$month."-".$i;               
 
    $date=mktime(0, 0,0,$month, $i, $year); 
    $dayOfWeek=date('D', $date); /* code for fetch calender details start */ //
  
   $calenderQuery=mysqli_query($conn,"SELECT fdTitle,fdSubTitle,fdImg FROM `tb_calender`
   WHERE DAY(`fdDate`)='$i' AND MONTH(`fdDate`)='$newUrlMonth' AND YEAR(`fdDate`)='$urlYear' AND fdStatus=0
   AND fdDelete=0"); 
   if(mysqli_num_rows($calenderQuery)> 0){
   $calenderRow = mysqli_fetch_assoc($calenderQuery);
   $maxTitleLength = 40;
   $caltitle = $calenderRow['fdTitle'];
   if (strlen($caltitle) > $maxTitleLength) {
   $title = substr($caltitle, 0, $maxTitleLength) . "..";
   }else{
   $title = $caltitle;
   }

   $maxSubTitleLength = 20;
   $cal_sub_title = $calenderRow['fdSubTitle'];

   if (strlen($cal_sub_title) > $maxSubTitleLength) {
   $sub_title = substr($cal_sub_title, 0, $maxSubTitleLength) . "..";
   }else{
   $sub_title = $cal_sub_title;
   }

   if($calenderRow['fdImg']){
   $img = $calenderRow['fdImg'];
   }
   } else{    
   $histroyQuery=mysqli_query($conn,"SELECT fd_title, fd_sub_title,fd_discription,fd_img FROM `tb_history` WHERE
   DAY(`tb_calender_date`) = '$i' AND MONTH(`tb_calender_date`) = '$newUrlMonth' AND `fd_status`=0 AND
   `fd_delete`=0 ORDER BY `fd_id` DESC LIMIT 1");

   if(mysqli_num_rows($histroyQuery) > 0){
   $histroyRow = mysqli_fetch_assoc($histroyQuery);

   $histroy_title_length = 40;
   $histroy_title = $histroyRow['fd_title'];
   $histroy_disc = $histroyRow['fd_discription']; // store discription

   if (strlen($histroy_title) > $histroy_title_length) {
   $title = substr($histroy_title, 0, $histroy_title_length) . "..";
   }
   else{
   $title = $histroy_title;
   }
   $histroy_sub_title_length = 20;
   $histroy_sub_title = $histroyRow['fd_sub_title'];

   if (strlen($histroy_sub_title) > $histroy_title_length) {
   $sub_title = substr($histroy_sub_title, 0, $histroy_sub_title_length) . "..";
   }
   else{
   $sub_title = $histroy_sub_title;
   }
   if($histroyRow['fd_img']){
   $img = $histroyRow['fd_img'];
   }
   }
}
   /* code for fetch calender details end */

   /* code for highlight today card */

   if(($today==$i) && ($newUrlMonth==$currentMonth) && ($urlYear==$currentYear))
   {                
   echo '<div id="calcard" class="cardcal mx-md-2 my-md-2 my-3">
       <span>'.$i.'</span>
       <p>'.$dayOfWeek.'</p>';
       echo '<h5 class="title" onload = "setTextColorBasedOnBackground(this);">'.$title.'</h5>

       <p class="carddesc">'.$sub_title.'</p>';

       if (file_exists("admin/data/calender_image/" . $img) && $calenderRow['fdImg']){
       echo '
       <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$caltitle.'&discription='.$histroy_disc.'&image=admin/data/calender_image/'.$img.'"><img class="img-fluid lodeImgs"
               data-img='.$i.' onload="changeDivColor(this)"
               src="admin/data/calender_image/'.$img.'"></a>';

       }
       else if(file_exists("admin/data/image/" . $img) && $histroyRow['fd_img']){
       echo '
       <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$histroy_title.'&discription='.$histroy_disc.'&image=admin/data/image/'.$img.'"><img class="img-fluid lodeImgs"
               data-img='.$i.' onload="changeDivColor(this)" src="admin/data/image/'.$img.'"></a>';
       }
       else{
       echo '<button class="btnsync"><a
               href="index.php?histroy_feed&date='.$com_date.'">Explore</a></button>';
       }
       echo '
   </div>';
   }
   else{                
   echo '<div id="cardcal_'.$i.'" class="cardcal mx-md-2 my-md-2 my-3">
       <span>'.$i.'</span>
       <p>'.$dayOfWeek.'</p>
       <h5 class="title" onload = "setTextColorBasedOnBackground(this);">'.$title.'</h5>
       <p id="festiveImg" class="carddesc">'.$sub_title.'</p>';
       if (file_exists("admin/data/calender_image/" . $img) && $calenderRow['fdImg']){
           echo '
           <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$caltitle.'&discription='.$histroy_disc.'&image=admin/data/calender_image/'.$img.'"><img class="img-fluid lodeImgs"
                   data-img='.$i.' onload="changeDivColor(this)"
                   src="admin/data/calender_image/'.$img.'"></a>';

           }
       else if(file_exists("admin/data/image/".$img) && $histroyRow['fd_img']){                   
       echo '
       <a class="link" href="index.php?histroy_feed&date='.$com_date.'&title='.$histroy_title.'&discription='.$histroy_disc.'&image=admin/data/image/'.$img.'"><img class="img-fluid lodeImgs"
               data-img='.$i.' onload="changeDivColor(this)" src="admin/data/image/'.$img.'"></a>';

       }else{
       echo '<button class="btnsync"><a
               href="index.php?histroy_feed&date='.$com_date.'">Explore</a></button>';
       }
       echo '
   </div>';
   }
   unset($dayOfWeek);
}

?>