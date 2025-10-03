<?php
require_once 'common/config.php';

header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo BASE_URL; ?></loc>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?php echo BASE_URL; ?>contact</loc>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo BASE_URL; ?>upload</loc>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo BASE_URL; ?>sgpa-calculator</loc>
        <priority>0.9</priority>
    </url>

    <?php
    $branches = $conn->query("SELECT id FROM branches");
    while($branch = $branches->fetch_assoc()):
    ?>
    <url>
        <loc><?php echo BASE_URL; ?>branch/<?php echo $branch['id']; ?></loc>
        <priority>0.9</priority>
    </url>
    <?php endwhile; ?>
    
    <?php
    $subjects = $conn->query("SELECT id FROM subjects");
    while($subject = $subjects->fetch_assoc()):
    ?>
    <url>
        <loc><?php echo BASE_URL; ?>subject/<?php echo $subject['id']; ?></loc>
        <priority>0.8</priority>
    </url>
    <?php endwhile; ?>

</urlset>