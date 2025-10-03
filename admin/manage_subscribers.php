<?php
require_once 'common/header.php';
$subscribers = $conn->query("SELECT * FROM subscribers ORDER BY subscribed_at DESC");
?>
<h1 class="text-3xl font-bold text-slate-800 mb-6">Manage Subscribers</h1>
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Collected Emails (<?php echo $subscribers->num_rows; ?>)</h2>
    <div class="max-h-[75vh] overflow-y-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Subscribed On</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                <?php while($sub = $subscribers->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?php echo htmlspecialchars($sub['email']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500"><?php echo date('M d, Y h:i A', strtotime($sub['subscribed_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'common/footer.php'; ?>