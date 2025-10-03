<?php
require_once 'common/config.php';

$note_id = isset($_GET['note_id']) ? (int)$_GET['note_id'] : 0;
if ($note_id === 0) {
    die("Invalid note ID.");
}

$stmt = $conn->prepare("SELECT file_path, title FROM notes WHERE id = ?");
$stmt->bind_param("i", $note_id);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();

if (!$note) {
    die("Note not found.");
}

$file_url = BASE_URL . 'uploads/' . rawurlencode($note['file_path']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($note['title']); ?> - VTU wise</title>
    <style>
        body, html { 
            margin: 0; 
            padding: 0; 
            height: 100%; 
            overflow: hidden; 
        }
        iframe { 
            border: none; 
            width: 100%; 
            height: 100%; 
        }
    </style>
</head>
<body>

    <!-- 
        The #toolbar=0 has been removed from the src attribute.
        This will make the browser's default PDF toolbar visible,
        which includes download, print, zoom, and other controls.
    -->
    <iframe src="<?php echo $file_url; ?>" title="<?php echo htmlspecialchars($note['title']); ?>"></iframe>

    <!-- 
        All JavaScript that disabled right-click, printing, and saving has been removed.
    -->

</body>
</html>