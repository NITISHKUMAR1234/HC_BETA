<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $title = "";
    $discription = "";
    $image = "";
    $folder_path = "";
    if(isset($_GET['date']) && isset($_GET['title']) && isset($_GET['discription'])){        
        $title = $_GET['title'];
        $discription = $_GET['discription'];
        $image = $_GET['image'];
        if (file_exists("admin/data/calender_image/" . $image)) {
            $folder_path = "https://onespect.in.net/Calendar/beta/admin/data/calender_image/";
        }else{
            $folder_path = "https://onespect.in.net/Calendar/beta/admin/data/image/";
        }
    }else{
        $title = "Historical Events - Explore the Significant Events Throughout History";
        $discription = "Discover a comprehensive collection of historical events date-wise. Learn about important births, deaths, wars, inventions, and more that shaped the course of history.";
        $image = "Image/logo.jpeg";
        $folder_path = "";
    }
    ?>
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="Image/logo.jpeg">
    <meta name="description" content="<?php echo $discription; ?>">
    <meta name="keywords"
        content="historical events, date-wise events, important births, significant deaths, wars, inventions, historical milestones, key events in history">
    <meta name="author" content="historical-calendar.onespect.com">
    <meta property=“og:title” content="<?php echo $title; ?>" />
    <meta property="og:description" content="<?php echo $discription; ?>" />
    <meta property="og:image" content="<?php echo $folder_path.$image; ?>">
    <meta property="og:image:width" content="1280" />
    <meta property="og:image:height" content="640" />

    <!-- cdn for bootstrap 4 start -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- cdn for bootstrap 4 end -->

    <!-- code for custome js bundle start -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!-- code for custome js bundle end -->

    <!-- font awesome cdn start -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- font awesome cdn end -->
    <!-- cdn fro jquery start -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <!-- cdn fro jquery end -->

    <!-- sweetalert cdn start -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- sweetalert cdn end -->

    <!-- w3 css start -->

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- w3 css end -->

    <!-- css file start -->

    <link rel="stylesheet" href="css/style.css">
    <!-- <link rel="stylesheet" href="css/responsive.css"> -->

    <!-- css file end -->
</head>

<body>

    <script>
    /* function for remove special characters from the paragraphs */
    function removeSpecialCharacters(paragraph) {
        // Regular expression to match special characters except dot, comma, and question mark
        var regex = /[^a-zA-Z0-9.,? ]/g;

        // Remove special characters using replace()
        var cleanParagraph = paragraph.value.replace(regex, ' ');

        paragraph.value = cleanParagraph;
    }
    </script>