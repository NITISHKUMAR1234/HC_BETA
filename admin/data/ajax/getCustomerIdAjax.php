<?php require_once('../../../include/config.php');
extract($_POST); 
?>

<option value="">Select Admin ID</option>
<?php 
    $sql = "SELECT DISTINCT fd_id, fd_email FROM tb_admin";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
    ?>
    <option value="<?php echo $row["fd_id"]; ?>"><b><?php echo $row["fd_id"]; ?></b> , <?php echo $row["fd_email"]; ?></option> 
    <?php
    }
    } else {
    echo "0 results";
    }
?>