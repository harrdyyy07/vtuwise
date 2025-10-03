<?php
require_once 'common/header.php';


// --- SEO & Page Title Logic ---
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
if ($subject_id === 0) {
    die("Invalid subject selected.");
}

// Fetch Subject Details for SEO
$subject_stmt = $conn->prepare("SELECT subject_name, subject_code FROM subjects WHERE id = ?");
$subject_stmt->bind_param("i", $subject_id);
$subject_stmt->execute();
$subject = $subject_stmt->get_result()->fetch_assoc();

if ($subject) {
    $page_title = "{$subject['subject_name']} ({$subject['subject_code']}) Notes & QPs";
    $meta_description = "Download PDF notes, model question papers, and previous year question papers for {$subject['subject_name']} ({$subject['subject_code']}).";
} else {
    $page_title = "Notes Not Found";
    $meta_description = "The requested subject could not be found.";
}
// --- END SEO Logic ---

$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
if ($subject_id === 0) {
    die("Invalid subject selected.");
}

// Fetch Subject Details
$subject_stmt = $conn->prepare("SELECT subject_name, subject_code FROM subjects WHERE id = ?");
$subject_stmt->bind_param("i", $subject_id);
$subject_stmt->execute();
$subject = $subject_stmt->get_result()->fetch_assoc();

// Fetch Notes and separate them into modules and previous year QPs
$notes_stmt = $conn->prepare("SELECT * FROM notes WHERE subject_id = ? ORDER BY module_number, file_type");
$notes_stmt->bind_param("i", $subject_id);
$notes_stmt->execute();
$notes_result = $notes_stmt->get_result();

$modules = [];
$previous_year_qps = [];
while ($note = $notes_result->fetch_assoc()) {
    if ($note['module_number'] == 0) {
        $previous_year_qps[] = $note;
    } else {
        $modules[$note['module_number']][] = $note;
    }
}
?>

<div class="container mx-auto py-12 px-4">
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-slate-800"><?php echo htmlspecialchars($subject['subject_name']); ?></h1>
        <p class="text-lg text-slate-500"><?php echo htmlspecialchars($subject['subject_code']); ?></p>
    </div>

    <!-- The on-page PDF viewer has been removed -->
    
    <!-- Modules and Notes List -->
    <div class="space-y-8" id="notes-list-container">
        <?php if (!empty($modules) || !empty($previous_year_qps)): ?>
            
            <!-- Display Regular Modules First -->
            <?php ksort($modules); foreach ($modules as $module_number => $notes): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 border">
                    <h2 class="text-2xl font-bold mb-4 border-b pb-3">Module <?php echo $module_number; ?></h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php foreach($notes as $note): 
                            $icon = 'fa-file-alt text-blue-500'; // default
                            if (str_contains($note['file_type'], 'QP')) $icon = 'fa-file-signature text-green-500';
                            elseif ($note['file_type'] == 'Lab Manual') $icon = 'fa-flask text-purple-500';
                        ?>
                            <!-- Changed back to an <a> tag that opens view.php in a new tab -->
                            <a href="view.php?note_id=<?php echo $note['id']; ?>" target="_blank" class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-slate-50 hover:shadow-md transition text-left">
                                <i class="fas <?php echo $icon; ?> fa-2x"></i>
                                <div>
                                    <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($note['title']); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo htmlspecialchars($note['file_type']); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Display Previous Year Question Papers Separately -->
            <?php if (!empty($previous_year_qps)): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 border">
                    <h2 class="text-2xl font-bold mb-4 border-b pb-3">Previous Year Question Papers</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php foreach($previous_year_qps as $note): ?>
                            <a href="view.php?note_id=<?php echo $note['id']; ?>" target="_blank" class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-slate-50 hover:shadow-md transition text-left">
                                <i class="fas fa-file-signature text-green-500 fa-2x"></i>
                                <div>
                                    <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($note['title']); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo htmlspecialchars($note['file_type']); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <p class="text-slate-500 text-lg">No notes have been uploaded for this subject yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- All JavaScript for the on-page viewer has been removed -->

<?php require_once 'common/footer.php'; ?>