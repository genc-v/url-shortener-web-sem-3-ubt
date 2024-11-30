<?php
session_start();
$error = '';
$success = '';

$email = $_POST['email'] ?? '';
$fullName = $_POST['fullName'] ?? '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'] ?? '';

    // Validate email
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    if (!preg_match($emailPattern, $email)) {
        $error = "Invalid email format";
    }

    // Validate password
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (empty($error) && !preg_match($passwordPattern, $password)) {
        $error = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
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
        href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <style>
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input {
            width: 100%;
            padding-right: 40px; /* Space for the icon */
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: #888;
        }

        .password-toggle:hover {
            color: #333;
        }
    </style>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = '🙈'; // Change icon to "hide"
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = '👁️'; // Change icon to "show"
            }
        }
    </script>
</head>

<body>
    <div class="register">
        <form class="register-form" id="registerForm" method="POST">
            <h2>Create your account</h2>
            <p>Already have an account? <a href="login.php">Login Here!</a></p>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullName" placeholder="Your Full Name" value="<?php echo htmlspecialchars($fullName); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" placeholder="Email address" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span id="togglePasswordIcon" class="password-toggle" onclick="togglePassword()">👁️</span>
                </div>
            </div>
            <?php if ($error): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="message success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <button type="submit">Register</button>
        </form>
    </div>
    <div class="secondary-container">
        <img src="../../assets/images/png/RegisterImage.png" alt="">
        <p>Analyze your links and QR Codes as easily as creating them</p>
    </div>
</body>

</html>
