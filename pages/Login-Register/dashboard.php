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
</head>
<style>
  /* Import main styles */
@import url(../../assets/styles/main.css);

body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  background-color: #f4f7fc;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  margin-bottom: -20px;
}

p {
  margin-top: -5px;
  font-size: 1.44rem;
}

.containerUrl {
  width: 60%;
  padding: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin-top: 20px;
}

.inputUrl {
  width: 400px;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
}

.button {
  width: 410px;
  padding: 12px;
  background-color: #5f77eb;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: background-color 0.3s ease;
}

.button:hover {
  background-color: #4e68d9;
}

.urlList-container {
  width: 80%;
  margin-top: 20px;
}

.table-container {
  width: 100%;
  overflow-x: auto;
}

.url-table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  border-radius: 8px;
}

.url-table th, .url-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.url-table th {
  background-color: #f2f2f2;
  color: #333;
}

.url-table tr:hover {
  background-color: #f1f1f1;
}

.secondary-container {
  width: 40%;
  height: 100vh;
  background-color: rgba(14, 144, 195, 0.2);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 20px;
  display: none; /* Hidden on smaller screens */
}

.secondary-container > img {
  width: 40vw;
  background: transparent;
}

.secondary-container > p {
  margin-top: 20px;
  width: 90%;
  font-weight: 700;
  font-size: 1.75rem;
  opacity: 0.7;
  text-align: center;
}

.message {
  transform: translateY(-30px);
  color: red;
  opacity: 0.5;
  font-size: 1rem;
}

@media (max-width: 736px) {
  .containerUrl {
    width: 90%;
  }
  .urlList-container {
    width: 90%;
  }
  .secondary-container {
    display: none;
  }
}
</style>
<body>

  <!-- URL Shortener Section -->
  <div class="containerUrl">
    <input type="text" id="url-input" placeholder="Enter URL" class="inputUrl" />
    <input type="text" id="description-input" placeholder="Enter Description" class="inputUrl" />
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
        </tbody>
      </table>
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
