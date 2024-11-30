<?php
session_start();
$responseMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['url'], $_POST['description'])) {
    $urlInput = $_POST['url'];
    $description = $_POST['description'];

    $apiUrl = "http://34.76.194.134:5284/api/URL";

    // Get auth token from localStorage via JavaScript (sent as hidden field)
    $authToken = $_POST['authToken'] ?? '';

    if (!$authToken) {
        $responseMessage = 'Authentication token missing!';
    } else {
        $data = json_encode(array(
            "url" => $urlInput,
            "description" => $description
        ));

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer $authToken"
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $responseMessage = 'URL and description successfully submitted!';
        } else {
            $responseMessage = 'Failed to submit the data. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit URL and Description</title>
</head>
<body>
    <div class="container">
        <!-- Form for submitting URL and description -->
        <form method="POST" id="urlForm">
            <h2>Submit Your URL</h2>
            <div>
                <label for="url">URL:</label>
                <input type="url" id="url" name="url" placeholder="Enter URL" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Enter description" required></textarea>
            </div>
            <!-- Hidden field to send auth token -->
            <input type="hidden" id="authToken" name="authToken">
            <button type="submit">Submit</button>
        </form>

        <?php if ($responseMessage): ?>
            <div class="message"><?php echo $responseMessage; ?></div>
        <?php endif; ?>
    </div>

    <script>
        // Attach the authentication token to the hidden input
        const authToken = localStorage.getItem('authToken');
        if (authToken) {
            document.getElementById('authToken').value = authToken;
        } else {
            alert('Authentication token missing. Please log in.');
            window.location.href = 'login.php';
        }
    </script>
</body>
</html>
