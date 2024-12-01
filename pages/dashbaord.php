<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Links</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="test.css">
</head>

<body>
  <?php 
  include "../pages/Header-Footer/header.php";
  renderNavbar()
  ?>
  <div id="toast-container"></div>

  <div class="urlList-container">
    <h2 class="url-list__title">Dashbaord</h2>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th id="originalUrl-header" onclick="sortTable('originalUrl')">Original URL</th>
            <th>Short URL</th>
            <th id="dateCreated-header" onclick="sortTable('dateCreated')">Date Created</th>
            <th>Clicks</th>
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
  include "../pages/Header-Footer/footer.php";
  renderFooter()
  ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    const token = localStorage.getItem('authToken');

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
    let sortOrder = {
      originalUrl: 'asc',
      dateCreated: 'asc'
    };

    const fetchUrls = async () => {
      try {
        const response = await fetch(`http://34.76.194.134:5284/Urls/${token}`);
        const data = await response.json();
        urlData = data.urls; // Store the fetched data in a variable
        displayUrls(urlData); // Display the data
      } catch (error) {
        console.log("Error fetching data: ", error);
      }
    };

    const fetchFavicon = (url) => {
      const domain = new URL(url).hostname;
      return `https://www.google.com/s2/favicons?domain=${domain}`;
    };

    const displayUrls = (urls) => {
      const tableBody = document.getElementById('url-table-body');
      tableBody.innerHTML = ''; // Clear existing rows

      urls.forEach((url) => {
        const faviconUrl = fetchFavicon(url.originalUrl); // Get favicon URL
        const row = document.createElement('tr');
        row.innerHTML = `
      <td><img src="${faviconUrl}" alt="favicon" style="width: 16px; height: 16px; margin-right: 8px;">${url.originalUrl}</td>
      <td><a href="http://localhost:3000/${url.shortUrl}" target="_blank">http://localhost:3000/${url.shortUrl}</a></td>
      <td>${timeAgo(url.dateCreated)}</td>
      <td>${url.nrOfClicks}</td> <!-- Display the number of clicks here -->
      <td>${url.description}</td> <!-- Display description here -->
      <td class="dashbaord-actoins">
        <button onclick="openModal('${url.id}')">QR Code</button>
        <button onclick="openEditModal(${url.id}, '${url.description}')">Edit Description</button>
        <button onclick="openDeleteModal(${url.id})">Delete</button>
      </td>
    `;
        tableBody.appendChild(row);
      });
    };



    const openModal = (id) => {
      const modal = document.getElementById('qr-modal');
      const overlay = document.getElementById('modal-overlay');
      const qrCodeDiv = document.getElementById('qr-code');

      modal.classList.add('active');
      overlay.classList.add('active');

      qrCodeDiv.innerHTML = '';
      new QRCode(qrCodeDiv, {
        text: `http://localhost:5284/${id}`,
        width: 150,
        height: 150,
      });

      modal.dataset.currentId = id;
    };

    const downloadQRCode = () => {
      const qrCodeDiv = document.querySelector('#qr-code canvas');
      if (qrCodeDiv) {
        const link = document.createElement('a');
        link.href = qrCodeDiv.toDataURL("image/png");
        link.download = 'qr-code.png';
        link.click();
      }
    };

    const closeModal = () => {
      const modal = document.getElementById('qr-modal');
      const overlay = document.getElementById('modal-overlay');
      modal.classList.remove('active');
      overlay.classList.remove('active');
    };

    let deleteUrlId = null;

    const openDeleteModal = (id) => {
      deleteUrlId = id;
      document.getElementById('delete-modal').classList.add('active');
      document.getElementById('delete-modal-overlay').classList.add('active');
    };

    const closeDeleteModal = () => {
      document.getElementById('delete-modal').classList.remove('active');
      document.getElementById('delete-modal-overlay').classList.remove('active');
    };

    const confirmDelete = async () => {
      if (deleteUrlId) {
        try {
          const response = await fetch(`http://34.76.194.134:5284/api/Url/${deleteUrlId}`, {
            method: 'DELETE',
            headers: {
              'Authorization': `Bearer ${token}`,
            },
          });
          if (response.ok) {
            showToast('URL deleted successfully!');
            fetchUrls(); // Refresh the list
          } else {
            showToast('Error deleting URL', 'error');
          }
        } catch (error) {
          showToast('Error deleting URL', 'error');
        }
        closeDeleteModal(); // Close modal after action
      }
    };

    const openEditModal = (id, description) => {
      document.getElementById('edit-description-input').value = description;
      document.getElementById('edit-modal').classList.add('active');
      document.getElementById('edit-modal-overlay').classList.add('active');
      window.editUrlId = id;
    };

    const closeEditModal = () => {
      document.getElementById('edit-modal').classList.remove('active');
      document.getElementById('edit-modal-overlay').classList.remove('active');
    };

    const updateDescription = async () => {
      const newDescription = document.getElementById('edit-description-input').value;
      if (newDescription) {
        try {
          const response = await fetch(`http://34.76.194.134:5284/api/Url/${window.editUrlId}?description=${encodeURIComponent(newDescription)}`, {
            method: 'PUT',
            headers: {
              'accept': '*/*',
            },
          });
          if (response.ok) {
            showToast('Description updated successfully!');
            fetchUrls(); // Refresh the list
          } else {
            showToast('Error updating description', 'error');
          }
        } catch (error) {
          showToast('Error updating description', 'error');
        }
        closeEditModal(); // Close the modal after saving
      }
    };

    const showToast = (message, type = 'success') => {
      const toast = document.createElement('div');
      toast.classList.add('toast');
      toast.classList.add(type);
      toast.textContent = message;

      const toastContainer = document.getElementById('toast-container');
      toastContainer.appendChild(toast);

      // Remove the toast after 3 seconds
      setTimeout(() => {
        toast.remove();
      }, 3000); // Remove the toast after 3 seconds
    };



    const sortTable = (column) => {
      let sortedData = [...urlData];
      let sortDir = sortOrder[column] === 'asc' ? 'desc' : 'asc';
      sortOrder[column] = sortDir;

      // Update the header arrow direction
      const headers = document.querySelectorAll('th');
      headers.forEach((header) => {
        header.classList.remove('sorted-asc', 'sorted-desc');
      });

      const currentHeader = document.getElementById(`${column}-header`);
      if (sortDir === 'asc') {
        currentHeader.classList.add('sorted-asc');
      } else {
        currentHeader.classList.add('sorted-desc');
      }

      if (column === 'originalUrl') {
        sortedData.sort((a, b) => sortDir === 'asc' ? a.originalUrl.localeCompare(b.originalUrl) : b.originalUrl.localeCompare(a.originalUrl));
      } else if (column === 'dateCreated') {
        sortedData.sort((a, b) => sortDir === 'asc' ? new Date(a.dateCreated) - new Date(b.dateCreated) : new Date(b.dateCreated) - new Date(a.dateCreated));
      }

      displayUrls(sortedData); // Re-render the table with sorted data
    };

    window.onload = () => {
      fetchUrls();
    };
  </script>
</body>

</html>