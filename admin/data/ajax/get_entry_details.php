<?php
session_start();
require_once('../../../include/config.php'); // include connection file

/* if user is not logged in, redirect to login page */
if($_SESSION['admin_id'] == ""){
echo '<script>
window.location.href = `https://onespect.in.net/Calendar/beta/admin/index.php`;
</script>';
die();
}
/* code run on page load start */

if(!isset($_POST['fetchBtn'])){
$current_date = date("Y-m-d"); // get current date

/* code for fetch admin email */
$adminEmailQuery = mysqli_query($conn,"SELECT fd_id,fd_email FROM tb_admin");
if(mysqli_num_rows($adminEmailQuery) > 0){
$count = 0; // used for serial number
while($fetchAdminEmail = mysqli_fetch_assoc($adminEmailQuery)){
$admin = $fetchAdminEmail['fd_id']; // get admin id
$adminEmail = $fetchAdminEmail['fd_email']; // get admin email

$displayEmail = substr($adminEmail,0,4); // display only first four characters of the email
$maskedEmail = $displayEmail . str_repeat("*", strlen($adminEmail) - 4); // show * symbol after the four characters

// Sum the duplicate values
$add_duplicate = 0;
$duplicate = 0;
if($admin !="0"){

$count = $count + 1;

// /* count duplicate entries */

$duplicateQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_admin_id =
$admin AND date(fd_uploaded_on) = '$current_date' AND fd_status = 0 AND fd_delete = 0 GROUP BY
fd_title,fd_event_type,fd_sub_title,fd_discription,tb_calender_date HAVING total_duplicate > 1");

if(mysqli_num_rows($duplicateQuery) > 0){
while($countDuplicateRow = mysqli_fetch_assoc($duplicateQuery)){
$duplicate_row = $countDuplicateRow['total_duplicate'];
$add_duplicate += $duplicate_row ;
$duplicate = $duplicate + 1;
}
}
$total_duplicate = $add_duplicate - $duplicate; // total numbers of duplicate entries
}

// /* code for count total numbers of entries */
$totalEntryQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `total_entry` FROM `tb_history` WHERE `fd_admin_id` =
$admin AND date(fd_uploaded_on) = '$current_date' AND fd_status = 0 AND fd_delete = 0");

if(mysqli_num_rows($totalEntryQuery) > 0){
$total_entry = 0; // used for count total entries
while($countTotal = mysqli_fetch_assoc($totalEntryQuery)){
$total_entry = $countTotal['total_entry'];
}
}

echo '<tr>
    <td>'.$count.'</td>
    <td>'.$maskedEmail.'</td>
    <td>'.$current_date.'</td>
    <td>'.$total_duplicate.'</td>
    <td>'.$total_entry.'</td>
</tr>';
}
}
}

/* code run on page load end */


