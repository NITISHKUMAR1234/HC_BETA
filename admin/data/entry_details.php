<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//session_start();

require_once("../../include/header.php"); // include header file
require_once('../../include/config.php'); // include connection file

/* if user is not logged in, redirect to login page */
if($_SESSION['admin_id'] == ""){
    echo '<script>
    window.location.href = `https://onespect.in.net/Calendar/beta/admin/index.php`;
    </script>';
    die();
}

$current_month = date('m'); // get current month
$current_year = date('Y'); // get current year 
$previous_month = $current_month - 1; // get previous month 
?>
<style>
body {
    font-family: 'Montserrat', sans-serif;
    background: #f6f9ff;
}

.card {
    border: none;
    border-radius: 5px;
    box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
    overflow: auto;
}

.overview_table {
    text-align: center;
}

.overview_table thead tr {
    white-space: nowrap;
}

.entry_details {
    height: 400px;
}

.filter_table {
    border-collapse: collapse;
    /* Optional: This ensures that adjacent table cells share borders */
    border: 1px solid black;
    /* Add a 1px solid black border around the table */
}

.filter_table .table_head {
    background: #3f51b5 !important;
    color: #fff;
}

.filter_table td {
    border: 1px solid black;
    /* Add a 1px solid black border to table headers and cells */
    padding: 0px;
    text-align: center;
    /* Optional: Add padding to cells for better spacing */
}

.w3-btn {
    padding: 10.5px 16px;
}

@media (min-width: 1200px) {

    #main,
    #footer {
        margin-left: 126px;
    }
}
.text-bold{
    font-weight: bold;
    color: red;
}
/* -----------------------------------------------------
css for page title start 
-------------------------------------------------------*/
.container-fluid .row .page-title h4 {
    font-weight: bold;
    color: red;
}

.container-fluid .row .page-title h4 i {
    color: red;
}

.container-fluid .row .page-title .link-items {
    display: flex;
}

.container-fluid .row .page-title a {
    text-decoration: none;
    color: lightgrey;
    font-size: 12px;
}

/* -----------------------------------------------------
css for page title end 
------------------------------------------------------*/
/* styling for loader start */
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: none;
}

.loader {
    border: 10px solid #f3f3f3;
    border-top: 10px solid #3498db;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.blur {
    filter: blur(5px);
}


/* styling for loader end */
</style>
<!-- code for page title start -->
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="page-title col-lg-12 mb-3">
                <h4><i class="fa fa-database" aria-hidden="true"></i> My Total Entries</h4>
                <span class="link-items">
                    <a class="link" href="index.php">Dashboard / </a>
                    <a class="link" href="index.php?entry_details">Total Entries details</a>
                </span>
            </div>
        </div>
    </div>
</section>
<!-- code for page title end -->
<!-- code for show loader start -->
<div class="loader-container" id="loader">
    <div class="loader"></div>
