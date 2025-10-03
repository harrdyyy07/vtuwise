<?php
header('Content-Type: text/html; charset=utf-8');

// --- CONFIGURATION ---
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Default XAMPP password is blank
$db_name = 'vtu_notes_db';
$admin_user = 'admin';
$admin_pass = 'password123';

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTU NOTES - Installation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen font-sans">
    <div class="w-full max-w-2xl bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-center text-slate-800 mb-6">VTU NOTES Installer</h1>
        <div class="space-y-3">
HTML;

$logs = [];
$success = true;

// Step 1: Connect to MySQL
$conn = new mysqli($db_host, $db_user, $db_pass);
if ($conn->connect_error) {
    $logs[] = ['status' => 'error', 'message' => "MySQL Connection Failed: " . $conn->connect_error];
    $success = false;
} else {
    $logs[] = ['status' => 'success', 'message' => "MySQL Server Connection Successful."];

    // Step 2: Create Database
    if ($conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`") === TRUE) {
        $logs[] = ['status' => 'success', 'message' => "Database '$db_name' created or already exists."];
        $conn->select_db($db_name);

        // Step 3: Create Tables
        $tables = [
            "admin" => "CREATE TABLE IF NOT EXISTS `admin` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL
            );",
            "branches" => "CREATE TABLE IF NOT EXISTS `branches` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `short_name` VARCHAR(20) NOT NULL UNIQUE
            );",
            "semesters" => "CREATE TABLE IF NOT EXISTS `semesters` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `sem_number` INT NOT NULL UNIQUE
            );",
            "subjects" => "CREATE TABLE IF NOT EXISTS `subjects` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `branch_id` INT NOT NULL,
                `sem_id` INT NOT NULL,
                `subject_code` VARCHAR(20) NOT NULL,
                `subject_name` VARCHAR(255) NOT NULL,
                `credits` TINYINT NOT NULL, -- ADD THIS LINE
                FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`sem_id`) REFERENCES `semesters`(`id`) ON DELETE CASCADE
            );",
            "notes" => "CREATE TABLE IF NOT EXISTS `notes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `subject_id` INT NOT NULL,
                `module_number` INT NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `file_path` VARCHAR(255) NOT NULL,
                `file_type` ENUM('Module Notes', 'Model QP', 'Previous Year QP', 'Lab Manual', 'Notes', 'Question Paper') NOT NULL,
                `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
            );", // <-- THE MISSING COMMA WAS ADDED HERE

            // --- NEW TABLE FOR PENDING UPLOADS ---
            "pending_notes" => "CREATE TABLE IF NOT EXISTS `pending_notes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `subject_id` INT NOT NULL,
                `module_number` INT NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `file_path` VARCHAR(255) NOT NULL,
                `file_type` ENUM('Module Notes', 'Model QP', 'Previous Year QP', 'Lab Manual') NOT NULL,
                `uploader_name` VARCHAR(100) NOT NULL,
                `uploader_email` VARCHAR(100) NOT NULL,
                `status` ENUM('pending', 'approved', 'declined') DEFAULT 'pending',
                `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
            );",
            
            // --- NEW TABLE FOR BLOG POSTS ---
            "blogs" => "CREATE TABLE IF NOT EXISTS `blogs` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `slug` VARCHAR(255) NOT NULL UNIQUE,
                `content` TEXT NOT NULL,
                `featured_image` VARCHAR(255) NOT NULL,
                `author` VARCHAR(100) DEFAULT 'Admin',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );",
            
             // --- NEW TABLE FOR SUBSCRIBERS ---
            "subscribers" => "CREATE TABLE IF NOT EXISTS `subscribers` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `email` VARCHAR(255) NOT NULL UNIQUE,
                `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );"
        ];

        foreach ($tables as $name => $sql) {
            if ($conn->query($sql) === TRUE) {
                $logs[] = ['status' => 'success', 'message' => "Table '$name' created successfully."];
            } else {
                $logs[] = ['status' => 'error', 'message' => "Error creating table '$name': " . $conn->error];
                $success = false;
            }
        }

        // ... (rest of the file is the same)
        
        // Step 4: Insert Default Data
        if ($success) {
            // Admin user
            $hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT IGNORE INTO `admin` (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $admin_user, $hashed_pass);
            $stmt->execute();
            $logs[] = ['status' => 'info', 'message' => "Default admin '{$admin_user}' created/updated."];

            // Branches
            $branches_data = [
                ['Computer Science & Engg.', 'CSE'],
                ['Information Science & Engg.', 'ISE'],
                ['Electronics & Communication', 'ECE'],
                ['Mechanical Engg.', 'MECH'],
                ['Civil Engg.', 'CIVIL']
            ];
            $stmt = $conn->prepare("INSERT IGNORE INTO `branches` (name, short_name) VALUES (?, ?)");
            foreach($branches_data as $branch) {
                $stmt->bind_param("ss", $branch[0], $branch[1]);
                $stmt->execute();
            }
            $logs[] = ['status' => 'info', 'message' => 'Default branches inserted.'];
            
            // Semesters
            $stmt = $conn->prepare("INSERT IGNORE INTO `semesters` (sem_number) VALUES (?)");
            for($i=1; $i<=8; $i++){
                $stmt->bind_param("i", $i);
                $stmt->execute();
            }
             $logs[] = ['status' => 'info', 'message' => 'Semesters 1-8 inserted.'];
        }

    } else {
        $logs[] = ['status' => 'error', 'message' => "Error creating database: " . $conn->error];
        $success = false;
    }

    // Step 5: Create Uploads Folder
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }
    // --- NEW DIRECTORY FOR PENDING FILES ---
    if (!is_dir('uploads/pending')) {
        if (mkdir('uploads/pending', 0755, true)) {
            $logs[] = ['status' => 'success', 'message' => "Directory 'uploads/pending' created."];
        } else {
            $logs[] = ['status' => 'error', 'message' => "Failed to create 'uploads/pending' directory."];
            $success = false;
        }
    } else {
        $logs[] = ['status' => 'info', 'message' => "Directory 'uploads/pending' already exists."];
    }
    $conn->close();
}

// Display logs
foreach ($logs as $log) {
    $color = 'text-green-700';
    if ($log['status'] === 'error') $color = 'text-red-700';
    if ($log['status'] === 'info') $color = 'text-blue-700';
    echo "<div class='p-3 bg-slate-50 rounded-lg font-medium {$color}'>{$log['message']}</div>";
}

// Final message and redirect
if ($success) {
    echo "<div class='mt-6 p-4 bg-green-100 text-green-800 rounded-lg text-center'>Installation Complete! Redirecting to homepage...</div>";
    echo "<script>setTimeout(() => { window.location.href = 'index.php'; }, 3000);</script>";
} else {
    echo "<div class='mt-6 p-4 bg-red-100 text-red-800 rounded-lg text-center'>Installation Failed! Please check your configuration and try again.</div>";
}

echo '</div></div></body></html>';
?>