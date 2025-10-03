<?php
require_once 'common/header.php';

// Handle Add/Edit/Delete Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $stmt = $conn->prepare("INSERT INTO subjects (branch_id, sem_id, subject_code, subject_name, credits) VALUES (?, ?, ?, ?, ?)");
        // THIS IS THE FIX: The type string was "iisis", it is now correctly "iissi"
        $stmt->bind_param("iissi", $_POST['branch_id'], $_POST['sem_id'], $_POST['subject_code'], $_POST['subject_name'], $_POST['credits']);
        $stmt->execute();
    } 
    elseif ($_POST['action'] == 'delete') {
        $subject_id = (int)$_POST['subject_id'];
        $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
    }
    header('Location: manage_subjects.php');
    exit;
}

// Fetch data for display
$branches = $conn->query("SELECT * FROM branches ORDER BY name");
$semesters = $conn->query("SELECT * FROM semesters ORDER BY sem_number");
$subjects = $conn->query("SELECT s.*, b.short_name, se.sem_number FROM subjects s JOIN branches b ON s.branch_id = b.id JOIN semesters se ON s.sem_id = se.id ORDER BY b.short_name, se.sem_number, s.subject_code");
?>
<h1 class="text-3xl font-bold text-slate-800 mb-6">Manage Subjects</h1>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add New Subject Form -->
    <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-1">
        <h2 class="text-xl font-bold mb-4">Add New Subject</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="add">
            <div>
                <label>Branch</label>
                <select name="branch_id" class="w-full p-2 border rounded" required>
                    <?php mysqli_data_seek($branches, 0); while($b = $branches->fetch_assoc()): ?>
                        <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Semester</label>
                <select name="sem_id" class="w-full p-2 border rounded" required>
                    <?php mysqli_data_seek($semesters, 0); while($s = $semesters->fetch_assoc()): ?>
                        <option value="<?php echo $s['id']; ?>">Semester <?php echo $s['sem_number']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
             <div>
                <label>Credits</label>
                <input type="number" name="credits" class="w-full p-2 border rounded" min="0" max="10" required placeholder="e.g., 4">
            </div>
            <div>
                <label>Subject Code</label>
                <input type="text" name="subject_code" class="w-full p-2 border rounded" required placeholder="e.g., 21CS42">
            </div>
            <div>
                <label>Subject Name</label>
                <input type="text" name="subject_name" class="w-full p-2 border rounded" required placeholder="e.g., Data Structures">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add Subject</button>
        </form>
    </div>
    <!-- Existing Subjects List -->
    <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-2">
        <h2 class="text-xl font-bold mb-4">Existing Subjects</h2>
        <div class="max-h-[75vh] overflow-y-auto">
            <ul class="divide-y divide-slate-200">
                <?php mysqli_data_seek($subjects, 0); while($s = $subjects->fetch_assoc()): ?>
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold"><?php echo htmlspecialchars($s['subject_name']); ?> (<?php echo htmlspecialchars($s['subject_code']); ?>)</p>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($s['short_name']); ?> - Sem <?php echo $s['sem_number']; ?> - <?php echo $s['credits']; ?> Credits</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="edit_subject.php?id=<?php echo $s['id']; ?>" class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="subject_id" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="text-red-500 hover:text-red-700 p-2" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once 'common/footer.php'; ?>