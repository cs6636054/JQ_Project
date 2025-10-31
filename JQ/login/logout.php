<link rel="stylesheet" href="../style/style1.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
	session_start();

    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );

	session_destroy(); // ทำลาย session
?>
<main id="logout-succ">
    <span><h2 id="msg-header"></h2></span> <hr><br>
    <div class="logout-success">
        <a href="../Profile-Login.php">
            <button type="button" id="login-button">กดเพื่อเข้าสู่ระบบอีกครั้ง</button>
        </a>
    </div>
</main>

<script>
    const header = document.getElementById('msg-header');
    header.innerHTML = "ออกจากระบบสำเร็จ ขอบคุณที่ใช้บริการ";
</script>