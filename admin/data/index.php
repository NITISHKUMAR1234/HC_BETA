<?php
session_start(); // session is start

require_once('../../include/header.php'); // header file include
/* if user is not logged in then redirect to login page */
if(empty($_SESSION['email'])){
    header("Location: ../index.php"); // redirect to index page
    die();
}
require_once('../../include/config.php'); // include connection file
?>
        <style>
        @media screen and (max-width: 680px) {
            .headText {
                margin-top: 60px;
            }
        }
        </style>
        <div class="row no-gutters">
            <div class="col-1">
                <?php
                    include_once('../../include/sidebar.php');
                ?>
            </div>
            <div class="col-11">
                <main id="main" class="main">
                        <?php
                        /* code for include upload historical data page */
                    if(isset($_GET['upload_histroy'])){
                        require_once('upload_histroy_data.php');
                    }
                     /* code for include upload calender data page */
                    else if(isset($_GET['calender_data'])){
                        require_once('upload_calender_data.php');
                    }
                    /* code for include my duplicate page */
                    else if(isset($_GET['myduplicate'])){
                        require_once('my_duplicate.php');
                    }
                    /* code for include entry details page */
                    else if(isset($_GET['entry_details'])){
                        require_once('entry_details.php');
                    }
                    /* code for include entry graph page */
                    else if(isset($_GET['entry_details_graph'])){
                        require_once('entry_details_graph.php');
                    }
                    else{
                        require_once('dashbord_default.php');
                    ?>
                        <script>
                        $(document).ready(function() {
                            document.getElementById('home').style.background = 'gray';
                        });
                        </script>
                    <?php    }   
                    ?>
                    </main>
            </div>
        </div>
    </body>
</html>