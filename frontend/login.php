<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <meta name="description" content="Sign up for the UBT URL Shortener and start creating short links.">
    <meta name="keywords" content="URL shortener, UBT, link shortening, custom links, short URLs">
    <link rel="stylesheet" href="signup.css">
    <link rel="icon" type="image/png" href="assets/images/png/favicon.ico">
</head>

<body>

    <script>
        if (localStorage.getItem('authToken')) {
            window.location.href = 'index.php';
        }

        async function handleRegister(event) {
            event.preventDefault();

            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

            if (password !== confirmPassword) {
                document.getElementById('error-message').innerText = "Passwords do not match!";
                return;
            }

            const response = await fetch('http://34.76.194.134:5284/api/User/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            if (response.ok) {
                const data = await response.json();
                localStorage.setItem('authToken', data.token);
                window.location.href = 'index.php';
            } else {
                document.getElementById('error-message').innerText = 'Registration failed. Please try again.';
            }
        }
    </script>

    <div class="register">
        <form class="register-form" id="registerForm" onsubmit="handleRegister(event)">
            <h2>Sign Up</h2>
            <p class="pt-6">Already have an account? <a href="login.html">Log in here!</a></p>
            <div class="form-group">
                <label><b>Email</b></label>
                <input type="email" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label><b>Password</b></label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label><b>Confirm Password</b></label>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="message" id="error-message"></div>
            <button type="submit">Register</button>
        </form>
    </div>
    <div class="secondary-container">
        <img src="assets/images/png/signupImage.png" alt="">
        <p>Join Bytely and start shortening your links easily.</p>
    </div>

</body>

</html>
