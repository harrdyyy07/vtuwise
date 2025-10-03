<?php
require_once 'common/header.php';

// Handle File Upload and Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'upload') {
        $subject_id = (int)$_POST['subject_id'];
        // Module number is 0 for Previous Year QPs
        $module_number = ($_POST['file_type'] == 'Previous Year QP') ? 0 : (int)$_POST['module_number'];
        $title = trim($_POST['title']);
        $file_type = $_POST['file_type'];
        
        if (isset($_FILES['note_file']) && $_FILES['note_file']['error'] == 0) {
            $file_name = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '-', basename($_FILES['note_file']['name']));
            $target_path = dirname(__DIR__) . '/uploads/' . $file_name;
            
            if (move_uploaded_file($_FILES['note_file']['tmp_name'], $target_path)) {
                $stmt = $conn->prepare("INSERT INTO notes (subject_id, module_number, title, file_path, file_type) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iisss", $subject_id, $module_number, $title, $file_name, $file_type);
                $stmt->execute();
            }
        }
    }
    elseif ($_POST['action'] == 'delete') {
        $note_id = (int)$_POST['note_id'];
        
        $stmt_file = $conn->prepare("SELECT file_path FROM notes WHERE id = ?");
        $stmt_file->bind_param("i", $note_id);
        $stmt_file->execute();
        $file_path = $stmt_file->get_result()->fetch_assoc()['file_path'];

        $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->bind_param("i", $note_id);
        if ($stmt->execute() && $file_path) {
            $full_path = dirname(__DIR__) . '/uploads/' . $file_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }
    }
    // Redirect to the same subject page to see changes
    header('Location: manage_notes.php?subject_id=' . $_POST['subject_id']);
    exit;
}

$subjects = $conn->query("SELECT id, subject_name, subject_code FROM subjects ORDER BY subject_code");
$selected_subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
$notes = [];
if ($selected_subject_id > 0) {
    $notes_stmt = $conn->prepare("SELECT * FROM notes WHERE subject_id = ? ORDER BY module_number, file_type");
    $notes_stmt->bind_param("i", $selected_subject_id);
    $notes_stmt->execute();
    $notes_result = $notes_stmt->get_result();
    while($row = $notes_result->fetch_assoc()) {
        $notes[] = $row;
    }
}
?>
<h1 class="text-3xl font-bold text-slate-800 mb-6">Manage Notes</h1>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <form method="GET" class="flex items-end space-x-4">
        <div>
            <label for="subject-select" class="block font-medium">Select Subject</label>
            <select name="subject_id" id="subject-select" class="w-full md:w-96 p-2 border rounded-md" onchange="this.form.submit()">
                <option value="">-- Choose a Subject --</option>
                <?php mysqli_data_seek($subjects, 0); while($s = $subjects->fetch_assoc()): ?>
                    <option value="<?php echo $s['id']; ?>" <?php if ($selected_subject_id == $s['id']) echo 'selected'; ?>>
                        <?php echo $s['subject_code'] . ' - ' . htmlspecialchars($s['subject_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>
</div>

<?php if ($selected_subject_id > 0): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Upload New File</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="subject_id" value="<?php echo $selected_subject_id; ?>">
             <div>
                <label for="file-type">File Type</label>
                <select name="file_type" id="file-type" class="w-full p-2 border rounded" required>
                    <option>Module Notes</option>
                    <option>Model QP</option> <option>Previous Year QP</option>
                    <option>Lab Manual</option>
                </select>
            </div>
            <div id="module-select-container">
                <label for="module-number">Module Number</label>
                <select id="module-number" name="module_number" class="w-full p-2 border rounded" required>
                    <?php for($i=1; $i<=5; $i++) echo "<option value='$i'>Module $i</option>"; ?>
                </select>
            </div>
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="w-full p-2 border rounded" required placeholder="e.g., Module 1 Notes (Official)">
            </div>
            <div>
                <label for="note-file">PDF File</label>
                <input type="file" id="note-file" name="note_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".pdf" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Upload</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Uploaded Files</h2>
        <div id="filter-tabs" class="flex flex-wrap gap-2 border-b pb-4 mb-4">
            <button class="filter-btn active bg-blue-600 text-white px-3 py-1 text-sm rounded-full" data-filter="all">All</button>
            <?php for ($i=1; $i<=5; $i++): ?>
                <button class="filter-btn bg-gray-200 text-gray-700 px-3 py-1 text-sm rounded-full" data-filter="<?php echo $i; ?>">Module <?php echo $i; ?></button>
            <?php endfor; ?>
             <button class="filter-btn bg-gray-200 text-gray-700 px-3 py-1 text-sm rounded-full" data-filter="qp">QPs</button>
        </div>
        <ul id="notes-list" class="space-y-3 max-h-80 overflow-y-auto pr-2">
            <?php if (!empty($notes)): ?>
                <?php foreach($notes as $note): ?>
                <li class="note-item flex justify-between items-center p-2 border rounded-md" data-module="<?php echo $note['module_number']; ?>" data-type="<?php echo $note['file_type']; ?>">
                    <div>
                        <p class="font-semibold"><?php echo htmlspecialchars($note['title']); ?></p>
                        <p class="text-sm text-slate-500">
                            <?php 
                                if($note['module_number'] > 0) {
                                    echo "Module " . $note['module_number'] . " - ";
                                }
                                echo htmlspecialchars($note['file_type']); 
                            ?>
                        </p>
                    </div>
                    <form method="POST" onsubmit="return confirm('Delete this file permanently?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                        <input type="hidden" name="subject_id" value="<?php echo $selected_subject_id; ?>">
                        <button type="submit" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
                    </form>
                </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-slate-500">No files uploaded for this subject yet.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- File Type Dropdown Logic ---
    const fileTypeSelect = document.getElementById('file-type');
    const moduleSelectContainer = document.getElementById('module-select-container');

    fileTypeSelect.addEventListener('change', function() {
        // If "Previous Year QP" is selected, hide the module number dropdown
        if (this.value === 'Previous Year QP') {
            moduleSelectContainer.classList.add('hidden');
        } else {
            moduleSelectContainer.classList.remove('hidden');
        }
    });

    // --- Filter Tabs Logic ---
    const filterTabsContainer = document.getElementById('filter-tabs');
    const noteItems = document.querySelectorAll('.note-item');

    filterTabsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('filter-btn')) {
            // Update active button style
            filterTabsContainer.querySelector('.active').classList.remove('active', 'bg-blue-600', 'text-white');
            filterTabsContainer.querySelector('.active').classList.add('bg-gray-200', 'text-gray-700');
            e.target.classList.add('active', 'bg-blue-600', 'text-white');
            e.target.classList.remove('bg-gray-200', 'text-gray-700');

            const filter = e.target.dataset.filter;
            
            noteItems.forEach(item => {
                const itemModule = item.dataset.module;
                const itemType = item.dataset.type;
                let show = false;

                if (filter === 'all') {
                    show = true;
                } else if (filter === 'qp') {
                    if (itemType.includes('QP')) {
                        show = true;
                    }
                } else {
                    if (itemModule === filter) {
                        show = true;
                    }
                }

                item.style.display = show ? 'flex' : 'none';
            });
        }
    });
});
</script>
<?php endif; ?>

<?php require_once 'common/footer.php'; ?>