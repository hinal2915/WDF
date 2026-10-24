# StudentHub - University Management Portal

A full-stack, responsive web application for managing student registrations, event tracking, and administrative operations. 

**Developer:** 25DCE083
**Department:** Computer Engineering (CHARUSAT)

## Features
* **Role-Based Access Control (RBAC):** Secure admin dashboard and student portals.
* **RESTful JSON API & AJAX:** Asynchronous CRUD operations for student records using the Fetch API without page reloads.
* **Event Management:** Secure file upload handling with server-side validation (size, MIME type) for event posters.
* **System Audit Logging:** Automated tracking of authentication events and administrative actions.
* **Responsive UI:** CSS Grid/Flexbox layouts optimized for cross-device compatibility.

## Tech Stack
* **Frontend:** HTML5, CSS3, Vanilla JavaScript (Fetch API)
* **Backend:** PHP 8.2 (Session Management, File Handling)
* **Database:** MySQL (MySQLi with Prepared Statements)
* **Environment:** XAMPP (Apache)

## Installation & Setup
1. Clone the repository to your local server directory (e.g., `C:\xampp\htdocs\`).
2. Import the `studenthub_db.sql` file into your local MySQL server using phpMyAdmin.
3. Update database credentials in the PHP connection strings if necessary.
4. Launch the application via `http://localhost/StudentHub/login.php`.