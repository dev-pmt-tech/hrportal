<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch employees
$stmt = $pdo->query("SELECT * FROM employees ORDER BY id DESC");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dynamic counts
$departments = array_column($employees, 'department');
$totalDepartments = count(array_unique($departments));
$totalSSNIT = count(array_filter($employees, fn($e) => !empty($e['ssnit'])));

// Capitalize username first letter
$username = ucfirst($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en" x-data="{ showAddModal: false, darkMode: false, showProfile: false }">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HR Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
  [x-cloak] { display: none !important; }
  .modal-enter { opacity: 0; transform: scale(0.95); }
  .modal-enter-active { transition: all 0.3s ease; opacity: 1; transform: scale(1); }
  .modal-leave { opacity: 1; transform: scale(1); }
  .modal-leave-active { transition: all 0.2s ease; opacity: 0; transform: scale(0.95); }
  .fade-scale { transition: transform 0.3s ease, opacity 0.3s ease; }
</style>
</head>
<body :class="darkMode ? 'bg-gray-900 text-gray-100' : 'bg-gray-50 text-gray-900'" class="font-inter transition-colors duration-300">

<!-- Top Navbar -->
<header :class="darkMode ? 'bg-gray-800 shadow-lg' : 'bg-white shadow-sm'" class="fixed w-full z-30 transition-colors duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
    <h1 class="text-2xl font-bold text-teal-500">HR Portal</h1>
    <div class="flex items-center space-x-4 relative">
      <button @click="darkMode = !darkMode" class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 transition">
        <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
      </button>
      
      <!-- Profile Dropdown -->
      <div class="relative" @click.away="showProfile = false">
        <button @click="showProfile = !showProfile" class="flex items-center space-x-2 focus:outline-none">
          <span class="font-medium" :class="darkMode ? 'text-gray-100' : 'text-gray-700'">Hello, <?= $username ?></span>
          <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=14b8a6&color=fff" class="w-8 h-8 rounded-full border border-gray-300">
        </button>
        <div x-show="showProfile" x-cloak class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-700 shadow-lg rounded-lg overflow-hidden z-50">
          <a href="profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
          <a href="settings.php" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Settings</a>
          <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Main Content -->
<main class="pt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

 <!-- Stats Overview with Animated Counters + Icons -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
  
  <!-- Employees -->
  <div class="bg-gradient-to-r from-teal-400 to-teal-600 text-white rounded-lg shadow p-5 flex flex-col items-start hover:scale-105 transform transition fade-scale">
    <div class="flex items-center space-x-3">
      <!-- Employees Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 p-2 bg-white bg-opacity-20 rounded-full transform transition hover:rotate-12 hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6m-3-3a4 4 0 01-4-4V7a4 4 0 018 0v6a4 4 0 01-4 4z" />
      </svg>
      <span class="text-sm font-medium">Total Employees</span>
    </div>
    <span class="text-3xl font-bold mt-3" 
          x-data="{count:0}" 
          x-init="let end=<?= count($employees) ?>; let i=0; setInterval(()=>{if(i<end){i++; count=i}}, 50)" 
          x-text="count"></span>
  </div>

  <!-- Departments -->
  <div class="bg-gradient-to-r from-purple-400 to-purple-600 text-white rounded-lg shadow p-5 flex flex-col items-start hover:scale-105 transform transition fade-scale">
    <div class="flex items-center space-x-3">
      <!-- Department Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 p-2 bg-white bg-opacity-20 rounded-full transform transition hover:rotate-12 hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6" />
      </svg>
      <span class="text-sm font-medium">Departments</span>
    </div>
    <span class="text-3xl font-bold mt-3" 
          x-data="{count:0}" 
          x-init="let end=<?= $totalDepartments ?>; let i=0; setInterval(()=>{if(i<end){i++; count=i}}, 100)" 
          x-text="count"></span>
  </div>

  <!-- SSNIT Reports -->
  <div class="bg-gradient-to-r from-orange-400 to-orange-600 text-white rounded-lg shadow p-5 flex flex-col items-start hover:scale-105 transform transition fade-scale">
    <div class="flex items-center space-x-3">
      <!-- SSNIT Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 p-2 bg-white bg-opacity-20 rounded-full transform transition hover:rotate-12 hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17a4 4 0 01-8 0V7a4 4 0 018 0v10zm10 0a4 4 0 01-8 0V7a4 4 0 018 0v10z" />
      </svg>
      <span class="text-sm font-medium">SSNIT Reports</span>
    </div>
    <span class="text-3xl font-bold mt-3" 
          x-data="{count:0}" 
          x-init="let end=<?= $totalSSNIT ?>; let i=0; setInterval(()=>{if(i<end){i++; count=i}}, 150)" 
          x-text="count"></span>
  </div>

</div>


  <!-- Employees Section -->
<section class="mb-10">
  <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-3 md:space-y-0">
    <!-- Title -->
    <h2 class="text-xl font-bold text-teal-600 dark:text-teal-400">Employees</h2>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full md:w-auto">
      <button onclick="exportCSV()" 
        class="bg-indigo-500 text-white px-4 py-2 rounded shadow hover:bg-indigo-600 transition transform hover:scale-105 text-center">
        📂 Export CSV
      </button>
      <button @click="showAddModal = true" 
        class="bg-teal-500 text-white px-4 py-2 rounded shadow hover:bg-teal-600 transition transform hover:scale-105 text-center">
        ➕ Add Employee
      </button>
    </div>
  </div>

  <!-- Search -->
  <input type="text" id="searchInput" placeholder="Search employees..." 
    class="mb-4 p-2 rounded border w-full focus:outline-none focus:ring-2 focus:ring-teal-400 transition bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">

  <!-- Table -->
  <div class="overflow-x-auto shadow rounded-lg bg-white dark:bg-gray-800 transition-colors">
    <table id="employeeTable" class="min-w-full border-collapse">
      <thead class="bg-gray-100 dark:bg-gray-700 transition-colors">
        <tr>
          <th onclick="sortTable(0)" class="cursor-pointer p-3 text-left font-medium text-gray-900 dark:text-gray-100">Staff ID</th>
          <th onclick="sortTable(1)" class="cursor-pointer p-3 text-left font-medium text-gray-900 dark:text-gray-100">Full Name</th>
          <th onclick="sortTable(2)" class="cursor-pointer p-3 text-left font-medium text-gray-900 dark:text-gray-100">Department</th>
          <th onclick="sortTable(3)" class="cursor-pointer p-3 text-left font-medium text-gray-900 dark:text-gray-100">Date of Employment</th>
          <th onclick="sortTable(4)" class="cursor-pointer p-3 text-left font-medium text-gray-900 dark:text-gray-100">Salary</th>
          <th class="p-3 text-left font-medium text-gray-900 dark:text-gray-100">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($employees as $emp): ?>
        <tr class="odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
          <td class="p-3 border-b text-gray-900 dark:text-gray-100"><?= htmlspecialchars($emp['staff_id']) ?></td>
          <td class="p-3 border-b text-gray-900 dark:text-gray-100"><?= htmlspecialchars($emp['full_name']) ?></td>
          <td class="p-3 border-b text-gray-900 dark:text-gray-100"><?= htmlspecialchars($emp['department']) ?></td>
          <td class="p-3 border-b text-gray-900 dark:text-gray-100"><?= htmlspecialchars($emp['date_of_employment']) ?></td>
          <td class="p-3 border-b text-gray-900 dark:text-gray-100"><?= htmlspecialchars($emp['salary']) ?></td>
          <td class="p-3 border-b">
  <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 w-full">
    <a href="view_employee.php?id=<?= $emp['id'] ?>" 
       class="flex items-center justify-center bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition w-full sm:w-auto">
       👁 View
    </a>
    <a href="edit_employee.php?id=<?= $emp['id'] ?>" 
       class="flex items-center justify-center bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 transition w-full sm:w-auto">
       ✏️ Edit
    </a>
  </div>
</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>


<!-- Add Employee Modal -->
<div x-show="showAddModal" x-cloak
     x-transition:enter="modal-enter"
     x-transition:enter-end="modal-enter-active"
     x-transition:leave="modal-leave"
     x-transition:leave-end="modal-leave-active"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6 relative shadow-lg transform transition-all fade-scale">
    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Add New Employee</h3>
    <button @click="showAddModal = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">&times;</button>
    <form method="POST" action="add_employee.php" class="space-y-2">
      <input type="text" name="full_name" placeholder="Full Name" class="w-full border rounded p-2" required>
      <input type="text" name="staff_id" placeholder="Staff ID" class="w-full border rounded p-2" required>
      <input type="text" name="department" placeholder="Department / Role" class="w-full border rounded p-2" required>
      <input type="date" name="date_of_employment" class="w-full border rounded p-2" required>
      <input type="number" name="salary" placeholder="Salary" class="w-full border rounded p-2" required>
      <input type="text" name="ssnit" placeholder="SSNIT Number" class="w-full border rounded p-2" required>
      <input type="text" name="contact" placeholder="Contact Info" class="w-full border rounded p-2" required>
      <input type="text" name="bank_account" placeholder="Bank Account" class="w-full border rounded p-2" required>
      <div class="flex justify-end space-x-2 mt-2">
        <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded border hover:bg-gray-100 dark:hover:bg-gray-700 transition">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded bg-teal-500 text-white hover:bg-teal-600 transition">Add</button>
      </div>
    </form>
  </div>
</div>

</main>

<script>
// Search filter
const searchInput = document.getElementById('searchInput');
const employeeTable = document.getElementById('employeeTable').getElementsByTagName('tbody')[0];
searchInput.addEventListener('input', function() {
  const filter = this.value.toLowerCase();
  Array.from(employeeTable.getElementsByTagName('tr')).forEach(row => {
    row.style.display = [...row.cells].some(td => td.textContent.toLowerCase().includes(filter)) ? '' : 'none';
  });
});

// Export CSV
function exportCSV() {
  let rows = document.querySelectorAll("#employeeTable tr");
  let csv = [];
  rows.forEach(row => {
    let cols = row.querySelectorAll("td, th");
    let data = Array.from(cols).map(col => `"${col.innerText}"`).join(",");
    csv.push(data);
  });
  let blob = new Blob([csv.join("\n")], { type: "text/csv" });
  let url = window.URL.createObjectURL(blob);
  let a = document.createElement("a");
  a.setAttribute("hidden", "");
  a.href = url;
  a.download = "employees.csv";
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}

// Sort table
function sortTable(colIndex) {
  let table = document.getElementById("employeeTable");
  let switching = true;
  let dir = "asc";
  while (switching) {
    switching = false;
    let rows = table.rows;
    for (let i = 1; i < rows.length - 1; i++) {
      let shouldSwitch = false;
      let x = rows[i].cells[colIndex].innerText.toLowerCase();
      let y = rows[i + 1].cells[colIndex].innerText.toLowerCase();
      if ((dir === "asc" && x > y) || (dir === "desc" && x < y)) {
        shouldSwitch = true;
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        break;
      }
    }
    if (!switching && dir === "asc") {
      dir = "desc";
      switching = true;
    }
  }
}
</script>

</body>
</html>
