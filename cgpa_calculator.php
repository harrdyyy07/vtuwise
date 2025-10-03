<?php require_once 'common/header.php'; ?>

<?php
// Calculation logic
function calculateCGPA($semesterSgpas) {
    if (empty($semesterSgpas)) {
        return 0;
    }
    return array_sum($semesterSgpas) / count($semesterSgpas);
}

function getGradeLetter($gradePoint) {
    $gradeMap = [
        10 => 'S',
        9 => 'A',
        8 => 'B',
        7 => 'C',
        6 => 'D',
        5 => 'E',
        0 => 'F'
    ];
    return $gradeMap[$gradePoint] ?? 'F';
}

// Process form submission
$cgpa = 0;
$sgpas = [];
$selectedSemesters = [];
$showResult = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate'])) {
    $selectedSemesters = $_POST['semesters'] ?? [];
    $sgpaValues = $_POST['sgpa'] ?? [];
    
    foreach ($selectedSemesters as $semester) {
        if (isset($sgpaValues[$semester]) && is_numeric($sgpaValues[$semester])) {
            $sgpas[$semester] = (float)$sgpaValues[$semester];
        }
    }
    
    $cgpa = calculateCGPA($sgpas);
    $showResult = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTU CGPA Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">VTU CGPA Calculator</h1>
                <p class="text-blue-100">Calculate your CGPA for VTU results</p>
            </div>
            
            <!-- Calculator Form -->
            <div class="p-6">
                <form method="post" action="cgpa-calculator.php">
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Select Semesters</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <?php for($i=1; $i<=8; $i++): ?>
                                <div class="flex items-center">
                                    <input type="checkbox" name="semesters[]" value="<?= $i ?>" 
                                           class="form-checkbox h-4 w-4 text-blue-600" 
                                           <?= in_array($i, $selectedSemesters) ? 'checked' : '' ?>>
                                    <label class="ml-2">Semester <?= $i ?></label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- SGPA Inputs -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Enter SGPA for Selected Semesters</label>
                        <div class="space-y-2">
                            <?php for($i=1; $i<=8; $i++): ?>
                                <div id="semester-<?= $i ?>-input" class="<?= in_array($i, $selectedSemesters) ? 'block' : 'hidden' ?>">
                                    <label class="text-gray-600">Semester <?= $i ?> SGPA:</label>
                                    <input type="number" name="sgpa[<?= $i ?>]" step="0.01" min="0" max="10" 
                                           class="form-input w-24 ml-2 px-2 py-1 border rounded" 
                                           value="<?= $sgpas[$i] ?? '' ?>">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" name="calculate" 
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Calculate CGPA
                        </button>
                    </div>
                </form>
                
                <!-- Results -->
                <?php if ($showResult): ?>
                <div class="mt-8 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Calculation Results</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium mb-2">Semester-wise SGPA:</h4>
                        <ul class="space-y-1">
                            <?php foreach ($sgpas as $semester => $sgpa): ?>
                                <li>Semester <?= $semester ?>: <?= number_format($sgpa, 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="mt-4 p-3 bg-blue-50 rounded border border-blue-100">
                            <p class="font-bold text-blue-800">Your CGPA: <?= number_format($cgpa, 2) ?></p>
                            <p class="text-sm text-blue-600 mt-1">Calculated as average of <?= count($sgpas) ?> semester(s)</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- How to Use Section -->
        <div class="max-w-3xl mx-auto mt-8 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4">
                <h2 class="text-xl font-bold">How to Use This Calculator</h2>
            </div>
            <div class="p-6">
                <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                    <li>Select the semesters you want to include in your CGPA calculation</li>
                    <li>Enter the SGPA for each selected semester</li>
                    <li>Click "Calculate CGPA" to see your result</li>
                    <li>CGPA is calculated as the average of all selected semester SGPAs</li>
                </ol>
                
                <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Note:</strong> This calculator follows VTU's grading system. CGPA is calculated as the average of SGPAs of all selected semesters.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide SGPA inputs based on selected semesters
        document.querySelectorAll('input[name="semesters[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const semesterNum = this.value;
                const sgpaInput = document.getElementById(`semester-${semesterNum}-input`);
                
                if (this.checked) {
                    sgpaInput.classList.remove('hidden');
                } else {
                    sgpaInput.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>

<?php require_once 'common/footer.php'; ?>