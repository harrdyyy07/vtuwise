# VTU Notes Web Application

A comprehensive web application for VTU students to find and share study materials, including notes, question papers, and lab manuals. The site also includes helpful tools like an advanced SGPA calculator.

## Features

-   Dynamic Hero Slider
-   Branch and Semester-wise subject Browse
-   P-Cycle and C-Cycle for first-year students
-   AJAX-powered live search for subjects
-   Multi-level navigation dropdown
-   User-facing file upload system with admin approval
-   Admin panel for managing subjects, notes, and user uploads
-   Advanced SGPA calculator with PDF report generation
-   Persistent Dark/Light mode theme switcher

## Setup Instructions

### Prerequisites
-   XAMPP (or any Apache, MySQL, PHP server stack)
-   A web browser
-   A database tool like phpMyAdmin

### Installation
1.  Place the `vtu_notes` project folder inside your `htdocs` directory.
2.  Start Apache and MySQL services from the XAMPP Control Panel.
3.  Open your browser and navigate to `http://localhost/phpmyadmin/`. Create a new database named `vtu_notes_db`.
4.  Navigate to `http://localhost/vtu_notes/install.php` to automatically set up all the necessary tables.
5.  The website is now live at `http://localhost/vtu_notes/`.

### Admin Credentials
-   **URL**: `http://localhost/vtu_notes/admin/`
-   **Username**: admin
-   **Password**: password123

## Folder Structure
-   `/admin/`: Contains all admin panel files.
-   `/common/`: Includes shared files like the header, footer, and database configuration.
-   `/uploads/`: Stores all approved PDF files.
-   `/uploads/pending/`: Temporarily stores user-submitted files awaiting admin approval.
-   **Root Files**: `index.php`, `subjects.php`, `notes.php`, etc., are the main user-facing pages.