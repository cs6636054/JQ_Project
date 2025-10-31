<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการร้านอาหารและค้นหาคิว</title> 
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <header>
        <?php include "NavBar.php"; ?>
        <?php include "connect.php"; ?>
    </header>
    <main>
        <div class="search-container">
            <form action="search.php" method="get" role="search">
                <input type="text" placeholder="Search.." name="search"> 
                <button type="submit">Submit</button>
            </form>
        </div>

        <section class="show-restaurant">
            <h2>RESTAURANT</h2> 
            
            <div class="show-res">
                <?php 
                    
                    $stmt = $pdo->prepare("
                        SELECT R.name, COUNT(Q.queue_id) AS C FROM jq_queues Q 
                        JOIN jq_restaurants R ON Q.restaurant_id = R.restaurant_id 
                        WHERE Q.status = 'waiting' AND R.name LIKE ? GROUP BY R.name;");
                        
                    if(!empty($_GET['search'])) {
                        $value = '%' . $_GET['search'] . '%';
                    } else {
                        $value = '%';
                    }
                    $stmt->execute([$value]);
                    
                    while ($row = $stmt->fetch()) { 
                        
                ?>
                        <a href="booking-page.php" class="link-booking-page">
                            <div class="show-img-info">     
                                <img src="img/<?=$row["name"]?>.jpg" alt="รูปภาพร้าน <?= htmlspecialchars($row["name"]) ?>" width="100px">
                                
                                <div class="show-info">
                                    <p class="name">ชื่อร้าน: <?= $row["name"]."<br>"?></p>
                                    <p class="num">จำนวนคิว: <?= $row["C"]?></p>
                                </div> 
                            </div> 
                        </a>
                <?php } ?>

            </div>
        </section>
    </main>

    </body>
</html>