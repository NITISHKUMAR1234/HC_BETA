<?php
session_start(); //Session start
require_once('include/header.php');
require_once('include/config.php'); // include connection file
require('include/navbar.php'); // include top navbar 

if(empty($_SESSION['loginuseremail'])){
    echo '<h1>Unauthroized<h1>';
}

$user_email = $_SESSION['loginuseremail'];
$query = "SELECT * FROM tb_history INNER JOIN tb_saved ON tb_history.fd_id = tb_saved.fdPostId WHERE fdUserID = '".$user_email."';";
$querySet = @mysqli_query($conn, $query);
if(@mysqli_num_rows($querySet) > 0) 
{
?>
<div class="container" style="margin-top: 80px;">
    <?php while($post = @mysqli_fetch_assoc($querySet)) { 
        $id = $post['fd_id'];
        $title = $post['fd_title'];
        $sub_title = $post['fd_sub_title'];
        $histroy_img = $post['fd_img'];
        $discription = $post['fd_discription'];
        $date = $post['fd_uploaded_on'];
    ?>
        <div class="mb-3 row justify-content-center align-items-center" id="<?php echo $id; ?>" >
            <div class="col-md-8 card-container">
                <div class="border">
                    <div class="row">
                        <div class="col-md-12 headText">
                            <div class="px-2 mx-2" style="background-color:0;">
                                <div class="row">
                                    <div class="col-9">
                                        <h5 class="title"><b><?php echo $title; ?></b>
                                        </h5>
                                        <h5 class="sub-title">
                                            <?php echo $sub_title; ?></h5>
                                    </div>
                                    <div class="col-3">
                                        <span class="float-right mt-4"
                                            style="font-size: 12px;"><?php echo date("d/m/Y", strtotime($date)); ?>
                                            <i class="fa fa-calendar ml-1 w3-text-red" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>             
                        <div class="col-md-12 img-area w3-center">
                            <div class="main-img" style="background-color: #000;">
                                <img src="https://onespect.in.net/Calendar/beta/admin/data/image/<?php echo $histroy_img; ?>" alt="image">                            
                            </div>
                        </div>                   
                        <div class="p-2 m-2">
                            <div class="col-md-12 disc">
                                <div class="text-area">
                                    <p id="<?php echo $feedId; ?>"><?php echo $discription; ?></p>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-danger" onclick="DeleteSavedPost(<?php echo $id; ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php } 
else { ?>
<div class="container" style="margin-top: 80px;">
    <h1 class="text-secondary text-center">No Saved Post</h1>
</div>
<?php } ?>
<script>
    function DeleteSavedPost(postId) {
        userId = "<?php echo $user_email; ?>";
        postId = postId;

        $.ajax({
            url: "ajax/delete_saved_feed.php",
            type: "POST",
            data: {
                postId: postId,
                userId: userId
            },
            success: function(response) {
                document.getElementById(postId).remove();
            }
        })
    }
</script>
</body>
</html>