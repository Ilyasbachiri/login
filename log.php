<?php
include 'db_config.php'; // تضمين ملف الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من وجود البيانات المطلوبة
    if (empty($_POST['email']) || empty($_POST['password'])) {
        header("Location: error.html");
        exit();
    }

    // تنظيف المدخلات
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // استخدام Prepared Statement لتجنب هجمات SQL Injection
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // التحقق من كلمة المرور باستخدام password_verify
            if (password_verify($password, $user['password'])) {
                // بدء الجلسة وتخزين بيانات المستخدم
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                header("Location: home.html");
                exit();
            }
        }
        
        // في حالة فشل المصادقة
        header("Location: error.html");
        exit();
    } else {
        // في حالة حدوث خطأ في الاستعلام
        header("Location: error.html");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>BIA - sing in</title>
    <link rel="icon" href="LOGO.jpg">
    <link rel="stylesheet" href="frem.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div id="loader"></div>

    <div class="main">  	
        <div class="signup">
            <form action="log.php" method="post">
                <label for="chk" aria-hidden="true">SIGN IN</label>
                <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
                 <input type="password" name="password" id="password" placeholder="Password" required minlength="8">
                 <input type="checkbox"  class="show-password" onclick="togglePassword()">
                 
           </div>
                <button>LOGIN</button>
                <p style="color: white; text-align: center; margin-top: 20px;">
                    Already have an account? <a href="frem.php" style="color: #6d44b8;">sing up here</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        var loud = document.getElementById("loader");
        window.addEventListener("load", function() {
            loud.style.display = "none";
        });
        function togglePassword() {
    const passwordInput = document.getElementById('password');
    const showPassword = document.querySelector('.show-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        showPassword.textContent = 'Hide';
    } else {
        passwordInput.type = 'password';
        showPassword.textContent = 'Show';
    }
}
    </script>
    
</body>
</html>