
<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
‎    // تنظيف البيانات المدخلة
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // تشفير كلمة المرور

‎    // استخدام Prepared Statement لحماية البيانات
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: log.php"); // توجيه إلى الصفحة الرئيسية عند النجاح
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: error.html"); // توجيه إلى صفحة الخطأ عند الفشل
            exit();
        }
    } else {
        $conn->close();
        header("Location: error.html"); // توجيه إلى صفحة الخطأ في حال حدوث خطأ في الاستعلام
        exit();
    }
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>BIA</title>
	<link rel="icon" href="LOGO.jpg">
    <link rel="stylesheet" href="frem.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>

	<div id="loader"></div>

	<div class="main">  	
		

			<div class="signup">
				<form action="frem.php" method="post">
					<label for="chk" aria-hidden="true">SING UP</label>
					<input type="text" name="username" placeholder="Username" required>
					<input type="email" name="email" placeholder="Email" required>
					<input type="password" name="password" placeholder="Password" required>
					<button>CONFIRMATION</button>
				</form>
			</div>
            



	<script>
        var loud =document.getElementById("loader");
        window.addEventListener("load",function(){
            loud.style.display="none";
        });
    </script>
	
</body>
</html>