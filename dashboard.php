<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Links</title>
  <link rel="stylesheet" href="dashboard.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="test.css">
  <link rel="icon" type="image/png" href="assets/images/png/favicon.ico">
</head>

<body>
  <?php
  include "header.php";
  renderNavbar()
    ?>
  <div id="toast-container"></div>

  <div class="urlList-container">
    <h2>Dashboard</h2>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th id="originalUrl-header" onclick="sortTable('originalUrl')">Original URL</th>
            <th>Short URL</th>
            <th id="dateCreated-header" onclick="sortTable('dateCreated')">Date Created</th>
            <th id="clicks-header" onclick="sortTable('clicks')">Clicks</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="url-table-body"></tbody>
      </table>
    </div>
  </div>

  <div class="modal-overlay" id="modal-overlay"></div>
  <div class="modal" id="qr-modal">
    <h3>QR Code</h3>
    <div class="qr-code" id="qr-code"></div>
    <div class="cta__buttons">
      <button onclick="downloadQRCode()">Download QR Code</button>
      <button class="close-button" onclick="closeModal()">Close</button>
    </div>
  </div>

  <div class="modal-overlay" id="delete-modal-overlay"></div>
  <div class="modal" id="delete-modal">
    <h3>Are you sure you want to delete this URL?</h3>
    <div class="cta__buttons">
      <button id="confirm-delete" onclick="confirmDelete()">Yes</button>
      <button onclick="closeDeleteModal()">No</button>
    </div>
  </div>

  <div class="modal-overlay" id="edit-modal-overlay"></div>
  <div class="modal" id="edit-modal">
    <h3>Edit Description</h3>
    <input type="text" id="edit-description-input" />
    <div class="cta__buttons">
      <button onclick="updateDescription()">Save</button>
      <button onclick="closeEditModal()">Cancel</button>
    </div>
  </div>

  <?php
  include "footer.php";
  renderFooter()
    ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    const token = localStorage.getItem('authToken');

    if (!localStorage.getItem('authToken')) {
      window.location.href = 'login.php';
    }

    const timeAgo = (date) => {
      const now = new Date();
      const past = new Date(date);
      const seconds = Math.floor((now - past) / 1000);
      const intervals = [
        { label: 'year', seconds: 31536000 },
        { label: 'month', seconds: 2592000 },
        { label: 'week', seconds: 604800 },
        { label: 'day', seconds: 86400 },
        { label: 'hour', seconds: 3600 },
        { label: 'minute', seconds: 60 },
        { label: 'second', seconds: 1 },
      ];
      for (const interval of intervals) {
        const count = Math.floor(seconds / interval.seconds);
        if (count > 0) {
          return `${count} ${interval.label}${count > 1 ? 's' : ''} ago`;
        }
      }
      return 'just now';
    };

    let urlData = [];
    let currentPage = 1;
    const pageSize = 10;

    const fetchUrls = async (pageNumber = 1) => {
      try {
        const response = await fetch(`http://34.76.194.134:5284/Urls/${token}?pageNumber=${pageNumber}&pageSize=${pageSize}`);
        const data = await response.json();
        urlData = data.urls;
        displayUrls(urlData);
        updatePaginationControls(data.totalPages);
      } catch (error) {
        console.error('Error fetching URLs:', error);
      }
    };

    const updatePaginationControls = (totalPages) => {
      const paginationContainer = document.getElementById('pagination-controls');
      paginationContainer.innerHTML = '';

      for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.classList.add('pagination-button');
        if (i === currentPage) {
          pageButton.classList.add('active');
        }
        pageButton.addEventListener('click', () => {
          currentPage = i;
          fetchUrls(currentPage);
        });
        paginationContainer.appendChild(pageButton);
      }
    };

    const displayUrls = (urls) => {
      const tableContainer = document.querySelector('.table-container');
      const tableBody = document.getElementById('url-table-body');
      const urlListContainer = document.querySelector('.urlList-container');

      tableBody.innerHTML = '';

      if (urls.length === 0) {
        tableContainer.style.display = 'none';
        urlListContainer.innerHTML = `
          <div class="empty-message">
            <h3>You don't have any links yet!</h3>
            <button onclick="window.location.href='createurl.php'" class="create-url-button">Shorten a Link</button>
          </div>
        `;
      } else {
        tableContainer.style.display = 'block';
        urls.forEach((url) => {
          const faviconUrl = fetchFavicon(url.originalUrl);
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>
              <div class="original-url">
                <img src="${faviconUrl}" alt="favicon" style="width: 16px; height: 16px; margin-right: 8px;">
                <div>${url.originalUrl}</div>
              </div>
            </td>
            <td><a href="http://bytely.xyz/${url.shortUrl}" target="_blank">bytely.xyz/${url.shortUrl}</a></td>
            <td>${timeAgo(url.dateCreated)}</td>
            <td>${url.nrOfClicks}</td>
            <td><div class="text-clamp">${url.description}</div></td>
            <td>
              <div class="dashboard-actions">
                <svg onclick="openModal('${url.shortUrl}')" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="rgb(255, 23, 52)">
                  <!-- SVG content -->
                </svg>
                <svg onclick="openEditModal(${url.id}, '${url.description}')" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <!-- SVG content -->
                </svg>
                <svg onclick="openDeleteModal(${url.id})" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <!-- SVG content -->
                </svg>
              </div>
            </td>
          `;
          tableBody.appendChild(row);
        });
      }
    };

    window.onload = () => {
      fetchUrls();
    };
  </script>
</body>

</html>