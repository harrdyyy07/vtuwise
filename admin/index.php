<?php 
require_once 'common/header.php';

// Fetch stats
$total_subjects = $conn->query("SELECT COUNT(*) as count FROM subjects")->fetch_assoc()['count'];
$total_notes = $conn->query("SELECT COUNT(*) as count FROM notes")->fetch_assoc()['count'];
$total_branches = $conn->query("SELECT COUNT(*) as count FROM branches")->fetch_assoc()['count'];
?>

<h1 class="text-3xl font-bold text-slate-800 mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-4 bg-blue-100 text-blue-600 rounded-full">
                <i class="fas fa-book fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-slate-500">Total Subjects</p>
                <p class="text-3xl font-bold"><?php echo $total_subjects; ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-4 bg-green-100 text-green-600 rounded-full">
                <i class="fas fa-file-alt fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-slate-500">Total Notes Files</p>
                <p class="text-3xl font-bold"><?php echo $total_notes; ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="p-4 bg-purple-100 text-purple-600 rounded-full">
                <i class="fas fa-sitemap fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-slate-500">Total Branches</p>
                <p class="text-3xl font-bold"><?php echo $total_branches; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Quick Links</h2>
    <div class="flex space-x-4">
        <a href="manage_subjects.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Manage Subjects</a>
        <a href="manage_notes.php" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Upload Notes</a>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>