<?php
// --- BLOCK 1: Handle AJAX Requests ---
require_once 'common/config.php';
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    if ($_POST['action'] == 'get_subjects') {
        $branch_id = (int)$_POST['branch_id'];
        $sem_id = (int)$_POST['sem_id'];
        $stmt = $conn->prepare("SELECT subject_code, subject_name, credits FROM subjects WHERE branch_id = ? AND sem_id = ? ORDER BY subject_code");
        $stmt->bind_param("ii", $branch_id, $sem_id);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
    exit;
}

// --- BLOCK 2: Display the Page ---
require_once 'common/header.php';
$branches = $conn->query("SELECT id, name FROM branches ORDER BY name ASC");
$semesters = $conn->query("SELECT id, sem_number FROM semesters ORDER BY sem_number ASC");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<div class="container mx-auto py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-slate-800 dark:text-white">Advanced SGPA Calculator</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 mt-2">Fetch subjects automatically and get a downloadable PDF report.</p>
        </div>
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <input id="user-name" type="text" placeholder="Your Name" class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600">
            <input id="user-usn" type="text" placeholder="Your USN" class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600">
            <select id="branch-select" class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600">
                <option value="">Select Branch</option>
                <?php mysqli_data_seek($branches, 0); while($b = $branches->fetch_assoc()): ?>
                    <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <select id="semester-select" class="w-full p-3 border rounded-lg dark:bg-slate-700 dark:border-slate-600">
                <option value="">Select Semester</option>
                 <?php mysqli_data_seek($semesters, 0); while($s = $semesters->fetch_assoc()): ?>
                    <option value="<?php echo $s['id']; ?>" data-sem-number="<?php echo $s['sem_number']; ?>">Semester <?php echo $s['sem_number']; ?></option>
                <?php endwhile; ?>
            </select>
            <button id="fetch-subjects-btn" class="w-full bg-blue-600 text-white font-bold p-3 rounded-lg hover:bg-blue-700 md:col-span-2 lg:col-span-4">Fetch Subjects</button>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg">
            <div class="overflow-x-auto">
                <table id="subjects-table" class="min-w-full">
                    <thead>
                        <tr class="border-b dark:border-slate-700">
                            <th class="p-3 text-left">Subject</th>
                            <th class="p-3 text-center w-24">Credits</th>
                            <th class="p-3 text-center w-28">Marks</th>
                            <th class="p-3 text-center w-28">Grade</th>
                            <th class="p-3 text-center w-16"></th>
                        </tr>
                    </thead>
                    <tbody id="subjects-container">
                       <tr><td colspan="5" class="p-8 text-center text-slate-400">Please select your details above to fetch subjects.</td></tr>
                    </tbody>
                </table>
            </div>
             <div class="mt-4 flex gap-4">
                <button id="add-custom-btn" class="bg-gray-200 dark:bg-slate-700 text-sm font-semibold p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-slate-600">Add Custom Subject</button>
             </div>
        </div>

        <div id="action-result-area" class="hidden mt-8">
            <div class="flex justify-center gap-4 mb-8">
                <button id="calculate-btn" class="bg-green-600 text-white font-bold text-lg py-3 px-8 rounded-lg hover:bg-green-700">Calculate</button>
                <button id="reset-btn" class="bg-gray-300 dark:bg-slate-600 font-bold text-lg py-3 px-8 rounded-lg hover:bg-gray-400 dark:hover:bg-slate-500">Reset</button>
            </div>
            <div id="result-container" class="hidden bg-blue-50 dark:bg-slate-800 p-8 rounded-xl shadow-lg text-center border dark:border-slate-700">
                 <p class="text-lg font-medium text-blue-800 dark:text-slate-300">Your SGPA</p>
                 <p id="sgpa-result" class="text-7xl font-bold text-blue-600 dark:text-blue-400 my-2">0.00</p>
                 <div class="grid grid-cols-3 gap-4 mt-4 text-slate-600 dark:text-slate-400">
                     <div><span class="font-bold block" id="total-credits-result">0</span>Total Credits</div>
                     <div><span class="font-bold block" id="total-marks-result">0</span>Total Marks</div>
                     <div><span class="font-bold block" id="percentage-result">0%</span>Percentage</div>
                 </div>
                 <button id="download-pdf-btn" class="mt-8 bg-purple-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-purple-700">Download as PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const { jsPDF } = window.jspdf;
    
    // All element selectors
    const fetchSubjectsBtn = document.getElementById('fetch-subjects-btn');
    const subjectsContainer = document.getElementById('subjects-container');
    const addCustomBtn = document.getElementById('add-custom-btn');
    const calculateBtn = document.getElementById('calculate-btn');
    const resetBtn = document.getElementById('reset-btn');
    const downloadPdfBtn = document.getElementById('download-pdf-btn');
    const actionResultArea = document.getElementById('action-result-area');
    const resultContainer = document.getElementById('result-container');
    
    const getGradePoint = (marks) => {
        if (marks >= 90) return 10;
        if (marks >= 80) return 9;
        if (marks >= 70) return 8;
        if (marks >= 60) return 7;
        if (marks >= 55) return 6;
        if (marks >= 50) return 5;
        if (marks >= 40) return 4;
        return 0; // Fail
    };
    
    const createRow = (subject = {subject_code: '', subject_name: '', credits: ''}) => {
        const tr = document.createElement('tr');
        tr.className = 'subject-row border-b dark:border-slate-700';
        tr.innerHTML = `
            <td class="p-3">
                <p class="font-semibold subject-name">${subject.subject_name || 'Custom Subject'}</p>
                <p class="text-sm text-slate-500 subject-code">${subject.subject_code}</p>
            </td>
            <td class="p-3 text-center"><input type="number" name="credits" value="${subject.credits}" class="w-full p-2 border rounded dark:bg-slate-700 dark:border-slate-600 text-center" ${subject.credits ? 'readonly' : ''} required></td>
            <td class="p-3 text-center"><input type="number" name="marks" placeholder="0-100" class="w-full p-2 border rounded dark:bg-slate-700 dark:border-slate-600 text-center" required></td>
            <td class="p-3 text-center grade-point font-bold">--</td>
            <td class="p-3 text-center"><button class="remove-row-btn text-red-500 hover:text-red-700 text-2xl">&times;</button></td>
        `;
        return tr;
    };

    fetchSubjectsBtn.addEventListener('click', async () => {
        const branchId = document.getElementById('branch-select').value;
        const semId = document.getElementById('semester-select').value;
        if (!branchId || !semId) {
            alert('Please select both Branch and Semester.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'get_subjects');
        formData.append('branch_id', branchId);
        formData.append('sem_id', semId);

        const response = await fetch('sgpa_calculator.php', { method: 'POST', body: formData });
        const subjects = await response.json();
        
        subjectsContainer.innerHTML = '';
        if (subjects.length > 0) {
            subjects.forEach(subject => subjectsContainer.appendChild(createRow(subject)));
            actionResultArea.classList.remove('hidden');
        } else {
            subjectsContainer.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-400">No subjects found for this selection. Please add them in the admin panel.</td></tr>';
            actionResultArea.classList.add('hidden');
        }
    });

    addCustomBtn.addEventListener('click', () => {
        const firstRowIsPlaceholder = subjectsContainer.querySelector('td[colspan="5"]');
        if (firstRowIsPlaceholder) {
            subjectsContainer.innerHTML = ''; // Clear placeholder if it exists
        }
        subjectsContainer.appendChild(createRow());
        actionResultArea.classList.remove('hidden');
    });

    subjectsContainer.addEventListener('click', e => {
        if (e.target.classList.contains('remove-row-btn')) {
            e.target.closest('.subject-row').remove();
        }
    });

    calculateBtn.addEventListener('click', () => {
        let totalCredits = 0, weightedGradePoints = 0, totalMarks = 0, maxMarks = 0;
        let hasFailed = false;

        document.querySelectorAll('.subject-row').forEach(row => {
            const credits = parseInt(row.querySelector('input[name="credits"]').value);
            const marks = parseInt(row.querySelector('input[name="marks"]').value);
            const gradePointCell = row.querySelector('.grade-point');
            
            if (isNaN(credits) || isNaN(marks) || credits <= 0) {
                gradePointCell.textContent = '--';
                return;
            }

            const gradePoint = getGradePoint(marks);
            gradePointCell.textContent = gradePoint;
            if (gradePoint === 0) {
                hasFailed = true;
                gradePointCell.classList.add('text-red-500');
            } else {
                 gradePointCell.classList.remove('text-red-500');
            }
            
            totalCredits += credits;
            weightedGradePoints += credits * gradePoint;
            totalMarks += marks;
            maxMarks += 100; // Assuming 100 marks per subject
        });
        
        const sgpa = hasFailed ? 0 : (totalCredits > 0 ? (weightedGradePoints / totalCredits) : 0);
        const percentage = maxMarks > 0 ? (totalMarks / maxMarks) * 100 : 0;

        document.getElementById('sgpa-result').textContent = sgpa.toFixed(2);
        document.getElementById('total-credits-result').textContent = totalCredits;
        document.getElementById('total-marks-result').textContent = `${totalMarks} / ${maxMarks}`;
        document.getElementById('percentage-result').textContent = `${percentage.toFixed(2)}%`;
        
        resultContainer.classList.remove('hidden');
        resultContainer.scrollIntoView({ behavior: 'smooth' });
    });

    resetBtn.addEventListener('click', () => {
        subjectsContainer.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-400">Please select a branch and semester to fetch subjects.</td></tr>';
        actionResultArea.classList.add('hidden');
        resultContainer.classList.add('hidden');
        document.getElementById('branch-select').value = '';
        document.getElementById('semester-select').value = '';
    });
    
    downloadPdfBtn.addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const logoImg = new Image();
        logoImg.src = '<?php echo BASE_URL; ?>images/logo1.0.png'; // Make sure this path is correct
        
        logoImg.onload = () => {
            // Header
            doc.addImage(logoImg, 'PNG', 15, 12, 15, 15);
            doc.setFontSize(22);
            doc.text("VTU SGPA Result", 105, 20, { align: 'center' });
            doc.setFontSize(10);
            doc.text("Generated from VTU wise", 105, 26, { align: 'center' });
            
            // User Details
            const semSelect = document.getElementById('semester-select');
            const semesterText = semSelect.options[semSelect.selectedIndex].text;
            doc.autoTable({
                startY: 35,
                body: [
                    ['Name:', document.getElementById('user-name').value || 'N/A'],
                    ['USN:', document.getElementById('user-usn').value || 'N/A'],
                    ['Semester:', semesterText || 'N/A'],
                ],
                theme: 'grid',
                styles: { fontSize: 10 }
            });

            // Result Display
            const finalSgpa = document.getElementById('sgpa-result').textContent;
            const totalCredits = document.getElementById('total-credits-result').textContent;
            let totalPoints = (parseFloat(finalSgpa) * parseFloat(totalCredits)).toFixed(2);
            
            const resultStartY = doc.autoTable.previous.finalY + 10;
            doc.setFontSize(14);
            doc.text("Your SGPA", 105, resultStartY, { align: 'center' });
            doc.setFontSize(36);
            doc.text(finalSgpa, 105, resultStartY + 13, { align: 'center' });
            doc.setFontSize(10);
            doc.text(`SGPA = (Sum of (Grade x Credits)) / (Total Credits)`, 105, resultStartY + 21, { align: 'center' });
            doc.text(`${totalPoints} / ${totalCredits} = ${finalSgpa}`, 105, resultStartY + 26, { align: 'center' });

            // Subjects Table
            const tableData = [];
            let totalCalcMarks = 0;
            let totalCalcPoints = 0;
            let totalCalcCredits = 0;
            document.querySelectorAll('.subject-row').forEach(row => {
                const credits = parseInt(row.querySelector('input[name="credits"]').value);
                const marks = parseInt(row.querySelector('input[name="marks"]').value);
                const gradePoint = row.querySelector('.grade-point').textContent;
                const points = (parseInt(gradePoint) * credits).toFixed(2);

                tableData.push([
                    row.querySelector('.subject-code').textContent,
                    row.querySelector('input[name="marks"]').value,
                    gradePoint,
                    credits,
                    points
                ]);
                totalCalcMarks += marks;
                totalCalcPoints += parseFloat(points);
                totalCalcCredits += credits;
            });
            
            doc.autoTable({
                startY: resultStartY + 35,
                head: [['Subject Code', 'VTU Marks', 'Grade', 'Credits', 'Points']],
                body: tableData,
                foot: [['Total', totalCalcMarks.toFixed(2), '', totalCalcCredits, totalCalcPoints.toFixed(2)]],
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
                footStyles: { fillColor: [236, 240, 241], textColor: [44, 62, 80], fontStyle: 'bold' }
            });
            
            // Footer
            const today = new Date();
            const dateStr = today.toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' });
            doc.setFontSize(8);
            doc.text(`Generated On: ${dateStr}`, 15, doc.internal.pageSize.getHeight() - 10);
            
            doc.save(`VTU_SGPA_Report_${document.getElementById('user-name').value}.pdf`);
        };
        logoImg.onerror = () => {
            alert("Error: Logo image could not be loaded for PDF generation. Please check the path in the code.");
        }
    });
});
</script>

<?php require_once 'common/footer.php'; ?>