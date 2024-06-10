<?php
session_start();
require_once('include/config.php');
require_once('include/header.php');
?>
<script>
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
</script>
<?php

function httpPost($url, $data)
{
    $postdata = http_build_query($data);
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
   return file_get_contents($url, false, $context);
}

if(isset($_POST['loginBtn']))
{
    if(isset($_POST['email']) && isset($_POST['password']))
    {
        $eMail = mysqli_real_escape_string($conn,$_POST['email']);
        $pAwSSword = mysqli_real_escape_string($conn,$_POST['password']);      
        if (filter_var($eMail, FILTER_VALIDATE_EMAIL)) {
           $userSelect = "SELECT `fd_email` FROM `tb_users` WHERE fd_email='$eMail'";          
            $userQueryRun = mysqli_query($conn,$userSelect);
            if(mysqli_num_rows($userQueryRun)>0)
            {
                $passEncode = base64_encode($pAwSSword);
                $query = "SELECT * FROM `tb_users` WHERE fd_email='$eMail' AND fd_password = '$passEncode'";
                $checkUserSqlPass = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($checkUserSqlPass)>0)
                {
                    $userPassLoginRow = mysqli_fetch_assoc($checkUserSqlPass);                     
                    $_SESSION['loginuseremail'] = $userPassLoginRow['fd_email'];
                    
                    if(isset($_COOKIE['redirectURL'])) {
                        $cookie = $_COOKIE['redirectURL'];
                        $redirectURL = 'index.php'.$_COOKIE['redirectURL'];               
                             
                        echo "<script>setCookie('$cookie','$redirectURL',-1);
                        window.location.href='$redirectURL'</script>";
                      } else {
                        echo "<script>window.location.href='index.php'</script>";
                      }                    // echo "<script>
                }
                else
                {
                    echo '<body><script> swal({
                        title: "Login Failed",
                        text: "Somthing Went Wrong! Please Try Again",
                        icon: "error",
                        dangerMode: true,
                      })
                    .then((value) => {
                        window.location.href = `user_login.php`;
                    }); </script></body>';
                }
            }
            else
            {
                $passEncode = base64_encode($pAwSSword);
                $registerNewQuery = "INSERT INTO tb_users (`fd_email`,`fd_password`) VALUES ('$eMail','$passEncode')";
                $registerNewUser = mysqli_query($conn,$registerNewQuery);
                   if($registerNewUser)
                   {
                    $_SESSION['loginuseremail'] = $eMail;                    
                    echo "<script>
                        window.location.href = 'index.php';
                    </script>";
                   }
            }
        }
    }
}
?>  