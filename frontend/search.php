<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="search.css">
    <link rel="icon" type="image/png" href="assets/images/png/favicon.ico">
    <script>
        // Redirect to login if no auth token
        if (!localStorage.getItem('authToken')) {
            window.location.href = 'login.html';
        }

        async function handleSearch(event) {
            event.preventDefault();

            const urlName = document.getElementById('urlName').value;
            const authToken = localStorage.getItem('authToken');
            const apiUrl = `http://34.76.194.134:5284/api/search?UrlName=${encodeURIComponent(urlName)}&token=${authToken}`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'GET',
                    headers: { "Accept": "application/json" }
                });

                const resultContainer = document.getElementById('results');
                resultContainer.innerHTML = ""; // Clear old results

                if (response.ok) {
                    const results = await response.json();
                    if (results.length > 0) {
                        results.forEach(result => {
                            resultContainer.innerHTML += `
                                <li>
                                    <strong>Original URL:</strong> <a href="${result.originalUrl}" target="_blank">${result.originalUrl}</a><br>
                                    <strong>Short URL:</strong> <a href="${result.shortUrl}" target="_blank">${result.shortUrl}</a><br>
                                    <strong>Description:</strong> ${result.description}
                                </li>
                            `;
                        });
                    } else {
                        resultContainer.innerHTML = "<p>No URLs found for this user.</p>";
                    }
                } else {
                    resultContainer.innerHTML = "<p>Failed to fetch results. Please try again.</p>";
                }
            } catch (error) {
                document.getElementById('results').innerHTML = "<p>Error connecting to server.</p>";
            }
        }
    </script>
</head>

<body>
    <div class="search">
        <h2>Search</h2>
        <form class="search-form" onsubmit="handleSearch(event)">
            <div class="form-group">
                <label for="urlName">Search by URL Name</label>
                <input type="text" id="urlName" placeholder="Enter URL name" required>
            </div>
            <button type="submit" class="cta-search">Search</button>
        </form>

        <h2>Search Results</h2>
        <div class="results">
            <ul id="results"></ul>
        </div>
    </div>
</body>

</html>
