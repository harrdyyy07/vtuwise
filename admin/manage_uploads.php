<?php
require_once 'common/header.php';

// Handle Approve/Decline actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $pending_id = (int)$_POST['pending_id'];

    // Fetch details of the pending note
    $stmt = $conn->prepare("SELECT * FROM pending_notes WHERE id = ?");
    $stmt->bind_param("i", $pending_id);
    $stmt->execute();
    $pending_note = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($pending_note) {
        if ($_POST['action'] == 'approve') {
            // 1. Move file from pending to main uploads folder
            $source = dirname(__DIR__) . '/uploads/pending/' . $pending_note['file_path'];
            $destination = dirname(__DIR__) . '/uploads/' . $pending_note['file_path'];
            if (rename($source, $destination)) {
                // 2. Insert into the main 'notes' table
                $insert_stmt = $conn->prepare("INSERT INTO notes (subject_id, module_number, title, file_path, file_type) VALUES (?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("iisss", $pending_note['subject_id'], $pending_note['module_number'], $pending_note['title'], $pending_note['file_path'], $pending_note['file_type']);
                $insert_stmt->execute();
                $insert_stmt->close();

                // 3. Update status in 'pending_notes' table
                $update_stmt = $conn->prepare("UPDATE pending_notes SET status = 'approved' WHERE id = ?");
                $update_stmt->bind_param("i", $pending_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        } elseif ($_POST['action'] == 'decline') {
            // 1. Delete the file
            $file_to_delete = dirname(__DIR__) . '/uploads/pending/' . $pending_note['file_path'];
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }
            // 2. Update status in 'pending_notes' table
            $update_stmt = $conn->prepare("UPDATE pending_notes SET status = 'declined' WHERE id = ?");
            $update_stmt->bind_param("i", $pending_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }
    header('Location: manage_uploads.php');
    exit;
}

// Fetch all pending notes for display
$pending_notes = $conn->query("
    SELECT pn.*, s.subject_name, s.subject_code 
    FROM pending_notes pn 
    JOIN subjects s ON pn.subject_id = s.id 
    WHERE pn.status = 'pending' 
    ORDER BY pn.uploaded_at DESC
");
?>

<h1 class="text-3xl font-bold text-slate-800 mb-6">Manage User Uploads</h1>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Pending Submissions (<?php echo $pending_notes->num_rows; ?>)</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Uploader</th>
                    <th class="p-3 text-left">Subject</th>
                    <th class="p-3 text-left">File Details</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php if ($pending_notes->num_rows > 0): ?>
                    <?php while($note = $pending_notes->fetch_assoc()): ?>
                    <tr>
                        <td class="p-3">
                            <p class="font-semibold"><?php echo htmlspecialchars($note['uploader_name']); ?></p>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($note['uploader_email']); ?></p>
                        </td>
                        <td class="p-3">
                            <p class="font-semibold"><?php echo htmlspecialchars($note['subject_name']); ?></p>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($note['subject_code']); ?></p>
                        </td>
                        <td class="p-3">
                            <p class="font-semibold"><?php echo htmlspecialchars($note['title']); ?></p>
                            <p class="text-sm text-slate-500">
                                <?php echo htmlspecialchars($note['file_type']); ?>
                                <?php if($note['module_number'] > 0) echo " - Module " . $note['module_number']; ?>
                            </p>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="<?php echo BASE_URL . 'uploads/pending/' . $note['file_path']; ?>" target="_blank" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md text-sm font-semibold">View</a>
                                <form action="manage_uploads.php" method="POST" class="inline">
                                    <input type="hidden" name="pending_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-semibold">Approve</button>
                                </form>
                                <form action="manage_uploads.php" method="POST" class="inline">
                                    <input type="hidden" name="pending_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" name="action" value="decline" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm font-semibold">Decline</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="p-4 text-center text-slate-500">No pending uploads.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'common/footer.php'; ?>