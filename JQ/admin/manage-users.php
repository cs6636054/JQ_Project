<?php
session_start();
include "../connect.php";

// ให้เฉพาะ superadmin เข้าถึงได้
if ($_SESSION['role'] !== 'superadmin') {
    die("เฉพาะ superadmin เท่านั้น");
}


if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    
    if ($user_id == $_SESSION['user_id']) {
        echo "<script>alert('ไม่สามารถลบบัญชีของคุณเองได้');</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM jq_users WHERE user_id = ?");
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        header("Location: manage-users.php"); 
        exit;
    }
}


$users = $pdo->query("SELECT * FROM jq_users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>
    <div class="manage-queue-header">
        <a href="superadmin-homepage.php">back</a>
    </div>
    <head>
        <div class="head">
            <h2> Manage Users</h2>
        </div>
    </head>
    <section>
        <table class="user-table">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Fullname</th>
            <th>Email</th>
            <th>ลบผู้ใช้</th>
        </tr>

        <?php foreach ($users as $u): ?>
        <tr>
        <td><?= $u['user_id'] ?></td>
        <td><?= $u['username'] ?></td>
        <td><?= $u['fullname'] ?></td>
        <td><?= $u['email'] ?></td>
        <td>
            <?php if ($u['user_id'] != $_SESSION['user_id']){ ?>
                <a href="manage-users.php?user_id=<?= $u['user_id'] ?>"
                onclick="return confirm('ยืนยันการลบผู้ใช้ <?= $u['username'] ?> ?')">Delete</a>
            <?php } ?>
        </td>
        </tr>
        <?php endforeach; ?>

        </table>
    </section>
</body>
</html>
