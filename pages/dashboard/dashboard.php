<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <?php
    include '../Header-Footer/header.php';

    renderNavbar();
    ?>

    <div class="content-container">
        <div class="content">
            <div class="hero">
                <div class="hero-text">
                    <p class="slogan">Great Connections Start with a click OR SCAN</p>
                    <h1>The Bytely Connections Platform</h1>
                    <p class="text">All the products you need to build brand connections, manage links and QR Codes, and
                        connect with
                        audiences everywhere, in a single unified platform.</p>

                    <div class="buttonContainer">
                        <a href="../dashboard/dashboard.php"><button>Get started for free</button></a>
                        <a href="../Login-Register/register.php"><button class="buttonClear">Sign up for
                                more</button></a>
                    </div>
                </div>

                <div class="hero-image"><img src="../../assets\images\png\dashboard-image.png" alt=""></div>
            </div>

            <div class="features">
                <h1>Features</h1>
                <div class="cards-container">
                    <div class="card">
                        <div class="card-top"></div>
                        <div class="card-bottom"></div>
                    </div>
                    <div class="card"></div>
                    <div class="card"></div>
                </div>


            </div>


        </div>
    </div>

    <?php
    include '../Header-Footer/footer.php';

    renderFooter();
    ?>

</body>

</html>