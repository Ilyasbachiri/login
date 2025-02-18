<?php
include 'db_config.php'; // تضمين ملف الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // تأمين المدخلات لتجنب هجمات SQL Injection
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        header("Location: home.html");
    } else {
        header("Location: error.html");
    }
}

$conn->close();
exit();
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
				<form action="log.php" method="post">
					<label for="chk" aria-hidden="true">SING IN</label>
					<input type="email" name="email" placeholder="Email" required>
					<input type="password" name="password" placeholder="Password" required>
					<button>LOGIN</button>
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