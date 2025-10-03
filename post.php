<?php
require_once 'common/config.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if (empty($slug)) {
    die("Post not found.");
}

$stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

// Set SEO variables for the header
$page_title = $post['title'];
$meta_description = substr(strip_tags($post['content']), 0, 160);

require_once 'common/header.php';
?>
<div class="container mx-auto py-12 px-4 max-w-4xl">
    <article>
        <img src="<?php echo BASE_URL . 'uploads/' . $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-auto max-h-96 object-cover rounded-lg mb-8">
        <h1 class="text-4xl font-extrabold text-slate-800 dark:text-white"><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="text-slate-500 dark:text-slate-400 my-4">
            By <strong><?php echo htmlspecialchars($post['author']); ?></strong> | Published on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
        </p>
        <div class="prose dark:prose-invert max-w-none mt-8 text-lg leading-relaxed">
            <?php
                // THIS IS THE FIX:
                // We allow a specific list of safe HTML tags for links and basic formatting,
                // while removing any other potentially harmful code.
                $allowed_tags = '<p><a><b><strong><i><em><u><ul><ol><li><br>';
                echo nl2br(strip_tags($post['content'], $allowed_tags));
            ?>
        </div>
    </article>
</div>
<?php require_once 'common/footer.php'; ?>