<!DOCTYPE html>
<html lang="th">
<?php include "connect.php"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JQ</title>
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="(min-width: 481px) and (max-width: 900px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <header>
        <?php include "NavBar.php";?>
    </header>

    <main>
        <div class="search-container"> 
            <form role="search" action="search.php" method="get">
                <input type="text" placeholder="Search.." name="search"> 
                <button type="submit">search</button>
            </form>
        </div>
        
        <section class="show-restaurant">
            <h2>RESTAURANT</h2>
            
            <div class="show-res">
                <?php   
                $stmt = $pdo->prepare("SELECT R.name,COUNT(Q.queue_id) AS C FROM jq_queues Q JOIN jq_restaurants R ON Q.restaurant_id=R.restaurant_id WHERE Q.status='waiting' GROUP BY R.name;");
                $stmt->execute();
                while($row = $stmt->fetch()) { ?>
                
                <a href="booking-page.php?name=<?= $row["name"] ?>" class="link-booking-page">
                    <div class="show-img-info">    
                        <img src="img/<?=$row["name"]?>.jpg" alt="รูปภาพร้านอาหาร: <?= htmlspecialchars($row["name"]) ?>" width="100px">
                        
                        <div class="show-info">
                            <p class="name"><b>ชื่อร้าน: </b><?= $row["name"]."<br>"?></p>
                            <p class="num"><b>จำนวนคิว: </b><?= $row["C"]?></p>
                        </div> 
                    </div> 
                </a>
                <?php } ?>  
            </div>
        </section>
        
        </main>
    
    </body>
</html>