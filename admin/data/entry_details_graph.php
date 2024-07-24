<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//session_start(); // session is start

require_once('../../include/header.php'); // header file include
/* if user is not logged in then redirect to login page */
if(empty($_SESSION['email'])){
    header("Location: ../index.php"); // redirect to index page
    die();
}
// require_once('../../include/config.php'); // include connection file
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/1.2.0/chartjs-plugin-zoom.min.js" integrity="sha512-TT0wAMqqtjXVzpc48sI0G84rBP+oTkBZPgeRYIOVRGUdwJsyS3WPipsNh///ay2LJ+onCM23tipnz6EvEy2/UA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <section id="inner-headline">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 d-flex" style="height:69.5px;">
                        <h2 class="pageTitle mb-4">Historical Data Graph on Calendar</h2>
                        <!-- <span>All Historical data of water meter</span> -->
                        <!-- <div class="ms-auto m-3 d-flex">
                            <a href="aemhisdata.php"><button class="btn btn-primary mt-1 mx-2">Historical DATA</button></a>
                        </div> -->
                    </div>
                </div>
            </div>
        </section><br>
        <?php
            $totDateTime = date("Y-m-d H:i:s");
            $currentDateTime = new DateTime();
            $currentDateTime->modify('-7 days');
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s')
        ?>
        <!-- form area start by nitish kumar -->
        <div class="">
            <form method="POST">
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-1 d-flex flex-row-reverse">
                        <label class="col-form-label">From</label>
                    </div>
                    <div class="col-sm-2">
                        <input class="form-control" name="Start_date" id="Start_date" value="<?php echo $formattedDateTime; ?>" placeholder="Start Date" type="text" onfocus="(this.type='datetime-local')" required>
                    </div>
                    <div class="col-sm-1 d-flex flex-row-reverse">
                        <label class="col-form-label">To</label>
                    </div>
                    <div class="col-sm-2">
                        <input class="form-control" name="End_date" id="End_date" value="<?php echo $totDateTime; ?>" placeholder="End Date" type="text" onfocus="(this.type='datetime-local')" required>
                    </div>
                    <!-- <div class="col-sm-1 d-flex flex-row-reverse">
                        <label class="col-form-label ">Of</label>
                    </div> -->
                    <!-- <div class="col-sm-2">
                         <select class="form-control"  > 
                        <select class="form-control custom-select btn" name="Attribute" id="Attribute" required>
                            <option value="">Select Parameter</option>
                            <option value="4">Normal Entrys</option>
                            <option value="3">Dublicate Entrys</option>
                            <option value="2">Total Entrys</option>
                            <option value="6">Normal vs Dublicate vs Total</option>
                            <option value="1">Super Imposed</option> 
                            <option value="5">Most Viewd Date</option>
                        </select>
                    </div> -->
                </div>
                <div class="row"><br></div>
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-1 d-flex flex-row-reverse">
                        <label class=" col-form-label" style="white-space:nowrap;">Admin ID</label>
                    </div>
                    <div class="col-sm-2">
                    <!-- <select class="form-control custom-select btn" onchange="onChangeCallAjax()" name="SGateDCID" id="SGateDCID"> -->
                        <select class="form-control custom-select btn" onchange="onChangeCallAjax()" name="AdminGateID" id="AdminGateID">

                        </select>
                    </div>
                    <div class="col-sm-1 d-flex flex-row-reverse">
                        <label class="col-form-label">Events</label>
                    </div>
                    <div class="col-sm-2">
                    <!-- <select class="form-control custom-select btn" onchange="onChangeCallAjax()" name="meterSlNo" id="meterSlNo"> -->
                        <select class="form-control custom-select btn" onchange="onChangeCallAjax()" name="EventSlNo" id="EventSlNo">

                        </select>
                    </div>
                    <label class="col-sm-1"></label>
                    <div class="col-sm-2 align-right">
                        <button class="btn btn-info btn-sm" type="button" title="Submit button" onclick="ETF();" style="white-space:nowrap;">Show Chart</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- form area ends by nitish kumar -->

        <!-- ajax return area by nitish-->
        <div id="chart_ddiv" class="container-fluid" ></div>
        <!-- ajax java script by nitish starts -->
        <script type="text/javascript">
            // code for load stargatedcid select list default
            $(document).ready(function() {
                $.ajax({
                url: 'ajax/getCustomerIdAjax.php',
                type: 'POST',
                success:function(getdata,status){
                    $('#AdminGateID').html(getdata);
                }
                });
            });
            // code for change Metr slNo acording to stargatedcid
            $(document).on('change', '#AdminGateID', function() {
                let AdminGateID = document.getElementById("AdminGateID").value;
                $.ajax({
                url: 'ajax/fetch_Events_ajax.php',
                type: 'POST',
                data: {AdminGateID : AdminGateID},
                success:function(getdata,status){
                    $('#EventSlNo').html(getdata);
                }
                });
            });
            // code for load Metr slNo select List default
            $(document).ready(function() {
                let AdminGateID = document.getElementById("AdminGateID").value;
                $.ajax({
                url: 'ajax/fetch_Events_ajax.php',
                type: 'POST',
                data: {AdminGateID : AdminGateID},
                success:function(getdata,status){
                    $('#EventSlNo').html(getdata);
                }
                });
            });

            function ETF(){
                AdminGate_id = document.getElementById("AdminGateID").value;
                event_id = document.getElementById("EventSlNo").value;
                s_date = document.getElementById("Start_date").value;
                e_date = document.getElementById("End_date").value;
                // attribute = document.getElementById("Attribute").value;
                // emid = '05584666';
                    if (AdminGate_id == "") {
                        // swal("Required Admin ID Field");
                        // return false;
                    }
                    if (event_id == "") {
                        // swal("Required Events Field");
                        // return false;
                    }
                    if (s_date == "") {
                        swal("Required Start Date Field");
                        return false;
                    }
                    if (e_date == "") {
                        swal("Required End Date Field");
                        return false;
                    }
                    // if (attribute == "") {
                    //     swal("Required Parameter Field");
                    //     return false;
                    // }
                $.ajax({
                    url: 'ajax/dataGraph.php',
                    type: 'POST',
                    data: {s_date : s_date, e_date : e_date, event_id:event_id, AdminGate_id:AdminGate_id},
                    // success:function(getdata,status){
                    success:function(getdata){
                    $('#chart_ddiv').html(getdata);
                    console.log(getdata);
                    // console.log(status);
                    }
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                document.getElementById('entry_details_graph').style.background = 'gray';
            });
        </script>
    </body>
</html>