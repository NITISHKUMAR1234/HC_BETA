<?php require_once('../../../include/config.php'); ?>
<?php
$s_date  = $_POST['s_date'];
$e_date = $_POST['e_date'];
$attribute = $_POST['attribute'];
$emid = $_POST["emid"];
$sGate_id = $_POST['sGate_id'];
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
        <!-- <?php //if ($attribute == 1) { ?>

        <?php // } ?> -->
        <?php if ($attribute == 2) { ?>
            <div class="container p-2" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart2" style="width:100%;max-width:700px"></canvas>
                    <?php
                    $datetime = '';
                    $value = '';
                    $s = "SELECT fd_uploaded_on AS `upload_date`, COUNT(*) AS `this_month_total` FROM `tb_history` WHERE `fd_admin_id` = $sGate_id AND `fd_event_type` = $emid AND `fd_uploaded_on` >  '$s_date' AND `fd_uploaded_on` < '$e_date' AND fd_status = 0 AND fd_delete = 0 GROUP BY DATE(fd_uploaded_on)";
                    $result = $conn->query($s);
                    while ($row = $result->fetch_assoc()) {
                        $datetime = $datetime . '"' . $row['upload_date'] . '",';
                        $value = $value . '"' . $row['this_month_total'] . '",';
                    }
                    $datetime = trim($datetime, ",");
                    $value = trim($value, ",");
                    ?>
                    <script>
                        const ctx = document.getElementById('myChart2').getContext('2d');
                        const myChart2 = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: [<?php echo $datetime; ?>],
                                datasets: [{
                                    label: 'DATA VALUES',
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
        <?php } ?>
        <?php //if ($attribute == 3) { ?>

        <?php //} ?>
        <?php //if ($attribute == 4) { ?>

        <?php //} ?>
    </div>
</body>

</html>