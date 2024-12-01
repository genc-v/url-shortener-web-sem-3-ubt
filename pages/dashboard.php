<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>URL Shortener</title>
  <style>
    .containerUrl {
      margin-top: 20px;
      text-align: center;
    }
    .inputUrl {
      margin: 5px;
      padding: 10px;
      width: 250px;
    }
    .button {
      margin: 5px;
      padding: 10px 20px;
      cursor: pointer;
    }
    .urlList-container {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    .table-container {
      margin-top: 20px;
    }
    .url-table {
      width: 100%;
      border-collapse: collapse;
    }
    .url-table th, .url-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    .url-table th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

  <!-- URL Shortener Section -->
  <div class="containerUrl">
    <input
      type="text"
      id="url-input"
      placeholder="Enter URL"
      class="inputUrl"
    />
    <input
      type="text"
      id="description-input"
      placeholder="Enter Description"
      class="inputUrl"
    />
    <button class="button" onclick="shortenUrl()">Shorten URL</button>
    <button class="button" onclick="redirectToOriginalUrl()">Redirect</button>
  </div>

  <!-- URL List Section -->
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
          <!-- Table rows will be inserted here -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const token = localStorage.getItem('authToken')
      
      // Function to fetch user URLs
      const fetchUrls = async () => {
        try {
          const response = await fetch(`http://http://34.76.194.134:5284/Urls/${token}`);
          const data = await response.json();

          const tableBody = document.getElementById('url-table-body');
          data.urls.forEach(url => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${url.originalUrl}</td>
              <td>http://http://34.76.194.134:5284/${url.shortUrl}</td>
              <td>${url.dateCreated}</td>
            `;
            tableBody.appendChild(row);
          });
        } catch (error) {
          console.log("Error fetching data: ", error);
        }
      };

      // Call the fetchUrls function when the page loads
      fetchUrls();
    });

    // Shorten the URL
    const shortenUrl = async () => {
      const url = document.getElementById('url-input').value;
      const description = document.getElementById('description-input').value;
        const token = localStorage.getItem('authToken')

      try {
        const encodedUrl = encodeURIComponent(url);
        const encodedDescription = encodeURIComponent(description); // Encode the description
        const response = await fetch(
          `http://http://34.76.194.134:5284/api/URL?url=${encodedUrl}&token=${token}&description=${encodedDescription}`,
          { method: 'POST' }
        );
        const data = await response.json();

        console.log('Shortened URL:', data);
        document.getElementById('description-input').value = ""; // Clear the description after shortening
      } catch (error) {
        console.error("Error shortening URL:", error);
      }
    };

    // Redirect to original URL
    const redirectToOriginalUrl = async () => {
      const url = document.getElementById('url-input').value;

      try {
        const encodedUrl = encodeURIComponent(url);
        const response = await fetch(`http://http://34.76.194.134:5284/${encodedUrl}`);
        const longUrl = await response.text();

        // Check if the longUrl starts with a valid protocol (e.g., http:// or https://)
        if (isValidUrl(longUrl)) {
          window.open(longUrl, '_blank'); // Redirect to the original URL
        } else {
          console.error("Invalid URL format:", longUrl);
        }
      } catch (error) {
        console.error("Error redirecting:", error);
      }
    };

    const isValidUrl = (url) => {
      return url.startsWith("http://") || url.startsWith("https://");
    };
  </script>

</body>
</html>
