<?php
session_start(); // start the session
require_once('include/config.php');

// unset session variables
$_SESSION = array('loginuseremail','loginuser_firstname','loginuser_lastname','loginuser_image');

// destroy session
session_destroy();

// redirect to login page
header("location: user_login.php");
exit;
?>