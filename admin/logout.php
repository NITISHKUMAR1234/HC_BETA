<?php
session_start();
session_start();
session_unset();
unset($_SESSION["email"]);
session_destroy();

header("location:index.php");
exit();