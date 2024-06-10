<?php
session_start(); // session start
require_once('../../../include/config.php'); // include connection file
$admin_id = $_SESSION['admin_id']; // session variable for admin id

/* query for fetch duplicate data */

/* count duplicate entries */

$add_duplicate = 0; // used for sum duplicate entry
$counter = 0; // used as a counter variable
$sno = 0; // used for serial number

/* This query is run when the admin id is equal to 1 and 2 */
if($admin_id == 1 || $admin_id == 2){   
    /* query for count duplicate entry */
$duplicateQuery = @mysqli_query($conn,"SELECT fd_id,fd_event_type,fd_img,fd_Country_ID,tb_calender_date,fd_uploaded_on,fd_admin_id,fd_title,fd_sub_title,fd_discription, COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_status = 0 AND fd_delete = 0 GROUP BY
fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1 ORDER BY fd_id DESC LIMIT 30");
}
/* This query is run when the admin id is not equal to 1 and 2 */
else{ 
/* query for count duplicate entry */
$duplicateQuery = @mysqli_query($conn,"SELECT fd_id,fd_event_type,fd_img,fd_Country_ID,tb_calender_date,fd_uploaded_on,fd_admin_id,fd_title,fd_sub_title,fd_discription, COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_admin_id =
$admin_id AND fd_status = 0 AND fd_delete = 0 GROUP BY
fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1 ORDER BY fd_id DESC LIMIT 30");
}

if(@mysqli_num_rows($duplicateQuery) > 0){
while($countDuplicateRow = @mysqli_fetch_assoc($duplicateQuery)){  
$admin = $countDuplicateRow['fd_admin_id']; // fetch admin id from the tb_history_table
$maxLengthOfTitle = 20; // maximum length of title
$title = $countDuplicateRow['fd_title']; // store title

/* if the length of the title is maximum then it display in the shortend way */
if (strlen($title) > $maxLengthOfTitle) {
    $shortenedTitle = substr($title, 0, $maxLengthOfTitle) . "...";
} else {
    $shortenedTitle = $title;
}

$sub_title = $countDuplicateRow['fd_sub_title']; // store sub title 
$maxLengthOfSubTitle = 20; // maximum length of sub title

/* if the length of the sub title is maximum then it display in the shortend way */
if (strlen($sub_title) > $maxLengthOfSubTitle) {
    $shortenedSubTitle = substr($sub_title, 0, $maxLengthOfSubTitle) . "...";
} else {
    $shortenedSubTitle = $sub_title;
}

$discription = $countDuplicateRow['fd_discription'];
$maxLengthOfDisc = 20; // maximum length of discription

/* if the length of the discription is maximum then it display in the shortend way */
if (strlen($discription) > $maxLengthOfDisc ) {
    $shortenedDisc = substr($discription, 0, $maxLengthOfDisc ) . "...";
} else {
    $shortenedDisc = $discription;
}

$historical_date = $countDuplicateRow['tb_calender_date']; // store historical date
$dateTime = $countDuplicateRow['fd_uploaded_on']; // store uploaded time
$dateTimeObj = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
$uploaded_on = $dateTimeObj->format('Y-m-d');


$sno ++; // increase the serial number one by one
$duplicate_row = $countDuplicateRow['total_duplicate'];
$add_duplicate += $duplicate_row ;
$counter += 1;

/* query for fetch admin email from the tb_admin table */
$admin_email_query = @mysqli_query($conn,"SELECT fd_email FROM tb_admin WHERE fd_id = $admin");
$adminEmailRow = @mysqli_fetch_assoc($admin_email_query);
$admin_email_id = $adminEmailRow['fd_email']; // store admin email 

echo '<tr>
<td>'.$sno.'</td>
<td>'.$admin_email_id.'</td>
<td>'.$historical_date.'</td>
<td>'.$shortenedTitle.'</td>
<td>'.$shortenedSubTitle.'</td>
<td>'.$shortenedDisc.'</td>
<td>'.$uploaded_on.'</td>
<td>
<button class="btn btn-warning editBtn" type="button" value="'.$countDuplicateRow['fd_id'].'" onclick="showModel()" id="editBtn" name="editBtn"
data-id = "'.$countDuplicateRow['fd_id'].'"
data-date = "'.$historical_date.'"
data-event = "'.$countDuplicateRow['fd_event_type'].'"
data-img = "'.$countDuplicateRow['fd_img'].'"
data-title = "'.$title.'"
data-subTitle = "'.$sub_title.'"
data-cid = "'.$countDuplicateRow['fd_Country_ID'].'"
data-disc = "'.$discription.'">
<i class="fa fa-pencil" aria-hidden="true"></i>
</button>
</td>
<td>
<button class="btn btn-danger deleteBtn" type="button" id="deleteBtn" name="deleteBtn"
data-id = "'.$countDuplicateRow['fd_id'].'"
data-date = "'.$historical_date.'"
data-event = "'.$countDuplicateRow['fd_event_type'].'"
data-img = "'.$countDuplicateRow['fd_img'].'"
data-title = "'.$title.'"
data-subTitle = "'.$sub_title.'"
data-cid = "'.$countDuplicateRow['fd_Country_ID'].'"
data-disc = "'.$discription.'">

<i class="fa fa-trash" aria-hidden="true"></i>
</button>
</td>
</tr>';
}
}
else{
    echo "<tr>
    <td colspan='7'>No Data Available!</td>
    </tr>";
}
$total_duplicate = $add_duplicate - $counter; // find total duplicate entry


mysqli_close($conn); // connection close
?>