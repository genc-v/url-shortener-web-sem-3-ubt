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
        <form class="register-form" id="loginForm">
            <h2>Log in and start sharing</h2>
            <p class="pt-6">Don't have an account? <a href="register.php">Sign Up here!</a></p>
            <div class="form-group">
                <label><b>Email</b></label>
                <input type="text" id="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label><b>Password</b></label>
                <input type="password" id="password" placeholder="Password" required>
            </div>
            <div id="message" class="message"></div>
            <button type="submit">Log In</button>
        </form>
    </div>
    <div class="secondary-container">
        <img src="assets/images/png/signupImage.png" alt="">
        <p>Join Bytely and start shortening your links easily.</p>
    </div>

    <script>
        // Redirect if already logged in
        if (localStorage.getItem('authToken')) {
            window.location.href = 'index.html';  // Redirect to index.html instead of index.php
        }

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const messageElement = document.getElementById('message');

            // Validate input
            if (!email || !password) {
                messageElement.textContent = 'Please enter both email and password.';
                messageElement.style.color = 'red';
                return;
            }

            // Make the API request to login
            const loginData = {
                email: email,
                password: password
            };

            fetch('http://localhost:5001/api/User/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(loginData)
            })
            .then(response=>response.text())
            .then(data => {
                if (data) {
                    localStorage.setItem('authToken', data);
                    messageElement.textContent = 'Login successful!';
                    messageElement.style.color = 'green';
                    window.location.href = 'index.php';  // Redirect to index.html after successful login
                } else {
                    messageElement.textContent = 'There was an error with the login process. Please try again.';
                    messageElement.style.color = 'red';
                }
                }
            )
            .catch(() => {
                messageElement.textContent = 'There was an error with the login process. Please try again.';
                messageElement.style.color = 'red';
            });
        });
    </script>
</body>

</html>