/* code for fetch data according to the selected parameters start */
else{
$sno = 0;
$id = $_POST['admin_id'];
$sdate = $_POST['start_date'];
$edate = $_POST['end_date'];
$counter = 0; // used as counter a variable

$admin_id = ($id == "0") ? "" : "AND fd_admin_id = '$id'";

/* if start date and end date both are selected */
$date = ($sdate != "0" && $edate != "0") ? "AND date(fd_uploaded_on) BETWEEN '$sdate' AND '$edate'" : "";

/* Query for fetch DISTINCT date wise admin data  */
$dateQuery = mysqli_query($conn,"SELECT DISTINCT t2.fd_email,t1.fd_admin_id,DATE(t1.fd_uploaded_on) AS historical_date FROM tb_history AS t1 INNER JOIN tb_admin AS t2 ON t1.fd_admin_id = t2.fd_id WHERE t1.fd_status = 0 AND t1.fd_delete = 0 $date $admin_id ORDER BY DATE(t1.fd_uploaded_on) DESC LIMIT 50");

if(mysqli_num_rows($dateQuery) > 0){
    while($fetchDateRow = mysqli_fetch_assoc($dateQuery)){  
    $add_duplicate_data = 0;
    $count_duplicate = 0;
    $uploaded_on = $fetchDateRow['historical_date']; // store uploaded time    
    // $admin = $fetchDateRow['fd_admin_id'];
    $admin_email = $fetchDateRow['fd_email'];

    $counter++; // increase the serial number one by one       

    /* if date is not selected */
    // if($date == ""){
        /* Query for count Total Numbers Of Duplicate entries */
        $duplicateQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_status = 0
        AND fd_delete = 0 AND date(fd_uploaded_on) = '$uploaded_on' $admin_id GROUP BY
        LOWER(fd_title),fd_event_type,tb_calender_date HAVING total_duplicate > 1");

if(mysqli_num_rows($duplicateQuery) > 0){
    while($countDuplicateRow = mysqli_fetch_assoc($duplicateQuery)){   
    $duplicate_row = $countDuplicateRow['total_duplicate'];
    $add_duplicate_data += $duplicate_row ;
    $count_duplicate = $count_duplicate + 1;
    }
    // echo $total_duplicate_data = $add_duplicate_data - $count_duplicate; // total numbers of duplicate entries
    }
    
    /* code for count total numbers of entries */
    $totalEntryQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `total_entry` FROM `tb_history` WHERE fd_status = 0 AND fd_delete = 0 AND date(fd_uploaded_on) = '$uploaded_on' $admin_id");
    
    if(mysqli_num_rows($totalEntryQuery) > 0){
    $total_entry = 0; // used for count total entries
    while($countTotal = mysqli_fetch_assoc($totalEntryQuery)){
    $total_entry = $countTotal['total_entry'];
    }
    }
    // }
    /* if date is selected */
    // else{        
              /* Query for count Total Numbers Of Duplicate entries */
    //           $duplicateQuery = mysqli_query($conn,"SELECT DISTINCT date(fd_uploaded_on),COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_status = 0 AND fd_delete = 0 $date $admin_id GROUP BY LOWER(fd_title),fd_event_type,tb_calender_date HAVING total_duplicate > 1 ORDER BY DATE(fd_uploaded_on) DESC");
      
    //   if(mysqli_num_rows($duplicateQuery) > 0){
    //       while($countDuplicateRow = mysqli_fetch_assoc($duplicateQuery)){  
    //         $uploaded_on = $countDuplicateRow['fd_uploaded_on'];

    //       $duplicate_row = $countDuplicateRow['total_duplicate'];
    //       $add_duplicate_data += $duplicate_row ;
    //       $count_duplicate = $count_duplicate + 1;
    //       }
    //       // echo $total_duplicate_data = $add_duplicate_data - $count_duplicate; // total numbers of duplicate entries
    //       }
          
    //       /* code for count total numbers of entries */
    //       $totalEntryQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `total_entry` FROM `tb_history` WHERE fd_status = 0 AND fd_delete = 0 $date $admin_id ORDER BY DATE(fd_uploaded_on) DESC");
          
    //       if(mysqli_num_rows($totalEntryQuery) > 0){
    //       $total_entry = 0; // used for count total entries
    //       while($countTotal = mysqli_fetch_assoc($totalEntryQuery)){
    //       $total_entry = $countTotal['total_entry'];
    //       }
    //     //   }  
    // }

    echo '<tr>
    <td>'.$counter.'</td>
    <td>'.$admin_email.'</td>
    <td>'.$uploaded_on.'</td>
    <td>'.$add_duplicate_data.'</td>
    <td>'.$total_entry.'</td>
    </tr>';
    }

    

    }
    }
// if($admin_id == ""){
// $admin_email_id_query = mysqli_query($conn,"SELECT fd_id,fd_email FROM tb_admin");
// if(mysqli_num_rows($admin_email_id_query) > 0){
// while($admin_email_row = mysqli_fetch_assoc($admin_email_id_query)){
// $admin_email = $admin_email_row['fd_email'];
// $id = $admin_email_row['fd_id'];
// $currentDate = new DateTime($startDate);

