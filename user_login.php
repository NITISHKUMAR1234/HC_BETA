<?php
session_start();
require_once('include/config.php');
require_once('include/header.php');

if($_SESSION['loginuseremail'] != ''){
    echo "<script>window.location.href='index.php'</script>";
    die();
}
$error_message = false;
?>
<link rel="stylesheet" href="css/user_login.css">
    <?php
    require_once('include/navbar.php');
   ?>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 mt-5 mb-4">
                <div class="card mt-4 mb-4">
                    <div class="card-body">
                        <div class="card-logo mt-5">
                            <div class="row justify-content-center align-items-center">
                                <img src="Image/priest.png" alt="user image">
                            </div>
                        </div>
                        <div class="card-title pt-2">
                            <h3>Welcome back!</h3>
                            <p>Sign in to your account</p>
                        </div>
                        <div class="signinBtn">
                            <form class="row justify-content-center align-items-center" action="login_action.php">
                                <div class="col-10 form-group">
                                    <a class="btn btn-md w-100 btn-light btn-block border-secondary"
                                        href="https://onespect.in/login.php?apiLogin=CalenderLogin"
                                        role="button" title="Continue with ONESPECT">
                                        <img src="https://onespect.in/img/S.png" width="20px" height="20px"
                                            alt="onespect logo">Sign in with onespect</a>
                                </div>
                            </form>
                        </div>
                        <div class="row justify-content-center align-items-center line">
                            <p class="col-9"><span>Or</span></p>
                        </div>
                        <form class="row align-items-center justify-content-center pb-4" method="POST" id="form"
                            name="form" action="login_action.php">
                            <?php
                                    if($error_message){
                                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Invalid email id or password!</strong> try again!
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>';
                                    }

                                    ?>
                            <div class="form-group col-10">
                                <label for="email">Email Address</label>
                                <input class="form-control" id="email" name="email" type="email" required>
                            </div>
                            <div class="form-group col-10">
                                <label for="password">Password</label>
                                <input class="form-control" id="password" name="password" type="password" required>
                                <span id="togglePassword" class="fa fa-eye-slash" onclick="togglePassword()"></span>
                            </div>
                            <div class="form-group col-10">
                                <input id="checkbox" name="checkbox" type="checkbox">
                                <span class="ml-1">Remember me</span>
                                <a href="#">Forgot my password</a>
                            </div>
                            <div class="action col-10 pb-5">
                                <button class="btn w-100 p-2 loginBtn" type="submit" name="loginBtn" id="loginBtn"><b>Login</b></button>
                                <a class="btn btn-warning w-100 mt-2" href="index.php"><b>Go Back</b></a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
    function togglePassword() {
        var password = document.getElementById("password");
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
    </script>
</body>
</html>