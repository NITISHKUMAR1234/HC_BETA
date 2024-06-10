<?php
session_start(); // session is start

require_once('../include/header.php'); // include header file
/* if user is looged in, redirect to index page */
if(!empty($_SESSION['email'])){
    header("Location: data/index.php");
    die();
}
require_once('../include/config.php'); // include connection file

$error_msg = false; // error variable
?>

<!-- external file for css -->
<link rel="stylesheet" href="css/style.css">
<?php

/* code for login form start */
if(isset($_POST['loginBtn'])){
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$query = mysqli_query($conn, "SELECT fd_id,fd_email,fd_password FROM tb_admin WHERE fd_email = '$email' AND fd_password =
'$password' AND fd_status = 0");

if($row = mysqli_fetch_assoc($query)){
$_SESSION['email'] = $row['fd_email'];
$_SESSION['admin_id'] = $row['fd_id'];
echo '<script>window.location.href = "data/index.php";
</script>';
}
else{
$error_msg = true;
}
}
/* code for login form end */
?>
<!-- code for login form start -->
<section>
    <div class="bg-img">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>Admin login</h4>
                            <i class="fa fa-lock mt-2" aria-hidden="true"></i>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <?php
                            if($error_msg){
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Invalid email id or password!</strong> try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                                }
                            ?>
                                <div class="form-group mt-2">
                                    <label for="email">Email address</label>
                                    <input class="form-control mt-2" name="email" id="email" type="text"
                                        placeholder="Enter the Email address" required autocomplete="off">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="email">Password</label>
                                    <input class="form-control mt-2" name="password" id="password" type="password"
                                        placeholder="Enter the password" required autocomplete="off">
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn w-100" name="loginBtn" id="loginBtn">Login Now</button>                                  
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- code for login form end -->
<?php
mysqli_close($conn); // connection close
?>
</body>

</html>