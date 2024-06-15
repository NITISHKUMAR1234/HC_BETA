<?php
@session_start(); // session is start
require_once("../../include/header.php"); // include header file
require_once('../../include/config.php'); // include connection file

/* if user is not logged in, redirect to login page */
if($_SESSION['admin_id'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/admin/index.php`;
    </script>';
    die();
}

$admin_email = $_SESSION['email']; // store admin email id
$admin_id  = $_SESSION['admin_id']; // store admin id

?>
<style>
/* styling of card images */
#imgId img {
    min-width: 100%;
    width: 200px;
    height: 200px;
}
</style>
<!-- external css file for feed -->
<link rel="stylesheet" href="css/upload_histroy_data.css">
<!-- code for feed start -->
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="summary">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="float-left"><b>All Historical Feeds</b></h5>
                        <h5 class="float-right">
                            <b>(Total: </b>
                            <span id="total_entry">0</span>,
                            <b>Today: </b>
                            <span id="today_entry">0</span>,
                            <b>Duplicate Entry Today: </b>
                            <span id="duplicate_entry">0</span>
                            <b>)</b>
                        </h5>
                    </div>
                    <div class="card-body" id="allData">

                    </div>
                </div>
            </div>
            <div class="col-lg-5 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h5><b>Upload Historical Data's</b></h5>
                    </div>
                    <form method="POST" name="feedForm" id="feedForm" action="" enctype="multipart/form-data">
                        <div class="row card-body">
                            <div class="col-md-6 form-group">
                                <label for="date">Date</label>
                                <input class="form-control form-control-sm" name="date" id="date" type="date"
                                    placeholder="select date">
                                <div class="error" id="dateErr"></div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="event">Event</label>
                                <select class="form-control form-control-sm" name="event" id="event">
                                    <option value="" selected disabled>Select</option>
                                    <?php
                                    /* code for fetch event start */
                                    $query = mysqli_query($conn,"SELECT fd_ID,fd_event_name FROM tb_events WHERE fd_status = 0 AND fd_delete = 0");
                                    if(mysqli_num_rows($query) > 0){
                                        while($row = mysqli_fetch_assoc($query)){
                                            echo '<option value="'.$row['fd_ID'].'">'.$row["fd_event_name"].'</option>';
                                        }
                                    }
                                     /* code for fetch event end */
                                    ?>
                                </select>
                                <div class="error" id="eventErr"></div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="image">Choose image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                    <label class="input-group-text" for="image">Upload</label>
                                </div>
                                <div class="error" id="imgErr"></div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="event">Title</label>
                                <input class="form-control form-control-sm" name="title" id="title" type="text"
                                    placeholder="Enter the title" oninput = "removeSpecialCharacters(this);" required>
                                <div class="error" id="titleErr"></div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="event">Sub-title</label>
                                <input class="form-control form-control-sm" name="sub_title" id="sub_title" type="text"
                                    placeholder="Enter the sub title" oninput = "removeSpecialCharacters(this);" required>
                                <div class="error" id="subTitleErr"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="id">Related Country</label>
                                <select class="form-control form-control-sm" name="country_name" id="country_name"
                                    required>
                                    <option value="" selected disabled>Select</option>
                                    <?php
                                    /* code for fetch country name start */
                                    $query = mysqli_query($conn,"SELECT fd_Name,fdID FROM tb_countries");
                                    if(mysqli_num_rows($query) > 0){
                                        while($row = mysqli_fetch_assoc($query)){
                                            echo '<option value="'.$row['fdID'].'">'.$row["fd_Name"].'</option>';
                                        }
                                    }
                                     /* code for fetch country name end */
                                    ?>
                                </select>
                                <div class="error" id="countryErr"></div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="discription">Discription</label>
                                <textarea class="discription form-control form-control-sm" name="discription"
                                    id="discription" type="text" oninput = "removeSpecialCharacters(this);" placeholder="Write something...." minlength="200"></textarea>
                                <div class="error" id="discErr"></div>
                            </div>
                            <div class="w-100 px-4" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                <button type="submit" name="submitBtn" id="submitBtn" class="btn w3-indigo"onclick="return validateForm();">Upload Now</button>
                                <button type="reset" class="btn w3-red">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- code for show loader start -->
    <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>
    <!-- code for show loader end -->

    <!-- update form container start -->
    <div class="container">
        <div class="row">
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
                                        <select class="form-control form-control-sm" name="updateEvent"
                                            id="updateEvent">
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
                                        <input class="form-control" name="feedImg" id="feedimg"
                                            placeholder="Choose image" accept="image/*" type="file">
                                        <div class="error" id="updateImgErr"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="title">Title</label>
                                        <input class="form-control" type="text" name="updateTitle" id="updateTitle"
                                            placeholder="Enter the title" oninput = "removeSpecialCharacters(this);">
                                        <div class="error" id="updateTitleErr"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="category_name">Sub title</label>
                                        <input class="form-control" type="text" name="updatesub_title"
                                            id="updatesub_title" placeholder="Enter the sub title" oninput = "removeSpecialCharacters(this);">
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
                                            id="updateDisc" placeholder="write something...." oninput = "removeSpecialCharacters(this);"></textarea>
                                        <div class="error" id="updateDiscErr"></div>
                                    </div>
                                    <div class="action mt-2 ml-3" id="action">
                                        <button class="btn w3-indigo" id="updateBtn" name="updateBtn" type="button"
                                            value="" onclick="return validateUpdateForm();">Update</button>
                                        <button type="reset" class="btn btn-danger btn-md" name="cancelBtn"
                                            id="cancelBtn" onclick="$('#feedModal').hide()">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                      <!-- code for update form end -->
                </div>
            </div>
        </div>
    </div>
    <!-- update form container end -->
</section>

<!-- code for feed end -->
<script>
/* ---code for show model start--- */

const showModel = () => {
    $('#feedModal').show();
    let model = $('.open_model').html();
}

/* ---code for show model end--- */

/* ----code for validate feed form start--- */

function printError(checkId, showMsg) {
    document.getElementById(checkId).innerHTML = showMsg;
}
var isValidate = false;

function validateForm() {

    //----varibale declaration----//
    var date = document.feedForm.date.value.replace(/\s/g, "");;
    var event = document.feedForm.event.value.replace(/\s/g, "");;
    var img = document.feedForm.image.value.replace(/\s/g, "");;
    var title = document.feedForm.title.value.replace(/\s/g, "");;
    var sub_title = document.feedForm.sub_title.value.replace(/\s/g, "");;
    var disc = document.feedForm.discription.value.replace(/\s/g, "");
    var country = document.feedForm.country_name.value.replace(/\s/g, "");

    //----create error variable----//
    var dateErr = eventErr = imgErr = titleErr = subTitleErr = countryErr = discErr = true;

    /* ---validate date field---- */
    if (date == "") {
        printError("dateErr", "* Date is required");
        document.getElementById("date").style.border = "1px solid red";
    } else {
        // Extract the year from the input value
        var year = date.slice(6);

        // Check if the year has more than 4 digits
        if (year.length > 4) {
            printError("dateErr", "* Year cannot be greater than 4 digits");
            document.getElementById("date").style.border = "1px solid red";
            isValidate = false;
        } else {
            printError("dateErr", "");
            dateErr = false;
            document.getElementById("date").style.border = "1px solid #28a745";
        }
    }

    /* ---validate event field---- */
    if (event == "") {
        printError("eventErr", "* Event is required");
        document.getElementById("event").style.border = "1px solid red";
        // isValidate = false;
    } else {
        printError("eventErr", "");
        eventErr = false;
        document.getElementById("event").style.border = "1px solid #28a745";
    }

    /* ---validate image field---- */
    if (img == "") {
        printError("imgErr", "* Image is required");
        document.getElementById("image").style.border = "1px solid red";
    } else {
        printError("imgErr", "");
        imgErr = false;
        document.getElementById("image").style.border = "1px solid #28a745";
    }

    /* ---validate title field---- */
    if (title == "") {
        printError("titleErr", "* Title is required");
        document.getElementById("title").style.border = "1px solid red";
        document.getElementById("title").innerText = '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>';
    } else if (title.length < 3) {
        printError("titleErr", "* Title must be more than 3 characters");
        document.getElementById("title").style.border = "1px solid red";
        // isValidate = false;
    } else {
        var regex = /^[A-Z][a-zA-Z0-9\s\-\.,()':]+[\?\!\.]?$/;
        if (regex.test(title) === false) {
            printError("titleErr", "Enter valid name format(starts with capital letter)");
            document.getElementById("title").style.border = "1px solid red";
        } else {
            printError("titleErr", "");
            titleErr = false;
            document.getElementById("title").style.border = "1px solid #28a745";
        }
    }
    /* ---validate sub title field---- */
    if (sub_title == "") {
        printError("subTitleErr", "* Sub title is required");
        document.getElementById("sub_title").style.border = "1px solid red";
    } else if (title.length < 3) {
        printError("subTitleErr", "* Sub title must be more than 3 character");
        document.getElementById("sub_title").style.border = "1px solid red";
    } else {
        var regex = /^[A-Z][a-zA-Z0-9\s\-\.,()':]+[\?\!\.]?$/;
        if (regex.test(sub_title) === false) {
            printError("subTitleErr", "Enter valid name format(starts with capital letter)");
            document.getElementById("sub_title").style.border = "1px solid red";
        } else {
            printError("subTitleErr", "");
            subTitleErr = false;
            document.getElementById("sub_title").style.border = "1px solid #28a745";
        }
    }

    /* validate country id start */
    if (country == "") {
        printError("countryErr", "* Country is required");
        document.getElementById("country_name").style.border = "1px solid red";
    } else {
        printError("countryErr", "");
        countryErr = false;
        document.getElementById("country_name").style.border = "1px solid #28a745";
    }
    /* validate country id end */
    /* ---validate discription field---- */
    if (disc == "") {
        printError("discErr", "* Discription is required");
        document.getElementById("discription").style.border = "1px solid red";
    } else if (disc.length < 200) {
        printError("discErr", "* Discription must be more than 200 character");
        document.getElementById("discription").style.border = "1px solid red";
    } else {
        printError("discErr", "");
        discErr = false;
        document.getElementById("discription").style.border = "1px solid #28a745";
    }
    if ((dateErr || eventErr || imgErr || titleErr || subTitleErr || countryErr || discErr) == true) {
        return false;
        isValidate = false;
    } else {
        isValidate = true;
    }
}

/* ----code for validate feed form end--- */

/* ----code for validate update feed form start--- */

var formIsValidate = false;

function errorMsg(checkName, showErr) {
    document.getElementById(checkName).innerHTML = showErr;
}


function validateUpdateForm() {
    let date = document.updateForm.updateDate.value.replace(/\s/g, "");;
    let event = document.updateForm.updateEvent.value.replace(/\s/g, "");;
    let title = document.updateForm.updateTitle.value.replace(/\s/g, "");;
    let sub_title = document.updateForm.updatesub_title.value.replace(/\s/g, "");;
    let country_id = document.updateForm.update_country_id.value.replace(/\s/g, "");
    let disc = document.updateForm.updateDisc.value.replace(/\s/g, "");


    let updateDateErr = updateEventErr = updateTitleErr = updateSubTitleErr = updateCountryErr = updateDiscErr = true;

    /* ---validate date field---- */
    if (date == "") {
        errorMsg("updateDateErr", "* Date is required");

    } else {
        errorMsg("updateDateErr", "");
        updateDateErr = false;

    }
    /* ---validate event field---- */
    if (event == "") {
        errorMsg("updateEventErr", "* Event is required");
        document.getElementById("updateEvent").style.border = "1px solid red";

    } else {
        errorMsg("updateEventErr", "");
        eventErr = false;
        document.getElementById("updateEvent").style.border = "1px solid #28a745";

    }
    /* ---validate image field---- */

    /* ---validate title field---- */
    if (title == "") {
        errorMsg("updateTitleErr", "* Title is required");

    } else if (title.length < 3) {
        errorMsg("updateTitleErr", "* Title must be more than 3m character");

    } else {
        var regex = /^[A-Z][a-zA-Z0-9\s\-\.,()':]+[\?\!\.]?$/;
        if (regex.test(title) === false) {
            errorMsg("updateTitleErr", "Enter valid name format(starts with capital letter)");

        } else {
            errorMsg("updateTitleErr", "");
            updateTitleErr = false;

        }
    }
    /* ---validate sub title field---- */
    if (sub_title == "") {
        errorMsg("updateSubTitleErr", "* Sub title is required");

    } else if (title.length < 3) {
        errorMsg("updateSubTitleErr", "* Sub title must be more than 3 character");

    } else {
        var regex = /^[A-Z][a-zA-Z0-9\s\-\.,()':]+[\?\!\.]?$/;
        if (regex.test(sub_title) === false) {
            errorMsg("updateSubTitleErr", "Enter valid name format(starts with capital letter)");

        } else {
            errorMsg("updateSubTitleErr", "");
            updateSubTitleErr = false;

        }
    }
    /* ---validate discription field---- */

    /* validate country id start */
    if (country_id == "") {
        errorMsg("updateCountryErr", "* Country is required");
        document.getElementById("update_country_id").style.border = "1px solid red";
    } else {
        errorMsg("updateCountryErr", "");
        updateCountryErr = false;
        document.getElementById("update_country_id").style.border = "1px solid #28a745";
    }
    /* validate country id end */


    if (disc == "") {
        errorMsg("updateDiscErr", "* Discription is required");

    } else if (disc.length < 200) {
        errorMsg("updateDiscErr", "* Discription must be more than 200 character");

    } else {
        errorMsg("updateDiscErr", "");
        updateDiscErr = false;

    }
    if ((updateDateErr || updateEventErr || updateTitleErr || updateSubTitleErr || updateCountryErr || updateDiscErr) ==
        true) {
        return false;
        formIsValidate = false;
    } else {
        formIsValidate = true;
    }
};

/* ----code for validate update feed form end--- */

$(document).ready(function() {
    document.getElementById('upload_histroy').style.background = 'gray'; // active sidebar menu item

    /* code for fetch data on page load start */
    function loadFeedData() {
        let admin_id = '<?php echo $admin_id; ?>';
        $('#loader').show();
        $.ajax({
            url: "ajax/get-histroy-data.php",
            method: "POST",
            data: {
                admin_id: admin_id
            },
            cache: false,
            success: function(data) {
                $('#loader').hide();
                let feedData = '';
                data = JSON.parse(data);
                if (data == '') {
                    $("#allData").html("<h4 class='text-center'>No data found!</h4>");
                } else {
                    $.each(data, function(index, value) {
                        feedData +=
                            '<section><div class="container"><div class="row justify-content-center align-items-center"><div class="col-lg-10 card-container"><br> <div class="card"><div class="card-body row"><div class="col-lg-12"><span style="font-size: 12px;">' +
                            value.tb_calender_date +
                            '<i class="fa fa-globe ml-1" aria-hidden="true"></i></span><span class="ml-2">' +
                            value.fd_email +
                            '</span><span class="editIcon"><button class="btn btn-warning editBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-country = "' + value.fd_country_ID +
                            '" data-disc="' + value.fd_discription +
                            '" onclick="showModel()"><i class="fa fa-pencil" aria-hidden="true"></i></button></span><span class="deleteIcon mr-2"><button type="button" id="traceBtn" class="btn btn-danger deleteBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-disc="' + value.fd_discription +
                            '"><i class="fa fa-trash" aria-hidden="true"></i></button></span></div><div class="col-lg-12 headText"><span class="title">' +
                            value.fd_title +
                            '</span><br><span class="sub-title">' + value
                            .fd_sub_title +
                            '</span></div><div class="col-lg-12 img-area w-center"><div class="main-img"><img src="image/' +
                            value.fd_img +
                            '" alt="image"></div></div><div class="col-lg-12 disc"><div class="text-area"><p>' +
                            value.fd_discription +
                            '</p></div></div></div></div></div></div></div></section>';

                        $('#allData').html(feedData);
                    });
                }
            }
        });
    }
    loadFeedData(); // calling a function

    /* code for fetch data on page load end */

    /* code for fetch data on date change start */
    $('#date').on('focusout', function() {
        let date = $(this).val(); // get the value of date input box
        let title = $('#title').val(); // get the value of title input box
        let disc = $('#discription').val(); // get the value of discription input box

        // Split the dateValue into an array using "-" as the separator
        let dateArray = date.split("-");

        let day = dateArray[2]; // Extract the day
        let month = dateArray[1]; // Extract the month      

        day = (date == '') ? "0" : day;
        month = (date == '') ? "0" : month;
        title = (title == "") ? "0" : title;
        disc = (disc == "") ? "0" : disc;

        $('#loader').show();
        $.ajax({
            url: "ajax/get-histroy-data.php",
            method: "POST",
            data: {
                day: day,
                month: month,
                title: title,
                disc: disc
            },
            cache: false,
            success: function(data) {
                $('#loader').hide();
                let feedData = '';
                data = JSON.parse(data);
                if (data == '') {
                    $("#allData").html("<h4 class='text-center'>No data found!</h4>");
                } else {
                    $.each(data, function(index, value) {
                        feedData +=
                            '<section><div class="container"><div class="row justify-content-center align-items-center"><div class="col-lg-10 card-container"><br> <div class="card"><div class="card-body row"><div class="col-lg-12"><span style="font-size: 12px;">' +
                            value.tb_calender_date +
                            '<i class="fa fa-globe ml-1" aria-hidden="true"></i></span><span class="ml-2">' +
                            value.fd_email +
                            '</span><span class="editIcon"><button class="btn btn-warning editBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-country = "' + value.fd_country_ID +
                            '" data-disc="' + value.fd_discription +
                            '" onclick="showModel()"><i class="fa fa-pencil" aria-hidden="true"></i></button></span><span class="deleteIcon mr-2"><button type="button" id="traceBtn" class="btn btn-danger deleteBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-disc="' + value.fd_discription +
                            '"><i class="fa fa-trash" aria-hidden="true"></i></button></span></div><div class="col-lg-12 headText"><span class="title">' +
                            value.fd_title +
                            '</span><br><span class="sub-title">' + value
                            .fd_sub_title +
                            '</span></div><div class="col-lg-12 img-area w-center"><div class="main-img"><img src="image/' +
                            value.fd_img +
                            '" alt="image"></div></div><div class="col-lg-12 disc"><div class="text-area"><p>' +
                            value.fd_discription +
                            '</p></div></div></div></div></div></div></div></section>';

                        $('#allData').html(feedData);
                    });
                }
            }
        });
    });

    /* code for fetch data on date change end */

    /* code for fetch data according to the title start */

    $('#title').on('focusout', function() {
        let title = $(this).val(); // store the value of title        
        let date = $('#date').val(); // get the value of date input box
        let disc = $('#discription').val(); // get the value of discription input box

        // Split the dateValue into an array using "-" as the separator
        let dateArray = date.split("-");

        let day = dateArray[2]; // Extract the day
        let month = dateArray[1]; // Extract the month      

        day = (date == '') ? "0" : day;
        month = (date == '') ? "0" : month;
        title = (title == "") ? "0" : title;
        disc = (disc == "") ? "0" : disc;

        $('#loader').show(); // show loader
        $.ajax({
            url: "ajax/get-histroy-data.php",
            method: "POST",
            data: {
                day: day,
                month: month,
                title: title,
                disc: disc
            },
            cache: false,
            success: function(data) {
                $('#loader').hide();
                let feedData = '';
                data = JSON.parse(data);
                if (data == '') {
                    $("#allData").html("<h4 class='text-center'>No data found!</h4>");
                } else {
                    $.each(data, function(index, value) {
                        feedData +=
                            '<section><div class="container"><div class="row justify-content-center align-items-center"><div class="col-lg-10 card-container"><br> <div class="card"><div class="card-body row"><div class="col-lg-12"><span style="font-size: 12px;">' +
                            value.tb_calender_date +
                            '<i class="fa fa-globe ml-1" aria-hidden="true"></i></span><span class="ml-2">' +
                            value.fd_email +
                            '</span><span class="editIcon"><button class="btn btn-warning editBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-country = "' + value.fd_country_ID +
                            '" data-disc="' + value.fd_discription +
                            '" onclick="showModel()"><i class="fa fa-pencil" aria-hidden="true"></i></button></span><span class="deleteIcon mr-2"><button type="button" id="traceBtn" class="btn btn-danger deleteBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-disc="' + value.fd_discription +
                            '"><i class="fa fa-trash" aria-hidden="true"></i></button></span></div><div class="col-lg-12 headText"><span class="title">' +
                            value.fd_title +
                            '</span><br><span class="sub-title">' + value
                            .fd_sub_title +
                            '</span></div><div class="col-lg-12 img-area w-center"><div class="main-img"><img src="image/' +
                            value.fd_img +
                            '" alt="image"></div></div><div class="col-lg-12 disc"><div class="text-area"><p>' +
                            value.fd_discription +
                            '</p></div></div></div></div></div></div></div></section>';

                        $('#allData').html(feedData);
                    });
                }
            }
        });
    })

    /* code for fetch data according to the title end  */

    /* code for fetch data according to the discription start */

    $('#discription').on('focusout', function() {
        let title = $('#title').val(); // store the value of title        
        let date = $('#date').val(); // get the value of date input box
        let disc = $(this).val(); // get the value of discription input box

        // Split the dateValue into an array using "-" as the separator
        let dateArray = date.split("-");

        let day = dateArray[2]; // Extract the day
        let month = dateArray[1]; // Extract the month      

        day = (date == '') ? "0" : day;
        month = (date == '') ? "0" : month;
        title = (title == "") ? "0" : title;
        disc = (disc == "") ? "0" : disc;

        $('#loader').show(); // show loader
        $.ajax({
            url: "ajax/get-histroy-data.php",
            method: "POST",
            data: {
                day: day,
                month: month,
                title: title,
                disc: disc
            },
            cache: false,
            success: function(data) {
                $('#loader').hide();
                let feedData = '';
                data = JSON.parse(data);
                if (data == '') {
                    $("#allData").html("<h4 class='text-center'>No data found!</h4>");
                } else {
                    $.each(data, function(index, value) {
                        feedData +=
                            '<section><div class="container"><div class="row justify-content-center align-items-center"><div class="col-lg-10 card-container"><br> <div class="card"><div class="card-body row"><div class="col-lg-12"><span style="font-size: 12px;">' +
                            value.tb_calender_date +
                            '<i class="fa fa-globe ml-1" aria-hidden="true"></i></span><span class="ml-2">' +
                            value.fd_email +
                            '</span><span class="editIcon"><button class="btn btn-warning editBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-country = "' + value.fd_country_ID +
                            '" data-disc="' + value.fd_discription +
                            '" onclick="showModel()"><i class="fa fa-pencil" aria-hidden="true"></i></button></span><span class="deleteIcon mr-2"><button type="button" id="traceBtn" class="btn btn-danger deleteBtn" data-id="' +
                            value.fd_id + '" data-date="' + value
                            .tb_calender_date + '" data-event="' + value
                            .fd_event_type + '" data-img="' + value.fd_img +
                            '" data-title="' + value.fd_title +
                            '" data-subtitle="' + value.fd_sub_title +
                            '" data-disc="' + value.fd_discription +
                            '"><i class="fa fa-trash" aria-hidden="true"></i></button></span></div><div class="col-lg-12 headText"><span class="title">' +
                            value.fd_title +
                            '</span><br><span class="sub-title">' + value
                            .fd_sub_title +
                            '</span></div><div class="col-lg-12 img-area w-center"><div class="main-img"><img src="image/' +
                            value.fd_img +
                            '" alt="image"></div></div><div class="col-lg-12 disc"><div class="text-area"><p>' +
                            value.fd_discription +
                            '</p></div></div></div></div></div></div></div></section>';

                        $('#allData').html(feedData);
                    });
                }
            }
        });
    })

    /* code for fetch data according to the discription end  */

    /* code for fetch total numbers of entry start */

    function totalEntries() {
        let admin_id = '<?php echo $admin_id; ?>';
        $('#loader').show(); // show loader
        $.ajax({
            url: "ajax/get_total_histroy_entries.php",
            method: "POST",
            data: {
                admin_id: admin_id
            },
            cache: false,
            success: function(response) {
                console.log(response);
                $('#loader').hide(); // hide loader
                var response = JSON.parse(response);
                $('#total_entry').html(response.total_entry);
                $('#today_entry').html(response.today_entry);
                $('#duplicate_entry').html(response.duplicate_entry);
            }
        });
    }
    totalEntries(); // calling a function

    /* code for fetch total numbers of entry end */

    /* ---code for insert data start--- */

    $('#submitBtn').on('click', function(e) {
        e.preventDefault();
        var form = $('#feedForm')[0];
        var formData = new FormData(form);
        formData.append('submitBtn', '1');
        if (isValidate == true) {
            $('#loader').show();
            $('#submitBtn').html("Please wait....");
            $("#submitBtn").attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: "ajax/insert_histroy_data.php",
                data: formData,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function(data) {
                    $('#loader').hide();
                    $('#submitBtn').html("Upload Now");
                    $("#submitBtn").attr("disabled", false);
                    if(data == 0){
                        swal({
                            title: "Already exist!",
                            text: "You are trying to insert duplicate data!",
                            icon: "warning",
                        });
                    }
                    else if (data == 1) {
                        swal({
                            title: "Image size too large!",
                            text: "Image must be less than 2MB!",
                            icon: "warning",
                        });
                    } else if (data == 2) {
                        swal({
                            title: "Added successfully!",
                            text: "Now you can add more!",
                            icon: "success",
                        });
                        $('#feedForm')[0].reset();
                        loadFeedData();
                        totalEntries();
                    } else {
                        swal({
                            title: "Something went wrong!",
                            text: "try again!",
                            icon: "error",
                            dangerMode: true
                        });
                    }
                }
            });
        }
    });

    /* ---code for insert data end--- */

    /* code for edit button start */
    $(document).on("click", ".editBtn", function() {
        var data_id = $(this).attr("data-id");
        let data_date = $(this).attr("data-date");
        let data_event = $(this).attr("data-event");
        let data_img = $(this).attr("data-img");
        let data_title = $(this).attr("data-title");
        let data_subtitle = $(this).attr("data-subtitle");
        let data_country = $(this).attr("data-country");
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
                        url: "ajax/delete_histroy_data.php",
                        type: "POST",
                        data: {
                            feed_id: feed_id
                        },
                        success: function(data) {
                            if (data == 1) {
                                swal("Poof! Your Education data has been deleted!", {
                                    icon: "success",
                                }).then(okay => {
                                    if (okay) {
                                        loadFeedData();
                                        totalEntries();
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
    // });
    // });

    /* code for delete data end */

    /* ----code for update data start--- */

    $('#updateBtn').on('click', function() {
        let updateBtn = $(this).val();
        let updateForm = $('#updateForm')[0];
        let formData = new FormData(updateForm);
        formData.append('updateBtn', updateBtn);

        // e.preventDefault();
        // if (formIsValidate == true) {
        $('#loader').show();
        $('#updateBtn').html('Please wait....');
        $("#updateBtn").attr("disabled", true);
        $.ajax({
            type: 'POST',
            url: "ajax/insert_histroy_data.php",
            data: formData,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                if (data == 0) {
                    swal({
                        title: "Image size too large!",
                        text: "Image must be less than 2MB!",
                        icon: "warning",
                    });
                } else if (data == 1) {
                    $('#loader').hide();
                    $('#updateBtn').html('Update');
                    $("#updateBtn").attr("disabled", false);
                    swal({
                        title: "Updated successfully!",
                        text: "You clicked the button!",
                        icon: "success",
                    }).then(okay => {
                        $('#feedModal').hide()
                        loadFeedData();
                        totalEntries();
                    });
                } else if (data == 3) {
                    $('#loader').hide();
                    $('#updateBtn').html('Update');
                    $("#updateBtn").attr("disabled", false);

                    swal({
                        title: "Permission Denied!",
                        text: "You don\'t have permission to modify this data!",
                        icon: "error",
                        dangerMode: true
                    });
                    $('#feedModal').hide()
                } else {
                    $('#loader').hide();
                    $('#updateBtn').html('Update');
                    $("#updateBtn").attr("disabled", false);
                    swal({
                        title: "Something went wrong!",
                        text: "try again!",
                        icon: "error",
                        dangerMode: true
                    });
                }
            }
        });
        // }
    });
    /* ----code for update data end--- */
});
</script>
<?php
mysqli_close($conn); // connection close
?>
</body>

</html>