<?php
function renderNavbar()
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    <div class="overlay" onclick="menuToggle()" id="overlay"></div>
    <div class="navbar-container">
        <div class="navbar">
            <a href="index.php" class="navbar-left">
                <img src="assets/images/png/logo.png" class="logo" alt="">
            </a>
            <div class="navbar-center" id="navbar-center">
                <a class="link <?php echo $currentPage == 'index.php' ? 'current' : ''; ?>" href="index.php">Home</a>
                <a class="link <?php echo $currentPage == 'search.php' ? 'current' : ''; ?>" href="search.php">Search</a>
                <a class="link <?php echo $currentPage == 'createurl.php' ? 'current' : ''; ?>" href="createurl.php">Create
                    URL</a>
                <a class="link <?php echo $currentPage == 'dashboard.php' ? 'current' : ''; ?>"
                    href="dashboard.php">Dashboard</a>
            </div>

            <div class="navbar-right">
                <svg xmlns="http://www.w3.org/2000/svg" class="hamburgerIcon" onclick="menuToggle()" viewBox="0 0 24 24"
                    width="48" height="48" fill="currentColor" aria-label="Menu">
                    <rect x="3" y="6" width="18" height="2" rx="1"></rect>
                    <rect x="3" y="11" width="18" height="2" rx="1"></rect>
                    <rect x="3" y="16" width="18" height="2" rx="1"></rect>
                </svg>

                <div id="authButtonContainer"></div>
            </div>
        </div>
    </div>

    <script>
        const authToken = localStorage.getItem('authToken');
        let dropDownToggle = false;

        const authButtonContainer = document.getElementById('authButtonContainer');
        const dropDownMenu = document.getElementById("navbar-center");
        const overlay = document.getElementById("overlay");

        if (authToken) {
            authButtonContainer.innerHTML = '<button class="buttonClear logButton" onClick="logOut()">Log Out</button>';
        } else {
            authButtonContainer.innerHTML = '<button class="buttonClear logButton" onClick="logIn()">Log In</button>';
        }

        function logOut() {
            localStorage.removeItem('authToken');
            window.location.href = 'login.php';
        }

        function logIn() {
            window.location.href = "login.php";
        }

        function menuToggle() {
            dropDownToggle = !dropDownToggle;
            if (dropDownToggle) {
                authButtonContainer.style.right = '-10px';
                dropDownMenu.style.right = '-10px';
                overlay.style.display = "block";
            } else {
                authButtonContainer.style.right = '-60vw';
                dropDownMenu.style.right = '-60vw';
                overlay.style.display = "none";
            }
        }
    </script>

    <style>
        .overlay {
            position: fixed;
            width: 200vw;
            height: 200vh;
            z-index: 2;
            background: var(--gray);
            opacity: 0.6;
            transform: translate(-10vw, -10vw);
            display: none;

        }

        .navbar-container {
            margin-top: 10px;
            max-width: 100%;
            display: flex;
            justify-content: center;
        }

        .navbar {
            overflow: hidden;
            width: 100%;
            max-width: 1440px;
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
            transition: width 0.3s ease;
        }

        .link:hover {
            color: var(--blue);
            transition: color 0.3s ease;
        }

        .link:hover::before {
            width: 100%;
        }

        .current {
            font-weight: bold;
            color: var(--blue);
        }

        .current::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            translate: 0 22px;
            background: var(--blue);
            transition: width 0.3s ease;
        }

        .hamburgerIcon {
            display: none;
        }

        .navbar-center {
            transition: 0.5s;
        }

        @media (max-width: 736px) {


            .current::before {
                width: 0;
            }

            #authButtonContainer {
                padding: 15px;
                scale: 0.8;
                position: fixed;
                right: -60vw;
                top: 250px;
                transition: 0.5s;
                z-index: 3;
            }

            .logButton {
                border: none;
                color: white;
                width: 120px;
                font-size: 2.75rem;
                font-weight: 600;
                background: transparent;

            }

            .hamburgerIcon {
                display: block;
                color: var(--blue);
            }

            .navbar-container {
                padding: 5px;
            }

            .navbar {
                max-width: 100%;
                overflow-x: hidden;
                padding: 0;
            }

            .link:hover::before {
                width: 0;
            }

            .link:hover {
                color: white;
            }

            .navbar-center {
                display: flex;
                flex-direction: column;
                position: fixed;
                justify-content: center;
                right: -60vw;
                top: 95px;
                gap: 5px;
                height: 200px;
                padding: 10px;
                z-index: 3;
                border-radius: 40px 0px 0px 40px;
                background-color: var(--blue);
            }

            .link {
                text-align: right;
                color: white;
                opacity: 1;
                transform: translateY(-15px);
                font-size: 2.25rem;
            }
        }
    </style>
    <?php
}
?>