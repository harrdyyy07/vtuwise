<?php
require_once 'common/config.php';

// --- BLOCK 1: Handle AJAX Requests ---
// This block is ONLY for the JavaScript dropdowns. It runs, sends data, and stops.
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] == 'get_semesters') {
        $result = $conn->query("SELECT id, sem_number FROM semesters ORDER BY sem_number ASC");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
    
    if ($_POST['action'] == 'get_subjects') {
        $branch_id = (int)$_POST['branch_id'];
        $sem_id = (int)$_POST['sem_id'];
        $stmt = $conn->prepare("SELECT id, subject_name, subject_code FROM subjects WHERE branch_id = ? AND sem_id = ? ORDER BY subject_code");
        $stmt->bind_param("ii", $branch_id, $sem_id);
        $stmt->execute();
        echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }
    exit; // Crucial: This stops the script from rendering the HTML below for AJAX calls.
}

// --- BLOCK 2: Handle Full Form Submission ---
// This block runs ONLY when the user clicks the final "Submit for Review" button.
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = (int)$_POST['subject_id'];
    $file_type = $_POST['file_type'];
    $module_number = ($file_type == 'Previous Year QP') ? 0 : (int)$_POST['module_number'];
    $title = trim($_POST['title']);
    $uploader_name = trim($_POST['uploader_name']);
    $uploader_email = trim($_POST['uploader_email']);

    if ($subject_id > 0 && !empty($title) && !empty($uploader_name) && filter_var($uploader_email, FILTER_VALIDATE_EMAIL) && isset($_FILES['note_file']) && $_FILES['note_file']['error'] == 0) {
        $pending_dir = 'uploads/pending/';
        $file_name = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '-', basename($_FILES['note_file']['name']));
        $target_path = $pending_dir . $file_name;

        if (move_uploaded_file($_FILES['note_file']['tmp_name'], $target_path)) {
            $stmt = $conn->prepare("INSERT INTO pending_notes (subject_id, module_number, title, file_path, file_type, uploader_name, uploader_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssss", $subject_id, $module_number, $title, $file_name, $file_type, $uploader_name, $uploader_email);
            
            if ($stmt->execute()) {
                $message = '<div class="bg-green-100 text-green-700 p-4 rounded-lg text-center"><strong>Success!</strong> Your file has been submitted for review. Thank you for your contribution!</div>';
            } else {
                unlink($target_path);
                $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg text-center"><strong>Error!</strong> Could not save your submission to the database.</div>';
            }
        } else {
            $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg text-center"><strong>Error!</strong> Could not upload your file.</div>';
        }
    } else {
        $message = '<div class="bg-red-100 text-red-700 p-4 rounded-lg text-center"><strong>Error!</strong> Please fill all fields correctly and select a file.</div>';
    }
}

// --- BLOCK 3: Display the Page ---
// This part runs for a normal page load.
require_once 'common/header.php';
$branches = $conn->query("SELECT * FROM branches ORDER BY name");
?>

