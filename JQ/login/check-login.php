<link rel="stylesheet" href="../style/style1.css">
<?php
include "../connect.php";
session_start();

$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';

// ตรวจสอบผู้ใช้ปกติ
$stmt = $pdo->prepare("SELECT * FROM jq_users WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);
$user = $stmt->fetch();

if ($user) {
    // login สำเร็จสำหรับ user
    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["fullname"] = $user["fullname"];
    header("Location: ../Homepage.php");
    exit();
} else {
    // ตรวจสอบแอดมิน
    $stmt = $pdo->prepare("SELECT * FROM jq_admins WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["admin_name"] = $admin["fullname"];
        $_SESSION["restaurant_id"] = $admin["restaurant_id"];
        $_SESSION["role"] = $admin["role"];

        if ($admin["role"] === "superadmin") {
            header("Location: ../admin/superadmin-homepage.php");
        } else {
            header("Location: ../admin/Admin-homepage.php");
        }
        exit();
    } else {
        
        echo '<h2 style="margin-top: 20px; text-align: center;">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง<br></h2><hr><br>';
        echo '<section class="login-again">';
        echo '    <a href="../Profile-Login.php">'; 
        echo '        <button type="button">เข้าสู่ระบบใหม่อีกครั้ง</button>'; 
        echo '    </a>';
        echo '</section>';
        exit();
    }
}
?>
