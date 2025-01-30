<?php
session_start();
$error = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['urlName'], $_POST['authToken'])) {
    $urlName = $_POST['urlName'];
    $authToken = $_POST['authToken'];

    $apiUrl = "http://34.76.194.134:5284/api/search";

    $queryParams = http_build_query([
        'UrlName' => $urlName,
        'token' => $authToken
    ]);

    $fullUrl = $apiUrl . '?' . $queryParams;

    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $results = json_decode($response, true);
    } elseif ($http_code == 404) {
        $error = "No URLs found for this user.";
    } else {
        $error = "Failed to fetch results. Please try again later.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="search.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/png/favicon.ico">
    <script>
        if (!localStorage.getItem('authToken')) {
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const authToken = localStorage.getItem('authToken');
            if (authToken) {
                document.getElementById('authToken').value = authToken;
            }
        });
    </script>
</head>

<body>
    <?php
    include "header.php";
    renderNavbar()
        ?>
    <div class="search">
        <h2>Search</h2>
        <form class="search-form" method="POST">
            <div class="form-group">
                <label for="urlName">Search by URL Name</label>
                <input type="text" id="urlName" name="urlName" placeholder="Enter URL name" required>
            </div>
            <input type="hidden" id="authToken" name="authToken">
            <button type="submit" class="cta-search">Search</button>
        </form>

        <?php if ($error): ?>
            <div class="message">
                <h3><?php echo htmlspecialchars($error); ?></h3>
            </div>
        <?php endif; ?>

        <?php if (!empty($results)): ?>
            <h2>Search Results</h2>
            <div class="results">
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li>
                            <strong>Original URL:</strong> <a href="<?php echo htmlspecialchars($result['originalUrl']); ?>"
                                target="_blank"><?php echo htmlspecialchars($result['originalUrl']); ?></a><br>
                            <strong>Short URL:</strong> <a href="<?php echo htmlspecialchars($result['shortUrl']); ?>"
                                target="_blank"><?php echo htmlspecialchars($result['shortUrl']); ?></a><br>
                            <strong>Description:</strong> <?php echo htmlspecialchars($result['description']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <?php
    include "footer.php";
    renderFooter()
        ?>
</body>

</html>