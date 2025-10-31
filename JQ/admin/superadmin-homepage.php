<?php
session_start();
include "../connect.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../Profile-Login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];

// นับจำนวนคิวในร้านตัวเอง
$stmt = $pdo->prepare("SELECT * FROM jq_queues WHERE restaurant_id = ?");
$stmt->bindParam(1, $restaurant_id);
$stmt->execute();
$rows = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="style-admin.css">
</head>
<body>
    <header>
        <h2>Welcome, <?= $_SESSION['admin_name'] ?></h2>
    </header>
    <main>
    <div class="dashboard-cards">
        <div class="card"><a href="manage-users.php">manage users</a></div>
        <div class="card"><a href="manage-restaurant.php">manage restaurant</a></div>
        <div class="card"><a href="../stat.php">view stats</a></div>   
        <div class="card"><a href="../login/logout.php">logout</a></div>
    </div>
</main>
</body>
</html>
