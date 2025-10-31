<?php
session_start();
include 'connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['username'])) {
    header("Location: Profile-Login.php");
    exit();
} else {
    header("Location: Profile-info.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="(min-width: 481px) and (max-width: 900px)">
</head>
<body>
    <header>
        <?php 
            include "NavBar.php"; 
        ?>
    </header>

</body>
</html>