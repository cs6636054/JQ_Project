<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../connect.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../Profile-Login.php");
    exit();
}

$restaurant_id = $_SESSION['restaurant_id'];

// นับจำนวนคิวทั้งหมด
$stmt = $pdo->prepare("SELECT COUNT(queue_id) AS total FROM jq_queues WHERE restaurant_id = ?");
$stmt->execute([$restaurant_id]);
$total_queues = $stmt->fetch();

// นับจำนวนคิวที่รออยู่
$stmt = $pdo->prepare("SELECT COUNT(queue_id) AS waiting FROM jq_queues WHERE restaurant_id = ? AND status = 'waiting'");
$stmt->execute([$restaurant_id]);
$waiting_queues = $stmt->fetch();

// ยอดจองต่อวัน
$stmt = $pdo->prepare("
    SELECT DATE(booking_time) AS day, SUM(number_of_people) AS total_people
    FROM jq_queues
    WHERE restaurant_id = ?
    GROUP BY DATE(booking_time)
    ORDER BY day ASC
");
$stmt->execute([$restaurant_id]);
$daily = $stmt->fetchAll(PDO::FETCH_ASSOC);
$daily_labels = [];
$daily_values = [];
foreach ($daily as $row){
    $daily_labels[] = $row['day'];
    $daily_values[] = $row['total_people'];
}

// จำนวนคนต่อชั่วโมง
$stmt = $pdo->prepare("
    SELECT HOUR(booking_time) AS hour, SUM(number_of_people) AS total_people
    FROM jq_queues
    WHERE restaurant_id = ?
    GROUP BY HOUR(booking_time)
    ORDER BY hour ASC
");
$stmt->execute([$restaurant_id]);
$hourly = $stmt->fetchAll(PDO::FETCH_ASSOC);
$hour_labels = [];
$hour_values = [];
foreach ($hourly as $row){
    $hour_labels[] = $row['hour'] . ":00";
    $hour_values[] = $row['total_people'];
}

// สถานะคิว
$stmt = $pdo->prepare("
    SELECT status, COUNT(*) AS count
    FROM jq_queues
    WHERE restaurant_id = ?
    GROUP BY status
");
$stmt->execute([$restaurant_id]);
$status = $stmt->fetchAll(PDO::FETCH_ASSOC);
$status_labels = [];
$status_values = [];
foreach ($status as $row){
    $status_labels[] = ucfirst($row['status']);
    $status_values[] = $row['count'];
}
?>



<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Admin </title>
<link rel="stylesheet" href="style-admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Welcome, <?= $_SESSION['admin_name'] ?></h2>
<div class="manage-container">
    <div class="dashboard-cards">
        <div class="card">คิวทั้งหมด: <?= $total_queues['total'] ?></div>
        <div class="card">คิวที่รออยู่: <?= $waiting_queues['waiting'] ?></div>
        <div class="card"><a href="manage-queue.php">จัดการคิว</a></div>
        <div class="card"><a href="../login/logout.php">ออกจากระบบ</a></div>
    </div>
    
<h3>ยอดจองต่อวัน</h3>
<canvas id="dailyChart" width="600" height="300"></canvas>

<h3>จำนวนคนต่อช่วงเวลา</h3>
<canvas id="hourlyChart" width="600" height="300"></canvas>

<h3>สถานะ</h3>
<canvas id="statusChart" width="400" height="400"></canvas>

<script>
// 1. Daily bookings
new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($daily_labels) ?>,
        datasets: [{
            label: 'ยอดจองต่อวัน (จำนวนคน)',
            data: <?= json_encode($daily_values) ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: { responsive: true }
});

// 2. Hourly bookings
new Chart(document.getElementById('hourlyChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($hour_labels) ?>,
        datasets: [{
            label: 'จำนวนคนต่อช่วงเวลา',
            data: <?= json_encode($hour_values) ?>,
            backgroundColor: 'rgba(255, 159, 64, 0.7)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// 3. Queue status
new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($status_labels) ?>,
        datasets: [{
            data: <?= json_encode($status_values) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)'
            ]
        }]
    },
    options: { responsive: true }
});
</script>
</div>
</body>
</html>
