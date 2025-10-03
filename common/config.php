<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is blank
define('DB_NAME', 'vtu_notes_db');

// Create Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");

// --- CORRECTED BASE URL LOGIC ---
// This new logic correctly finds the project's root folder (e.g., 'vtu_notes')
// and creates a reliable base URL that works everywhere on the site.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
// Get the path parts of the current script's directory
$path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
// The project folder is assumed to be the first directory after the root
// This handles cases like /vtu_notes/ and /vtu_notes/admin/ correctly
$project_root = '/' . ($path_parts[1] ?? '');
// Ensure there's a trailing slash
if ($project_root === '//') {
    $project_root = '/';
} else {
    $project_root = rtrim($project_root, '/') . '/';
}
define('BASE_URL', $protocol . $host . $project_root);
// --- END OF CORRECTION ---

?>