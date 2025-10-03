<?php
require_once dirname(__DIR__) . '/common/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            header('Location: index.php');
            exit;
        }
    }
    $error_message = 'Invalid username or password.';
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - VTU NOTES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Admin Panel Login</h1>
        </div>
        <?php if ($error_message): ?>
            <p class="bg-red-100 text-red-700 p-3 rounded-md text-center mb-4"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-slate-600">Username</label>
                <input type="text" name="username" id="username" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-600">Password</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</body>
</html>