// $firstFourLetters = substr($admin_email, 0, 4); // display only first four characters of the email
// $hideEmail = $firstFourLetters . str_repeat("*", strlen($admin_email) - 4); // show * symbol after the four characters

// while ($currentDate <= new DateTime($endDate)) {
//     $date = $currentDate->format('Y-m-d');
//     echo $test = "SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_status = 0
//     AND fd_delete = 0 date(fd_uploaded_on) = '$date' AND fd_admin_id = $id GROUP BY
//     fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1 LIMIT 10";
// /* code for count duplicate entry start */
// $duplicateDataQuery = mysqli_query($conn,$test);


// if(mysqli_num_rows($duplicateDataQuery) > 0){
// $add_duplicate_data = 0;
// $count_duplicate = 0;
// while($countDuplicateRow = mysqli_fetch_assoc($duplicateDataQuery)){   
// $duplicate_row = $countDuplicateRow['total_duplicate'];
// $add_duplicate_data += $duplicate_row ;
// $count_duplicate = $count_duplicate + 1;
// }
// }

// echo $add_duplicate_data . "<br>";
// }
// /* code for count duplicate entry end */
// }
// }
// }
// }

// /* query to fetch admin id accroding to the selected parameters */
// $admin_email_id_query = "SELECT DISTINCT fd_admin_id,date(fd_uploaded_on) FROM tb_history WHERE fd_status = 0 AND
// fd_delete = 0
// $admin_id $date ORDER BY fd_id DESC LIMIT 50";
// $run_admin_email_id_query = mysqli_query($conn,$admin_email_id_query);

// if(mysqli_num_rows($run_admin_email_id_query) > 0){
// while($fetch_email_id_row = mysqli_fetch_assoc($run_admin_email_id_query)){
// $sno +=1;
// $add_duplicate_data = 0;
// $count_duplicate = 0;
// $uploading_time = $fetch_email_id_row['fd_uploaded_on'];

// $admin_email_id = $fetch_email_id_row['fd_admin_id']; // store admin id

// $dateTime = new DateTime($uploading_time);
// $convertToDate = $dateTime->format('Y-m-d'); // convert datetime into date format

// /* query for fetch email */
// $emailQuery = mysqli_query($conn,"SELECT fd_email FROM tb_admin WHERE fd_id = $admin_email_id");
// $fetch_email_row = mysqli_fetch_assoc($emailQuery);
// $admin_email = $fetch_email_row['fd_email'];

// $firstFourLetters = substr($admin_email, 0, 4);
// $hideEmail = $firstFourLetters . str_repeat("*", strlen($admin_email) - 4);

// /* count duplicate entries */

// $duplicateDataQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_status =
// 0
// AND fd_delete = 0 $admin_id AND date(fd_uploaded_on) = '$convertToDate' GROUP BY
// fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1");

// if(mysqli_num_rows($duplicateDataQuery) > 0){
// while($countDuplicateRow = mysqli_fetch_assoc($duplicateDataQuery)){
// $duplicate_row = $countDuplicateRow['total_duplicate'];
// $add_duplicate_data += $duplicate_row ;
// $count_duplicate = $count_duplicate + 1;
// }
// }

// $total_duplicate_data = $add_duplicate_data - $count_duplicate; // total numbers of duplicate entries

// /* code for count total numbers of entries */
// $totalEntryQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `total_entry` FROM `tb_history` WHERE fd_status = 0
// AND
// fd_delete = 0 $admin_id AND date(fd_uploaded_on) = '$convertToDate'");

// if(mysqli_num_rows($totalEntryQuery) > 0){
// while($totalRows = mysqli_fetch_assoc($totalEntryQuery)){
// $total_entry_data = $totalRows['total_entry'];
// }
// }

// echo '<tr>
    // <td>'.$sno.'</td>
    // <td>'.$hideEmail.'</td>
    // <td>'.$convertToDate.'</td>
    // <td>'.$total_duplicate_data.'</td>
    // <td>'.$total_entry_data.'</td>
    // </tr>';
// }
// }else{
// echo "No data Avaialable!";
// }
// }
?>