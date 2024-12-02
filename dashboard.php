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
    let sortOrder = {
      originalUrl: 'asc',
      dateCreated: 'asc',
      clicks: 'asc'
    };

    const fetchUrls = async () => {
      try {
        const response = await fetch(`http://34.76.194.134:5284/Urls/${token}`);
        const data = await response.json();
        urlData = data.urls;
        displayUrls(urlData);
      } catch (error) {
        return;
      }
    };

    const fetchFavicon = (url) => {
      const domain = new URL(url).hostname;
      return `https://www.google.com/s2/favicons?domain=${domain}`;
    };

    const displayUrls = (urls) => {
      const tableBody = document.getElementById('url-table-body');
      tableBody.innerHTML = '';

      urls.forEach((url) => {
        const faviconUrl = fetchFavicon(url.originalUrl);
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>
          <div class="original-url">
          <img src="${faviconUrl}" alt="favicon" style="width: 16px; height: 16px; margin-right: 8px;">
          <div>
          ${url.originalUrl}
          </div>
          </div>
          </td>
          <td><a href="http://bytely.xyz/${url.shortUrl}" target="_blank">bytely.xyz/${url.shortUrl}</a></td>
          <td>${timeAgo(url.dateCreated)}</td>
          <td>${url.nrOfClicks}</td> 
          <td> 
          <div  class="text-clamp">
          ${url.description}
          </div>
           </td>
          <td >

          <div class="dashboard-actoins"><svg onclick="openModal('${url.shortUrl}')" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        stroke="rgb(255, 23, 52)">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M2 16.9C2 15.5906 2 14.9359 2.29472 14.455C2.45963 14.1859 2.68589 13.9596 2.955 13.7947C3.43594 13.5 4.09063 13.5 5.4 13.5H6.5C8.38562 13.5 9.32843 13.5 9.91421 14.0858C10.5 14.6716 10.5 15.6144 10.5 17.5V18.6C10.5 19.9094 10.5 20.5641 10.2053 21.045C10.0404 21.3141 9.81411 21.5404 9.545 21.7053C9.06406 22 8.40937 22 7.1 22C5.13594 22 4.15391 22 3.4325 21.5579C3.02884 21.3106 2.68945 20.9712 2.44208 20.5675"
                                                stroke="#rgb(255, 23, 52)" stroke-width="1.5" stroke-linecap="round">
                                            </path>
                                            <path
                                                d="M22 7.1C22 8.40937 22 9.06406 21.7053 9.545C21.5404 9.81411 21.3141 10.0404 21.045 10.2053C20.5641 10.5 19.9094 10.5 18.6 10.5H17.5C15.6144 10.5 14.6716 10.5 14.0858 9.91421C13.5 9.32843 13.5 8.38562 13.5 6.5V5.4C13.5 4.09063 13.5 3.43594 13.7947 2.955C13.9596 2.68589 14.1859 2.45963 14.455 2.29472C14.9359 2 15.5906 2 16.9 2C18.8641 2 19.8461 2 20.5675 2.44208C20.9712 2.68945 21.3106 3.02884 21.5579 3.4325"
                                                stroke="#rgb(255, 23, 52)" stroke-width="1.5" stroke-linecap="round">
                                            </path>
                                            <path
                                                d="M16.5 6.25C16.5 5.73459 16.5 5.47689 16.6291 5.29493C16.6747 5.23072 16.7307 5.17466 16.7949 5.12911C16.9769 5 17.2346 5 17.75 5C18.2654 5 18.5231 5 18.7051 5.12911C18.7693 5.17466 18.8253 5.23072 18.8709 5.29493C19 5.47689 19 5.73459 19 6.25C19 6.76541 19 7.02311 18.8709 7.20507C18.8253 7.26928 18.7693 7.32534 18.7051 7.37089C18.5231 7.5 18.2654 7.5 17.75 7.5C17.2346 7.5 16.9769 7.5 16.7949 7.37089C16.7307 7.32534 16.6747 7.26928 16.6291 7.20507C16.5 7.02311 16.5 6.76541 16.5 6.25Z"
                                                fill="#rgb(255, 23, 52)"></path>
                                            <path
                                                d="M12.75 22C12.75 22.4142 13.0858 22.75 13.5 22.75C13.9142 22.75 14.25 22.4142 14.25 22H12.75ZM14.3889 13.8371L14.8055 14.4607L14.8055 14.4607L14.3889 13.8371ZM13.8371 14.3889L13.2135 13.9722L13.2135 13.9722L13.8371 14.3889ZM19 12.75H17V14.25H19V12.75ZM12.75 19V22H14.25V19H12.75ZM17 12.75C16.3134 12.75 15.742 12.7491 15.281 12.796C14.8075 12.8441 14.3682 12.9489 13.9722 13.2135L14.8055 14.4607C14.914 14.3882 15.078 14.3244 15.4328 14.2883C15.8002 14.2509 16.2822 14.25 17 14.25V12.75ZM14.25 17C14.25 16.2822 14.2509 15.8002 14.2883 15.4328C14.3244 15.078 14.3882 14.914 14.4607 14.8055L13.2135 13.9722C12.9489 14.3682 12.8441 14.8075 12.796 15.281C12.7491 15.742 12.75 16.3134 12.75 17H14.25ZM13.9722 13.2135C13.6719 13.4141 13.4141 13.6719 13.2135 13.9722L14.4607 14.8055C14.5519 14.669 14.669 14.5519 14.8055 14.4607L13.9722 13.2135Z"
                                                fill="#rgb(255, 23, 52)"></path>
                                            <path
                                                d="M22.75 13.5C22.75 13.0858 22.4142 12.75 22 12.75C21.5858 12.75 21.25 13.0858 21.25 13.5H22.75ZM20.7654 21.8478L21.0524 22.5407L21.0524 22.5407L20.7654 21.8478ZM21.8478 20.7654L21.1548 20.4784V20.4784L21.8478 20.7654ZM17 22.75H19V21.25H17V22.75ZM22.75 17V13.5H21.25V17H22.75ZM19 22.75C19.4557 22.75 19.835 22.7504 20.1454 22.7292C20.4625 22.7076 20.762 22.661 21.0524 22.5407L20.4784 21.1548C20.4012 21.1868 20.284 21.2163 20.0433 21.2327C19.7958 21.2496 19.4762 21.25 19 21.25V22.75ZM21.25 19C21.25 19.4762 21.2496 19.7958 21.2327 20.0433C21.2163 20.284 21.1868 20.4012 21.1548 20.4784L22.5407 21.0524C22.661 20.762 22.7076 20.4625 22.7292 20.1454C22.7504 19.835 22.75 19.4557 22.75 19H21.25ZM21.0524 22.5407C21.7262 22.2616 22.2616 21.7262 22.5407 21.0524L21.1548 20.4784C21.028 20.7846 20.7846 21.028 20.4784 21.1549L21.0524 22.5407Z"
                                                fill="#rgb(255, 23, 52)"></path>
                                            <path
                                                d="M2 7.1C2 5.13594 2 4.15391 2.44208 3.4325C2.68945 3.02884 3.02884 2.68945 3.4325 2.44208C4.15391 2 5.13594 2 7.1 2C8.40937 2 9.06406 2 9.545 2.29472C9.81411 2.45963 10.0404 2.68589 10.2053 2.955C10.5 3.43594 10.5 4.09063 10.5 5.4V6.5C10.5 8.38562 10.5 9.32843 9.91421 9.91421C9.32843 10.5 8.38562 10.5 6.5 10.5H5.4C4.09063 10.5 3.43594 10.5 2.955 10.2053C2.68589 10.0404 2.45963 9.81411 2.29472 9.545C2 9.06406 2 8.40937 2 7.1Z"
                                                stroke="#rgb(255, 23, 52)" stroke-width="1.5"></path>
                                            <path
                                                d="M5 6.25C5 5.73459 5 5.47689 5.12911 5.29493C5.17466 5.23072 5.23072 5.17466 5.29493 5.12911C5.47689 5 5.73459 5 6.25 5C6.76541 5 7.02311 5 7.20507 5.12911C7.26928 5.17466 7.32534 5.23072 7.37089 5.29493C7.5 5.47689 7.5 5.73459 7.5 6.25C7.5 6.76541 7.5 7.02311 7.37089 7.20507C7.32534 7.26928 7.26928 7.32534 7.20507 7.37089C7.02311 7.5 6.76541 7.5 6.25 7.5C5.73459 7.5 5.47689 7.5 5.29493 7.37089C5.23072 7.32534 5.17466 7.26928 5.12911 7.20507C5 7.02311 5 6.76541 5 6.25Z"
                                                fill="#rgb(255, 23, 52)"></path>
                                            <path
                                                d="M5 17.75C5 17.2346 5 16.9769 5.12911 16.7949C5.17466 16.7307 5.23072 16.6747 5.29493 16.6291C5.47689 16.5 5.73459 16.5 6.25 16.5C6.76541 16.5 7.02311 16.5 7.20507 16.6291C7.26928 16.6747 7.32534 16.7307 7.37089 16.7949C7.5 16.9769 7.5 17.2346 7.5 17.75C7.5 18.2654 7.5 18.5231 7.37089 18.7051C7.32534 18.7693 7.26928 18.8253 7.20507 18.8709C7.02311 19 6.76541 19 6.25 19C5.73459 19 5.47689 19 5.29493 18.8709C5.23072 18.8253 5.17466 18.7693 5.12911 18.7051C5 18.5231 5 18.2654 5 17.75Z"
                                                fill="#rgb(255, 23, 52)"></path>
                                            <path
                                                d="M16 17.75C16 17.0478 16 16.6967 16.1685 16.4444C16.2415 16.3352 16.3352 16.2415 16.4444 16.1685C16.6967 16 17.0478 16 17.75 16C18.4522 16 18.8033 16 19.0556 16.1685C19.1648 16.2415 19.2585 16.3352 19.3315 16.4444C19.5 16.6967 19.5 17.0478 19.5 17.75C19.5 18.4522 19.5 18.8033 19.3315 19.0556C19.2585 19.1648 19.1648 19.2585 19.0556 19.3315C18.8033 19.5 18.4522 19.5 17.75 19.5C17.0478 19.5 16.6967 19.5 16.4444 19.3315C16.3352 19.2585 16.2415 19.1648 16.1685 19.0556C16 18.8033 16 18.4522 16 17.75Z"
                                                fill="#rgb(255, 23, 52)"></path>
                                        </g>
                                    </svg>
            
            <svg
            onclick="openEditModal(${url.id}, '${url.description}')"
            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20.1497 7.93997L8.27971 19.81C7.21971 20.88 4.04971 21.3699 3.27971 20.6599C2.50971 19.9499 3.06969 16.78 4.12969 15.71L15.9997 3.84C16.5478 3.31801 17.2783 3.03097 18.0351 3.04019C18.7919 3.04942 19.5151 3.35418 20.0503 3.88938C20.5855 4.42457 20.8903 5.14781 20.8995 5.90463C20.9088 6.66146 20.6217 7.39189 20.0997 7.93997H20.1497Z" stroke="#FF1734" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M21 21H12" stroke="#FF1734" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            <svg  onclick="openDeleteModal(${url.id})" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7 9.5L12 14.5M12 9.5L7 14.5M19.4922 13.9546L16.5608 17.7546C16.2082 18.2115 16.032 18.44 15.8107 18.6047C15.6146 18.7505 15.3935 18.8592 15.1583 18.9253C14.8928 19 14.6042 19 14.0271 19H6.2C5.07989 19 4.51984 19 4.09202 18.782C3.71569 18.5903 3.40973 18.2843 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.07989 5 6.2 5H14.0271C14.6042 5 14.8928 5 15.1583 5.07467C15.3935 5.14081 15.6146 5.2495 15.8107 5.39534C16.032 5.55998 16.2082 5.78846 16.5608 6.24543L19.4922 10.0454C20.0318 10.7449 20.3016 11.0947 20.4054 11.4804C20.4969 11.8207 20.4969 12.1793 20.4054 12.5196C20.3016 12.9053 20.0318 13.2551 19.4922 13.9546Z" stroke="#FF1734" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
</div>

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
        text: `http:/bytely.xyz/${id}`,
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
        sortedData.sort((a, b) =>
          sortDir === 'asc'
            ? a.originalUrl.localeCompare(b.originalUrl)
            : b.originalUrl.localeCompare(a.originalUrl)
        );
      } else if (column === 'dateCreated') {
        sortedData.sort((a, b) =>
          sortDir === 'asc'
            ? new Date(a.dateCreated) - new Date(b.dateCreated)
            : new Date(b.dateCreated) - new Date(a.dateCreated)
        );
      } else if (column === 'clicks') {
        sortedData.sort((a, b) =>
          sortDir === 'asc' ? a.nrOfClicks - b.nrOfClicks : b.nrOfClicks - a.nrOfClicks
        );
      }

      displayUrls(sortedData);
    };

    window.onload = () => {
      fetchUrls();
    };
  </script>
</body>

</html>