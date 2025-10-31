<?php
    session_start();
    include 'connect.php';
    

    // ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
    if (!isset($_SESSION['username'])) {
        header("Location: Profile.php");
        exit();
    }

    $restaurant_name = isset($_GET['name']) ? $_GET['name'] : '';
    
    $stmt = $pdo->prepare("SELECT restaurant_id FROM jq_restaurants WHERE name = ?");
    $stmt->bindParam(1, $restaurant_name);
    $stmt->execute();
    $restaurant = $stmt->fetch();
    ?>
<!DOCTYPE html>
<html lang="th"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking: <?= htmlspecialchars($restaurant_name) ?></title> 
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <header>
        <?php include 'NavBar.php'; ?>
    </header>

    <main>
        <h1 id="booking-title">จองคิวร้าน <?= htmlspecialchars($restaurant_name) ?></h1>
        <hr>
        
        <section class="booking-page">
            
            <form action="process-booking.php" method="POST">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? '' ?>">
                <input type="hidden" name="restaurant_id" value="<?= $restaurant['restaurant_id'] ?? '' ?>">

                <div class="select-people">
                    <label for="num_people">จำนวนคนที่มา:</label>
                    <input type="number" id="num_people" name="number_of_people" min="1" max="6" required>
                </div>

                <div class="time-booking">
                    <label for="booking_time">เวลาที่ต้องการจองคิว:</label>
                    <input type="datetime-local" id="booking_time" name="booking_time" required>
                </div>

                <input type="submit" value="confirm Booking">
            </form>
            
        </section>
        
    </main>
    
    </body>
</html>