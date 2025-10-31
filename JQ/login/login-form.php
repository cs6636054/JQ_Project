<form action="/~cs6636461/JQ/login/check-login.php" method="POST" id="loginForm"> 
    <section class="form-login">
        <label>Username:</label>
        <input type="text" name="username" placeholder="Enter your username" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <input type="submit" value="Login" id="submitButton"> 
        
        <div id="loadingMessage" style="display:none; text-align: center; padding: 10px;">
            กำลังตรวจสอบ... โปรดรอสักครู่
        </div>
    </section>
</form>

<script>
    // ใช้วิธี DOMContentLoaded เพื่อให้โค้ดทำงานทันทีที่โครงสร้าง HTML พร้อม
    document.addEventListener('DOMContentLoaded', function() {
        
        const submitButton = document.getElementById('submitButton');
        const loadingMessage = document.getElementById('loadingMessage');

        let loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function(event) {

            loadingMessage.style.display = 'block';
            submitButton.disabled = true;
            
        });
    });
</script>