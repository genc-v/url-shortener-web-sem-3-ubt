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

        <form class="register-form" id="registerForm">
            <h2>Create your account</h2>
            <p>Already have an account? <a href="login.php">Login Here!</a></p>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="fullName" placeholder="Your Full Name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" id="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" placeholder="Password" required>
            </div>
            <div id="message" class="message"></div>
            <button type="submit">Register</button>

        </form>
    </div>
    <div class="secondary-container">
        <img src="assets/images/png/RegisterImage.png" alt="">
        <p>Analyze your links and QR Codes as easily as creating them</p>
    </div>

    <script>
        // Redirect if already logged in
        if (localStorage.getItem('authToken')) {
            window.location.href = 'dashboard.php';
        }

        // Handle form submission
        document.getElementById('registerForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const messageElement = document.getElementById('message');

            // Validate input
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+[\]{}|;:,.<>?])(?=.{8,})/;


            if (!emailPattern.test(email)) {
                messageElement.textContent = 'Invalid email format.';
                messageElement.style.color = 'red';
                return;
            }

            if (!passwordPattern.test(password)) {
                messageElement.textContent = 'Password must be at least 8 characters, include one uppercase letter and one number.';
                messageElement.style.color = 'red';
                return;
            }

            // Prepare data for API request
            const registrationData = {
                email: email,
                fullName: fullName,
                password: password
            };

            // Make the API request to register
            fetch('http://localhost:5001/api/User/signup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(registrationData)
            })
            .then(response => {
                messageElement.textContent = 'Registration successful! Redirecting to login...';
                messageElement.style.color = 'green';
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
            })
            .catch(error => {
                messageElement.textContent = 'An error occurred. Please try again later.';
                messageElement.style.color = 'red';
            });
        });
    </script>
</body>

</html>
