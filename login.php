<?php
session_start();
$error = '';
$success = '';

echo "<script>
   
    if (localStorage.getItem('authToken')) {
        window.location.href = 'index.php'; 
    }
</script>";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? ''; // Get the full name from the form


    $url = "http://34.76.194.134:5284/api/User/login";


    $data = json_encode(array(
        "email" => $email,
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

        $success = 'Login succsesful!';

        echo "<script>
        window.location.href = 'index.php'; 
        localStorage.setItem('authToken', '$response');
        </script>";


        exit;
    } else {

        $error = 'There was an error with the login process. Please try again.';
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
            <h2 class="">Log in and start sharing</h2>
            <p class="pt-6">Dont have an account? <a href="register.php" class="">Sign Up here!</a></p>
            <div class="form-group">
                <label><b>Email</b></label>
                <input type="text" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label><b>Password</b></label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <?php if ($error): ?>
                <div class="message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="message"><?php echo $success; ?></div>
            <?php endif; ?>
            <button type="submit" class="">Log In</button>
        </form>
    </div>
    <div class="secondary-container">
        <img src="assets/images/png/loginImage.png" alt="">
        <p>Power your links, QR Codes, and landing pages with Bytely's Connections Platform.</p>
    </div>
</body>

</html>