# ShikPoshan (شیک پوشان)

A PHP-based web application for [**Project About Online Fashion Store.**]. This project was developed as a school project.

## Features

* User Registration (with password hashing)
* User Login and Logout
* Session Management
* Password Recovery (placeholder mechanism, logs reset link)
* User Dashboard:
    * Edit user email and password
    * Delete user account (with password confirmation)
* Image Upload Functionality:
    * Server-side validation for file type (JPG, PNG, GIF) and size
    * Generation of unique filenames
    * Optional product name association
* Image Display Gallery:
    * Lists uploaded images (newest first)
    * Thumbnail previews
    * Option to delete individual images (requires secure backend implementation)
* Homepage with dynamic slideshow and content sections
* Responsive "Glassmorphism" UI theme across pages
* Secure handling of database credentials using an external configuration file.

## Technologies Used

* PHP (procedural with `mysqli`)
* MySQL
* HTML5
* CSS3 (Flexbox, Grid, CSS Variables, Media Queries)
* JavaScript (for slideshow, client-side form validation enhancements)
* IonIcons (for icons)

## Project Structure Overview

```
ShikPoshan/
├── database_setup/
│   └── schema.sql           # SQL file for database structure (recommended)
├── fonts/                   # Font files (Vazir, IranNastaliq)
├── html/
│   └── products.html        # Static products page example
├── images/
│   ├── logos/
│   │   └── hat.png
│   ├── loginbg.png          # Background for login/glass pages
│   ├── registerbg.jpg       # Specific background for register (if used)
│   └── ... (other images for slideshow, products, etc.) ...
├── js/
│   ├── slide.js
│   ├── login.js
│   ├── register.js
│   ├── recovery.js
│   ├── edit_validation.js
│   └── delete_validation.js
├── php/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── edit.php
│   ├── delete.php
│   └── recovery.php
├── styles/                  # Main CSS stylesheets
│   ├── style.css            # Main site styles
│   ├── loginphp.css         # Styles for login.php
│   ├── registerphp.css      # Styles for register.php
│   ├── recoveryphp.css      # Styles for recovery.php
│   ├── user_dashboard.css
│   ├── user_edit.css
│   ├── user_delete.css
│   ├── upload.css           # Styles for upload/main.php
│   ├── uploaded.css         # Styles for upload/display.php
│   └── tos.css
├── upload/
│   ├── main.php             # File upload form page
│   ├── upload.php           # Handles file uploads
│   ├── display.php          # Displays uploaded files
│   ├── delete_image.php     # Handles image deletion
│   └── uploads/             # Directory for uploaded files (needs write permissions)
├── config.php.example       # Example database configuration
├── setup_database.php       # PHP script to create database tables (alternative to .sql)
├── index.php                # Homepage
├── LICENSE                  # Project License
└── README.md                # This file
```

## Setup Instructions

To set up and run this project locally, you will need a web server environment that supports PHP and MySQL (like XAMPP, WAMP, MAMP, or a custom LAMP/LEMP stack).

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/ParsaR18/ShikPoshan.git
    cd ShikPoshan
    ```

2.  **Configure Database Connection:**
    * In the root of the `ShikPoshan/` project, find the `config.php.example` file.
    * Make a copy of this file and rename the copy to `config.php`.
    * Open `config.php` and update the database credentials with your local MySQL settings:
        ```php
        define('DB_HOST', 'localhost');
        define('DB_USERNAME', 'your_local_db_username'); // e.g., 'root'
        define('DB_PASSWORD', 'your_local_db_password'); // e.g., '' or your root password
        define('DB_NAME', 'project'); // or the name you want for your database
        ```
    * **Important Security Note:** The `config.php` file contains sensitive credentials and is correctly listed in `.gitignore` so it won't be committed. **Never commit your actual `config.php` file.**

3.  **Database Setup (Choose ONE method):**

    * **Method A: Using the PHP Setup Script (Recommended for ease)**
        1.  Ensure your `config.php` is correctly set up with user credentials that have permission to create databases and tables (e.g., `root` on a local dev server).
        2.  Navigate to `http://localhost/ShikPoshan/setup_database.php` in your web browser. This script will attempt to create the database (if it doesn't exist) and the required tables (`users`, `password_resets`).
        3.  Review the messages on the page to confirm success.
        4.  **Important:** After successful setup, it is recommended to delete or rename `setup_database.php` from your web server for security.

    * **Method B: Using an `.sql` File**
        1.  Ensure you have a SQL schema file (e.g., `ShikPoshan/database_setup/schema.sql`). This file should contain the `CREATE TABLE` statements. (You can generate this from your working local database using a tool like phpMyAdmin if the PHP setup script isn't used).
        2.  Manually create a database on your MySQL server (e.g., named `project` or whatever you specified in `config.php`).
        3.  Import the `schema.sql` file into your newly created database. This can be done via:
            * phpMyAdmin: Select the database, go to the "Import" tab, and upload the `.sql` file.
            * MySQL Command Line: `mysql -u YOUR_DB_USERNAME -p YOUR_DB_NAME < database_setup/schema.sql`

4.  **Directory Permissions:**
    * Ensure the `ShikPoshan/upload/uploads/` directory exists and is **writable** by your web server process (e.g., `www-data`, `apache`). This is necessary for the file upload functionality.
        * On Linux/macOS, you might use commands like `mkdir -p ShikPoshan/upload/uploads` and then `sudo chown www-data:www-data ShikPoshan/upload/uploads` (replace `www-data` with your web server's user) and `sudo chmod 755 ShikPoshan/upload/uploads`. For local development, more permissive permissions like `777` are sometimes used but are not recommended for production.

5.  **Web Server Configuration:**
    * Place the entire `ShikPoshan` project folder into your web server's document root (e.g., `htdocs` for XAMPP/WAMP, `www` for MAMP).
    * Ensure your web server (e.g., Apache) is running with PHP enabled.

6.  **Access the Project:**
    * Open your web browser and navigate to the project directory (e.g., `http://localhost/ShikPoshan/` or `http://localhost/ShikPoshan/index.php`).

## Usage Notes

* **Default Admin/User:** There is no default admin user created by the setup script. You will need to register the first user.
* **Password Recovery:** The password recovery feature is a placeholder. It generates a reset link and logs it to the PHP error log instead of sending an email. To test it, you'll need to check your PHP error logs for the link.

## Security Considerations for Production

* Always use strong, unique passwords for your database users. Do not use the `root` user with an empty password for a live application. Create a dedicated database user with only the necessary privileges for the `project` database.
* Keep your `config.php` file (containing actual credentials) secure and out of public access or version control.
* Sanitize all user inputs thoroughly (beyond current implementations if moving to production).
* Implement CSRF protection on all forms that change state.
* Regularly update your PHP version and any libraries used.
* Configure your web server to prevent directory listing and to disallow execution of scripts in the `uploads/` directory.

## License

This project is licensed under the MIT License - see the `LICENSE` file for details.

---
*Developed by: Parsa Rezaei (as per copyright notice in original `index.php`)*