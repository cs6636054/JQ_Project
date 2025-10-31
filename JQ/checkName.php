<?php
include "connect.php";

// ตรวจสอบว่ามีค่า username ถูกส่งมาหรือไม่
if (!isset($_GET["username"])) {
    echo "denied";
    exit();
}

$username = trim($_GET["username"]);

$sql = "SELECT COUNT(*) FROM jq_users WHERE username = :username";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    if ($count == 0) { //ไม่ซ้ำ
        echo "okay";
    } else {
        echo "denied"; //ซ้ำ
    }

} catch (PDOException $e) {
    error_log("Database error in checkName.php: " . $e->getMessage());
    echo "denied";
}
usleep(100000);

?>