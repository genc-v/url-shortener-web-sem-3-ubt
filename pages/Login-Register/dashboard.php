<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="search.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dashbaord.css">
</head>

<body>


<div class="dashbaord-wrapper">

    <div class="containerUrl">
        <input type="text" id="url-input" placeholder="Enter URL" class="inputUrl" />
        <input type="text" id="description-input" placeholder="Enter Description" class="inputUrl" />
    <button class="button" onclick="shortenUrl()">Shorten URL</button>
  </div>

  <div class="urlList-container">
      <h2>Your Previous Urls</h2>
      <div class="table-container">
          <table class="url-table">
              <thead>
                  <tr>
                      <th>Original URL</th>
            <th>Short Url</th>
            <th>Date Created</th>
          </tr>
        </thead>
        <tbody id="url-table-body">
            </tbody>
        </table>
    </div>
</div>
</div>

  <script>
    const token = localStorage.getItem('authToken');

    const fetchUrls = async () => {
      try {
        const response = await fetch(`http://34.76.194.134:5284/Urls/${token}`);
        const data = await response.json();

        const tableBody = document.getElementById('url-table-body');
        tableBody.innerHTML = ''; // Clear the existing rows
        data.urls.forEach(url => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${url.originalUrl}</td>
            <td>http://localhost:5284/${url.shortUrl}</td>
            <td>${url.dateCreated}</td>
          `;
          tableBody.appendChild(row);
        });
      } catch (error) {
        console.log("Error fetching data: ", error);
      }
    };

    document.addEventListener('DOMContentLoaded', function () {
      fetchUrls();
    });

    const shortenUrl = async () => {
      const url = document.getElementById('url-input').value;
      const description = document.getElementById('description-input').value;

      try {
        const encodedUrl = encodeURIComponent(url);
        const encodedDescription = encodeURIComponent(description);
        const response = await fetch(
          `http://34.76.194.134:5284/api/URL?url=${encodedUrl}&token=${token}&description=${encodedDescription}`,
          { method: 'POST' }
        );
        const data = await response.json();

        console.log('Shortened URL:', data);
        document.getElementById('description-input').value = ""; // Clear description input after submission

        // Optionally, add the new URL to the list without re-fetching from the API
        const tableBody = document.getElementById('url-table-body');
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${url}</td>
          <td>http://localhost:5284/${data.shortUrl}</td>
          <td>${new Date().toLocaleString()}</td>
        `;
        tableBody.appendChild(row);

      } catch (error) {
        console.error("Error shortening URL:", error);
      }
    };
  </script>

</body>
</html>