</div>
<!-- code for show loader end -->
<section>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-12">
                <div class="card">
                    <span class="card-title ml-4">
                        <h4 class="text-bold">Overview</h4>
                    </span>
                    <div class="card-body">
                        <table class="table table-striped overview_table" id="overview_table">
                            <thead>
                                <tr>
                                    <th scope="col">Sl. No.</th>
                                    <th scope="col">Email Id</th>
                                    <th scope="col">This Month</th>
                                    <th scope="col">Last 1 Month</th>
                                    <th scope="col">Last Month Duplicate Entry</th>
                                    <th scope="col">Total Entry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    /* code for fetch admin eamil id */
                                $sql = mysqli_query($conn,"SELECT fd_email,fd_id FROM tb_admin");                                
                                if(mysqli_num_rows($sql) > 0){
                                    $count = 0; // used for serial number
                                    while($row = mysqli_fetch_assoc($sql)){  
                                        $count ++;                                      
                                        $admin_id = $row['fd_id']; // admin id
                                        $admin_email = $row['fd_email']; // admin email id

                                        $displayEmail = substr($admin_email,0,4);
                                        $maskedEmail = $displayEmail . str_repeat("*", strlen($admin_email) - 4);
                                        

                                        /* code for count total numbers of entry of this month */
                                                            
                                        $this_month_query = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `this_month_total` FROM `tb_history` WHERE `fd_admin_id` = $admin_id AND MONTH(`fd_uploaded_on`) = '$current_month' AND YEAR(`fd_uploaded_on`) = '$current_year' AND fd_status = 0 AND fd_delete = 0");

                                        $thisMonthRow = mysqli_fetch_assoc($this_month_query);                                       
                                        $this_month = $thisMonthRow['this_month_total'];     
                                        
                                        /* Query for count last 1 month data  */
                                      
                                        $lastMonthQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `last_month_entry` FROM `tb_history` WHERE MONTH(`fd_uploaded_on`) = $previous_month AND YEAR(`fd_uploaded_on`) = '$current_year' AND fd_admin_id = '$admin_id' AND fd_status = 0 AND fd_delete = 0");

                                        $lastMonthRow = mysqli_fetch_assoc($lastMonthQuery);
                                        $last_month_data = $lastMonthRow['last_month_entry'];  
                                        
                                        /* count duplicate entry */
                                        $duplicate_query = "SELECT COUNT(`fd_id`) as count FROM tb_history WHERE fd_admin_id = $admin_id AND
                                        MONTH(`fd_uploaded_on`) = $previous_month AND YEAR(`fd_uploaded_on`) = '$current_year'AND  fd_status = 0 AND fd_delete = 0 GROUP BY fd_title,fd_event_type,tb_calender_date HAVING count > 1";
                                       
                                        $duplicate_result = mysqli_query($conn, $duplicate_query);
                                        
                                        // Sum the count values
                                        $duplicate_sum = 0;
                                        while ($duplicateRow = mysqli_fetch_assoc($duplicate_result)) {
                                            $duplicate_count = $duplicateRow['count'];
                                            $duplicate_sum += $duplicate_count;
                                        }                                     
                                                                         
                                        
                                        /* count total numbers of entries */

                                        $totalQuery = mysqli_query($conn,"SELECT COUNT(`fd_id`) AS `net_total` FROM `tb_history` WHERE `fd_admin_id` = $admin_id AND fd_status = 0 AND fd_delete = 0");

                                        if(mysqli_num_rows($totalQuery) > 0){
                                            $countTotal = mysqli_fetch_assoc($totalQuery);
                                            $total = $countTotal['net_total'];
                                        }
                                        else{
                                            $total = "0";
                                        }
                                        

                                 ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $maskedEmail; ?></td>
                                    <td><?php echo $this_month; ?></td>
                                    <td><?php echo $last_month_data; ?></td>
                                    <td><?php echo $duplicate_sum; ?></td>
                                    <td><b><?php echo $total; ?></b></td>
                                </tr>
                                <?php                                       
                                    }
                                }else{
                                    echo '<h5>No Data Available!</h5>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 form-group mt-2">
                <label for="admin_email"><b>Email</b></label>
                <select class="w3-select w3-white w-100" name="filter_admin" id="filter_admin">
                    <option value="0" selected disabled>Select</option>
                    <?php
                                    $countAdminQuery = mysqli_query($conn,"SELECT fd_id,fd_email FROM tb_admin");
                                    if(mysqli_num_rows($countAdminQuery) > 0){
                                        while($total_admin_row = mysqli_fetch_assoc($countAdminQuery)){
                                            $admin_email_id = $total_admin_row['fd_email']; // admin email id

                                            $firstFourLetterEmail = substr($admin_email_id,0,4); // display first four character of the email
                                            $hideEmail = $firstFourLetterEmail . str_repeat("*", strlen($admin_email_id) - 4); // Display * symbol after four letter of email

                                            echo '<option value="'.$total_admin_row["fd_id"].'">'.$hideEmail.'</option>';
                                        }
                                    }
                                    ?>
                </select>
            </div>
            <div class="col-lg-3 form-group mt-2">
                <label for="start_date"><b>FROM (Start Date)</b></label>
                <input class="w3-select w3-white w-100" onchange="showDates()" name="filter_start_date"
                    id="filter_start_date" type="date">
            </div>
            <div class="col-lg-3 form-group mt-2">
                <label for="start_date"><b>To (End Date)</b></label>
                <input class="w3-select w3-white w-100" onchange="showDates()" name="filter_end_date"
                    id="filter_end_date" type="date">
            </div>
            <div class="col-lg-3 action mb-2">
                <label for="action"><b>ACTION</b></label>
                <button type="button" class="w3-btn w3-red w-100" name="fetchBtn" id="fetchBtn"
                    value="true">Fetch</button>
            </div>

            <div class="col-lg-12 mt-0">
                <div class="card entry_details">
                    <div class="card-title">
                        <h4 class="text-bold ml-4 float-left"><i class="fa fa-table" aria-hidden="true"></i> Entry Data</h4>
                        <h5 class="text-bold float-right mr-4" id="result"><b>(Today's Entry)</b></h5>
                    </div>
                    <div class="card-body">
                        <table class="table entry_data" id="entry_data">
                            <thead>
                                <tr>
                                    <th scope="col">Sl. No.</th>
                                    <th scope="col">Email Id</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Duplicate Entry</th>
                                    <th scope="col">Total Entry</th>
                                </tr>
                            </thead>
                            <tbody id="tableData">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
<script>
/* code for show start date and end date  */

function showDates() {
    var startDate = document.getElementById("filter_start_date").value;
    var endDate = document.getElementById("filter_end_date").value;
    var result = document.getElementById("result");

    if (startDate && endDate) {
        result.innerHTML = "<b> FROM (Start Date):</b> " + startDate + "<b> TO (End Date): </b>" + endDate;
    }
}

$(document).ready(function() {
    document.getElementById('entry_details').style.background = 'gray';

    /* function for fetch data on page load start */

    function loadData() {
        $('#loader').show();
        $.ajax({
            url: "ajax/get_entry_details.php",
            type: "POST",
            success: function(data) {
                $('#loader').hide();
                $("#tableData").html(data);
            }
        });
    }
    loadData(); // calling a function

    /* function for fetch data on page load end */

    /* code for fetch data according to selected parameters  */

    $('#fetchBtn').on('click', function() {
        let id = $('#filter_admin').val();
        let sDate = $('#filter_start_date').val();
        let eDate = $('#filter_end_date').val();
        let fetchBtn = $('#fetchBtn').val();

        if ((sDate != "" && eDate == "") || (sDate == "" && eDate != "")) {
            swal({
                title: "Start Date and End Date Both Are Must Be Selected!",
                text: "Both are must be selected!",
                icon: "warning",
            });
        } else if ((id == null) && (sDate == "") && (eDate == "")) {
            swal({
                title: "Please Select Valid Filteration Parameters!",
                text: "Atleast One Parameter Must Be Selected!",
                icon: "warning",
            });
        } else {
            let admin_id = (id == null) ? "0" : id;
            let start_date = (sDate == "") ? "0" : sDate;
            let end_date = (eDate == "") ? "0" : eDate;

            $('#loader').show();
            $.ajax({
                url: "ajax/get_entry_details.php",
                type: "POST",
                data: {
                    admin_id: admin_id,
                    start_date: start_date,
                    end_date: end_date,
                    fetchBtn: fetchBtn
                },
                success: function(data) {                    
                    $('#loader').hide();
                    $("#tableData").html(data);
                }
            });
        }
    });
});
</script>