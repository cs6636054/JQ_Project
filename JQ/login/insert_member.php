
<?php include "../connect.php" ?>
<?php
    try { 
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
        $stmt = $pdo->prepare("INSERT INTO jq_users (username, password, fullname, email, phone) 
                                VALUES (?, ?, ?, ?, ?)");
                                
        $stmt->bindParam(1, $_POST["username"]);
        $stmt->bindParam(2, $_POST["password"]);
        $stmt->bindParam(3, $_POST["fullname"]);
        $stmt->bindParam(4, $_POST["email"]);
        $stmt->bindParam(5, $_POST["phone"]);
        
        $stmt->execute();
        
        $pid = $pdo->lastInsertId();
        $username = $_POST["username"];

    } catch (PDOException $e) { 
        echo "การเพิ่มข้อมูลล้มเหลว: " . $e->getMessage();
        exit(); 
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style1.css">
</head>
<body>
    <nav class="success-insert-member">
        สมัครสมาชิกสำเร็จ ยินดีต้อนรับคุณ <?= $username ?>
        <br>
        User ID ของคุณคือ: <?= $pid ?><br>

         <div class="gotohome">
            <a href="../Homepage.php">
                <button type="button">Go to Homepage</button>
            </a>
        </div>
    </nav>
</body>
</html>