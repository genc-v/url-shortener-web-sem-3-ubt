<?php
// navbar.php


function renderNavbar()
{

    ?>
    <div class="navbar-container">
        <div class="navbar">
            <div class="navbar-left">
                <img src="../../assets/images/png/logo.png" class="logo" alt="">
            </div>
            <div class="navbar-center">
                <a class="link" href="">Home</a>
                <a class="link" href="">About Us</a>
                <a class="link" href="">Search</a>
                <a class="link" href="">Urls</a>

            </div>
            <div class="navbar-right">
                <div id="authButtonContainer"></div>
            </div>
        </div>
    </div>

    <script>

        const authToken = localStorage.getItem('authToken');

        // Get the container where the button should be placed
        const authButtonContainer = document.getElementById('authButtonContainer');

        // Render button based on the presence of authToken
        if (authToken) {
            // If authToken exists, render 'Log Out' button
            authButtonContainer.innerHTML = '<button onClick="logOut()">Log Out</button>';
        } else {
            // If no authToken exists, render 'Log In' button
            authButtonContainer.innerHTML = '<button onClick="logIn()">Log In</button>';
        }

        function logOut() {
            localStorage.removeItem('authToken');
            window.location.href = '../Login-Register/login.php';
        }

        function logIn() {
            window.location.href = "../Login-Register/login.php"
        }
    </script>


    <style>
        .navbar-container {
            margin-top: 10px;
            max-width: 100%;
            display: flex;
            justify-content: center;
        }

        .navbar {
            width: 80%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .logo {
            height: 50px;
        }

        .link {
            position: relative;
            font-weight: 600;
            color: black;
            opacity: 0.8;
            margin: 0 20px;
            font-size: 1.75rem;

        }

        .link::before {
            content: "";
            position: absolute;
            width: 0%;
            height: 2px;
            translate: 0 22px;
            background: var(--blue);
            transition: 0.3s;
        }

        .link:hover {
            color: var(--blue);
            transition: 0.3s;
        }


        .link:hover::before {
            width: 100%;
        }



        button {
            transition: all 0.3s;
            margin-left: 20px;
            background: transparent;
            border: 2px solid var(--blue);
            color: var(--blue)
        }

        button:hover {
            cursor: pointer;
            color: white;
            background-color: var(--blue);

        }
    </style>

    <?php
}
