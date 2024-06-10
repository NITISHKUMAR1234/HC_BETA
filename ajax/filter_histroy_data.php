<?php
session_start(); // session is start
require_once('../include/config.php'); // include connection file 

/* if user is not logged in , it will redirect to login page */
if(isset($_SESSION['loginuseremail'])){
    $user_email = $_SESSION['loginuseremail']; //  store user email id
}

if(isset($_POST['event_id'])){

    $event_id = $_POST['event_id'];
    $event_type = ($event_id == "All") ? "" : "AND fd_event_type = '$event_id'"; // store the value of event. If the event id is "All" then it will fetch all the data from the selected date otherwise it it will fetch the data according to the selected event type

    /* query for fetch data according to their event type */
}