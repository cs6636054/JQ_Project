<?php 
    session_start(); 

    if (isset($_SESSION['username'])) {
        $user_username = $_SESSION['username'];
    } else {
        header("Location: login/login.php"); 
        exit();
    }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ผู้ใช้: <?= htmlspecialchars($user_username); ?></title> 
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="screen and (min-width: 481px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <header>
        <?php include "NavBar.php"; ?>
    </header>

    <main>
        <div class="greeting">
            <h1 style="text-align: center">Hello, <?php echo htmlspecialchars($user_username); ?></h1>
        </div>
        
        <br>
        <hr>
        
        <section id="Profile-info">
            <?php
                include "connect.php";
                try { 
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                    
                    $stmt = $pdo->prepare("SELECT username, fullname, email, phone FROM jq_users WHERE username = ? ");
                    $stmt->bindParam(1, $user_username);
                    $stmt->execute();
                    
                    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
            
                        echo "<h3>Username : " . htmlspecialchars($row["username"]) . "</h3>";
                        echo "<h3>Full Name : " . htmlspecialchars($row["fullname"]) . "</h3>";
                        echo "<h3>Email : " . htmlspecialchars($row["email"]) . "</h3>";
                        echo "<h3>Phone : " . htmlspecialchars($row["phone"]) . "</h3>";
                    } else {
                        echo "<p>ไม่พบข้อมูลผู้ใช้ในระบบ</p>";
                    }

                } catch (PDOException $e) { 
                    echo "การโหลดข้อมูลล้มเหลว: " . $e->getMessage();
                    exit(); 
                }
            ?>

            <div class="logout">
                <a href="login/logout.php">
                    <button type="button">ออกจากระบบ</button>
                </a>
            </div>
        </section>
        
        </main>
</body>
</html>