<?php require_once('../../../include/config.php'); ?>

<style>
    input[type=checkbox] {
    display: none;
}

.w3-center img {
    margin: 1px;
    transition: transform 0.25s ease;
    cursor: zoom-in;
}

input[type=checkbox]:checked ~ label > img {
    transform: scale(2);
    cursor: zoom-out;
}
</style>

<?php

    $s_date  = $_POST['s_date'];
    $e_date = $_POST['e_date'];
    $attribute = $_POST['attribute'];
    $emid = $_POST["emid"];

    if (isset($emid)){

        $wmid2 = "AND fdDevAddress = '$emid'";

    }else{
        $wmid2 = "";
    }
?>

<!-- code for graph  -->
    <div class="row justify-content-center">
        <?php if ($attribute == 1) { ?>
            <div class="w3-round mt-5" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart3" style="height:350px;"></canvas>
                        <?php
                            $datetime = '';
                            $value1 = '';
                            $value2 = '';
                            $value3 = '';
                            $valueT = '';
                            $s = "SELECT * FROM (SELECT * FROM tbEnergyMeterData WHERE fdTimeStampEPU>'$s_date' AND fdTimeStampEPU<'$e_date' $wmid2 ORDER BY fdSlNo DESC) tmp order by tmp.fdTimeStampEPU ASC";
                            // echo $s;
                            $res = mysqli_query($conn,$s);
                            while($row = mysqli_fetch_array($res))
                            {
                                $datetime = $datetime . '"'. $row['fdTimeStampEPU'].'",';
                                $value1 = $value1 . '"'. $row['fdPowerFactorLine1'].'",';
                                $value2 = $value2 . '"'. $row['fdPowerFactorLine2'].'",';
                                $value3 = $value3 . '"'. $row['fdPowerFactorLine3'].'",';
                                $valueT = $valueT . '"'. $row['fdPowerFactor'].'",';
                            }
                            $datetime = trim($datetime,",");
                            $value1 = trim($value1,",");
                            $value2 = trim($value2,",");
                            $value3 = trim($value3,",");
                            $valueT = trim($valueT,",");
                        ?>
                        <script>
                        // first chart strats
                        var ctx3 = document.getElementById('myChart3').getContext('2d');
                        var myChart3 = new Chart(ctx3, {
                            type: 'line',
                            data: {
                                labels: [<?php echo $datetime; ?>],
                                datasets: [{
                                    label: 'Power Factor Red Phase',
                                    data: [<?php echo $value1; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'red',
                                    ],
                                    borderWidth: 1,
                                    pointRadius:0,
                                    order: 4
                                },{
                                    label: 'Power Factor Yellow Phase',
                                    data: [<?php echo $value2; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'yellow',
                                    ],
                                    borderWidth: 1,
                                    pointRadius:0,
                                    order: 3
                                },{
                                    label: 'Power Factor Blue Phase',
                                    data: [<?php echo $value3; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'blue',
                                    ],
                                    borderWidth: 1,
                                    pointRadius:0,
                                    order: 2
                                },{
                                    label: 'Power Factor Total',
                                    data: [<?php echo $valueT; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'aqua',
                                    ],
                                    borderWidth: 2,
                                    pointRadius:0,
                                    lineTension: 0.5,
                                    order: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        min: 0.75, // Set the minimum value of the y-axis
                                        max: 1 // Set the maximum value of the y-axis
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                plugins: {
                                    title: {
                                        display: false,
                                        text: (ctx3) => {
                                        const {axis = 'xy', intersect, mode} = ctx3.chart.options.interaction;
                                        return 'Mode: ' + mode + ', axis: ' + axis + ', intersect: ' + intersect;
                                        }
                                    },
                                }
                            }
                        });
                        </script>
                </div>
            </div>
        <?php
        }
        if ($attribute == 2) { ?>

            <div class="w3-round mt-5" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart" style="height:350px;"></canvas>
                <?php
                    $datetime = '';
                    $value = '';
                    // $PrevVal = '';
                    $s = "SELECT COUNT(`fd_id`) AS `this_month_total`, fd_uploaded_on FROM `tb_history` WHERE `fd_admin_id` = $emid AND `fd_uploaded_on` >  '$s_date' AND `fd_uploaded_on` < '$e_date' AND fd_status = 0 AND fd_delete = 0";

                    // echo $s;
                    $res = mysqli_query($conn,$s);
                    $i = 1;
                    $n = 0;
                    while($row = mysqli_fetch_array($res))
                    {
                        // if($i == 1){
                            $datetime = $datetime . '"'. $row['fd_uploaded_on'].'",';
                            $value = $value . '"'. $row['this_month_total'].'",';
                            // $n = $n+1;
                        // }if($i == $n){
                        //     $datetime = $datetime . '"'. $row['fdTimeStampEPU'].'",';
                        //     $value = $value . '"'. $row['fdPowerFactorLine3'].'",';
                        //     $n = $n+1;
                        // }
                        // $i++;
                    }
                    $datetime = trim($datetime,",");
                    $value = trim($value,",");
                ?>
                <script>
                // first chart strats
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [<?php echo $datetime; ?>],
                        datasets: [{
                            label: 'POWER FACTOR BLUE PHASE',
                            data: [<?php echo $value; ?>],
                            backgroundColor: [
                                'white',
                            ],
                            borderColor: [
                                'Blue',

                            ],
                            borderWidth: 1,
                            pointRadius:0,

                        }]
                    },
                    options: {
                        scales: {
                                    y: {
                                        min: 0.75, // Set the minimum value of the y-axis
                                        max: 1 // Set the maximum value of the y-axis
                                    }
                                },
                        interaction: {
                        intersect: false,
                        mode: 'index',
                        },
                        plugins: {
                        title: {
                            display: false,
                            text: (ctx) => {
                            const {axis = 'xy', intersect, mode} = ctx.chart.options.interaction;
                            return 'Mode: ' + mode + ', axis: ' + axis + ', intersect: ' + intersect;
                            }
                        },
                        }
                    }
                });
                </script>
                </div>
            </div>

            <?php
            }
            if ($attribute == 3) {
            ?>
            <div class="w3-round mt-5" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart2" style="height:350px;"></canvas>

                    <?php
                        $stock2 = '';
                        $items2 = '';
                        $s2 = "SELECT * FROM (SELECT * FROM tbEnergyMeterData WHERE fdTimeStampEPU>'$s_date' AND fdTimeStampEPU<'$e_date' $wmid2 ORDER BY fdSlNo DESC) tmp order by tmp.fdTimeStampEPU ASC";
                        $res2 = mysqli_query($conn,$s2);
                        $i2 = 1;
                        $n2 = 0;
                        while($row2 = mysqli_fetch_array($res2))
                        {
                            if($i2 == 1){
                                $stock2 = $stock2 . '"'. $row2['fdTimeStampEPU'].'",';
                                $items2 = $items2 . '"'. $row2['fdPowerFactorLine2'].'",';
                                $n2 = $n2+1;
                            }if($i2 == $n2){
                                $stock2 = $stock2 . '"'. $row2['fdTimeStampEPU'].'",';
                                $items2 = $items2 . '"'. $row2['fdPowerFactorLine2'].'",';
                                $n2 = $n2+1;
                            }
                            $i2++;
                        }
                        $stock2 = trim($stock2,",");
                        $items2 = trim($items2,",");
                    ?>
                    <script>
                        var ctx_2 = document.getElementById('myChart2').getContext('2d');
                        var myChart2 = new Chart(ctx_2, {
                            type: 'line',
                            data: {
                                labels: [<?php echo $stock2; ?>],
                                datasets: [{
                                    label: 'POWER FACTOR YELLOW PHASE',
                                    data: [<?php echo $items2; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'yellow',
                                    ],
                                    borderWidth: 1,
                                    pointRadius: 0
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        min: 0.75, // Set the minimum value of the y-axis
                                        max: 1 // Set the maximum value of the y-axis
                                    }
                                },
                                interaction: {
                        intersect: false,
                        mode: 'index',
                        },
                        plugins: {
                        title: {
                            display: false,
                            text: (ctx_2) => {
                            const {axis = 'xy', intersect, mode} = ctx_2.chart.options.interaction;
                            return 'Mode: ' + mode + ', axis: ' + axis + ', intersect: ' + intersect;
                            }
                        },
                        }
                            }
                        });
                    </script>
                    <div id="scripttext"></div>
                </div>
            </div>
            <?php } ?>
            <?php
            if ($attribute == 4) {
            ?>
            <div class="w3-round mt-5" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart2" style="height:350px;"></canvas>

                    <?php
                        $stock2 = '';
                        $items2 = '';
                        $s2 = "SELECT * FROM (SELECT * FROM tbEnergyMeterData WHERE fdTimeStampEPU>'$s_date' AND fdTimeStampEPU<'$e_date' $wmid2 ORDER BY fdSlNo DESC) tmp order by tmp.fdTimeStampEPU ASC";
                        $res2 = mysqli_query($conn,$s2);
                        $i2 = 1;
                        $n2 = 0;
                        while($row2 = mysqli_fetch_array($res2))
                        {
                            if($i2 == 1){
                                $stock2 = $stock2 . '"'. $row2['fdTimeStampEPU'].'",';
                                $items2 = $items2 . '"'. $row2['fdPowerFactorLine1'].'",';
                                $n2 = $n2+1;
                            }if($i2 == $n2){
                                $stock2 = $stock2 . '"'. $row2['fdTimeStampEPU'].'",';
                                $items2 = $items2 . '"'. $row2['fdPowerFactorLine1'].'",';
                                $n2 = $n2+1;
                            }
                            $i2++;
                        }
                        $stock2 = trim($stock2,",");
                        $items2 = trim($items2,",");
                    ?>
                    <script>
                        var ctx_2 = document.getElementById('myChart2').getContext('2d');
                        var myChart2 = new Chart(ctx_2, {
                            type: 'line',
                            data: {
                                labels: [<?php echo $stock2; ?>],
                                datasets: [{
                                    label: 'POWER FACTOR RED PHASE',
                                    data: [<?php echo $items2; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'Red',
                                    ],
                                    borderWidth: 1,
                                    pointRadius: 0
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        min: 0.75, // Set the minimum value of the y-axis
                                        max: 1 // Set the maximum value of the y-axis
                                    }
                                },
                                interaction: {
                        intersect: false,
                        mode: 'index',
                        },
                        plugins: {
                        title: {
                            display: false,
                            text: (ctx_2) => {
                            const {axis = 'xy', intersect, mode} = ctx_2.chart.options.interaction;
                            return 'Mode: ' + mode + ', axis: ' + axis + ', intersect: ' + intersect;
                            }
                        },
                        }
                            }
                        });
                    </script>
                    <div id="scripttext"></div>
                </div>
            </div>
            <?php } ?>
            <?php
            if ($attribute == 5) {
            ?>
            <div class="w3-round mt-5" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart2" style="height:350px;"></canvas>

                    <?php
                        $stock2 = '';
                        $items2 = '';
                        $s2 = "SELECT * FROM (SELECT * FROM tbEnergyMeterData WHERE fdTimeStampEPU>'$s_date' AND fdTimeStampEPU<'$e_date' $wmid2 ORDER BY fdSlNo DESC) tmp order by tmp.fdTimeStampEPU ASC";
                        $res2 = mysqli_query($conn,$s2);
                        $i2 = 1;
                        $n2 = 0;
                        while($row2 = mysqli_fetch_array($res2))
                        {
                            if($i2 == 1){
                                $stock2 = $stock2 . '"'. $row2['fdTimeStampEPU'].'",';
                                $items2 = $items2 . '"'. $row2['fdPowerFactor'].'",';
                                $n2 = $n2+1;
                            }if($i2 == $n2){
                                $stock2 = $stock2 . '"'. $row2['fdTimeStampEPU'].'",';
                                $items2 = $items2 . '"'. $row2['fdPowerFactor'].'",';
                                $n2 = $n2+1;
                            }
                            $i2++;
                        }
                        $stock2 = trim($stock2,",");
                        $items2 = trim($items2,",");
                    ?>
                    <script>
                        var ctx_2 = document.getElementById('myChart2').getContext('2d');
                        var myChart2 = new Chart(ctx_2, {
                            type: 'line',
                            data: {
                                labels: [<?php echo $stock2; ?>],
                                datasets: [{
                                    label: 'POWER FACTOR TOTAL',
                                    data: [<?php echo $items2; ?>],
                                    backgroundColor: [
                                        'white',
                                    ],
                                    borderColor: [
                                        'aqua',
                                    ],
                                    borderWidth: 1,
                                    pointRadius: 0
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        min: 0.75, // Set the minimum value of the y-axis
                                        max: 1 // Set the maximum value of the y-axis
                                    }
                                },
                                interaction: {
                        intersect: false,
                        mode: 'index',
                        },
                        plugins: {
                        title: {
                            display: false,
                            text: (ctx_2) => {
                            const {axis = 'xy', intersect, mode} = ctx_2.chart.options.interaction;
                            return 'Mode: ' + mode + ', axis: ' + axis + ', intersect: ' + intersect;
                            }
                        },
                        }
                            }
                        });
                    </script>
                    <div id="scripttext"></div>
                </div>
            </div>
            <?php } ?>
            <?php if ($attribute == 6) { ?>
            <div class="w3-round mt-5" style='background-color:#272B34;color:gray;'>
                <div class="row w-100">
                    <canvas id="myChart3" style="height:350px;"></canvas>
                        <?php
                            $datetime = '';
                            $value1 = '';
                            $value2 = '';
                            $valueT = '';

                            $preValue1 = '';
                            $preValue2 = '';
                            $PreValueT = '';

                            $s = "SELECT * FROM (SELECT fdPosActEnrg, fdPosReactEnrg, fdTimeStampEPU FROM tbEnergyMeterData WHERE fdTimeStampEPU > '$s_date' AND fdTimeStampEPU <'$e_date' $wmid2 ORDER BY fdSlNo DESC) tmp order by tmp.fdTimeStampEPU ASC";
                            // echo $s;

                            $iForCal = 1;
                            $res = mysqli_query($conn,$s);
                            while($row = mysqli_fetch_array($res))
                            {
                                $CurentValue1 = $row['fdPosActEnrg'];
                                // echo ',';
                                $CurentValue2 = $row['fdPosReactEnrg'];
                                // echo ',';

                                if($iForCal > 1){
                                    // echo $row['fdTimeStampEPU'];
                                    $datetime = $datetime . '"'. $row['fdTimeStampEPU'] .'",';

                                    $calValue1 = ((float)$CurentValue1 - (float)$preValue1);
                                    $value1 = $value1 . '"'. $calValue1 .'",';

                                    $calValue2 = ((float)$CurentValue2 - (float)$preValue2);
                                    $value2 = $value2 . '"'. $calValue2 .'",';

                                    // $valueT = $valueT. '"'. $row['fdPosTotEnrg'] .'",';
                                    $valueT = $valueT . '"'. ((float)$calValue2 / ((float)$calValue1 + (float)$calValue2)) * 100 .'",';
                                }else{
                                    // echo 'else part running';
                                }

                                $preValue1 = $CurentValue1;
                                $preValue2 = $CurentValue2;
                                // $PreValueT = $CurenValueT;

                                $iForCal = $iForCal+1;
                            }
                            $datetime = trim($datetime,",");
                            $value1 = trim($value1,",");
                            $value2 = trim($value2,",");
                            $valueT = trim($valueT,",");
                        ?>
                        <script>
                        // last chart strats
                        var ctx3 = document.getElementById('myChart3').getContext('2d');

                        // defining min max for chart
                        var valueT = [<?php echo $valueT; ?>];
                        var floatArray = valueT.map(parseFloat);

                        var max3 = Math.max(...floatArray);
                        var min3 = Math.min(...floatArray);

                        var myChart3 = new Chart(ctx3, {
                            type: 'line',
                            data: {
                                labels: [<?php echo $datetime; ?>],
                                datasets: [{
                                    label: 'ACTIVE',
                                    type: 'bar',
                                    data: [<?php echo $value1; ?>],
                                    backgroundColor: [
                                        'rgb(0, 0, 200)',
                                    ],
                                    borderColor: [
                                        'rgb(0, 0, 200)',
                                    ],
                                    borderWidth: 1,
                                    pointRadius:0,
                                    yAxisID: 'y3',
                                    order: 2,
                                },{
                                    label: 'REACTIVE',
                                    type: 'bar',
                                    data: [<?php echo $value2; ?>],
                                    backgroundColor: [
                                        'rgb(10, 95, 215)',
                                    ],
                                    borderColor: [
                                        'rgb(10, 95, 215)',
                                    ],
                                    borderWidth: 1,
                                    pointRadius:0,
                                    yAxisID: 'y3',
                                    order: 3
                                },{
                                    label: 'LOSSES',
                                    type: 'line',
                                    data: floatArray,
                                    backgroundColor: [
                                        'rgb(0, 255, 255)',
                                    ],
                                    borderColor: [
                                        'rgb(0, 255, 255)',
                                    ],
                                    borderWidth: 3,
                                    // pointRadius:0,
                                    lineTension: 0.5,
                                    yAxisID: 'y1',
                                    order: 1,
                                    axisType: 'secondary',
                                    suffix: ' %',

                                    backgroundColor: floatArray.map(function(value) { return value === max3 ? '#39FF14' : value === min3 ? '#f70d1a' : 'rgb(0, 255, 255)';}),

                                    pointBackgroundColor: floatArray.map(function(value) { return value === max3 ? '#39FF14' : value === min3 ? '#f70d1a' : 'rgb(0, 255, 255)';}),

                                    pointBorderColor: floatArray.map(function(value) { return value === max3 ? '#39FF14' : value === min3 ? '#f70d1a' : 'rgb(0, 255, 255)';}),

                                    pointRadius: floatArray.map(function(value) { return value === max3 ? '5' : value === min3 ? '5': '0'; }),

                                    pointStyle: floatArray.map(function(value) { return value === max3 ? 'rectRot' : value === min3 ? 'rectRot': 'circle'; }),

                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    x:{
                                        ticks: {
                                            color: "white",
                                        },
                                    },
                                    y1: {

                                        display: true,
                                        position: 'right',
                                        // grid line settings
                                        grid: {
                                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                                        },
                                        min:0,
                                        max:100,
                                        ticks: {
                                            color: "white",
                                            callback: function(value, index) {
                                                return value + ' %'; // Add your desired suffix here
                                            },
                                        }
                                    }, y3: {
                                        title:{
                                            display: true,
                                        },
                                        position: 'left',
                                        ticks: {
                                            color: "white",
                                        },
                                    },
                                },

                                interaction: {
                                intersect: false,
                                mode: 'index',
                                },
                                plugins: {
                                    zoom: {
                                        zoom: {
                                            wheel: {
                                                enabled: true,
                                            },
                                            pinch: {
                                                enabled: true
                                            },
                                            mode: 'x',
                                        }
                                    },
                                title: {
                                    display: false,
                                    text: (ctx3) => {
                                    const {axis = 'xy', intersect, mode} = ctx3.chart.options.interaction;
                                    return 'Mode: ' + mode + ', axis: ' + axis + ', intersect: ' + intersect;
                                    }
                                },
                                }
                            }
                        });


                        </script>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
