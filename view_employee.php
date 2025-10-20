<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
<<<<<<< HEAD
  header("Location: login.php");
  exit;
}

$id = $_GET['id'] ?? 0;

// Fetch employee
=======
    header("Location: login.php");
    exit;
}

// Get employee ID
$id = $_GET['id'] ?? 0;

// Fetch employee data
>>>>>>> 9c37376e3602a47338fc9738bfb0bc03c797cda7
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emp) {
<<<<<<< HEAD
  echo "Employee not found.";
  exit;
}

// Salary calculations
$basic_salary = $emp['salary'];
$ssnit_deduction = $basic_salary * 0.055;
$allowance = $basic_salary * 0.10;
$bonus = $basic_salary * 0.05;
$gross_salary = $basic_salary + $allowance + $bonus;
$net_salary = $gross_salary - $ssnit_deduction;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payslip - <?= htmlspecialchars($emp['full_name']) ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
  body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #e0f7fa, #f8faff);
    transition: background 0.4s ease;
  }
  .dark body {
    background: #0f172a;
    color: white;
  }
  .card-glass {
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(12px);
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    transition: all 0.4s ease;
  }
  .dark .card-glass {
    background: rgba(30,41,59,0.85);
  }
  @keyframes shimmer {
    0% { background-position: -400px 0; }
    100% { background-position: 400px 0; }
  }
  .shimmer {
    background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0.05) 75%);
    background-size: 800px 100%;
    animation: shimmer 3s infinite;
  }
  .glow {
    color: #0284c7;
    text-shadow: 0 0 10px rgba(14,165,233,0.5);
  }
  .fade-in {
    animation: fadeSlide 0.6s ease forwards;
  }
  @keyframes fadeSlide {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  @media print { .no-print { display:none; } body { background:white; color:black; } }
</style>
</head>
<body class="flex justify-center py-10 px-4 transition">

<!-- Dark Mode Toggle -->
<div class="fixed top-6 right-6 no-print">
  <button onclick="toggleDarkMode()" class="bg-gray-800 text-white px-3 py-2 rounded-full shadow hover:bg-black transition">
    <i class="fa-solid fa-moon"></i>
  </button>
</div>

<div id="payslip" class="card-glass w-full max-w-4xl fade-in">

  <!-- Header -->
  <div class="bg-gradient-to-r from-blue-700 via-cyan-500 to-blue-700 shimmer text-white p-6 flex justify-between items-center rounded-t-2xl shadow">
    <div>
      <h1 class="text-3xl font-bold tracking-wide">Employee Payslip</h1>
      <p class="text-sm opacity-90"><?= date('F Y') ?></p>
    </div>
    <i class="fa-solid fa-money-check-dollar text-4xl"></i>
  </div>

  <!-- Profile Section -->
  <div class="p-6 flex flex-col md:flex-row items-center md:items-start gap-6">
    <div class="relative">
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="w-28 h-28 rounded-full border-4 border-blue-400 shadow-md" alt="employee photo">
      <span class="absolute bottom-0 right-0 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">Active</span>
    </div>
    <div class="flex-1 grid md:grid-cols-2 gap-6">
      <div>
        <h2 class="text-lg font-semibold text-blue-700 mb-2 flex items-center gap-2"><i class="fa-solid fa-id-badge"></i> Personal Info</h2>
=======
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
>>>>>>> 9c37376e3602a47338fc9738bfb0bc03c797cda7
        <p><strong>Full Name:</strong> <?= htmlspecialchars($emp['full_name']) ?></p>
        <p><strong>Staff ID:</strong> <?= htmlspecialchars($emp['staff_id']) ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($emp['department']) ?></p>
        <p><strong>Date of Employment:</strong> <?= htmlspecialchars($emp['date_of_employment']) ?></p>
      </div>
<<<<<<< HEAD

      <div>
        <h2 class="text-lg font-semibold text-blue-700 mb-2 flex items-center gap-2"><i class="fa-solid fa-phone"></i> Contact & Bank Info</h2>
=======
      <div class="bg-teal-50 p-4 rounded shadow">
        <h2 class="font-bold text-lg mb-2">Contact & Bank Info</h2>
>>>>>>> 9c37376e3602a47338fc9738bfb0bc03c797cda7
        <p><strong>Contact:</strong> <?= htmlspecialchars($emp['contact']) ?></p>
        <p><strong>Bank Account:</strong> <?= htmlspecialchars($emp['bank_account']) ?></p>
        <p><strong>SSNIT Number:</strong> <?= htmlspecialchars($emp['ssnit']) ?></p>
      </div>
    </div>
  </div>
<<<<<<< HEAD

  <!-- Salary Details -->
  <div class="px-6 pb-6">
    <h2 class="text-lg font-semibold text-blue-700 mb-3 flex items-center gap-2"><i class="fa-solid fa-sack-dollar"></i> Salary Breakdown</h2>
    <table class="w-full text-sm border-collapse overflow-hidden rounded-lg shadow">
      <thead class="bg-blue-50">
        <tr>
          <th class="p-2 text-left">Description</th>
          <th class="p-2 text-right">Amount (GHS)</th>
        </tr>
      </thead>
      <tbody>
        <tr class="hover:bg-blue-50 transition"><td class="p-2">Basic Salary</td><td class="p-2 text-right"><?= number_format($basic_salary, 2) ?></td></tr>
        <tr class="hover:bg-blue-50 transition"><td class="p-2">Allowance (10%)</td><td class="p-2 text-right"><?= number_format($allowance, 2) ?></td></tr>
        <tr class="hover:bg-blue-50 transition"><td class="p-2">Bonus (5%)</td><td class="p-2 text-right"><?= number_format($bonus, 2) ?></td></tr>
        <tr class="text-red-500 hover:bg-red-50 transition"><td class="p-2">SSNIT Deduction (5.5%)</td><td class="p-2 text-right">-<?= number_format($ssnit_deduction, 2) ?></td></tr>
        <tr class="bg-blue-100 font-semibold text-blue-900">
          <td class="p-2">Net Salary</td>
          <td class="p-2 text-right glow"><?= number_format($net_salary, 2) ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Payment Summary Cards -->
  <div class="px-6 pb-6 grid md:grid-cols-3 gap-4 text-center">
    <div class="p-4 rounded-xl shadow bg-blue-50">
      <p class="text-gray-500 text-sm">Gross Salary</p>
      <h3 class="text-xl font-bold text-blue-700"><?= number_format($gross_salary, 2) ?></h3>
    </div>
    <div class="p-4 rounded-xl shadow bg-red-50">
      <p class="text-gray-500 text-sm">Deductions</p>
      <h3 class="text-xl font-bold text-red-600"><?= number_format($ssnit_deduction, 2) ?></h3>
    </div>
    <div class="p-4 rounded-xl shadow bg-green-50">
      <p class="text-gray-500 text-sm">Net Paid</p>
      <h3 class="text-xl font-bold text-green-700"><?= number_format($net_salary, 2) ?></h3>
    </div>
  </div>

  <!-- Footer -->
  <div class="px-6 pb-6 flex flex-col md:flex-row justify-between items-center text-sm border-t border-gray-200 pt-4 gap-4">
    <div class="text-gray-600">
      <p><strong>Prepared By:</strong> HR Department</p>
      <p><strong>Date Generated:</strong> <?= date('d M, Y') ?></p>
    </div>
    <div class="flex flex-col items-center">
     <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode(
  "EMPLOYEE DETAILS\n" .
  "Name: {$emp['full_name']}\n" .
  "Staff ID: {$emp['staff_id']}\n" .
  "Department: {$emp['department']}\n" .
  "Date of Employment: {$emp['date_of_employment']}\n" .
  "Contact: {$emp['contact']}\n" .
  "Bank Account: {$emp['bank_account']}\n" .
  "SSNIT: {$emp['ssnit']}\n" .
  "Basic Salary: {$basic_salary}\n" .
  "Net Salary: {$net_salary}\n" .
  "Generated: " . date('d M, Y h:i A')
) ?>&size=150x150" class="rounded-lg shadow" alt="QR Code">

      <p class="text-gray-500 text-xs mt-1">Scan to verify</p>
    </div>
    <div class="text-center">
      <p class="font-semibold">_____________________</p>
      <p class="text-gray-600">Authorized Signature</p>
    </div>
  </div>

  <!-- Verification Badge -->
  <div class="flex justify-center py-3">
    <div class="px-4 py-1 rounded-full bg-white/60 backdrop-blur-md shadow-md text-xs text-gray-700 flex items-center gap-2">
      <i class="fa-solid fa-shield-check text-green-500"></i> Verified Secure HR System
    </div>
  </div>
</div>

<!-- Floating Buttons -->
<div class="fixed bottom-6 right-6 flex flex-col gap-3 no-print">
  <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-full shadow-lg transition"><i class="fa-solid fa-print"></i></button>
  <button onclick="downloadPDF()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-full shadow-lg transition"><i class="fa-solid fa-file-pdf"></i></button>
  <a href="dashboard.php#employees" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-full shadow-lg transition"><i class="fa-solid fa-arrow-left"></i></a>
</div>

<script>
function downloadPDF() {
  const element = document.getElementById('payslip');
  const opt = {
    margin: 0.3,
    filename: 'Payslip_<?= htmlspecialchars($emp['full_name']) ?>.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(element).save();
}

function toggleDarkMode() {
  document.documentElement.classList.toggle('dark');
}
</script>
=======
</div>

>>>>>>> 9c37376e3602a47338fc9738bfb0bc03c797cda7
</body>
</html>
