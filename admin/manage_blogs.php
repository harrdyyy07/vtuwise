<?php
require_once 'common/header.php';

function create_slug($string){
   $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
   return trim($slug, '-');
}

// Handle Add/Delete Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $author = trim($_POST['author']);
        $slug = create_slug($title);
        $image_path = '';

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $target_dir = dirname(__DIR__) . "/uploads/";
            $image_name = 'blog_' . time() . '_' . basename($_FILES["featured_image"]["name"]);
            $target_file = $target_dir . $image_name;
            if (move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_file)) {
                $image_path = $image_name;
            }
        }

        $stmt = $conn->prepare("INSERT INTO blogs (title, slug, content, featured_image, author) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $slug, $content, $image_path, $author);
        $stmt->execute();
    }
    elseif ($_POST['action'] == 'delete') {
        $blog_id = (int)$_POST['blog_id'];
        // You would also delete the image file from the server here in a real app
        $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->bind_param("i", $blog_id);
        $stmt->execute();
    }
    header('Location: manage_blogs.php');
    exit;
}

$blogs = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>
<h1 class="text-3xl font-bold text-slate-800 mb-6">Manage Blog</h1>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add New Post Form -->
    <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-1">
        <h2 class="text-xl font-bold mb-4">Add New Post</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="add">
            <div>
                <label>Post Title</label>
                <input type="text" name="title" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Author</label>
                <input type="text" name="author" value="Admin" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Content</label>
                <textarea name="content" rows="8" class="w-full p-2 border rounded" required></textarea>
            </div>
            <div>
                <label>Featured Image</label>
                <input type="file" name="featured_image" class="w-full text-sm" accept="image/*" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Publish Post</button>
        </form>
    </div>
    <!-- Existing Posts List -->
    <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-2">
        <h2 class="text-xl font-bold mb-4">Published Posts</h2>
        <div class="max-h-[75vh] overflow-y-auto">
            <ul class="divide-y">
                <?php while($post = $blogs->fetch_assoc()): ?>
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold"><?php echo htmlspecialchars($post['title']); ?></p>
                            <p class="text-sm text-slate-500">By <?php echo htmlspecialchars($post['author']); ?> on <?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>
                        </div>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="blog_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once 'common/footer.php'; ?>