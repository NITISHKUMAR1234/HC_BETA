<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file
$currDate = $_POST['currDate']; // store url date,month and year
$month = $_POST['month']; // store url month
$date = $_POST['date']; // store url date

if (isset($_SESSION['loginuseremail'])) {
    $user_email = $_SESSION['loginuseremail'];
}
?>
<?php
if (isset($_POST["limit"]) && isset($_POST["start"])) {
    $limit = $_POST["limit"];
    $start = $_POST["start"];

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            * {
                font-family: 'Bebas Neue', sans-serif;
                font-family: 'Poppins', sans-serif;
                font-family: 'Rubik', sans-serif;
            }

            .card1 {

                border: 0.1px solid rgb(147 145 145 / 34%);
                border-radius: 10px;
                max-width: 700px;
                min-width: 150px;
            }

            .container {
                margin-top: 1%;
                margin-bottom: 1%;
                width: fit-content;
                /* height: 400px; */
            }

            .card-content {
                color: gray;
            }

            .card-head,
            .card-time {
                color: rgb(71, 70, 70);
            }

            #img {
                /* border: 2px solid rgb(182, 51, 51); */
                width: 100%;
                max-width: max-content;
                height: 100%;
                align-items: center;
            }

            .image {
                border-top-right-radius: 10px;
                border-top-left-radius: 10px;
                text-align: center;
                width: 100%;
                height: 250px;
                margin-bottom: 22px;
                background-color: grey;
            }

            .speaker-btn {
                background-color: transparent;
                text-align: end;
                top: 0;
                bottom: 20px;
                transform: translate(0, -39px);
                margin-right: 10px;
                cursor: pointer;
            }

            #speaker-btn {
                background-color: #fff;
                border: 0;
                border-radius: 50%;
                cursor: pointer;
                width: 30px;
                height: 30px;

                /* margin-right: 10px; */
            }

            #icon {
                /* background-color: #fff; */
                width: 20px;
                height: 20px;
                border-radius: 50%;
                margin-left: -4px;
            }

            hr {
                padding: 0;
                margin: 0;
            }

            #heading {
                position: relative;
                top: -13px;
            }

            .card-bottom {
                display: flex;
                justify-content: space-between;
            }

            .share-btn {
                background-color: transparent;
                margin-top: 5px;
                margin-right: 10px;
                display: flex;
            }

            #share-btn {
                margin: 0 8px;
                background-color: #fff;
                cursor: pointer;
                /* filter:drop-shadow(5px 5px 5px); */
            }

            #save-btn {
                margin: 0 8px;
                background-color: #fff;
                cursor: pointer;
                /* filter:drop-shadow(5px 5px 5px); */
            }

            #date {
                width: 40%;
                margin-bottom: 10px;
            }

            @media (max-width: 500px) {
                .card1 {
                    width: 98%;
                }
            }

            @media (max-width:500px) {
                .container {
                    width: 100%;
                    margin: 10px auto;
                }
            }
        </style>
    </head>

    <section>
        <!-- <div class="container"> -->
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 card-container">

                <?php

                /* if event is selected */
                if (isset($_POST['event_id'])) {
                    $event_id = $_POST['event_id'];
                    $event_type = ($event_id == "All") ? "" : "AND fd_event_type = '$event_id'"; // store the value of event. If the event id is "All" then it will fetch all the data from the selected date otherwise it it will fetch the data according to the selected event type
                    $start = 0; // Limit start from 0

                    /* Query for check historical date is available on database table or not? */
                    $verifydateQuery = @mysqli_query($conn, "SELECT DISTINCT tb_calender_date,fd_title, fd_id FROM tb_history WHERE
                MONTH(`tb_calender_date`)
                = '$month' AND DAY(`tb_calender_date`) = '$currDate' AND fd_status = 0 AND fd_delete = 0 $event_type ORDER BY fd_id DESC LIMIT $start,$limit");

                    if (@mysqli_num_rows($verifydateQuery) > 0) {
                        while ($fetchDate = mysqli_fetch_assoc($verifydateQuery)) {
                            $date = $fetchDate['tb_calender_date']; // store date of the historical data
                            ($fetchDate['fd_title']) ? $title = $fetchDate['fd_title'] : $title = ""; // store title of the feed


                            /* code for sub_title,image and discription */
                            $allDataQuery = @mysqli_query($conn, "SELECT fd_id,fd_img,fd_sub_title,fd_discription FROM tb_history WHERE
                fd_title = '$title'
                AND tb_calender_date = '$date' AND fd_status = 0 AND fd_delete = 0 ORDER BY
                `tb_calender_date`");

                            $fetchData = @mysqli_fetch_assoc($allDataQuery);

                            ($fetchData['fd_img']) ? $img = $fetchData['fd_img'] : $img = "";
                            ($fetchData['fd_sub_title']) ? $sub_title = $fetchData['fd_sub_title'] : $sub_title = "";
                            ($fetchData['fd_discription']) ? $discription = $fetchData['fd_discription'] : $discription = "";

                            $feedId = $fetchData['fd_id']; // Store feed id

                        }
                    } else {
                        echo "<h2 class='mt-5 text-center'>No Data Available!</h2>"; // show if data is not available
                    }
                } else {

                    /* Query for check historical date is available on database table or not? */

                    $dateQuery = @mysqli_query($conn, "SELECT tb_calender_date,fd_title,fd_sub_title,fd_discription FROM tb_history WHERE MONTH(`tb_calender_date`)
                = '$month' AND DAY(`tb_calender_date`) = '$currDate' AND fd_status = 0 AND fd_delete = 0");

                    if (@mysqli_num_rows($dateQuery) > 0) {

                        /* query for fetch distinct title,and date */
                        $titleQuery = "SELECT DISTINCT tb_calender_date, fd_title, fd_id FROM `tb_history` WHERE fd_status = 0 AND
                fd_delete = 0 AND MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$currDate' ORDER BY fd_id DESC LIMIT $start,
                $limit";

                        $runTitleQuery = @mysqli_query($conn, $titleQuery); // execute query
                        if (@mysqli_num_rows($runTitleQuery) > 0) {
                            while ($fetchTitle = @mysqli_fetch_assoc($runTitleQuery)) {
                                ($fetchTitle['tb_calender_date']) ? $historical_date = $fetchTitle['tb_calender_date'] : $historical_date = ""; // fetch historical date

                                ($fetchTitle['fd_title']) ? $title = $fetchTitle['fd_title'] : $title = ""; // store title

                                /* query for fetch image,sub_title and discription */

                                $imgQuery = @mysqli_query($conn, "SELECT fd_id,fd_img,fd_event_type,fd_sub_title,fd_discription FROM tb_history WHERE fd_title = '$title'
                AND tb_calender_date = '$historical_date' AND fd_status = 0 AND fd_delete = 0 ORDER BY `tb_calender_date`");

                                $ImgRow = @mysqli_fetch_assoc($imgQuery);

                                ($ImgRow['fd_discription']) ? $discription = $ImgRow['fd_discription'] : $discription = ""; // store discription
                                $img = $ImgRow['fd_img']; // store image name
                                ($ImgRow['fd_sub_title']) ? $sub_title = $ImgRow['fd_sub_title'] : $sub_title = ""; // store sub title
                                $feedId = $ImgRow['fd_id']; // Store feed id
                                $feedType = $ImgRow['fd_event_type']; // Store feed event type

                ?>

                                <!-- ====================================== -->
                                <div class="container">

                                    <div class="card1">

                                        <div class="image">
                                            <img id="img" src="admin/data/image/<?php echo $ImgRow['fd_img']; ?>" class="card-img-top" alt="loading">

                                            <div class="speaker-btn">
                                                <button id="speaker-btn" class="speak" onclick="tr.Read('<?php echo $feedId; ?>')">
                                                    <!-- <img id="icon" src="https://img.icons8.com/ios-filled/50/medium-volume--v1.png" alt="medium-volume--v1" /> -->
                                                    🔈

                                                </button>
                                            </div>
                                        </div>

                                        <div class="card-head m-0" id="heading">
                                            <h5 class="mx-3 my-0 font-weight-bold">
                                                <?php echo $title; ?>
                                                <?php print_r($title);?>
                                            </h5>
                                            <h6 class="mx-3 my-0 font-italic">
                                                <?php echo $sub_title; ?>
                                                <?php print_r($sub_title);?>
                                            </h6>
                                            <!-- <p class="text-center m-0">Hello Programmer</p> -->
                                        </div>
                                        <hr>

                                        <div class="card-content m-2">
                                            <p class="card-text" id="<?php echo $feedId; ?>">
                                                <?php echo $discription; ?>
                                            </p>
                                        </div>

                                        <hr>
                                        <div class="card-bottom">

                                            <div class="card-time m-2" id="date">
                                                <p class="mb-0">Date: <span id="time"><?php echo date("m.d.Y", strtotime($date)); ?></span></p>
                                            </div>
                                            <?php if (isset($_SESSION['loginuseremail'])) { ?>

                                                <div class="share-btn">
                                                    <div class="open_modal_button" id="open_modal_button" data-id="<?php echo $feedId; ?>" data-title="<?php echo $title; ?>" data-type="<?php echo $feedType; ?>" data-subTitle="<?php echo $sub_title; ?>" data-disc="<?php echo $discription; ?>" data-img="<?php echo $histroy_img; ?>" data-date="<?php echo $historical_date; ?>">

                                                        <svg id="share-btn" class="open_modal_button mt-1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50">
                                                            <path d="M 40 0 C 34.535156 0 30.078125 4.398438 30 9.84375 C 30 9.894531 30 9.949219 30 10 C 30 13.6875 31.996094 16.890625 34.96875 18.625 C 36.445313 19.488281 38.167969 20 40 20 C 45.515625 20 50 15.515625 50 10 C 50 4.484375 45.515625 0 40 0 Z M 28.0625 10.84375 L 17.84375 15.96875 C 20.222656 18.03125 21.785156 21 21.96875 24.34375 L 32.3125 19.15625 C 29.898438 17.128906 28.300781 14.175781 28.0625 10.84375 Z M 10 15 C 4.484375 15 0 19.484375 0 25 C 0 30.515625 4.484375 35 10 35 C 12.050781 35 13.941406 34.375 15.53125 33.3125 C 18.214844 31.519531 20 28.472656 20 25 C 20 21.410156 18.089844 18.265625 15.25 16.5 C 13.71875 15.546875 11.929688 15 10 15 Z M 21.96875 25.65625 C 21.785156 28.996094 20.25 31.996094 17.875 34.0625 L 28.0625 39.15625 C 28.300781 35.824219 29.871094 32.875 32.28125 30.84375 Z M 40 30 C 37.9375 30 36.03125 30.644531 34.4375 31.71875 C 31.769531 33.515625 30 36.542969 30 40 C 30 40.015625 30 40.015625 30 40.03125 C 29.957031 40.035156 29.917969 40.058594 29.875 40.0625 L 30 40.125 C 30.066406 45.582031 34.527344 50 40 50 C 45.515625 50 50 45.515625 50 40 C 50 34.484375 45.515625 30 40 30 Z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <?php if (!IsPostSaved($feedId, $feedType, $user_email)) { ?>
                                                        <div class="open_modal_button" id="saveBtn" onclick="SavePost(this)" data-id="<?php echo $feedId; ?>" data-title="<?php echo $title; ?>" data-type="<?php echo $feedType; ?>" data-subTitle="<?php echo $sub_title; ?>" data-disc="<?php echo $discription; ?>" data-img="<?php echo $histroy_img; ?>" data-date="<?php echo $historical_date; ?>">

                                                            <svg id="save-btn" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M4 17.9808V9.70753C4 6.07416 4 4.25748 5.17157 3.12874C6.34315 2 8.22876 2 12 2C15.7712 2 17.6569 2 18.8284 3.12874C20 4.25748 20 6.07416 20 9.70753V17.9808C20 20.2867 20 21.4396 19.2272 21.8523C17.7305 22.6514 14.9232 19.9852 13.59 19.1824C12.8168 18.7168 12.4302 18.484 12 18.484C11.5698 18.484 11.1832 18.7168 10.41 19.1824C9.0768 19.9852 6.26947 22.6514 4.77285 21.8523C4 21.4396 4 20.2867 4 17.9808Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                <?php }
                                                } ?>

                                                </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- <br> -->

                <?php   }
                        }
                    } else {
                        echo "<h2 class='mt-5 text-center'>No Data Available!</h2>"; // display if no any data available in the database table
                    }
                }

                ?>
            </div>
            <!-- </div> -->
        </div>
    </section>
    <script>
        // $(document).ready(function() {
        //     /* function for mute and unmute sound icon start */
        //     $('.speak').click(function() {
        //         if ($(this).hasClass('active')) {
        //             // return; // Ignore click if the icon is already active
        //             tr.Pause();
        //             $(this).removeClass('active');
        //             $(this).html('🔈');
        //             // Change to the desired icon when inactive
        //         } else {
        //             // Deactivate previously active icons
        //             $('.speak.active').removeClass('active').html('🔈');

        //             // Activate the clicked icon
        //             $(this).addClass('active').html('🔊');
        //         }
        //     });
        //     /* function for mute and unmute sound icon end */
        // });
        $(document).ready(function() {
            let isReading = false;
            let synth = window.speechSynthesis;
            let utterance = new SpeechSynthesisUtterance('<?php echo $feedId; ?>');

            $('#speaker-btn').click(function() {
                if (!isReading) {
                    // Start reading
                    synth.speak(utterance);
                    $('#icon').text('🔇');
                } else {
                    // Stop reading
                    synth.cancel();
                    $('#icon').text('🔊');
                }
                isReading = !isReading;
            });
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
                    $('#save-btn').attr('fill', 'black');
                }
            })
        }
        /* ==== Code for share_save by ankit 01/09/2023 end === */
    </script>
<?php
}
mysqli_close($conn); // connection close
?>