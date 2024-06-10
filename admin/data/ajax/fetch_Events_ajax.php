<?php require_once('../../../include/config.php');
extract($_POST); 

// 1
if (empty($_POST['SGateDCID'])){
    $CustomerID = " ";
} else {
    $CustomerID = "WHERE fd_admin_id = '".$_POST['SGateDCID']."'";
}

?>
<option value="">Select Events</option>
<?php 
    $sql = "SELECT DISTINCT fd_event_type FROM tb_history $CustomerID ORDER BY fd_event_type ASC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

        $event_id = $row['fd_event_type'];

        $sql2 = "SELECT DISTINCT fd_event_name FROM tb_events WHERE fd_ID =  $event_id";
        $result2 = mysqli_query($conn, $sql2);
        
        if (mysqli_num_rows($result2) > 0) {
        // output data of each row
        while($row2 = mysqli_fetch_assoc($result2)) {
        
        $event_name = $row2["fd_event_name"];
        
            }
        }

    ?>
    <option value="<?php echo $event_id; ?>"><?php echo $event_id; ?>, <?php echo $event_name; ?></option> 
    <?php
    }
    } else {
    echo "0 results";
    }
?>