<?php
require_once 'common/config.php';

// Handle AJAX Search Request (remains the same)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'search') {
    header('Content-Type: application/json');
    $query = '%' . trim($_POST['query']) . '%';
    
    $stmt = $conn->prepare("SELECT s.id, s.subject_code, s.subject_name, b.short_name 
                            FROM subjects s
                            JOIN branches b ON s.branch_id = b.id
                            WHERE s.subject_code LIKE ? OR s.subject_name LIKE ?
                            LIMIT 10");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjects = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($subjects);
    exit;
}

// Handle regular page load
require_once 'common/header.php';

$branch_id = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : 0;
$sem_id = isset($_GET['sem_id']) ? (int)$_GET['sem_id'] : 0;

$where_clauses = [];
$params = [];
$types = '';
$page_title = 'All Subjects';

// --- UPDATED TITLE LOGIC ---
if ($branch_id == 0 && $sem_id == 1) {
    $page_title = 'P and C Cycle - 2022 Scheme';
} else if ($branch_id == 0 && $sem_id == 2) {
    $page_title = 'P and C Cycle - 2022 Scheme';
} else {
    // Original logic for branch/semester specific titles
    if ($branch_id > 0) {
        $branch_name_stmt = $conn->prepare("SELECT name FROM branches WHERE id = ?");
        $branch_name_stmt->bind_param("i", $branch_id);
        $branch_name_stmt->execute();
        $page_title = $branch_name_stmt->get_result()->fetch_assoc()['name'] ?? 'Subjects';
    }
    if ($sem_id > 0) {
        $sem_name_stmt = $conn->prepare("SELECT sem_number FROM semesters WHERE id = ?");
        $sem_name_stmt->bind_param("i", $sem_id);
        $sem_name_stmt->execute();
        $sem_number = $sem_name_stmt->get_result()->fetch_assoc()['sem_number'] ?? '';
        $page_title = ($branch_id > 0 ? $page_title . " - " : "") . "Semester {$sem_number} Subjects";
    }
}
// --- END OF UPDATED TITLE LOGIC ---

if ($branch_id > 0) {
    $where_clauses[] = 's.branch_id = ?';
    $params[] = $branch_id;
    $types .= 'i';
}
if ($sem_id > 0) {
    $where_clauses[] = 's.sem_id = ?';
    $params[] = $sem_id;
    $types .= 'i';
}

$sql = "SELECT s.*, b.short_name, se.sem_number 
        FROM subjects s
        JOIN branches b ON s.branch_id = b.id
        JOIN semesters se ON s.sem_id = se.id";

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY s.subject_code";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$subjects_result = $stmt->get_result();
?>

<div class="container mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold text-center mb-10 text-slate-800 dark:text-white"><?php echo htmlspecialchars($page_title); ?></h1>
    
    <!-- Redesigned Subject Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php if ($subjects_result->num_rows > 0): ?>
            <?php while($subject = $subjects_result->fetch_assoc()): ?>
                <a href="<?php echo BASE_URL; ?>notes.php?subject_id=<?php echo $subject['id']; ?>" 
                   class="block bg-gray-900 dark:bg-black text-white p-6 rounded-2xl shadow-lg text-center transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <h3 class="font-bold text-lg leading-tight">
                        <?php echo htmlspecialchars($subject['subject_name']); ?>
                    </h3>
                    <p class="mt-1 text-sm font-semibold text-gray-400">
                        <?php echo htmlspecialchars($subject['subject_code']); ?>
                    </p>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full bg-white dark:bg-slate-800 rounded-lg shadow-md p-12 text-center">
                <p class="text-slate-500 dark:text-slate-400 text-lg">No subjects have been added for this selection yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>