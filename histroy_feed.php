<?php
// session_start(); // session is start
require_once('include/header.php'); // include header file
require_once('include/config.php'); // include connection file

$date = $_GET['date'];
$date_array = explode('-', $_GET['date']);
$month = $date_array[1];

if ($month < 10) {
    $month = '0' . $date_array[1];
} else {
    $month = $date_array[1];
}

$current_date = $date_array[2];

/* code for fetch default total numbers of like,dislike and views start */
$sql = mysqli_query($conn, "SELECT tb_calender_date,fd_like,fd_dislike,fd_views FROM tb_history WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$current_date'");
if (mysqli_num_rows($sql) > 0) {
    $fetch = mysqli_fetch_assoc($sql);
    $view_count = $fetch['fd_views'] + 1; // add +1 in views
    $viewCount = $view_count; // store total numbers of views after page refresh

    /* update query for update total numbers of views */
    $updateQuery = mysqli_query($conn, "UPDATE `tb_history` SET `fd_views` = '$viewCount' WHERE MONTH(`tb_calender_date`) = '$month' AND DAY(`tb_calender_date`) = '$current_date'");

    /* code for fetch total numbers of likes start */
    if ($fetch['fd_like']) {
        $totalLikes = $fetch['fd_like'];
    } else {
        $totalLikes = 0;
    }
    /* code for fetch total numbers of likes end */

    /* code for fetch total numbers of disikes start */
    if ($fetch['fd_dislike']) {
        $totalDislikes = $fetch['fd_dislike'];
    } else {
        $totalDislikes = 0;
    }
    /* code for fetch total numbers of disikes end */
}

/* code for fetch default total numbers of like,dislike and views end */

/* code for check user is already liked or disliked start */

if(isset($user_email , $liked, $dislike)){
    $user_email=$user_email;
    $query = mysqli_query($conn, "SELECT * FROM `tb_like_dlike_details` WHERE fd_user_id = '$user_email' AND MONTH(`fd_his_date`) = '$month' AND DAY(`fd_his_date`) = '$current_date'");
    $liked = "";
    $disliked = "";
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['fdStatus'] == "1") {
                $liked = "disabled";
            } else {
                $liked = "";
            }
            $liked=$liked;
            if ($row['fdStatus'] == "2") {
                $disliked = "disabled";
            } else {
                $disliked = "";
            }
            $dislike=$dislike;
        }
    }
}else{$user_email="null"; $liked="null"; $disliked="null";}

/* code for check user is already liked or disliked end */


?>
<!-- include external css file -->
<link rel="stylesheet" href="css/histroy_feed.css">
<style>
    /* --------------------------------------------------------------------
Styling for share modal start
------------------------------------------------------------------- */
    .copy_btn {
        outline: none;
        cursor: pointer;
        font-weight: 500;
        border-radius: 4px;
        border: 2px solid transparent;
        transition: background 0.1s linear, border-color 0.1s linear, color 0.1s linear;
    }

    .share_modal_content {
        margin: 20px 0;
    }

    #searchInput {
        border: 1px solid #000;
    }

    .form-control:focus {
        box-shadow: none !important;
        border: 2px solid #D9544D !important;
    }

    #sendBtn {
        background: #D9544D !important;
        color: #fff;
        margin-left: 10px;
        border: 1px solid #fff;
        width: 50%;
        border-radius: 5px;
        cursor: pointer;
    }

    #searchResults {
        height: auto;
        width: 50%;
        cursor: pointer;
        border: 1px solid gray;
        border-radius: 10px;
        padding: 0 5px 0 5px;
        list-style: none;
        display: none;
    }

    #searchResults li:hover {
        background: #eeee;
    }

    #url-input {
        width: 70%;
        /* Adjust this value based on the icon width */
    }
    .input-container{
        display: flex;
        padding: 0;
        justify-content: space-between;
    }

    #copy-button {
        position: absolute;
        top: 0;
        right: 0;
        background: none;
        border: none;
        cursor: pointer;
        background: #D9544D;
        color: #ffff;
    }


    /* --------------------------------------------------------------------
Styling for share modal end
------------------------------------------------------------------- */
</style>
<?php
require('include/navbar.php'); // include top navbar
?>
<!-- code for filteration buttons start -->
<div class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="filter_buttons ml-2 mr-2" id="filter_buttons">
            <button class="eventBtn active" onclick="setActiveItem(this)" type="button" value="All" name="all_events" id="all_events">All</button>
            <?php
            $eventsQuery = mysqli_query($conn, "SELECT fd_ID,fd_event_name FROM tb_events");
            if (mysqli_num_rows($eventsQuery) > 0) {
                while ($eventRow = mysqli_fetch_assoc($eventsQuery)) {
                    $event_id = $eventRow["fd_ID"];
                    echo '<button class="eventBtn ml-2" onclick="setActiveItem(this)" type="button" value="' . $event_id . '" name="eventBtn" id="eventBtn' . $event_id . '">' . $eventRow["fd_event_name"] . '</button>';
                }
            }
            ?>
        </div>
    </div>
    <!-- code for share modal start -->

    <div id="share_modal" class="w3-modal w3-animate-zoom" style="z-index: 105; display: none;">
        <div class="w3-modal-content mt-3" style="width:40%;border-radius:5px;max-width: 530px;">
            <div class="w3-round modal-box">
                <div>
                    <div class="modal-header">
                        <div>
                            <h3 class="modal-title font-weight-bold" id="shareModalLable">
                                Share
                            </h3>
                        </div>
                        <button type="button" class="close" onclick="closeShareModal ()">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="share_modal_content">
                        <p>Share directly to the users</p>
                        <div class="search-box">
                            <div class="form-group d-flex">
                                <input type="hidden" id="feedTypeInput" name="postType">
                                <input class="form-control form-control-sm w-100" type="text" name="sender" id="searchInput" placeholder="Search user email to share">
                                <button type="button" name="sendBtn" id="sendBtn"><i class="fa fa-share" aria-hidden="true"></i>
                                    Send
                                </button>
                            </div>
                            <ul id="searchResults"></ul>
                        </div>
                        <p class="text-center">Or copy link</p>
                        <div class="col-12 input-container">
                            <input type="text" value="" id="url-input" readonly>
                            <button class="btn btn-sm" id="copy-button" onclick="copyUrl()">
                                <i class="fa fa-link" aria-hidden="true"></i> Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- code for share modal end -->
