<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get employee ID
$id = $_GET['id'] ?? 0;

// Fetch employee data
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emp) {
    echo "Employee not found.";
    exit;
}

// Export to CSV when requested
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=employee_'.$emp['staff_id'].'.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Full Name', 'Staff ID', 'Department', 'Date of Employment', 'Salary', 'Contact', 'Bank Account', 'SSNIT']);
    fputcsv($output, [
        $emp['full_name'],
        $emp['staff_id'],
        $emp['department'],
        $emp['date_of_employment'],
        $emp['salary'],
        $emp['contact'],
        $emp['bank_account'],
        $emp['ssnit']
    ]);
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" x-data="{ tab: 'profile' }">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Employee</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-4xl mx-auto">

  <!-- Header -->
  <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 space-y-3 md:space-y-0">
    <h1 class="text-3xl font-bold text-teal-600">Employee Profile</h1>

    <div class="flex flex-wrap gap-2">
      <!-- Edit -->
      <a href="edit_employee.php?id=<?= $emp['id'] ?>"
         class="flex items-center gap-2 px-3 py-2 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600 transform hover:scale-105 transition">
         <i class="fa-solid fa-pen"></i> <span>Edit</span>
      </a>

      <!-- Back -->
      <a href="dashboard.php#employees"
         class="flex items-center gap-2 px-3 py-2 bg-gray-400 text-white rounded shadow hover:bg-gray-500 transform hover:scale-105 transition">
         <i class="fa-solid fa-arrow-left"></i> <span>Back</span>
      </a>

      <!-- Print -->
      <button onclick="window.print()"
         class="flex items-center gap-2 px-3 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 transform hover:scale-105 transition">
         <i class="fa-solid fa-print"></i> <span>Print</span>
      </button>

      <!-- Export -->
      <a href="view_employee.php?id=<?= $emp['id'] ?>&export=csv"
         class="flex items-center gap-2 px-3 py-2 bg-green-500 text-white rounded shadow hover:bg-green-600 transform hover:scale-105 transition">
         <i class="fa-solid fa-file-csv"></i> <span>Export</span>
      </a>
    </div>
  </div>

  <!-- Tabs -->
  <div class="bg-white shadow rounded mb-6">
    <div class="flex border-b">
      <button @click="tab='profile'"
              :class="tab==='profile' ? 'border-teal-500 text-teal-600' : 'text-gray-600'"
              class="px-4 py-2 border-b-2 font-semibold">
        Profile
      </button>
    </div>

    <!-- Profile Tab -->
    <div x-show="tab==='profile'" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-teal-50 p-4 rounded shadow">
        <h2 class="font-bold text-lg mb-2">Personal Info</h2>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($emp['full_name']) ?></p>
        <p><strong>Staff ID:</strong> <?= htmlspecialchars($emp['staff_id']) ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($emp['department']) ?></p>
        <p><strong>Date of Employment:</strong> <?= htmlspecialchars($emp['date_of_employment']) ?></p>
      </div>
      <div class="bg-teal-50 p-4 rounded shadow">
        <h2 class="font-bold text-lg mb-2">Contact & Bank Info</h2>
        <p><strong>Contact:</strong> <?= htmlspecialchars($emp['contact']) ?></p>
        <p><strong>Bank Account:</strong> <?= htmlspecialchars($emp['bank_account']) ?></p>
        <p><strong>SSNIT Number:</strong> <?= htmlspecialchars($emp['ssnit']) ?></p>
      </div>
    </div>
  </div>
</div>

</body>
</html>
