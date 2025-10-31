<?php
session_start();
include 'connect.php';

if (empty($_POST['restaurant_id']) || empty($_POST['number_of_people']) || empty($_POST['booking_time'])) {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบ'); window.history.back();</script>";
    exit();
}
$check = $pdo->prepare("SELECT * FROM jq_queues 
WHERE user_id = ? AND  status NOT IN ('completed', 'cancelled')");
$check->bindParam(1, $_SESSION['user_id']);
$check->execute();
$checkData = $check->fetch();
if ($checkData) {
    echo "
    <script>
        alert('คุณมีการจองคิวอยู่แล้ว ไม่สามารถจองซ้ำได้');
        window.location.href='Homepage.php';
    </script>";
    exit();
}


$stmt = $pdo->prepare("
    INSERT INTO jq_queues (restaurant_id, user_id, number_of_people, booking_time, status, created_at)
    VALUES (?, ?, ?, ?, 'waiting', NOW())
");

$stmt->bindParam(1, $_POST['restaurant_id']);
$stmt->bindParam(2, $_SESSION['user_id']);
$stmt->bindParam(3, $_POST['number_of_people']);

// แปลงเวลาเป็น MySQL DATETIME format
$booking_time = str_replace("T", " ", $_POST['booking_time']) . ":00";
$stmt->bindParam(4, $booking_time);

$success = $stmt->execute();
$queue_id = $pdo->lastInsertId();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <main>
        <?php
            if ($success) { ?>
            <div class="success-message">
                <h2>จองคิวสำเร็จ!</h2>
                <p>จำนวนคน: <?=  $_POST['number_of_people']; ?></p>
                <p>เวลาที่จอง: <?=  $booking_time; ?></p>
                <p>คิวที่: <?=  $queue_id; ?></p>
                <div class="note">
                    <strong>หมายเหตุ:</strong> กรุณามาถึงร้านก่อนเวลาที่จองอย่างน้อย 10 นาที
                </div>
                <div class="button-back-home">
                    <a href='Homepage.php'>กลับหน้าหลัก</a>
                </div>
            <?php } else { ?>
                <p>เกิดข้อผิดพลาด กรุณาลองใหม่</p>
            <?php } ?>
            </div>
    </main>
</body>
</html>


