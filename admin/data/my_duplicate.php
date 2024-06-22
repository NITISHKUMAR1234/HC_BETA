<?php
// session_start(); // session is start
require_once("../../include/header.php"); // include header file
require_once('../../include/config.php'); // include connection file

/* if user is does not logged in then, redirect to login page */
if($_SESSION['admin_id'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/admin/index.php`;
    </script>';
    die();
}

date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d',strtotime("today")); // find current date

$admin_id = $_SESSION['admin_id']; // session variable for admin id

/* code for fetch total numbers of entries */

/* if admin id equal to 1 or 2 */
if($admin_id == 1 || $admin_id == 2){
    /* query for count total numbers of entries */
$query = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `id` FROM `tb_history` WHERE 
fd_status = 0 AND fd_delete = 0");
}else{
/* query for count total numbers of entries */
$query = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `id` FROM `tb_history` WHERE `fd_admin_id` = $admin_id AND
fd_status = 0 AND fd_delete = 0");
}

if(mysqli_num_rows($query) > 0){
while($countRow = mysqli_fetch_assoc($query)){
$total_entry = $countRow['id'];
}
}else{
$total_entry = "No Data Available!";
}


/* code for count total numbers of entries per day */
/* if admin id equal to 1 or 2 */
if($admin_id == 1 || $admin_id == 2){
    /* query for count today entry */
$sql = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `admin_id` FROM `tb_history` WHERE 
date(fd_uploaded_on) = '$today' AND fd_status = 0 AND fd_delete = 0");
}else{
/* query for count today entry */
$sql = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `admin_id` FROM `tb_history` WHERE `fd_admin_id` = $admin_id AND
date(fd_uploaded_on) = '$today' AND fd_status = 0 AND fd_delete = 0");
}

if(mysqli_num_rows($sql) > 0){
while($countData = mysqli_fetch_assoc($sql)){
$today_entry = $countData['admin_id'];
}
}else{
$today_entry = "No Data Available!";
}
/* if the value of total entry is 0 then the value of today_entry is 00 */
if($today_entry == "0"){
    $today_entry = "00";
}
/* count duplicate entries */

$add_duplicate = 0; // used for sum duplicate entry
$counter = 0; // used as a counter variable

/* if admin id equal to 1 or 2 */
if($admin_id == 1 || $admin_id == 2){
    /* query for count duplicate entry */
$duplicateQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_status = 0 AND fd_delete = 0 GROUP BY
fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1");
}else{
/* query for count duplicate entry */
$duplicateQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) as total_duplicate FROM tb_history WHERE fd_admin_id =
$admin_id AND fd_status = 0 AND fd_delete = 0 GROUP BY
fd_title,fd_event_type,tb_calender_date HAVING total_duplicate > 1");
}

if(mysqli_num_rows($duplicateQuery) > 0){
while($countDuplicateRow = mysqli_fetch_assoc($duplicateQuery)){
$duplicate_row = $countDuplicateRow['total_duplicate'];
$add_duplicate += $duplicate_row ;
$counter += 1;
}
}

$total_duplicate = $add_duplicate - $counter; // find total duplicate entry

/* if the value of total duplicate entry is 0 then the value of total_duplicate is 00 */
if($total_duplicate == "0"){
    $total_duplicate = "00";
}

if(isset($_POST['updateBtn'])){
    $date = $_POST['updateDate'];
    $event = $_POST['updateEvent'];
    $filename = $_FILES['feedImg']['name']; 
    $file_size = $_FILES['feedImg']['size'];
    $tempname = $_FILES['feedImg']['tmp_name']; 
    $title = addslashes($_POST['updateTitle']); 
    $sub_title = addslashes($_POST['updatesub_title']); 
    $update_country_id = $_POST['update_country_id'];
    $disc = addslashes($_POST['updateDisc']); 
    $update = $_POST['updateBtn'];

    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    $newName = md5(microtime()).'.'.$ext;

    /* query for fetch admin id so that we can find which admin is added a particular data */
    $verify_admin_query = mysqli_query($conn,"SELECT fd_admin_id FROM tb_history WHERE fd_id = $update");
    $admin_id_row = mysqli_fetch_assoc($verify_admin_query);
    $data_added_by = $admin_id_row['fd_admin_id']; // store admin id

    /* admin can update only their own data  */
    if($admin_id == $data_added_by){
        /* Run if iamge is not uploaded */
        if($filename !=""){
            if($file_size > 2097152){
                echo '<script>
                swal({
                    title: "Image size too large!",
                    text: "Image must be less than 2MB!",
                    icon: "warning",
                });
                </script>';
            }
            else if(move_uploaded_file($tempname,"image/$newName")){
            $updateQuery = mysqli_query($conn,"UPDATE `tb_history` SET `fd_admin_id` = '$admin_id',`tb_calender_date`='$date',`fd_event_type`='$event',`fd_img`='$newName',`fd_title`='$title',`fd_sub_title`='$sub_title',   `fd_country_ID` = '$update_country_id',`fd_discription`='$disc' WHERE fd_id=$update");

            if($updateQuery){
                echo '<script>
                swal({
                    title: "Updated successfully!",
                    text: "Now you can add more!",
                    icon: "success",
                });
                </script>';
            }else{
                echo '<script>
                swal({
                    title: "Something went wrong!",
                    text: "try again!",
                    icon: "error",
                    dangerMode: true
                });
                </script>';
            }
        }
    }else{
        $updateQuery = mysqli_query($conn,"UPDATE `tb_history` SET `fd_admin_id` = '$admin_id',`tb_calender_date`='$date',`fd_event_type`='$event',`fd_title`='$title',`fd_sub_title`='$sub_title',`fd_country_ID` = '$update_country_id',`fd_discription`='$disc' WHERE fd_id=$update");

        if($updateQuery){
            echo '<script>
                swal({
                    title: "Updated successfully!",
                    text: "Now you can add more!",
                    icon: "success",
                });
                </script>';
        }else{
            echo '<script>
            swal({
                title: "Something went wrong!",
                text: "try again!",
                icon: "error",
                dangerMode: true
            });
            </script>';
        }
    }
}else{
    echo '<script>
    swal({
        title: "Permission Denied!",
        text: "You don\'t have permission to modify this data!",
        icon: "error",
        dangerMode: true
    });
    </script>';
}
}
?>
<!-- external css file -->
<link rel="stylesheet" href="css/my_duplicate.css" />
<!-- code for page title start -->
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="page-title col-lg-12 mb-3">
                <h4><i class="fa fa-repeat" aria-hidden="true"></i> My Duplicate</h4>
                <span class="link-items">
                    <a class="link" href="index.php">Dashboard / </a>
                    <a class="link" href="index.php?myduplicate">myduplicate</a>
                </span>
            </div>
        </div>
    </div>
</section>
<!-- code for page title end -->
<!-- code for show loader start -->
<div class="loader-container" id="loader">
    <div class="loader"></div>
</div>
<!-- code for show loader end -->
<div class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-12 overview-card">
            <div class="row">
                <div class="col-lg-3 card">
                    <div class="card-body">
                        <span class="float-left card-title">
                            <h6 class="ont-weight-bold">Total: </h6>
                            <p class="font-italic text-muted">Total Numbers Of Entries</p>
                        </span>
                        <span class="float-right card-sub-title mt-0">
                            <h6><?php echo $total_entry; ?></h6>
                        </span>
                    </div>
                </div>
                <div class="col-lg-3 card">
                    <div class="card-body">
                        <span class="float-left card-title">
                            <h6 class="ont-weight-bold">Today's: </h6>
                            <p class="font-italic text-muted">Today's Total Numbers Of Entries</p>
                        </span>
                        <span class="float-right card-sub-title mt-0">
                            <h6><?php echo $today_entry; ?></h6>
                        </span>
                    </div>
                </div>
                <div class="col-lg-3 card">
                    <div class="card-body">
                        <span class="float-left card-title">
                            <h6 class="ont-weight-bold">Duplicate: </h6>
                            <p class="font-italic text-muted">Total Numbers Of Duplicate Entries</p>
                        </span>
                        <span class="float-right card-sub-title mt-0">
                            <h6><?php echo $total_duplicate; ?></h6>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- code for duplicate data table start -->
    <div class="row justify-content-center align-items-center mt-4">
        <!-- code for edit form start -->

        <div id="feedModal" class="w3-modal w3-animate-zoom" style="z-index: 105; display: none;">
            <div class="w3-modal-content" style="width:40%;border-radius:5px;max-width: 530px;">
                <!-- code for update form start -->
                <form method="POST" enctype='multipart/form-data' name="updateForm" id="updateForm">
                    <div class="model-content">
                        <div class="col-lg-12 open-model">
                            <div class="model-header dismiss-btn pt-3 pr-2">
                                <button type="button" class="close" onclick="$('#feedModal').hide()">
                                    <span class="close-icon" aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-title pt-4 text-center">
                                <h3 class="font-weight-bold" id="feedModalLabel">
                                    Update feed
                                </h3>
                            </div>

                            <div class="modal-body py-4 row" id="feedData">
                                <div class="form-group col-md-6">
                                    <label for="id">Date</label>
                                    <input class="form-control" name="updateDate" id="updateDate"
                                        placeholder="Choose date" type="date">
                                    <div class="error" id="updateDateErr"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="id">Event</label>
                                    <select class="form-control form-control-sm" name="updateEvent" id="updateEvent">
                                        <option value="" selected disabled>Select</option>
                                        <?php
                                                /* code for fetch event start */
                                                $query = mysqli_query($conn,"SELECT fd_ID,fd_event_name FROM tb_events WHERE
                                                fd_status = 0 AND fd_delete = 0");
                                                if(mysqli_num_rows($query) > 0){
                                                while($row = mysqli_fetch_assoc($query)){
                                                echo '<option value="'.$row['fd_ID'].'">'.$row["fd_event_name"].'</option>';
                                                }
                                                }
                                                 /* code for fetch event end */
                                            ?>
                                    </select>
                                    <div class="error" id="updateEventErr"></div>
                                </div>
                                <div class="form-group col-md-12" id="imgId">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="id">Feed image</label>
                                    <input class="form-control" name="feedImg" id="feedimg" placeholder="Choose image"
                                        accept="image/*" type="file">
                                    <div class="error" id="updateImgErr"></div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="title">Title</label>
                                    <input class="form-control" type="text" name="updateTitle" id="updateTitle"
                                        placeholder="Enter the title">
                                    <div class="error" id="updateTitleErr"></div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="category_name">Sub title</label>
                                    <input class="form-control" type="text" name="updatesub_title" id="updatesub_title"
                                        placeholder="Enter the sub title">
                                    <div class="error" id="updateSubTitleErr"></div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="id">Related Country</label>
                                    <select class="form-control form-control-sm" name="update_country_id"
                                        id="update_country_id" required>
                                        <option value="" selected disabled>Select</option>
                                        <?php
                                            /* code for fetch countries name start */
                                            $countryQuery = mysqli_query($conn,"SELECT fd_Name,fdID FROM tb_countries");
                                            if(mysqli_num_rows($countryQuery) > 0){
                                            while($countryRow = mysqli_fetch_assoc($countryQuery)){
                                            echo '<option value="'.$countryRow['fdID'].'">'.$countryRow["fd_Name"].'</option>';
                                            }
                                            }
                                             /* code for fetch countries name end */
                                            ?>
                                    </select>
                                    <div class="error" id="updateCountryErr"></div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="discription">Discription</label>
                                    <textarea class="form-control discription" type="text" name="updateDisc"
                                        id="updateDisc" placeholder="write something...."></textarea>
                                    <div class="error" id="updateDiscErr"></div>
                                </div>
                                <div class="action mt-2 ml-3" id="action">
                                    <button class="btn w3-indigo" id="updateBtn" name="updateBtn" type="submit" value=""
                                        onclick="return validateUpdateForm();">Update</button>
                                    <button type="reset" class="btn btn-danger btn-md" name="cancelBtn" id="cancelBtn"
                                        onclick="$('#feedModal').hide()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- code for update form end -->
            </div>
        </div>
        <!-- code for edit form end -->

        <div class="card-table col-lg-12">
            <div class="row">
                <div class="card col-lg-12">
                    <div class="table-card-title ml-3">
                        <h5><i class="fa fa-table" aria-hidden="true"></i> Duplicate Entries Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table duplicate-data-table" id="duplicate-data-table">
                            <thead>
                                <tr>
                                    <th>Sl.No.</th>
                                    <th>Admin Email Id</th>
                                    <th>Historical Date</th>
                                    <th>Title</th>
                                    <th>Sub Title</th>
                                    <th>Discription</th>
                                    <th>Uploaded on</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody class="duplicate_data" id="duplicate_data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- code for duplicate data table end -->
</div>
<?php
mysqli_close($conn); // connection close
?>
<script>
/* code for show model onclick start */
const showModel = () => {
    $('#feedModal').show();
    let model = $('.editBtn').html();
}
/* code for show model onclick end */

$(document).ready(function() {
    document.getElementById('my_duplicate').style.background =
        'gray'; // active my duplicate entries option in the sidebar

    /* function for show all data on page load start */
    function loadDuplicateData() {
        $('#loader').show(); // show loader
        // $('#loader').hide(); // hide loader
        $.ajax({
            url: "ajax/get_total_duplicate_data.php",
            type: "POST",
            cache: false,
            success: function(response) {
                $('#loader').hide(); // hide loader
                $('#duplicate_data').html(response);
            }
        })
    }
    loadDuplicateData(); // calling a function
    /* function for show all data on page load end */

    /* code for edit button start */
    $(document).on("click", ".editBtn", function() {
        let data_id = $(this).val();
        let data_date = $(this).attr("data-date");
        let data_event = $(this).attr("data-event");
        let data_img = $(this).attr("data-img");
        let data_title = $(this).attr("data-title");
        let data_subtitle = $(this).attr("data-subTitle");
        let data_country = $(this).attr("data-cid");
        let data_disc = $(this).attr("data-disc");

        $("#updateDate").val(data_date);
        $("#updateEvent").val(data_event);
        $('#imgId').html('<img class="mt-2 mb-2" src="image/' + data_img + '" alt=""/>').trigger(
            "create");
        $("#updateTitle").val(data_title);
        $("#updatesub_title").val(data_subtitle);
        $("#update_country_id").val(data_country);
        $("#updateDisc").val(data_disc);

        $("#updateBtn").val(data_id);
    });
    /* code for edit button end */

    /* code for delete data start */

    $(document).on("click", ".deleteBtn", function() {
        let feed_id = $(this).attr("data-id");
        let image_id = $(this).attr("data-img");   

        swal({
                title: "Are you sure you want to delete this data?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "ajax/delete_duplicate_data.php",
                        type: "POST",
                        data: {
                            feed_id: feed_id,
                            image_id: image_id
                        },
                        success: function(data) {
                            if (data == 1) {
                                swal("Poof! Your Education data has been deleted!", {
                                    icon: "success",
                                }).then(okay => {
                                    if (okay) {
                                        loadDuplicateData();                                       
                                    }
                                });
                            } else if (data == 0) {
                                swal({
                                    title: "Permission Denied!",
                                    text: "You don\'t have permission to delete this data!",
                                    icon: "error",
                                    dangerMode: true
                                });
                            } else {
                                swal({
                                    title: "Something went wrong!",
                                    text: "try again!",
                                    icon: "error",
                                });
                            }
                        }
                    });
                } else {
                    swal("Your Historical Data is Safe!");
                }
            });
    });
   
    /* code for delete data end */

});
</script>
</body>

</html>