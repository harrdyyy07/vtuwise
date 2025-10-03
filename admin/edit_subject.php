<?php
require_once 'common/header.php';

$subject_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($subject_id === 0) {
    die("Invalid subject ID.");
}

// Handle form submission for UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_id = (int)$_POST['branch_id'];
    $sem_id = (int)$_POST['sem_id'];
    $credits = (int)$_POST['credits'];
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);

    $stmt = $conn->prepare("UPDATE subjects SET branch_id = ?, sem_id = ?, subject_code = ?, subject_name = ?, credits = ? WHERE id = ?");
    $stmt->bind_param("iissii", $branch_id, $sem_id, $subject_code, $subject_name, $credits, $subject_id);
    $stmt->execute();

    // Redirect back to the main management page
    header('Location: manage_subjects.php');
    exit;
}

// Fetch the existing subject data to pre-fill the form
$stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();

if (!$subject) {
    die("Subject not found.");
}

// Fetch branches and semesters for the dropdowns
$branches = $conn->query("SELECT * FROM branches ORDER BY name");
$semesters = $conn->query("SELECT * FROM semesters ORDER BY sem_number");
?>

<h1 class="text-3xl font-bold text-slate-800 mb-6">Edit Subject</h1>

<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <form method="POST" action="edit_subject.php?id=<?php echo $subject_id; ?>" class="space-y-4">
        <div>
            <label>Branch</label>
            <select name="branch_id" class="w-full p-2 border rounded" required>
                <?php while($b = $branches->fetch_assoc()): ?>
                    <option value="<?php echo $b['id']; ?>" <?php if ($b['id'] == $subject['branch_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($b['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label>Semester</label>
            <select name="sem_id" class="w-full p-2 border rounded" required>
                <?php while($s = $semesters->fetch_assoc()): ?>
                    <option value="<?php echo $s['id']; ?>" <?php if ($s['id'] == $subject['sem_id']) echo 'selected'; ?>>
                        Semester <?php echo $s['sem_number']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label>Credits</label>
            <input type="number" name="credits" value="<?php echo htmlspecialchars($subject['credits']); ?>" class="w-full p-2 border rounded" min="0" max="10" required>
        </div>
        <div>
            <label>Subject Code</label>
            <input type="text" name="subject_code" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label>Subject Name</label>
            <input type="text" name="subject_name" value="<?php echo htmlspecialchars($subject['subject_name']); ?>" class="w-full p-2 border rounded" required>
        </div>
        <div class="flex items-center space-x-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Save Changes</button>
            <a href="manage_subjects.php" class="text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'common/footer.php'; ?>