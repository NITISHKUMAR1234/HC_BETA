<?php
session_start();
require_once('../../../include/config.php'); // include connection file

/* code for fetch state name by the country name */

if(isset($_POST['country_id'])){
    $country_id = $_POST['country_id'];
    
    echo '<option value="" selected disabled>Select</option>';
    $query = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_states WHERE fd_Country_id = '$country_id'");
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){           
            echo '<option value="'.$row['fdID'].'">'.$row["fd_Name"].'</option>';
        }
    }
}

/* code for fetch city name by the state name  */

if(isset($_POST['state_id'])){
    $state_id = $_POST['state_id'];

    echo '<option value="" selected disabled>Select</option>';
    $sql = mysqli_query($conn,"SELECT fdID,fd_Name FROM tb_cities WHERE fd_State_id = '$state_id'");
    if(mysqli_num_rows($sql) > 0){
        while($cityRow = mysqli_fetch_assoc($sql)){                             
            echo '<option value="'.$cityRow["fdID"].'">'.$cityRow["fd_Name"].'</option>';
        }
    }
}
?>