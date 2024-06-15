<?php
@session_start(); //session is start
require_once('../../include/header.php'); // include header file
require_once('../../include/config.php'); // include connection file

/* if user is not logged in, redirect to login page */
if($_SESSION['admin_id'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/admin/index.php`;
    </script>';
    die();
}
$admin_id = $_SESSION['admin_id'];
?>
<style>
body {
    font-family: 'Montserrat', sans-serif;
    background: #f6f9ff;
}

.card {
    border: none;
    border-radius: 5px;
    box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
}

.card-title {
    text-align: center;
    padding-top: 40px;
}

.card-title h4 {
    color: darkblue;
    font-weight: 600;
}

.card-title p {
    color: darkblue;
}

#errorText {
    color: #dc3545;
}
</style>
    <?php
      /* code for insert calender data start */
     if(isset($_POST['uploadBtn'])){
        $date = $_POST['date'];
        $title = $_POST['title'];
        $sub_title = $_POST['sub_title'];
        $filename = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $tempname = $_FILES['image']['tmp_name'];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newName = md5(microtime()).'.'.$ext; 

        if($file_size > 2097152){
            echo '<script>
            swal({
                title: "Image size too large!",
                text: "Image must be less than 2MB!",
                icon: "warning",
            });
            </script>';
        }
        else if(move_uploaded_file($tempname,"calender_image/$newName")){
            $insertQuery = mysqli_query($conn,"INSERT INTO tb_calender (fd_admin_id,fdDate,fdImg,fdTitle,fdSubTitle,fdUploadedOn) VALUES ($admin_id,'$date','$newName','$title','$sub_title',now())");

            if($insertQuery){
                echo '<script>
                swal({
                    title: "Uploaded successfully!",
                    text: "You clicked the button!",
                    icon: "success",
                })
                </script>';
            }
            else{
                echo '<script>
                swal({
                    title: "Uploading Failed!",
                    text: "try again!",
                    icon: "error",
                });
                </script>';
            }
        }else{
            echo '<script>
            swal({
                title: "Something went wrong!",
                text: "try again!",
                icon: "error",
            });
            </script>';
        }
        
     }
     /* code for insert calender data start */
     ?>
    <div class="container-fluid">
        <div class="row">
            <!-- <div class="col-lg-12"> -->
            <div class="col-lg-6">
                <table class="w3-table w3-table-form w3-small w3-border" border="1">
                    <tr class="table-row table-row">
                        <td>
                            <label for="Date">
                                Date
                            </label>
                        </td>
                        <td>
                            <label for="title_dropdown">
                                Title
                            </label>
                        </td>
                        <td>
                            <label for="sub_title_dropdown">
                                Sub Title
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                </table>
                <div class="card">

                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-title">
                        <h4>Calender Form</h4>
                        <p class="w3-small">Enter Some Details Of Special Occassion</p>
                    </div>
                    <div class="card-body">
                        <form class="row" action="" method="POST" enctype='multipart/form-data' name="calForm" id="calForm">
                            <div class="col-lg-12 form-group">
                                <label for="date">Date</label>
                                <input class="form-control" type="date" name="date" id="date"
                                    placeholer="Enter Your Calender Date">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="date">Title</label>
                                <input class="form-control" type="text" name="title" id="title"
                                    placeholder="Enter Your Occassion Title (Like Holi,Diwali etc..)">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="sub_title">Sub Title</label>
                                <input class="form-control" type="text" name="sub_title" id="sub_title"
                                    placeholder="Enter Your Occassion Sub Title">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="label" class="form-label">Image</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" accept="image/*" onchange="validateImage(this)"
                                            id="image" name="image">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                    </div>
                                </div>
                                <p id="errorText"></p>
                            </div> 
                            <div class="w-100 px-4" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                                <button class="btn w3-indigo" type="submit" name="uploadBtn" id="uploadBtn">Upload Now</button>
                                <button type="reset" class="btn w3-red">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
<script>
/* function for validate image field start */

function validateImage(input) {
    var file = input.files[0];

    var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    var maxSize = 2 * 1024 * 1024; // 5MB

    // Check file type
    if (!allowedTypes.includes(file.type)) {
        showError('Invalid file type. Please select an image file (JPEG, PNG, or GIF).');
        return;
    }

    // Check file size
    if (file.size > maxSize) {
        showError('File size exceeds the limit of 2MB.');
        return;
    }

    // Clear any previous error message
    clearError();
}

function showError(message) {
    document.getElementById('errorText').innerText = message;
    document.getElementById('image').value = ''; // Clear the file input
}

function clearError() {
    document.getElementById('errorText').innerText = '';
}

/* function for validate image field end */
$(document).ready(function() {
    document.getElementById('calender_data').style.background = 'gray';
});
</script>
</body>

</html>