<?php 
require_once 'common/header.php';
$posts = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>
<div class="container mx-auto py-12 px-4">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-slate-800 dark:text-white">Our Blog</h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 mt-2">News, updates, and helpful articles for VTU students.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if ($posts->num_rows > 0): while($post = $posts->fetch_assoc()): ?>
            <a href="<?php echo BASE_URL; ?>blog/<?php echo $post['slug']; ?>" class="bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden group">
                <img src="<?php echo BASE_URL . 'uploads/' . $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        By <?php echo htmlspecialchars($post['author']); ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                    </p>
                    <p class="text-slate-700 dark:text-slate-300">
                        <?php echo substr(strip_tags($post['content']), 0, 100); ?>...
                    </p>
                </div>
            </a>
        <?php endwhile; else: ?>
            <p class="col-span-full text-center text-slate-500">No blog posts have been published yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'common/footer.php'; ?>