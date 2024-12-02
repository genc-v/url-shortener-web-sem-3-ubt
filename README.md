![alt](https://i.ibb.co/NYYLTPz/logo.png)
# URL Shortener
A feature-rich URL shortener built using PHP, .NET 7, and CSS. This application allows users to shorten, search, and modify URLs. It can be run locally using XAMPP or accessed online at [bytely.xyz](https://bytely.xyz).

## Features

- **Shorten URLs:** Generate short URLs for easier sharing.
- **Search URLs:** Search and manage previously shortened URLs.
- **Modify URLs:** Update or customize shortened links.

---

## Getting Started

### Running Locally with XAMPP

To run this project locally, follow these steps:

1. **Install XAMPP:**
   Download and install [XAMPP](https://www.apachefriends.org/index.html) if not already installed.

2. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/url-shortener.git
   ```
   Replace `yourusername` with your GitHub username.

3. **Place Files in XAMPP's `htdocs` Directory:**
   Copy all project files into the `xampp/htdocs/url-shortener` folder.

4. **Enable Custom 404 Handling:**
   Open the `httpd.conf` file located in your XAMPP Apache configuration folder (e.g., `xampp/apache/conf/httpd.conf`).

   Find and edit/add the following line:
   ```apache
   ErrorDocument 404 /index.html
   ```
   And run on port 3000 (any other port will not work due to cors)

5. **Start XAMPP:**
   Launch XAMPP and start the Apache server.

6. **Access the Application Locally:**
   Open your browser and navigate to:
   ```
   http://localhost/url-shortener
   ```

---

### Using Online Version

If you prefer not to run the application locally, visit the live version here:  
👉 [bytely.xyz](https://bytely.xyz)

---

## Tech Stack

- **Frontend:** CSS
- **Backend:** PHP and .NET 7
- **Server:** Apache (via XAMPP for local setup)

---

## Contributing

1. Fork the repository.
2. Create a new branch: `git checkout -b feature-name`.
3. Make your changes and commit: `git commit -m "Add feature-name"`.
4. Push to the branch: `git push origin feature-name`.
5. Submit a pull request.

---

## License

No licence just for fun
