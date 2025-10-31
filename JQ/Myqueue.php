<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>รายการคิวของคุณ</title> <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="screen and (min-width: 481px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <header>
        <?php
            include "NavBar.php";
        ?>
    </header>

    <main>
        <?php
            include "connect.php";
            session_start();
            
            $current_user_username = $_SESSION["username"] ?? null; 
            
            if (!isset($current_user_username)) {
                header("Location: Profile-Login.php");
                exit();
            }
        ?>
        
        <h1 id="listqueue" style="text-align: center;"></h1>
        <hr>
        
        <section class="myqueue"> 
            <?php
                try { 
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                    
                    $sql = "SELECT R.name AS restaurant_name, Q_USER.queue_id,Q_USER.booking_time,
                            (    SELECT COUNT(Q_WAITING.queue_id) 
                                 FROM jq_queues Q_WAITING
                                 WHERE Q_WAITING.restaurant_id = Q_USER.restaurant_id 
                                     AND Q_WAITING.status = 'waiting'
                                     AND (    Q_WAITING.booking_time < Q_USER.booking_time 
                                     OR (     Q_WAITING.booking_time = Q_USER.booking_time 
                                             AND Q_WAITING.queue_id < Q_USER.queue_id ))
                            ) AS queues_before
                        FROM jq_queues Q_USER JOIN jq_restaurants R ON Q_USER.restaurant_id = R.restaurant_id
                        JOIN jq_users U ON Q_USER.user_id = U.user_id 
                        WHERE U.username = ?  AND Q_USER.status = 'waiting' 
                        ORDER BY 
                            Q_USER.booking_time ASC, 
                            Q_USER.queue_id ASC 
                        LIMIT 1; 
                    ";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(1, $current_user_username); 
                    $stmt->execute();
                        
                    if ($row = $stmt->fetch()) { 
                        // แยกวันที่และเวลาเพื่อแสดงผล
                        $datetime_parts = explode(' ', $row["booking_time"]);
                        $display_date = $datetime_parts[0];
                        $display_time = substr($datetime_parts[1], 0, 5); // แสดงแค่ HH:MM
                        ?>
                        <a href="booking-page.php?name=<?= urlencode($row["restaurant_name"]) ?>" class="link-booking-page"> 
                            <div class="show-info">
                                <p class="name">ชื่อร้าน: <?= htmlspecialchars($row["restaurant_name"]) ?></p>
                                <p class="num">วันที่: <?= htmlspecialchars($display_date) ?> เวลา: <?= htmlspecialchars($display_time) ?></p>
                                <p class="queue">จำนวนคิวก่อนหน้า: <?= htmlspecialchars($row["queues_before"]) ?> คิว</p>
                            </div> 
                        </a>
                        <?php 
                    } else { 
                        ?>
                        <div class="show-info">
                            <p class="runq">ไม่มีคิวที่กำลังรออยู่ในขณะนี้</p>
                        </div>
                        <?php 
                    }

                } catch (PDOException $e) { 
                    // แสดงข้อผิดพลาดฐานข้อมูลกรณีที่ไม่สามารถเชื่อมต่อได้
                    echo "การโหลดข้อมูลล้มเหลว : " . $e->getMessage();
                    exit(); 
                }
            ?>
        </section>
    </main>
    <script>
        const queue = document.getElementById('listqueue');
        queue.innerHTML = "รายการ Queue ของคุณ";
    </script>
    
    </body>
</html>