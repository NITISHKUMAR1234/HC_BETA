<?php
// $conn = mysqli_connect("localhost","nikusoft_test_ca","+&$^,{2[}s&q","nikusoft_test_cal");
$servername = "94.203.132.33";
$username = "root";
$password = "Mirach@12345$";
$dbname = "dbHcTest";

$conn = mysqli_connect($servername, $username, $password, $dbname);


// function for run Query
define('SITE_URL','https://onespect.in.net/Calendar/beta/');

function DB_run_query($sql)  
{
    global $conn;
    return @mysqli_query($conn,$sql);  // @ use for hiding  default errors
}

function DB_escap_string($str)
{
    global $conn;
    return @mysqli_real_escape_string($conn,$str);
}

// function for Num Rows
function DB_num_rows($rsql)
{
    return @mysqli_num_rows($rsql); // @ use for hiding  default errors
}

// function for fetch data in associative Array
function DB_fetch_assoc($rsql)
{
    return @mysqli_fetch_assoc($rsql); // @ use for hiding  default errors
}
// function for fetch data in Objects
function DB_fetch_object($rsql)
{
    return @mysqli_fetch_object($rsql); // @ use for hiding  default errors
}
// get profile url
function DB_profileIMg($email)
{
    return DB_fetch_object(DB_run_query("SELECT fd_profile_image FROM tb_users WHERE fd_email='$email'"))->fd_profile_image;
}

# check post is seved or not | 02/09/2023
function IsPostSaved(int $postId, int $postType, string $userId): bool {
    $query = "SELECT * FROM tb_saved WHERE fdPostId=".$postId." AND fdPostType=".$postType." AND fdUserID='".$userId."';";
    if(DB_num_rows(DB_run_query($query)) > 0) {
        return true;
    }
    return false;
}

function CountSavedPosts(string $user) {
    $query = "SELECT * FROM tb_history INNER JOIN tb_saved ON tb_history.fd_id = tb_saved.fdPostId WHERE fdUserID = '".$user."';";
    return DB_num_rows(DB_run_query($query));    
}

function CountSharedPosts(string $user) {
    $query = "SELECT * FROM tb_history INNER JOIN tb_shared ON tb_history.fd_id = tb_shared.fdPostId WHERE fdRecever='".$user."'";
    return DB_num_rows(DB_run_query($query));
}
?>