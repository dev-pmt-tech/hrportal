<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if id is provided
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];

// Fetch employee details
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Employee not found.");
}

// Update employee
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'];
    $staff_id = $_POST['staff_id'];
    $department = $_POST['department'];
    $date_of_employment = $_POST['date_of_employment'];
    $salary = $_POST['salary'];
    $ssnit = $_POST['ssnit'];
    $contact = $_POST['contact'];
    $bank_account = $_POST['bank_account'];

    $update = $pdo->prepare("UPDATE employees SET full_name=?, staff_id=?, department=?, date_of_employment=?, salary=?, ssnit=?, contact=?, bank_account=? WHERE id=?");
    $update->execute([$full_name, $staff_id, $department, $date_of_employment, $salary, $ssnit, $contact, $bank_account, $id]);

    header("Location: dashboard.php?success=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Employee</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-inter">

<div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-8 transform transition-all scale-95 hover:scale-100 duration-300">
    <h2 class="text-2xl font-bold text-teal-600 mb-6 text-center">Edit Employee</h2>

    <form method="POST" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($employee['full_name']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Staff ID</label>
                <input type="text" name="staff_id" value="<?= htmlspecialchars($employee['staff_id']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Department</label>
                <input type="text" name="department" value="<?= htmlspecialchars($employee['department']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Date of Employment</label>
                <input type="date" name="date_of_employment" value="<?= htmlspecialchars($employee['date_of_employment']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Salary</label>
                <input type="number" name="salary" value="<?= htmlspecialchars($employee['salary']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">SSNIT Number</label>
                <input type="text" name="ssnit" value="<?= htmlspecialchars($employee['ssnit']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Contact</label>
                <input type="text" name="contact" value="<?= htmlspecialchars($employee['contact']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Bank Account</label>
                <input type="text" name="bank_account" value="<?= htmlspecialchars($employee['bank_account']) ?>" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-teal-500" required>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="dashboard.php" class="px-4 py-2 rounded-lg bg-gray-300 text-gray-700 hover:bg-gray-400 transition">Cancel</a>
            <button type="submit" class="px-6 py-2 rounded-lg bg-teal-600 text-white hover:bg-teal-700 shadow-lg transition">Update</button>
        </div>
    </form>
</div>

</body>
</html>
