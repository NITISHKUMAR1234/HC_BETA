<?php require_once('../../../include/config.php'); ?>
<?php
$s_date  = $_POST['s_date'];
$e_date = $_POST['e_date'];
// $attribute = $_POST['attribute'];
$event_id = $_POST["event_id"];
$AdminGate_id = $_POST['AdminGate_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="row justify-content-center">
        <div class="container p-2 mt-3" style='background-color:#272B34;color:gray;'>
            <div class="row w-100 m-1">
                <canvas id="myChart2" style="height:350px;"></canvas>
                <?php
                $datetime = '';
                $value = '';
                if ($AdminGate_id !== $_POST['AdminGate_id']) {
                    $s = "SELECT DATE(fd_uploaded_on) AS `upload_date`, COUNT(*) AS `this_month_total` FROM `tb_history` WHERE `fd_admin_id` = $AdminGate_id   AND `fd_uploaded_on` > '$s_date'   AND `fd_uploaded_on` < '$e_date'   AND fd_status = 0   AND fd_delete = 0 GROUP BY DATE(fd_uploaded_on)";
                } else if ($event_id !== $_POST["event_id"]) {
                    $s = "SELECT DATE(fd_uploaded_on) AS `upload_date`, COUNT(*) AS `this_month_total` FROM `tb_history` WHERE `fd_event_type` = $event_id AND `fd_uploaded_on` > '$s_date'   AND `fd_uploaded_on` < '$e_date'   AND fd_status = 0   AND fd_delete = 0 GROUP BY DATE(fd_uploaded_on)";
                } else if ($AdminGate_id !== $_POST['AdminGate_id'] && $event_id !== $_POST["event_id"]) {
                    $s = "SELECT DATE(fd_uploaded_on) AS `upload_date`, COUNT(*) AS `this_month_total` FROM `tb_history` WHERE `fd_admin_id` = $AdminGate_id `fd_event_type` = $event_id AND `fd_uploaded_on` > '$s_date'   AND `fd_uploaded_on` < '$e_date'   AND fd_status = 0   AND fd_delete = 0 GROUP BY DATE(fd_uploaded_on)";
                } else {
                    $s = "SELECT DATE(fd_uploaded_on) AS `upload_date`, COUNT(*) AS `this_month_total` FROM `tb_history` WHERE  `fd_uploaded_on` > '$s_date'   AND `fd_uploaded_on` < '$e_date'   AND fd_status = 0   AND fd_delete = 0 GROUP BY DATE(fd_uploaded_on)";
                }
                $result = mysqli_query($conn, $s);
                while ($row = $result->fetch_assoc()) {
                    $datetime = $datetime . '"' . $row['upload_date'] . '",';
                    $value = $value . '"' . $row['this_month_total'] . '",';
                }
                $datetime = trim($datetime, ",");
                $value = trim($value, ",");
                ?>
                <script>
                    const ctx2 = document.getElementById('myChart2').getContext('2d');
                    const myChart2 = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: [<?php echo $datetime; ?>],
                            datasets: [{
                                label: 'Total Entrys',
                                data: [<?php echo $value; ?>],
                                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'red', 'green', 'blue', ],
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>

</body>

</html>