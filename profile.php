<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch logged-in user details
$stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// No profile update since no editable fields exist
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold text-teal-600 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-user-circle"></i> My Profile
    </h1>

    <div class="space-y-4">
        <div>
            <label class="block text-gray-700 font-medium">Username</label>
            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled
                   class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Role</label>
            <input type="text" value="<?= htmlspecialchars($user['role']) ?>" disabled
                   class="w-full px-3 py-2 border rounded bg-gray-100">
        </div>
    </div>

    <div class="mt-6 text-right">
        <a href="dashboard.php" class="text-sm text-blue-600 hover:underline"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>
