<?php
session_start();
include "../connect.php";


if ($_SESSION['role'] !== 'superadmin') {
    die("superadmin only !!");
}

if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO jq_restaurants (name, location, category, contact_info) VALUES (?,?,?,?)");
    $stmt->bindParam(1, $_POST['name']);
    $stmt->bindParam(2, $_POST['location']);    
    $stmt->bindParam(3, $_POST['category']);
    $stmt->bindParam(4, $_POST['contact']);
    $stmt->execute();
    header("Location: manage-restaurant.php"); // refresh หน้าเพื่อเห็นข้อมูลใหม่
    exit();
}


if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM jq_restaurants WHERE restaurant_id = ?");
    $stmt->bindParam(1, $_GET['delete']);
    $stmt->execute();
    header("Location: manage-restaurant.php"); // refresh หลังลบ
    exit();
}


$restaurants = $pdo->query("SELECT * FROM jq_restaurants")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Manage Restaurants</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>
    <div class="manage-queue-header">
    <a href="superadmin-homepage.php">back</a>
    </div>
    <header>
        <h2>Manage Restaurants</h2>
    </header>
    
<section>
    <div class="table-restaurant-form">
        <form method="POST">
            <h3 for="name">name:</h3><input name="name" placeholder="ชื่อร้าน" required>
            <h3 for="location">location:</h3><input name="location" placeholder="สถานที่">
            <h3 for="category">category:</h3><input name="category" placeholder="ประเภทอาหาร">
            <h3 for="contact">contact:</h3><input name="contact" placeholder="เบอร์โทร">
            <button type="submit" name="add">เพิ่มร้าน</button>
        </form>
    </div>

    <table class="restaurant-table">
        <tr>
            <th>id</th>
            <th>name</th>
            <th>category</th>
            <th>delete</th>
        </tr>

        <?php foreach ($restaurants as $restaurant): ?>
        <tr>
            <td><?= $restaurant['restaurant_id'] ?></td>
            <td><?= $restaurant['name'] ?></td>
            <td><?= $restaurant['category'] ?></td>
            <td>
                <a href="?delete=<?= $restaurant['restaurant_id'] ?>"
                onclick="return confirm('Comfirm delete  restaurant => <?= $restaurant['name'] ?> ?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>
</section>
</body>
</html>
