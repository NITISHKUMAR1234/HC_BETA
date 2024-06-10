<?php
session_start(); // session is start
/* if user is not logged in, redirect to login page */
if($_SESSION['loginuseremail'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/user_login.php`;
    </script>';
    die();
}

require_once('include/header.php'); // include header file 
require_once('include/config.php'); // include connection file
require_once('include/navbar.php'); // inlude top navbar 

$user_email = $_SESSION['loginuseremail']; // session variable for user email
?>
<link rel="stylesheet" href="css/user_profile.css">
<style>
/* code for toggle current password start */
#togglePassword {
    position: absolute;
    top: 50%;
    right: 30px;
    transform: translateY(-50%);
    z-index: 1;
    cursor: pointer;
}

/* code for toggle current password end */

/* code for toggle new password start */
#toggleNewPassword {
    position: absolute;
    top: 50%;
    right: 30px;
    transform: translateY(-50%);
    z-index: 1;
    cursor: pointer;
}

/* code for toggle new password end */

/* code for toggle renew password start */
#toggleRenewPassword {
    position: absolute;
    top: 50%;
    right: 30px;
    transform: translateY(-50%);
    z-index: 1;
    cursor: pointer;
}

/* code for toggle renew password end */
.error {
    color: #dc3545;
    font-size: 13px;
}
</style>
<?php
if(isset($_POST["changePass"])){

    $cpass = $_POST["currentPassword"];
    $ccpass = base64_encode($cpass);
    $npass = $_POST["newpassword"];
    $pass = base64_encode($npass);
    
     $squery = "SELECT * FROM tb_users WHERE fd_email = '$user_email' AND fd_status  = 0 AND fd_delete = 0";     
         $run = mysqli_query($conn,$squery) or die ("query failed" .mysqli_error($conn));
                                  if(mysqli_num_rows($run) > 0){
                                    while($match = mysqli_fetch_assoc($run)){                                      
                                    $password = $match["fd_password"];                                   
                                    
    
        if($password == $ccpass){
    
          $uquery = "UPDATE tb_users SET fd_password = '$pass' WHERE fd_email = '$user_email'";
            $execute = mysqli_query($conn,$uquery) or die("connection failed" .mysqli_error($conn));
               if($execute){                   
                echo '<script>swal({
                    title: "Passowrd Updated!",
                    text: "Good job!",
                    icon: "success"
                });
                   </script>';
                }
                   
        } 
        else if($password != $ccpass){
            echo " <script>Swal.fire(
                'Incorrect entered password!!',
                'Please try again!',
                'warning'
              )
               </script>";
        }
        else{
            echo " <script>Swal.fire(
                'Something went wrong!!',
                'Please tery again!',
                'error'
              )
               </script>";
        }
    }    
}
    }
    
$sql = mysqli_query($conn,"SELECT * FROM tb_users WHERE fd_email = '$user_email'");
if(mysqli_num_rows($sql) > 0){
    $fetchRow = mysqli_fetch_assoc($sql);  
    $country_id = $fetchRow['fd_country'];
    $state_id = $fetchRow['fd_state'];
    $city_id = $fetchRow['fd_city'];
    
    /* code for fetch country name by its id */
    $cQuery = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_countries WHERE fdID = '$country_id'");
    $cname = mysqli_fetch_assoc($cQuery);
    $country_name = $cname['fd_Name'];

        /* code for fetch state name by its id */
        $sQuery = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_states WHERE fdID = '$state_id'");
        $sname = mysqli_fetch_assoc($sQuery);
        $state_name = $sname['fd_Name'];

         /* code for fetch city name by its id */
         $cityQuery = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_cities WHERE fdID = '$city_id'");
         $cityRow = mysqli_fetch_assoc($cityQuery);
         $city_name = $cityRow['fd_Name'];

    ($fetchRow['fd_fname']) ? $firstname = $fetchRow['fd_fname'] : $firstname = "";   
    ($fetchRow['fd_lname']) ? $lastname = $fetchRow['fd_lname'] : $lastname = "";     
    ($fetchRow['fd_Dob']) ? $dob = $fetchRow['fd_Dob'] : $dob = "";
    ($fetchRow['fd_contactno']) ? $contact = $fetchRow['fd_contactno'] : $contact = "";    
    ($fetchRow['fd_channel']) ? $channel = $fetchRow['fd_channel'] : $channel = "";
    ($fetchRow['fd_address']) ? $address = $fetchRow['fd_address'] : $address = "";
    ($fetchRow['fd_country']) ? $country = $fetchRow['fd_country'] : $country = "";
    ($fetchRow['fd_state']) ? $state = $fetchRow['fd_state'] : $state = "";    
    ($fetchRow['fd_city']) ? $city = $fetchRow['fd_city'] : $city = "";
    ($fetchRow['fd_zip']) ? $zip = $fetchRow['fd_zip'] : $zip = "";
    $user_img = $fetchRow['fd_profile_image'];
}

/* code for fetch user image name from the database table */

$sql = mysqli_query($conn,"SELECT fd_profile_image FROM tb_users WHERE fd_email = '$user_email'");
if(mysqli_num_rows($sql) > 0){
    $fetch = mysqli_fetch_assoc($sql);
    $user_img_name = $fetch['fd_profile_image'];
}
else{
    $user_img_name = "";
}
?>
<div class="container-fluid">
    <div class="card pagetitle">
        <div class="card-body top-title">
            <h2><i class="fa fa-user" aria-hidden="true"></i> Profile</h2>
        </div>
    </div><!-- End Page Title -->
</div>
<!-- code for show loader start -->
<div class="loader-container" id="loader">
    <div class="loader"></div>
</div>
<!-- code for show loader end -->
<div class="container-fluid">
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4 mb-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <form action="" id="profile-area">
                            <?php
                            if (file_exists("Image/" . $user_img) && $fetchRow['fd_profile_image']){
                            echo'<img src="Image/'.$user_img.'" alt="Profile">';
                            }else{
                            echo '<img src="https://onespect.com/user/profilepic/man.png" alt="Profile"
                                class="rounded-circle">';
                            }
                        ?>
                        </form>
                        <h2><?php echo $firstname . " " . $lastname; ?></h2>
                        <h3><?php echo $user_email; ?></h3>
                        <div class="social-links mt-2">
                            <a href="#" class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a href="#" class="facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                            <a href="#" class="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                            <a href="#" class="linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#profile-overview"
                                    role="tab" aria-controls="home" aria-selected="true">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile-edit" role="tab"
                                    aria-controls="profile" aria-selected="false">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="password-tab" data-toggle="tab" role="tab"
                                    aria-controls="password" href="#profile-change-password"
                                    aria-selected="false">Change Password</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <!--<form action="" method="POST" id="overviewForm" name="overviewForm">-->
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <form action="" method="POST" name="overview-area" id="overview-area">
                                    <h5 class="card-title">Profile Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">First Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $firstname; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Last Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $lastname; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email Address</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $user_email; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Date Of Birth</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $dob; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Contact Number</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $contact; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Channel Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $channel; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Street Address</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $address; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Country Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $country_name; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">State Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $state_name; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">City Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $city_name; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Zip Code</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $zip; ?></div>
                                    </div>
                                </form>
                            </div>
                            <!--</form>-->
                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                        Image</label>
                                    <div class="col-md-8 col-lg-9">
                                        <form id="previewForm" name="previewForm" method="POST"
                                            enctype="multipart/form-data">
                                            <?php
                                        if (file_exists("Image/" . $user_img) && $fetchRow['fd_profile_image']){
                                          echo'<img src="Image/'.$user_img.'" alt="Profile">';
                                        }else{
                                        echo '<img src="https://onespect.com/user/profilepic/man.png" alt="Profile">';
                                        }
                                        ?>
                                        </form>
                                        <div class="pt-2">
                                            <form id="imageForm" name="imageForm" method="POST"
                                                enctype="multipart/form-data">
                                                <button type="button" onclick="showInputBox();"
                                                    class="btn w3-indigo btn-sm" title="Upload new profile image"><i
                                                        class="fa fa-upload" aria-hidden="true"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm" id="deleteBtn"
                                                    name="deleteBtn" title="Remove my profile image"><i
                                                        class="fa fa-trash" aria-hidden="true"></i></button>
                                                <div class="input-group mt-3" id="imageInput" style="display: none;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Upload</span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="image"
                                                            name="image" accept="image/*">
                                                        <label class="custom-file-label" for="inputGroupFile01">Choose
                                                            file</label>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" id="profileForm" name="profileForm">
                                    <div class="row mb-2">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">First
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="fname" name="fname"
                                                value="<?php echo $firstname; ?>" placeholder="Enter Your First Name">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="lname" class="col-md-4 col-lg-3 col-form-label">Last
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text" class="form-control" id="lname" name="lname"
                                                value="<?php echo $lastname; ?>" placeholder="Enter Your Last Name">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="dob" class="col-md-4 col-lg-3 col-form-label">DOB</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="date" class="form-control" id="dob" name="dob"
                                                value="<?php echo $dob; ?>" placeholder="Your Date Of Birth">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone
                                            Number</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="number" class="form-control" id="phone" name="phone"
                                                value="<?php echo $contact; ?>" placeholder="Enter Your Phone Number">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Channel
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="channel" type="text" class="form-control" id="channel"
                                                name="channel" value="<?php echo $channel; ?>"
                                                placeholder="Enter Your Channel Name">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="address" class="col-md-4 col-lg-3 col-form-label">Street
                                            Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="address" type="text" class="form-control" id="address"
                                                value="<?php echo $address; ?>" placeholder="Enter Your Address">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="country_name" class="col-md-4 col-lg-3 col-form-label">Country
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select name="country_name" class="form-control" id="country_name"
                                                value="<?php echo $country; ?>">
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                    $query = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_countries");
                                                    while($row = mysqli_fetch_assoc($query)){
                                                    if($row['fdID']==$country)
                                                    {
                                                    $slected = 'selected';
                                                    }
                                                    else
                                                    {
                                                    $slected = '';
                                                    }
                                                ?>
                                                <option value="<?php echo $row['fdID'];?>" <?=$slected?>>
                                                    <?php echo $row['fd_Name']; ?>
                                                </option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="sname" class="col-md-4 col-lg-3 col-form-label">State
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select name="sname" class="form-control" id="sname"
                                                value="<?php echo $state; ?>">
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                $query = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_states WHERE
                                                fdID='$state'");
                                                while($row = mysqli_fetch_assoc($query)){
                                                if($row['fdID']==$state)
                                                {
                                                $selected = 'selected';
                                                }
                                                else
                                                {
                                                $selected = '';
                                                }
                                                ?>
                                                <option value="<?php echo $row['fdID'];?>" <?=$selected?>>
                                                    <?php echo $row['fd_Name']; ?>
                                                    <?php
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="country_name" class="col-md-4 col-lg-3 col-form-label">City
                                            Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select name="city_name" class="form-control" id="city_name"
                                                value="<?php echo $city; ?>">
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                $query = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_cities WHERE
                                                fdID='$city'");
                                                while($row = mysqli_fetch_assoc($query)){
                                                if($row['fdID']==$city)
                                                {
                                                $selected = 'selected';
                                                }
                                                else
                                                {
                                                $selected = '';
                                                }
                                                ?>
                                                <option value="<?php echo $row['fdID'];?>" <?=$selected?>>
                                                    <?php echo $row['fd_Name']; ?>
                                                    <?php
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="zip" class="col-md-4 col-lg-3 col-form-label">Zip Code</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="zip" type="text" class="form-control" id="zip"
                                                value="<?php echo $zip; ?>" placeholder="Enter Your Zip Code">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Job" class="col-md-4 col-lg-3 col-form-label"></label>
                                        <div class="col-md-8 col-lg-9">
                                            <button type="button" name="saveBtn" id="saveBtn" class="btn w3-indigo">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </form><!-- End Profile Edit Form -->
                            </div>
                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form method="POST" name="changePassForm" id="changePassForm"
                                    onsubmit="return validatePassForm();">

                                    <div class="row mb-2">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="currentPassword" type="password" class="form-control"
                                                id="currentPassword" placeholder="Enter Your Current Password">
                                            <div class="error" id="cpassErr"></div>
                                            <span id="togglePassword" class="fa fa-eye-slash"
                                                onclick="togglePassword()"></span>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword" type="password" class="form-control"
                                                id="newPassword" placeholder="Enter Your New Password">
                                            <div class="error" id="npassErr"></div>
                                            <span id="toggleNewPassword" class="fa fa-eye-slash"
                                                onclick="toggleNewPassword()"></span>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter
                                            New
                                            Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control"
                                                id="renewPassword" placeholder="Re-enter Your Password">
                                            <div class="error" id="rpassErr"></div>
                                            <span id="toggleRenewPassword" class="fa fa-eye-slash"
                                                onclick="toggleRenewPassword()"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label"></label>
                                        <div class="col-md-8 col-lg-9">
                                            <button type="submit" name="changePass" id="changePass"
                                                class="btn w3-indigo">Change Password</button>
                                        </div>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
/* function for refresh content start */

function refreshContent() {
    $("#overview-area").load(location.href + " #overview-area");
}
/* function for refresh content end */

/* calling a function in every 5 sec */
setInterval(function() {
    refreshContent();
}, 5000);
/* function for show image input box start */

function showInputBox() {
    input = document.getElementById('imageInput');
    if (input.style.display == "none") {
        input.style.display = "flex";
    } else {
        input.style.display = "none";
    }
}
/* function for show image input box end */

/* function for toggle current password start */

function togglePassword() {
    var password = document.getElementById("currentPassword");
    var toggleIcon = document.getElementById("togglePassword");

    if (password.type === "password") {
        password.type = "text";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    } else {
        password.type = "password";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    }
}

/* function toggle current password end */

/* function for toggle new password start */

function toggleNewPassword() {
    var password = document.getElementById("newPassword");
    var toggleIcon = document.getElementById("toggleNewPassword");

    if (password.type === "password") {
        password.type = "text";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    } else {
        password.type = "password";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    }
}

/* function toggle new password end */

/* function for toggle new password start */

function toggleRenewPassword() {
    var password = document.getElementById("renewPassword");
    var toggleIcon = document.getElementById("toggleRenewPassword");

    if (password.type === "password") {
        password.type = "text";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    } else {
        password.type = "password";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    }
}

/* function toggle new password end */

/* function for print error message start */

function printError(checkId, showMsg) {
    document.getElementById(checkId).innerHTML = showMsg;
}

/* function for print error message end */

/* function for validate change password form start */

function validatePassForm() {
    let cpass = document.changePassForm.currentPassword.value.replace(/\s/g, "");
    let npass = document.changePassForm.newpassword.value.replace(/\s/g, "");
    let rpass = document.changePassForm.renewpassword.value.replace(/\s/g, "");

    let cpassErr = npassErr = rpassErr = true;

    /* validate current password */
    if (cpass == "") {
        printError("cpassErr", "* Current password is required");
        document.getElementById("currentPassword").style.border = "1px solid red";
    } else {
        printError("cpassErr", "");
        document.getElementById("currentPassword").style.border = "1px solid #28a745";
        cpassErr = false;
    }

    /* validate new password */
    if (npass == "") {
        printError("npassErr", "* New password is required");
        document.getElementById("newPassword").style.border = "1px solid red";
    } else if (npass.length < 7) {
        printError("npassErr", "New Passowrd must be greater than 6 digit");
        document.getElementById("newPassword").style.border = "1px solid red";
    } else {
        let regex = /^(?=.*[a-zA-Z0-9])[a-zA-Z0-9][a-zA-Z0-9@]{5,}$/;
        if (regex.test(npass) === false) {
            printError("npassErr",
                "Enter valid new password (Password must be start from alphabets or digits and does include special characters like /,.,{,}, etc..)"
            );
            document.getElementById("newPassword").style.border = "1px solid red";
        } else {
            printError("npassErr", "");
            document.getElementById("newPassword").style.border = "1px solid #28a745";
            npassErr = false;
        }
    }

    /* validate confirm password */
    if (rpass !== npass) {
        printError("rpassErr", "Password does not matched");
        document.getElementById("renewPassword").style.border = "1px solid red";
    } else {
        printError("rpassErr", "");
        document.getElementById("renewPassword").style.border = "1px solid #28a745";
        rpassErr = false;
    }
    if ((cpassErr || npassErr || rpassErr) == true) {
        return false;
    }
}
$(document).ready(function() {
    $('#saveBtn').on('click', function() {
        let form = document.getElementById("profileForm");
        let formData = new FormData(form);
        $('#saveBtn').html("Please wait....");
        $("#saveBtn").attr("disabled", true);
        $('#loader').show();
        $.ajax({
            url: "ajax/update_profile.php",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(response) {
                $('#loader').hide();
                if (response == 1) {
                    $('#saveBtn').html("Save Changes");
                    $("#saveBtn").attr("disabled", false);
                    swal({
                        title: "Updated Successfully!",
                        text: "Good job!",
                        icon: "success"
                    })
                } else {
                    swal({
                        title: "Something went wrong!",
                        text: "Please Try Again",
                        icon: "error",
                        dangerMode: true,
                    })
                }
            }
        });
    });
    $('#image').on('change', function() {
        var form = $('#imageForm')[0];
        var imageData = new FormData(form);
        $('#loader').show();
        $.ajax({
            url: "ajax/update_profile.php",
            type: "POST",
            data: imageData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#loader').hide();
                if (response == 1) {
                    swal({
                        title: "File Size Too Large!",
                        text: "File must be less than 2MB!",
                        icon: "warning"
                    });
                } else if (response == 2) {
                    $('#imageInput').hide();
                    $("#previewForm").load(location.href + " #previewForm");
                    $("#profile-area").load(location.href + " #profile-area");
                    swal({
                        title: "Uploaded Successfully!",
                        text: "Good job!",
                        icon: "success"
                    });
                } else {
                    swal({
                        title: "Invalid file extension!",
                        text: "File must be .jpg,.png,.webp,.jpeg!",
                        icon: "warning"
                    });
                }
            }
        });
    });

    /* code for delete user image start */
    $('#deleteBtn').on('click', function() {
        let user_email = '<?php echo $user_email; ?>';
        $('#loader').show(); // show loader

        $.ajax({
            url: "ajax/delete_user_img.php",
            type: "POST",
            data: {
                user_email: user_email
            },
            cache: false,
            success: function(data) {
                $('#loader').hide();
                if (data == 1) {
                    $("#previewForm").load(location.href + " #previewForm");
                    $("#profile-area").load(location.href + " #profile-area");
                    swal({
                        title: "Updated Successfully!",
                        text: "Good job!",
                        icon: "success"
                    });
                } else {
                    swal({
                        title: "Something went wrong!",
                        text: "Please Try Again",
                        icon: "error",
                        dangerMode: true,
                    });
                }
            }
        });
    });
});
</script>
<!-- external js file for address filteration -->

<script src="JS/address_filteration.js"></script>
</body>

</html>