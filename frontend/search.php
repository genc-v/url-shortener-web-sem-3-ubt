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

            const searchForm = document.querySelector('.search-form');
            searchForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const urlName = document.getElementById('urlName').value;
                const authToken = document.getElementById('authToken').value;

                const apiUrl = "http://localhost:5001/api/search";
                const queryParams = new URLSearchParams({
                    'UrlName': urlName,
                    'token': authToken
                });

                fetch(`${apiUrl}?${queryParams}`, {
                    method: 'GET',
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else if (response.status === 404) {
                        throw new Error("No URLs found for this user.");
                    } else {
                        throw new Error("Failed to fetch results. Please try again later.");
                    }
                })
                .then(data => {
                    displayResults(data);
                })
                .catch(error => {
                    displayError(error.message);
                });
            });

            function displayResults(results) {
                let resultsContainer = document.querySelector('.results');
                const errorContainer = document.querySelector('.message');

                if (errorContainer) {
                    errorContainer.remove();
                }

                if (!resultsContainer) {
                    const newResultsContainer = document.createElement('div');
                    newResultsContainer.classList.add('results');
                    document.querySelector('.search').appendChild(newResultsContainer);
                    resultsContainer = newResultsContainer;
                }

                const resultsList = document.createElement('ul');
                results.forEach(result => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <strong>Original URL:</strong> <a href="${result.originalUrl}" target="_blank">${result.originalUrl}</a><br>
                        <strong>Short URL:</strong> <a href="${result.shortUrl}" target="_blank">${result.shortUrl}</a><br>
                        <strong>Description:</strong> ${result.description}
                    `;
                    resultsList.appendChild(listItem);
                });

                resultsContainer.appendChild(resultsList);
            }

            function displayError(message) {
                let errorContainer = document.querySelector('.message');
                const resultsContainer = document.querySelector('.results');

                if (resultsContainer) {
                    resultsContainer.remove();
                }

                if (errorContainer) {
                    errorContainer.innerHTML = `<h3>${message}</h3>`;
                } else {
                    const newErrorContainer = document.createElement('div');
                    newErrorContainer.classList.add('message');
                    newErrorContainer.innerHTML = `<h3>${message}</h3>`;
                    document.querySelector('.search').appendChild(newErrorContainer);
                }
            }
        });
    </script>
</head>

<body>
    <?php
    include "header.php";
    renderNavbar();
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
    </div>

    <?php
    include "footer.php";
    renderFooter();
    ?>
</body>

</html>
