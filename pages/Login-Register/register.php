<?php
session_start();
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $fullName = $_POST['fullName'] ?? ''; // Get the full name from the form

    // API endpoint for registration
    $url = "http://34.76.194.134:5284/api/User/signup";

    // Prepare data for POST request
    $data = json_encode(array(
        "email" => $email,
        "fullName" => $fullName,
        "password" => $password
    ));

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Execute cURL request and capture the response
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log the response for debugging
    echo "<script>console.log('HTTP Code: {$http_code}');</script>";
    echo "<script>console.log('Response: " . addslashes($response) . "');</script>";

    if ($http_code == 200) {
        // Registration successful
        $success = 'Registration successful! You can now log in.';
        echo "<script>console.log('Registration successful!');</script>";
        // Optionally redirect to login page
        exit;
    } else {
        // Registration failed
        $error = 'There was an error with the registration process. Please try again.';
        echo "<script>console.log('Error: {$error}');</script>";
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
                <input type="email" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <?php if ($error): ?>
                <div class=""><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class=""><?php echo $success; ?></div>
            <?php endif; ?>
            <button type="submit" class="">Register</button>

        </form>
    </div>
    <div class="secondary-container"><img src="../../assets/images/png/RegisterImage.png" alt="">
        <p>Analyze your links and QR Codes as easily as creating them</p>
    </div>
</body>

</html>