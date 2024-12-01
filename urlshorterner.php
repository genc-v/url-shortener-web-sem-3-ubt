<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input URLs</title>
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>

        @import url(../assets/styles/main.css);

        .containerUrl {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            margin: auto;
            display: flex;
            flex-direction: column;
            border-radius: 15px;
            border: 1px solid rgba(128, 128, 128, 0.16);
        }

        .inputUrl {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .button {
            width: 100%;
            padding: 12px;
            background-color: var(--blue);
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: gray;
        }

        .message {
            margin-top: 10px;
            font-size: 14px;
            margin:auto;
        }

        .shortened-url-container {
            margin-top: 20px;
        }

        .qr-code-container {
            margin-top: 20px;
            text-align: center;
        }

        #qr-code {
            margin: 10px auto;
            max-width: 200px;
        }

        .download-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .download-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

  <?php 
  include "./header.php";
  renderNavbar()
  ?>

    <div class="containerUrl">
        <p>Enter your long URL here</p>
        <input type="text" id="url-input" placeholder="Enter URL" class="inputUrl" />
        <p>Your Description</p>
        <input type="text" id="description-input" placeholder="Enter Description" class="inputUrl" />
        <button class="button" onclick="shortenUrl()">Shorten URL</button>
        <div id="message" class="message"></div>
        <div id="shortened-url-container" class="shortened-url-container"></div>
        <div id="qr-code-container" class="qr-code-container"></div>
    </div>

  <?php 
  include "./footer.php";
  renderFooter()
  ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  const token = localStorage.getItem('authToken');
  
  // Regex for URL validation
  const urlRegex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;

  const shortenUrl = async () => {
    const url = document.getElementById('url-input').value;
    const description = document.getElementById('description-input').value;
    const messageElement = document.getElementById('message');
    const shortenedUrlContainer = document.getElementById('shortened-url-container');
    const qrCodeContainer = document.getElementById('qr-code-container');
    
    // Validate URL format
    if (!urlRegex.test(url)) {
      messageElement.textContent = "Please enter a valid URL.";
      messageElement.style.color = "red";
      return;
    }

    if (!token) {
      messageElement.textContent = "No authentication token found. Please log in.";
      messageElement.style.color = "red";
      return;
    }

    try {
      const encodedUrl = encodeURIComponent(url);
      const encodedDescription = encodeURIComponent(description);
      
      const response = await fetch(
        `http://34.76.194.134:5284/api/URL?url=${encodedUrl}&token=${token}&description=${encodedDescription}`,
        { method: 'POST' }
      );

      const data = await response.text(); // Read response as plain text

      if (response.ok) {
        messageElement.textContent = "";
        messageElement.style.color = "green";
        
        const shortenedUrl = data.trim(); // Assuming the response is just the ID
        const shortenedLink = shortenedUrl;
        
        const linkElement = document.createElement('a');
        linkElement.href = `http://bytely.xyz/${shortenUrl}`;
        linkElement.textContent = `bytely.xyz/${shortenedLink}`;
        linkElement.target = "_blank"; // Open in a new tab
        shortenedUrlContainer.innerHTML = ""; // Clear previous link
        shortenedUrlContainer.appendChild(linkElement);

        // Generate QR Code only if QRCode.js is loaded
        if (shortenedLink) {
          generateQrCode(shortenedLink);
        } else {
          messageElement.textContent = "QR Code generation failed. Please try again.";
          messageElement.style.color = "red";
        }

        document.getElementById('description-input').value = ""; // Clear description input
  
      } else {
        console.error("Error response:", data);
        messageElement.textContent = `Error: ${data || 'Failed to shorten URL'}`;
        messageElement.style.color = "red";
      }
    } catch (error) {
      console.error("Fetch Error:", error);
      messageElement.textContent = "Error shortening URL: " + error.message;
      messageElement.style.color = "red";
    }
  };

const generateQrCode = (url) => {
    const qrCodeContainer = document.getElementById('qr-code-container');
    
    // Ensure QRCode.js is available
    if (typeof QRCode !== "undefined") {
      // Clear previous QR code if any
      qrCodeContainer.innerHTML = "";

      // Create the QR code
      const qrCode = new QRCode(qrCodeContainer, {
        text: url,
        width: 128,
        height: 128,
        colorDark: "#000000",
        colorLight: "#ffffff",
      });

      // Wait for the QR code image to load, then add the download link
      qrCodeContainer.querySelector("img").onload = () => {
        const qrImage = qrCodeContainer.querySelector("img");
        const downloadButton = document.createElement('a');
        downloadButton.href = qrImage.src;
        downloadButton.download = "qr_code.png";
        downloadButton.textContent = "Download QR Code";
        downloadButton.classList.add("download-btn");

        qrCodeContainer.appendChild(downloadButton);
      };
    } else {
      console.error("QRCode.js is not available.");
    }
  };

</script>

</body>
</html>
