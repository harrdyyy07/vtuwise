<?php
require_once 'common/header.php';

// Get the branch ID from the URL
$branch_id = isset($_GET['branch_id']) ? (int)$_GET['branch_id'] : 0;
if ($branch_id === 0) {
    die("Invalid branch selected.");
}

// Fetch the branch name for the title
$branch_stmt = $conn->prepare("SELECT name FROM branches WHERE id = ?");
$branch_stmt->bind_param("i", $branch_id);
$branch_stmt->execute();
$branch = $branch_stmt->get_result()->fetch_assoc();
if (!$branch) {
    die("Branch not found.");
}

// Fetch semesters 3 and above
$semesters_result = $conn->query("SELECT * FROM semesters WHERE sem_number > 2 ORDER BY sem_number ASC");

// --- NEW: Array of unique color gradients ---
$gradients = [
    'from-blue-700 to-indigo-900',
    'from-slate-800 to-gray-950',
    'from-teal-600 to-cyan-700',
    'from-indigo-800 to-violet-900',
    'from-neutral-700 to-stone-900',
    'from-purple-800 to-fuchsia-900',
];
?>

<div class="container mx-auto py-12 px-4">
    <!-- Page Title -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
            <?php echo htmlspecialchars($branch['name']); ?>
        </h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 mt-2">Please select a semester</p>
    </div>
    
    <!-- Semesters Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <?php if ($semesters_result->num_rows > 0): 
            $i = 0; // Initialize a counter
            while($sem = $semesters_result->fetch_assoc()):
                // Cycle through the gradients array
                $gradient_class = $gradients[$i % count($gradients)];
        ?>
                <a href="<?php echo BASE_URL; ?>subjects.php?branch_id=<?php echo $branch_id; ?>&sem_id=<?php echo $sem['id']; ?>" class="relative block p-8 h-40 rounded-2xl shadow-lg text-white bg-gradient-to-br <?php echo $gradient_class; ?> overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                    <div class="relative z-10">
                        <h3 class="text-4xl font-extrabold">Semester <?php echo $sem['sem_number']; ?></h3>
                    </div>
                </a>
        <?php 
            $i++; // Increment the counter
            endwhile; 
        endif; 
        ?>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>