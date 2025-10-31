<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการแจ้งเตือน</title> <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="screen and (min-width: 481px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <header>
        <?php 
            include 'connect.php';
            include "NavBar.php";
            session_start();

            $user_username = $_SESSION["username"] ?? null; 
            $current_user_id = $_SESSION["user_id"] ?? null; 
        
            // ตรวจสอบการล็อกอิน (ใช้ user_id ในการตรวจสอบหลัก)
            if (!isset($current_user_id) || !isset($user_username)) {
                header("Location: Profile-Login.php");
                exit();
            }
        ?>
    </header>

    <main>
        <section class="notification-list">
            <h1 style="text-align: center;">รายการแจ้งเตือนของคุณ</h1>
            <hr>
            
            <?php
            try { 
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                
                $sql = "SELECT message, notify_time FROM jq_notifications 
                        WHERE user_id = ? ORDER BY notify_time DESC; ";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $current_user_id, PDO::PARAM_INT);
                $stmt->execute();
                    
                // แสดงผลลัพธ์
                if ($stmt->rowCount() > 0) { 
                    // ใช้ <article> หรือ <div>
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                        
                        $display_time = date("d-M-Y H:i", strtotime($row["notify_time"]));
                        ?>
                        
                        <div class="notification-item"> 
                            <p class="notify-message"><?= htmlspecialchars($row["message"]) ?></p>
                            <p class="notify-time"><?= htmlspecialchars($display_time) ?></p>
                        </div>
                        
                    <?php 
                    }
                } else { 
                    ?>
                    <div style="text-align: center; padding: 20px;">
                        <p>คุณไม่มีการแจ้งเตือนใหม่</p>
                    </div>
                <?php }

            } catch (PDOException $e) { 
                echo "<div style='color: red; text-align: center;'>**เกิดข้อผิดพลาดในการโหลดข้อมูล:** " . $e->getMessage() . "</div>";
            }
            ?>
        </section>
    </main>
    
    </body>
</html>