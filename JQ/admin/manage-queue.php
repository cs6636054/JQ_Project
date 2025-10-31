<?php
session_start();
include "../connect.php";
if (!isset($_SESSION['restaurant_id'])) die("ไม่พบร้านอาหาร");

$restaurant_id = $_SESSION['restaurant_id'];

if (isset($_GET['status']) && isset($_GET['queue_id'])) {
    $stmt = $pdo->prepare("UPDATE jq_queues SET status = ? WHERE queue_id = ?");
    $stmt->execute([$_GET['status'], $_GET['queue_id']]);
}

$stmt = $pdo->prepare("
    SELECT q.queue_id, u.fullname, q.number_of_people, q.booking_time, q.status
    FROM jq_queues q
    JOIN jq_users u ON q.user_id = u.user_id
    WHERE q.restaurant_id = ?
    ORDER BY q.booking_time ASC
");
$stmt->execute([$restaurant_id]);
$queues = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>manage queues</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>

    <div class="manage-queue-header">
        <a href="Admin-homepage.php">back</a>
    </div>
    <header>
    <div class="manage-queue">
            <h2> Manage Your Restaurant Queue</h2>
        </div>
    </header>
    <main>
        <table class="restaurant-table">
            <tr>
                <th>Customer Name</th>
                <th>Number of People</th>
                <th>Time</th>
                <th>Status</th>
                <th>Change Status</th>
            </tr>
            <?php foreach ($queues as $q): ?>
            <tr>
            <td><?= $q['fullname'] ?></td>
            <td><?= $q['number_of_people'] ?></td>
            <td><?= $q['booking_time'] ?></td>
            <td><?= $q['status'] ?></td>
            <td class="queue-actions">
            <a href="?status=serving&queue_id=<?= $q['queue_id'] ?>">serving</a> 
            <a href="?status=completed&queue_id=<?= $q['queue_id'] ?>">complete</a> 
            <a href="?status=canceled&queue_id=<?= $q['queue_id'] ?>">canceled</a>
            </td>
            </tr>
            <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>
