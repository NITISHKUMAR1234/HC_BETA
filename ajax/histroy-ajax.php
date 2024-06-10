<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file
$currDate = $_POST['currDate']; // store url date,month and year
$month = $_POST['month']; // store url month
$date = $_POST['date']; // store url date

if(isset($_SESSION['loginuseremail'])){
    $user_email = $_SESSION['loginuseremail'];
}
?>
<?php
if (isset($_POST["limit"]) && isset($_POST["start"])) {
$limit = $_POST["limit"];
$start = $_POST["start"];

?>
<section>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 card-container">
                <br>
                <?php

                /* if event is selected */
                if(isset($_POST['event_id'])){
                $event_id = $_POST['event_id'];
                $event_type = ($event_id == "All") ? "" : "AND fd_event_type = '$event_id'"; // store the value of event. If the event id is "All" then it will fetch all the data from the selected date otherwise it it will fetch the data according to the selected event type
                $start = 0; // Limit start from 0

                /* Query for check historical date is available on database table or not? */
                $verifydateQuery = @mysqli_query($conn,"SELECT DISTINCT tb_calender_date,fd_title FROM tb_history WHERE
                MONTH(`tb_calender_date`)
                = '$month' AND DAY(`tb_calender_date`) = '$currDate' AND fd_status = 0 AND fd_delete = 0 $event_type ORDER BY fd_id DESC LIMIT $start,$limit");

                if(@mysqli_num_rows($verifydateQuery) > 0){
                    while($fetchDate = mysqli_fetch_assoc($verifydateQuery)){
                $date = $fetchDate['tb_calender_date']; // store date of the historical data
                ($fetchDate['fd_title']) ? $title = $fetchDate['fd_title'] : $title = ""; // store title of the feed


                /* code for sub_title,image and discription */
                $allDataQuery = @mysqli_query($conn,"SELECT fd_id,fd_img,fd_sub_title,fd_discription FROM tb_history WHERE
                fd_title = '$title'
                AND tb_calender_date = '$date' AND fd_status = 0 AND fd_delete = 0 ORDER BY
                `tb_calender_date`");

                $fetchData = @mysqli_fetch_assoc($allDataQuery);

                ($fetchData['fd_img']) ? $img = $fetchData['fd_img'] : $img = "";
                ($fetchData['fd_sub_title']) ? $sub_title = $fetchData['fd_sub_title'] : $sub_title = "";
                ($fetchData['fd_discription']) ? $discription = $fetchData['fd_discription'] : $discription = "";

                $feedId = $fetchData['fd_id']; // Store feed id
                ?>
                <div class="border">
                    <div class="row">
                        <div class="col-md-12 headText">
                            <div class="px-2 mx-2" style="background-color:0;">
                                <div class="row">
                                    <div class="col-9">
                                        <h5 class="title"><b><?php echo $title; ?></b>
                                        </h5>
                                        <h5 class="sub-title">
                                            <?php echo $sub_title; ?></h5>
                                    </div>
                                    <div class="col-3">
                                        <span class="float-right mt-4"
                                            style="font-size: 12px;"><?php echo date("d/m/Y", strtotime($date)); ?>
                                            <i class="fa fa-calendar ml-1 w3-text-red" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            /* if image is exist in the folder start */
                            if (file_exists("../admin/data/image/" . $img) && ($fetchData['fd_img'])) {
                            $histroy_img = $fetchData['fd_img'];
                        ?>
                        <div class="col-md-12 img-area w3-center">
                            <div class="main-img">
                                <img src="admin/data/image/<?php echo $histroy_img; ?>" alt="image">
                                <span class="float-right">
                                    <?php
                                    echo '<button onclick="tr.Read('.$feedId.')" class="sound-btn btn btn-sm bg-white">
                                    <span class="sound-icon">🔇</span>
                                    </button>';
                                ?>
                                </span>
                            </div>
                        </div>
                        <?php
                            }
                            /* if image is exist in the folder end */
                        ?>
                        <div class="p-2 m-2">
                            <div class="col-md-12 disc">
                                <div class="text-area">
                                    <p id="<?php echo $feedId; ?>"><?php echo $discription; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><br>
                <?php
                }
                }
                else{
                echo "<h2 class='mt-5 text-center'>No Data Available!</h2>"; // show if data is not available
                }
                }else{

                /* Query for check historical date is available on database table or not? */

                $dateQuery = @mysqli_query($conn,"SELECT tb_calender_date,fd_title,fd_sub_title,fd_discription FROM tb_history WHERE MONTH(`tb_calender_date`)
                = '$month' AND DAY(`tb_calender_date`) = '$currDate' AND fd_status = 0 AND fd_delete = 0");

                if(@mysqli_num_rows($dateQuery) > 0){

                    /* query for fetch distinct title,and date */
                $titleQuery = "SELECT DISTINCT tb_calender_date,fd_title FROM `tb_history` WHERE fd_status = 0 AND
                fd_delete = 0 AND MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate' ORDER BY fd_id DESC LIMIT $start,
                $limit";

                $runTitleQuery = @mysqli_query($conn,$titleQuery); // execute query
                if(@mysqli_num_rows($runTitleQuery) > 0){
                while($fetchTitle = @mysqli_fetch_assoc($runTitleQuery)){
                ($fetchTitle['tb_calender_date']) ? $historical_date = $fetchTitle['tb_calender_date'] : $historical_date = ""; // fetch historical date

                ($fetchTitle['fd_title']) ? $title = $fetchTitle['fd_title'] : $title = ""; // store title

                /* query for fetch image,sub_title and discription */

                $imgQuery = @mysqli_query($conn,"SELECT fd_id,fd_img,fd_event_type,fd_sub_title,fd_discription FROM tb_history WHERE fd_title = '$title'
                AND tb_calender_date = '$historical_date' AND fd_status = 0 AND fd_delete = 0 ORDER BY `tb_calender_date`");

                $ImgRow = @mysqli_fetch_assoc($imgQuery);

                ($ImgRow['fd_discription']) ? $discription = $ImgRow['fd_discription'] : $discription = ""; // store discription
                $img = $ImgRow['fd_img']; // store image name
                ($ImgRow['fd_sub_title']) ? $sub_title = $ImgRow['fd_sub_title'] : $sub_title = ""; // store sub title
                $feedId = $ImgRow['fd_id']; // Store feed id
                $feedType = $ImgRow['fd_event_type']; // Store feed event type
                ?>
                <div class="border">
                    <div class="row">
                        <div class="col-md-12 headText">
                            <div class="px-2 mx-2" style="background-color:0;">
                                <div class="row">
                                    <div class="col-9">
                                        <h5 class="title"><b><?php echo $title; ?></b>
                                        </h5>
                                        <h5 class="sub-title">
                                            <?php echo $sub_title; ?></h5>
                                    </div>
                                    <div class="col-3">
                                        <span class="float-right mt-4"
                                            style="font-size: 12px;"><?php echo date("d/m/Y", strtotime($historical_date)); ?>
                                            <i class="fa fa-calendar ml-1 w3-text-red" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            if (file_exists("../admin/data/image/" . $img) && ($ImgRow['fd_img'])) {
                                $histroy_img = $ImgRow['fd_img'];
                            ?>
                        <div class="col-md-12 img-area w3-center">
                            <div class="main-img">
                                <img src="admin/data/image/<?php echo $histroy_img; ?>" alt="image">
                                <span class="float-right">
                                    <?php
                                    echo '<button onclick="tr.Read('.$feedId.')" class="sound-btn btn btn-sm bg-white">
                                    <span class="sound-icon">🔇</span>
                                    </button>';

                                ?>
                                </span>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="p-2 m-2">
                            <div class="col-md-12 disc">
                                <div class="text-area">
                                    <p id="<?php echo $feedId; ?>"><?php echo $discription; ?></p>
                                </div>
                                <!-- code for share and save button start -->
                                <style>
                                    .my-icon:hover {
                                        color: #00f;
                                    }
                                </style>
                                <?php if(isset($_SESSION['loginuseremail'])) { ?>
                                <div class="share-area float-right">
                                    <button data-id = "<?php echo $feedId; ?>"
                                    data-title = "<?php echo $title; ?>"
                                    data-type = "<?php echo $feedType; ?>"
                                    data-subTitle = "<?php echo $sub_title; ?>"
                                    data-disc = "<?php echo $discription; ?>"
                                    data-img = "<?php echo $histroy_img; ?>"
                                    data-date = "<?php echo $historical_date; ?>"
                                    type="button" class="btn w3-pale-red btn-sm open_modal_button"
                                        id="open_modal_button"><i class="fa-solid fa-share my-icon"></i></button>

                                    <?php if(!IsPostSaved($feedId, $feedType, $user_email)) { ?>
                                    <button data-id = "<?php echo $feedId; ?>"
                                    data-title = "<?php echo $title; ?>"
                                    data-type = "<?php echo $feedType; ?>"
                                    data-subTitle = "<?php echo $sub_title; ?>"
                                    data-disc = "<?php echo $discription; ?>"
                                    data-img = "<?php echo $histroy_img; ?>"
                                    data-date = "<?php echo $historical_date; ?>"
                                    type="button" class="btn w3-pale-red btn-sm open_modal_button"
                                        id="saveBtn" onclick="SavePost(this)"><i class="fa-sharp fa-solid fa-bookmark my-icon"></i></button>
                                <?php }} ?>
                                <!-- code for share and save button end -->
                            </div>
                        </div>
                    </div>

                </div><br>
                <?php

                }
                }
                }
                else{
                echo "<h2 class='mt-5 text-center'>No Data Available!</h2>";
                // display if no any data available in the database table
                }
                }

                ?>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function() {
    /* function for mute and unmute sound icon start */
    $('.sound-btn').click(function() {
        if ($(this).hasClass('active')) {
            // return; // Ignore click if the icon is already active
            tr.Pause();
            $(this).removeClass('active');
            $(this).html('🔇'); // Change to the desired icon when inactive
        } else {
            // Deactivate previously active icons
            $('.sound-btn.active').removeClass('active').html('🔇');

            // Activate the clicked icon
            $(this).addClass('active').html('🔊');
        }
    });
    /* function for mute and unmute sound icon end */
});
/* ==== Code for share_save by ankit 01/09/2023 start === */
function SavePost(button) {
    let feedId = $(button).attr('data-id'); // Store post id value
    let feedType = $(button).attr('data-type'); // Store serach box value
    let userId = "<?php echo $_SESSION['loginuseremail']; ?>";

    $.ajax({
        url: "ajax/save_feed.php",
        type: "POST",
        data: {
            feedId: feedId,
            feedType: feedType,
            userId: userId
        },
        success: function(response) {
            button.remove();
        }
    })
}
/* ==== Code for share_save by ankit 01/09/2023 end === */
</script>
<?php
 }
 mysqli_close($conn); // connection close
 ?>