</div>
<!-- code for filteration buttons end -->

<!-- code for show loader start -->
<div class="loader-container" id="loader">
    <div class="loader"></div>
</div>
<!-- code for show loader end -->

<!-- Show data start -->
<div id="load-data" class="load-data mt-0"></div>
<!-- Show data end -->
<?php
echo '<div class="card col-12 navigation fixed-bottom">
    <div class="navigation-link pb-3">';
/* show like,dislike and view card if user is not logged in then that time it will redirect to login page */
if (isset($totalLikes, $totalDislikes, $viewCount)) {
    $totalLikes=$totalLikes;
    $totalDislikes=$totalDislikes;
    $viewCount=$viewCount;
}else {$totalLikes='0';
    $totalDislikes='0'; 
    $viewCount='0';}
if (!isset($_SESSION['loginuseremail'])) {
    echo '
        <form method="POST" name="form" id="form">
            <a href="#" onclick="setRedirectCookie(); redirectToLogin();" class="navigation-link" value="true">
                <i class="far fa-thumbs-up icon"></i>
            </a>
            <span class="like">' . $totalLikes . '</span>

            <a href="#" onclick="setRedirectCookie(); redirectToLogin();" class="navigation-link" value="true">
                <i class="far fa-thumbs-down icon pt-2"></i>
            </a>
            <span class="dislike">' . $totalDislikes . '</span>
            <a href="#" class="navigation-link">
                <i class="fa fa-eye icon mt-1" aria-hidden="true"></i>
            </a>

            <span class="views">' . $viewCount . '</span>
        </form>';
} else {
    /* show like,dislike and view if user is logged in */
    echo '<form method="POST" name="form" id="form">
            <button type="button" class="navigation-link btn btn-sm" id="likeBtn" value="true" ' . $liked . '>
                <span class="like">' . $totalLikes . '</span>
                <i class="far fa-thumbs-up icon"></i>
            </button>

            <button type="button" class="navigation-link btn btn-sm mt-2" value="true" id="dislikeBtn" ' . $disliked . '>
                <i class="far fa-thumbs-down icon"></i>
                <span class="dislike">' . $totalDislikes . '</span>
            </button>

            <button type="button" class="navigation-link btn btn-sm mt-2">
                <i class="fa fa-eye icon" aria-hidden="true"></i>
                <span class="views">' . $viewCount . '</span>
            </button>

            <a href="saved_feed.php" class="navigation-link btn btn-sm mt-2">
                <i class="fa-sharp fa-solid fa-bookmark icon"></i>
                <span class="views">' . CountSavedPosts($user_email) . '</span>
            </a>

            <a href="share_feed.php" class="navigation-link btn btn-sm mt-2">
                <i class="fa-solid fa-share icon"></i>
                <span class="views">' . CountSharedPosts($user_email) . '</span>
            </a>

        </form>';
}
echo '
    </div>
