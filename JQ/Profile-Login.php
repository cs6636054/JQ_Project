<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title> 
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="(min-width: 481px) and (max-width: 900px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <header>
        <?php include "NavBar.php"; ?>
    </header>

    <main>
        <div class="login-box"> 
            
            <?php
                include "login/login-form.php";
            ?>

            <div class="register">
                <a href="register.php">
                    <button type="button">register</button>
                </a>
            </div>
        </div>
    </main>
    
    </body>
</html>