<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Fetch current user password hash
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($current_password, $user['password_hash'])) {
        if ($new_password === $confirm_password && strlen($new_password) >= 6) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $updateStmt->execute([$hashed, $_SESSION['user_id']]);

            $_SESSION['message'] = "Password updated successfully!";
        } else {
            $_SESSION['error'] = "New passwords do not match or are too short.";
        }
    } else {
        $_SESSION['error'] = "Current password is incorrect.";
    }

    header("Location: settings.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold text-teal-600 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-gear"></i> Account Settings
    </h1>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-700 font-medium">Current Password</label>
            <input type="password" name="current_password" required
                   class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-teal-400">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">New Password</label>
            <input type="password" name="new_password" required minlength="6"
                   class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-teal-400">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Confirm New Password</label>
            <input type="password" name="confirm_password" required
                   class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-teal-400">
        </div>

        <button type="submit" 
                class="w-full flex items-center justify-center gap-2 bg-teal-600 text-white py-2 rounded hover:bg-teal-700 transition transform hover:scale-105">
            <i class="fa-solid fa-key"></i> Update Password
        </button>
    </form>

    <div class="mt-4 text-right">
        <a href="dashboard.php" class="text-sm text-blue-600 hover:underline"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>