</div>';
?>
<script>
    class TextReader {
        static #utterance;

        constructor() {
            if (!TextReader.#utterance) {
                TextReader.#utterance = new SpeechSynthesisUtterance();
                TextReader.#utterance.voice = window.speechSynthesis.getVoices()[1];
            }
        }

        Read(id) {
            this.Pause();
            const text = document.getElementById(id).innerHTML;
            TextReader.#utterance.text = text;
            window.speechSynthesis.speak(TextReader.#utterance);
        }

        Pause() {
            window.speechSynthesis.cancel()
        }
    }

    const tr = new TextReader();

    tr.Pause(); // Stop audio on page load
    /* function for create cookie start */
    function setRedirectCookie() {
        // var currentURL = window.location.href;
        let currentURL = window.location.search;
        // console.log(currentURL);
        // document.cookie = "redirectURL=" + currentURL;

        var date = new Date();
        date.setTime(date.getTime() + (1 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
        document.cookie = "redirectURL=" + currentURL + expires + "; path=/";
    }
    /* function for create cookie start end */

    /* function for rediect to login page start */
    function redirectToLogin() {
        window.location.href = "user_login.php";
    }

    /* function for rediect to login page end */

    /* code for active filter link start */

    /* code for active button start */
    function setActiveItem(item) {
        let itemList = document.getElementById("filter_buttons").getElementsByTagName("button");

        // Remove the active class from all items
        for (var i = 0; i < itemList.length; i++) {
            itemList[i].classList.remove("active");
        }

        // Add the active class to the clicked item
        item.classList.add("active");
    }

    /* code for active button end */

    /* target modal */
    var modal = document.getElementById("share_modal");
    /* code for open modal start */
    // function showShareModal(value) {


    /* function for close modal start */

    function closeShareModal() {
        $('#share_modal').hide();
    }

    // Function to handle outside clicks
    function outsideClick(event) {
        if (event.target === modal) {
            closeShareModal();
        }
    }

    // Add event listener for outside clicks
    document.addEventListener("click", outsideClick);

    /* Code for copy url start */
    function copyUrl() {
        let urlInput = document.getElementById("url-input");
        let field = document.querySelector(".input-container");
        let icon = '<i class="fa fa-link" aria-hidden="true"></i>';
        urlInput.select();
        if (document.execCommand("copy")) { //if the selected text copy
            field.classList.add("active");
            document.getElementById('copy-button').innerHTML = icon + ' Copied';
            setTimeout(() => {
                window.getSelection().removeAllRanges(); //remove selection from document
                field.classList.remove("active");
                document.getElementById('copy-button').innerHTML = icon + ' Copy Url';
            }, 3000);
        }
        // document.execCommand("copy");
        // document.getElementById('copy-button').innerHTML = icon + ' Copied';
        // setTimeout(function() {
        //     document.getElementById('copy-button').innerHTML = icon + ' Copy Url';
        //     urlInput.deselect();
        // }, 3000);
    }
    /* Code for copy url end */

    /* Code for remove space */
    function removeSpaces(text) {
        // Use a regular expression to replace all spaces (including multiple spaces) with an empty string
        return text.replace(/\s+/g, '');
    }

    $(document).ready(function() {
        /* code for open modal start */
        $(document).on("click", "#open_modal_button", function() {
            let title = $(this).attr('data-title');
            let sub_title = $(this).attr('data-subTitle');
            let discription = $(this).attr('data-disc');
            let image = $(this).attr('data-img');
            let date = $(this).attr('data-date');

            let data_id = $(this).attr('data-id');
            let data_type = $(this).attr('data-type');
            $('#sendBtn').val(data_id);
            $('#feedTypeInput').val(data_type);

            let url = 'onespect.in.net/Calendar/beta/index.php?histroy_feed&date=' + date + '&title=' + removeSpaces(title) + '&discription=' + removeSpaces(discription);

            $('#url-input').val(url);
            $('#share_modal').show();
        });
        /* code for open modal end */
        $('.sound-btn').click(function() {
            if ($(this).hasClass('active')) {
                // return; // Ignore click if the icon is already active
                tr.Pause();
                $(this).removeClass('active');
                $(this).html('🔇'); // Change to the desired icon when inactive
            } else {
                // Deactivate previously active icons
                $('.sound-btn.active').removeClass('active').html('🔇');

                // Activate the clicked icon
                $(this).addClass('active').html('🔊');
            }
        });

        var date = "<?php echo $date; ?>";
        var month = "<?php echo $month; ?>";
        var currDate = "<?php echo $current_date; ?>";
        var limit = 5;
        var action = 'active';
        var start = 0;

        function loadData(limit, start) {
            $.ajax({
                // URL changed from histroy-ajax.php
                url: "ajax/abcd.php",
                method: "POST",
                data: {
                    limit: limit,
                    start: start,
                    date: date,
                    month: month,
                    currDate: currDate
                },
                cache: false,
                success: function(data) {
                    $('#load-data').append(data);
                    if (data == '') {
                        action = 'active';
                    } else {
                        action = "inactive";
                    }
                }
            });
        }
        loadData(limit, start);
        if (action == 'inactive') {
            action = 'active';
            loadData(limit, start);
        }
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() > $(document).height() * 0.90 && action ==
                'inactive') {
                action = 'active';
                start = start + limit;
                setTimeout(function() {
                    loadData(limit, start);
                }, 300);

            }
        });

        /* code for filter data according to the event name start */

        $('.eventBtn').on('click', function() {
            let event_id = $(this).val();

            $('#loader').show(); // show loader
            $.ajax({
                // URL changed from history-ajax.php
                url: "ajax/abcd.php",
                type: "POST",
                data: {
                    event_id: event_id,
                    limit: limit,
                    start: start,
                    date: date,
                    month: month,
                    currDate: currDate
                },
                success: function(result) {
                    $('#loader').hide(); // hide loader
                    $('#load-data').html(result);
                }
            })
        })

        /* code for filter data according to the event name end */

        /* code for like button start */

        $("#likeBtn").on('click', function() {
            let likeBtn = $('#likeBtn').val();
            let date = "<?php echo $date; ?>";
            let currDate = "<?php echo $current_date; ?>";
            let month = "<?php echo $month; ?>";
            let user_email = "<?php echo $user_email; ?>";
            $.ajax({
                type: "POST",
                url: "ajax/feed_like_dislike.php",
                data: {
                    currDate: currDate,
                    month: month,
                    date: date,
                    user_email: user_email,
                    likeBtn: likeBtn
                },
                success: function(data) {
                    var data = JSON.parse(data);
                    $('.like').html(data.like_count);
                    $('.dislike').html(data.dislike_count);
                }
            });
            document.getElementById('likeBtn').disabled = true;
            document.getElementById('dislikeBtn').disabled = false;
        });

        /* code for like button end  */

        /* code for dislike button start */

        $("#dislikeBtn").on('click', function() {
            let dislikeBtn = $('#dislikeBtn').val();
            let date = "<?php echo $date; ?>";
            let currDate = "<?php echo $current_date; ?>";
            let month = "<?php echo $month; ?>";
            let user_email = "<?php echo $user_email; ?>";
            $.ajax({
                type: "POST",
                url: "ajax/feed_like_dislike.php",
                data: {
                    currDate: currDate,
                    month: month,
                    date: date,
                    user_email: user_email,
                    dislikeBtn: dislikeBtn
                },
                success: function(data) {
                    var data = JSON.parse(data);
                    $('.like').html(data.like_count);
                    $('.dislike').html(data.dislike_count);
                }
            });
            document.getElementById('dislikeBtn').disabled = true;
            document.getElementById('likeBtn').disabled = false;
        });

        /* code for dislike button end */

        /* ==== Code for share feed start === */

        $('#searchInput').on('keyup', function() {
            let searchInput = $(this).val(); // Store serahc box value

            if (searchInput.length > 3) {
                $('#searchResults').show();
                $.ajax({
                    url: "ajax/search_user.php",
                    type: "POST",
                    data: {
                        searchInput: searchInput
                    },
                    success: function(response) {
                        $('#searchResults').html(response);
                    }
                })
            } else {
                $('#searchResults').hide();
            }
        })
        /* ==== Code for share feed end === */

        /* ==== Code for share by ankit 29/08/2023 start === */
        $('#sendBtn').on('click', function() {
            let feedId = $(this).val(); // Store post id value
            let feedType = $('#feedTypeInput').val(); // Store serahc box value
            let reciver = $('#searchInput').val(); // Store serahc box value
            let sender = "<?php echo $_SESSION['loginuseremail']; ?>";

            $.ajax({
                url: "ajax/share_feed.php",
                type: "POST",
                data: {
                    reciver: reciver,
                    feedId: feedId,
                    sender: sender,
                    feedType: feedType,
                },
                success: function(response) {
                    // $('#searchResults').html(response);
                    closeShareModal();
                }
            })
        })
        /* ==== Code for share by ankit 29/08/2023 end === */

        // Handle click on li items to copy and paste into search input
        $(document).on('click', '#searchResults li', function() {
            var selectedValue = $(this).text();
            $('#searchInput').val(selectedValue);
            $('#searchResults').empty(); // Clear the results dropdown
            $('#searchResults').hide();
        });
    });
</script>
<?php
mysqli_close($conn);
?>
</body>

</html>