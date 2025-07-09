<?php
include 'db_config.php';
session_start();

// إذا كان المستخدم مسجل الدخول بالفعل، توجيهه إلى الصفحة الرئيسية


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من وجود جميع الحقول المطلوبة
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        header("Location: error.html");
        exit();
    }

    // تنظيف المدخلات
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // التحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: error.html");
        exit();
    }

    // التحقق من قوة كلمة المرور (اختياري)
    if (strlen($password) < 8) {
        header("Location: error.html?reason=weak_password");
        exit();
    }

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // استخدام Prepared Statement لإدراج المستخدم الجديد
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            // تسجيل الدخول تلقائياً بعد التسجيل
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            $conn->close();
            header("Location: home.html");
            exit();
        } else {
            // في حالة وجود بريد إلكتروني مكرر
            if ($conn->errno == 1062) {
                header("Location: error.html?reason=duplicate_email");
            } else {
                header("Location: error.html");
            }
            $stmt->close();
            $conn->close();
            exit();
        }
    } else {
        $conn->close();
        header("Location: error.html");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BIA - Sign Up</title>
    <link rel="icon" href="LOGO.jpg">
    <link rel="stylesheet" href="frem.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div id="loader"></div>

    <div class="main">  	
        <div class="signup">
            <form action="frem.php" method="post">
                <label for="chk" aria-hidden="true">SIGN UP</label>
                <input type="text" name="username" placeholder="Username" required minlength="3">
                <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" required minlength="8">
                <input type="checkbox"  class="show-password" onclick="togglePassword()" >
            </div>
                <button>REGISTER</button>
                <p style="color: white; text-align: center; margin-top: 20px;">
                    Already have an account? <a href="log.php" style="color: #6d44b8;">sing in here</a>
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