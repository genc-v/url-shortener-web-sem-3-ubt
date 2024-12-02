<?php
session_start();
$error = '';
$success = '';

echo "<script>
   
    if (localStorage.getItem('authToken')) {
        
        window.location.href = 'dashboard.php'; 
    }
</script>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $fullName = $_POST['fullName'] ?? '';

    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    if (!preg_match($emailPattern, $email)) {
        $error = "Invalid email format";
    }

    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d!@#$%^&*()]{8,}$/';
    if (empty($error) && !preg_match($passwordPattern, $password)) {
        $error = "Password must be at least 8 characters, One uppercase letter and one number.";
    }

    if (empty($error)) {
        $url = "http://34.76.194.134:5284/api/User/signup";
        $data = json_encode(array(
            "email" => $email,
            "fullName" => $fullName,
            "password" => $password
        ));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $success = 'Registration successful! Redirecting to login.';
            header("Location: login.php");
            exit;
        } else {
            $error = 'Error during registration: ' . $response;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="register">

        <form class="register-form" id="registerForm" method="POST">
            <h2 class="">Create your account</h2>
            <p class="">Already have an account? <a href="login.php" class="">Login Here!</a></p>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullName" placeholder="Your Full Name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <?php if ($error): ?>
                <div class="message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="message"><?php echo $success; ?></div>
            <?php endif; ?>
            <button type="submit" class="">Register</button>

        </form>
    </div>
    <div class="secondary-container"><img src="assets/images/png/RegisterImage.png" alt="">
        <p>Analyze your links and QR Codes as easily as creating them</p>
    </div>
</body>

</html>