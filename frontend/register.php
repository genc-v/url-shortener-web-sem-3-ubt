<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="description" content="UBT URL Shortener is the easiest way to create short and shareable links. Fast, secure, and reliable URL shortening for all your needs.">
    <meta name="keywords" content="URL shortener, UBT, link shortening, custom links, short URLs">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebApplication",
      "name": "UBT URL Shortener",
      "url": "http://bytely.xyz",
      "description": "A fast and secure tool to shorten URLs for UBT students and beyond."
    }
    </script>
    <link rel="stylesheet" href="signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/png/favicon.ico">
</head>

<body>

    <script>
        if (localStorage.getItem('authToken')) {
            window.location.href = 'index.php';
        }

        async function handleLogin(event) {
            event.preventDefault();

            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;

            const response = await fetch('http://34.76.194.134:5284/api/User/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            if (response.ok) {
                const token = await response.text();
                localStorage.setItem('authToken', token);
                window.location.href = 'index.php';
            } else {
                document.getElementById('error-message').innerText = 'There was an error with the login process. Please try again.';
            }
        }
    </script>

    <div class="register">
        <form class="register-form" id="registerForm" onsubmit="handleLogin(event)">
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
            <div class="message" id="error-message"></div>
            <button type="submit" class="">Log In</button>
        </form>
    </div>
    <div class="secondary-container">
        <img src="assets/images/png/loginImage.png" alt="">
        <p>Power your links, QR Codes, and landing pages with Bytely's Connections Platform.</p>
    </div>

</body>

</html>
