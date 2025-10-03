<?php
require_once dirname(__DIR__, 2) . '/common/config.php';

// Protect admin pages
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>
<body class="bg-slate-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-800 text-white flex flex-col">
            <div class="p-4 border-b border-slate-700">
                <a href="index.php" class="text-2xl font-bold">Admin Panel</a>
            </div>
            <nav class="flex-grow p-4 space-y-2">
                <a href="index.php" class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-700">
                    <i class="fas fa-tachometer-alt fa-fw"></i><span>Dashboard</span>
                </a>
                <a href="manage_subjects.php" class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-700">
                    <i class="fas fa-book fa-fw"></i><span>Manage Subjects</span>
                </a>
                <a href="manage_notes.php" class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-700">
                    <i class="fas fa-file-upload fa-fw"></i><span>Manage Notes</span>
                </a>
                
                <a href="manage_uploads.php" class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-700">
                    <i class="fas fa-check-circle fa-fw"></i><span>Manage Uploads</span>
                </a>
                <a href="manage_blogs.php" class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-700">
                    <i class="fas fa-newspaper fa-fw"></i><span>Manage Blogs</span>
                </a>

                <a href="manage_subscribers.php" class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-700">
                    <i class="fas fa-newspaper fa-fw"></i><span>Manage Subscribers</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700">
                <a href="login.php?action=logout" class="flex items-center space-x-3 p-2 rounded-md hover:bg-red-500">
                    <i class="fas fa-sign-out-alt fa-fw"></i><span>Logout</span>
                </a>
            </div>
        </aside>
        
        <main class="flex-1 p-8">