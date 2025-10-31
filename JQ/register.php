<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Member</title> 
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="screen and (min-width: 481px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <script>
        var xmlHttp;

        function resetStatus() {
            var input = document.getElementById("username_input");
            input.className = ""; // ล้างสถานะเดิม
        }

        function checkUsername() {
            var input = document.getElementById("username_input");
            var username = input.value.trim();
            if(username === "") return;

            input.className = "thinking";

            xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                    if (xmlHttp.responseText.trim() === "okay") {
                        input.className = "approved";
                    } else {
                        input.className = "denied";
                        input.focus();
                        input.select();
                    }
                }
            };

            var url = "checkName.php?username=" + encodeURIComponent(username);
            xmlHttp.open("GET", url, true);
            xmlHttp.send();
        }
    </script>
</head>
<body>
    <header>
        <?php include "NavBar.php"; ?>
    </header>

    <main>
        <section class="insert-member">
            <form action="login/insert_member.php" method="post">
                <label for="username_input">Username : </label> 
                <input type="text" id="username_input" name="username" 
                       placeholder="Enter your username" pattern=".+" required
                       onblur="checkUsername()"  oninput="resetStatus()"><br>

                <label for="password_input">Password : </label>
                <input type="password" id="password_input" name="password" 
                       placeholder="Enter your password" pattern=".+" required><br>

                <label for="fullname_input">Fullname : </label>
                <input type="text" id="fullname_input" name="fullname" 
                       placeholder="Enter your fullname" pattern=".+" required><br>

                <label for="email_input">Email : </label>
                <input type="text" id="email_input" name="email" 
                       placeholder="xxx@xxx.com" pattern=".+@.+\.com" required><br>
                
                <label for="phone_input">Phone : </label>
                <input type="text" id="phone_input" name="phone" 
                       placeholder="Enter your phone number" pattern="^0\d{9}$" required><br><br>
                
                <input type="submit" value="submit">
            </form>
        </section>
    </main>
</body>
</html>