<div class="container mx-auto py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-slate-800">Contribute to VTU NOTES</h1>
            <p class="text-lg text-slate-500 mt-2">Share your notes and help fellow students.</p>
        </div>
        
        <?php echo $message; ?>

        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            <form id="upload-form" action="upload.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Step 1: Branch -->
                <div>
                    <label class="block text-lg font-semibold text-slate-700">Step 1: Select Branch</label>
                    <select id="branch-select" name="branch_id" class="w-full p-3 mt-2 border border-gray-300 rounded-lg" required>
                        <option value="">-- Choose Branch --</option>
                        <?php while($b = $branches->fetch_assoc()): ?>
                            <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Step 2: Semester -->
                <div id="semester-step" class="hidden">
                    <label class="block text-lg font-semibold text-slate-700">Step 2: Select Semester</label>
                    <select id="semester-select" name="sem_id" class="w-full p-3 mt-2 border border-gray-300 rounded-lg" required></select>
                </div>

                <!-- Step 3: Subject -->
                <div id="subject-step" class="hidden">
                    <label class="block text-lg font-semibold text-slate-700">Step 3: Select Subject</label>
                    <select id="subject-select" name="subject_id" class="w-full p-3 mt-2 border border-gray-300 rounded-lg" required></select>
                </div>

                <!-- Step 4: File Details -->
                <div id="details-step" class="hidden space-y-6">
                    <h2 class="text-lg font-semibold text-slate-700 border-t pt-6">Step 4: Provide File Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="file-type">File Type</label>
                            <select name="file_type" id="file-type" class="w-full p-3 mt-1 border rounded" required>
                                <option>Module Notes</option>
                                <option>Model QP</option>
                                <option>Previous Year QP</option>
                                <option>Lab Manual</option>
                            </select>
                        </div>
                        <div id="module-select-container">
                            <label for="module-number">Module</label>
                            <select id="module-number" name="module_number" class="w-full p-3 mt-1 border rounded">
                                <?php for($i=1; $i<=5; $i++) echo "<option value='$i'>Module $i</option>"; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="title">File Title (e.g., Module 1 Official Notes)</label>
                        <input type="text" id="title" name="title" class="w-full p-3 mt-1 border rounded" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div>
                            <label for="uploader-name">Your Name</label>
                            <input type="text" id="uploader-name" name="uploader_name" class="w-full p-3 mt-1 border rounded" required>
                        </div>
                        <div>
                            <label for="uploader-email">Your Email</label>
                            <input type="email" id="uploader-email" name="uploader_email" class="w-full p-3 mt-1 border rounded" required>
                        </div>
                    </div>
                    <div>
                        <label for="note-file">Upload PDF File</label>
                        <input type="file" id="note-file" name="note_file" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".pdf" required>
                    </div>
                </div>

                <div id="submit-button-container" class="hidden pt-6 border-t">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold text-lg p-3 rounded-lg hover:bg-blue-700">Submit for Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// The JavaScript code was correct and does not need to be changed.
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('upload-form');
    const branchSelect = document.getElementById('branch-select');
    const semesterStep = document.getElementById('semester-step');
    const semesterSelect = document.getElementById('semester-select');
    const subjectStep = document.getElementById('subject-step');
    const subjectSelect = document.getElementById('subject-select');
    const detailsStep = document.getElementById('details-step');
    const submitContainer = document.getElementById('submit-button-container');
    const fileTypeSelect = document.getElementById('file-type');
    const moduleSelectContainer = document.getElementById('module-select-container');

    const fetchData = async (action, body) => {
        try {
            const response = await fetch('upload.php', { method: 'POST', body });
            return await response.json();
        } catch (error) {
            console.error('AJAX Error:', error);
            return [];
        }
    };

    branchSelect.addEventListener('change', async () => {
        const branchId = branchSelect.value;
        subjectStep.classList.add('hidden');
        detailsStep.classList.add('hidden');
        submitContainer.classList.add('hidden');
        if (!branchId) {
            semesterStep.classList.add('hidden');
            return;
        }
        const formData = new FormData();
        formData.append('action', 'get_semesters');
        const semesters = await fetchData('get_semesters', formData);
        
        semesterSelect.innerHTML = '<option value="">-- Choose Semester --</option>';
        semesters.forEach(s => semesterSelect.innerHTML += `<option value="${s.id}">Semester ${s.sem_number}</option>`);
        semesterStep.classList.remove('hidden');
    });

    semesterSelect.addEventListener('change', async () => {
        const semId = semesterSelect.value;
        detailsStep.classList.add('hidden');
        submitContainer.classList.add('hidden');
        if (!semId) {
            subjectStep.classList.add('hidden');
            return;
        }
        const formData = new FormData();
        formData.append('action', 'get_subjects');
        formData.append('branch_id', branchSelect.value);
        formData.append('sem_id', semId); 
        const subjects = await fetchData('get_subjects', formData);
        
        subjectSelect.innerHTML = '<option value="">-- Choose Subject --</option>';
        subjects.forEach(s => subjectSelect.innerHTML += `<option value="${s.id}">${s.subject_code} - ${s.subject_name}</option>`);
        subjectStep.classList.remove('hidden');
    });

    subjectSelect.addEventListener('change', () => {
        if (subjectSelect.value) {
            detailsStep.classList.remove('hidden');
            submitContainer.classList.remove('hidden');
        } else {
            detailsStep.classList.add('hidden');
            submitContainer.classList.add('hidden');
        }
    });

    fileTypeSelect.addEventListener('change', () => {
        if (fileTypeSelect.value === 'Previous Year QP') {
            moduleSelectContainer.classList.add('hidden');
        } else {
            moduleSelectContainer.classList.remove('hidden');
        }
    });
});
</script>

<?php require_once 'common/footer.php'; ?>