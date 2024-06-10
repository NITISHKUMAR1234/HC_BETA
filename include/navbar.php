<style>
.navbar-brand{
    padding: 0!important;
}
.navbar img {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    margin-right: 10px;
}

.navbar a {
    text-decoration: none;
}

.title {
    color: black;
}

.sub_title {
    color: red;
}

.navbar .collapse .navbar-nav li {
    color: gray;
}

/* code for dropdown menu start */

.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown img{
    width: 25px;
    height: 25px;
}


.dropdown-content {
    display: none;
    right: 0;
    left: auto;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

@media screen and (max-width: 991px) {
    .dropdown-content {
        display: none;
        left: 0;
        right: auto;
    }
}

.dropdown-content a {
    color: black;
    padding: 7px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3D5C7F;
}
/* Media queries start */
@media screen and (max-width: 991px) {
    /* Code for hide in medium device home menu */
.home{
    display: none;
}
}

/* Media queries end */
</style>

<!-- code for top navbar start -->


<!-- code for top navbar end -->


<!--======================= Original Code Starts from Here =======================-->
<!-- <style>
.navbar-brand{
    padding: 0!important;
}
.navbar img {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    margin-right: 10px;
}

.navbar a {
    text-decoration: none;
}

.title {
    color: black;
}

.sub_title {
    color: red;
}

.navbar .collapse .navbar-nav li {
    color: gray;
}

/* code for dropdown menu start */

.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown img{
    width: 25px;
    height: 25px;
}

.dropdown-content {
    display: none;
    right: 0;
    left: auto;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

@media screen and (max-width: 991px) {
    .dropdown-content {
        display: none;
        left: 0;
        right: auto;
    }
}

.dropdown-content a {
    color: black;
    padding: 7px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3D5C7F;
}
/* Media queries start */
@media screen and (max-width: 991px) {
    /* Code for hide in medium device home menu */
.home{
    display: none;
}
}

/* Media queries end */
</style> -->

<!-- code for top navbar start -->

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top w3-card">
    <a href="index.php" class="navbar-brand"><img src="Image/logo.jpeg" alt="CALENDER"><b class="title">CAL</b><b
            class="sub_title">ENDAR</b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav ml-auto">
            <a class="mt-2 mr-3 home" href="index.php"><i class="fa fa-home"></i> Home</a>
            <!-- display content when user is logged in start -->
            <?php
            if(isset($_SESSION['loginuseremail'])){
                $user_email = $_SESSION['loginuseremail']; // store user email
                /* code for fetch user image start */
                $profileQuery = mysqli_query($conn,"SELECT fd_profile_image FROM tb_users WHERE fd_email = '$user_email'");
                if(mysqli_num_rows($profileQuery) > 0){
                    $fetchImgRow = mysqli_fetch_assoc($profileQuery);
                    if($fetchImgRow['fd_profile_image']){
                    $user_img = $fetchImgRow['fd_profile_image'];
                    }
                    if(file_exists('Image/'.$user_img)){
                        $profile_img = $user_img;
                    }
                }
                  /* code for fetch user image end */
                ?>
            <li class="nav-item">
                <div class='dropdown'>
                    <?php
                        // echo '<img src="Image/'.$profile_img.'" onerror="this.src = \'https://onespect.com/user/profilepic/man.png\';" alt="user_image" width="30px" height="30px"> &#9660;';
                    ?>
                    </span>
                    <div class='dropdown-content'>
                        <a href='user_profile.php'><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href='logout.php'><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                    </div>
                </div>
            </li>
            <?php
            }
            //display content when user is logged in end //

            /* display content when user is not logged in start */
            else{
            ?>
            <!-- <a href="user_login.php" class="btn w3-indigo btn-sm"><b>Login</b></a> -->
            <a href="user_login.php" class="btn btn-danger"><b>Login</b></a>
        </ul>
        <?php
            }
            /* display content when user is not logged in end */
            ?>

    </div>
</nav>

<!-- code for top navbar end -->