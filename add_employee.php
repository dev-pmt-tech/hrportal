<?php
session_start();
require 'config.php';

// Only allow logged-in HR/admin users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $full_name = trim($_POST['full_name'] ?? '');
    $staff_id = trim($_POST['staff_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $date_of_employment = $_POST['date_of_employment'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $ssnit = trim($_POST['ssnit'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $bank_account = trim($_POST['bank_account'] ?? '');

    // Basic validation
    if (!$full_name || !$staff_id || !$department || !$date_of_employment || !$salary || !$ssnit || !$contact || !$bank_account) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: dashboard.php#employees");
        exit;
    }

    try {
        // Insert into employees table
        $stmt = $pdo->prepare("INSERT INTO employees 
            (full_name, staff_id, department, date_of_employment, salary, ssnit, contact, bank_account)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $full_name,
            $staff_id,
            $department,
            $date_of_employment,
            $salary,
            $ssnit,
            $contact,
            $bank_account
        ]);

        $_SESSION['success'] = "Employee added successfully!";
        header("Location: dashboard.php#employees");
        exit;

    } catch (PDOException $e) {
        // Log the error or show a friendly message
        error_log($e->getMessage());
        $_SESSION['error'] = "Error adding employee. Please try again.";
        header("Location: dashboard.php#employees");
        exit;
    }
} else {
    // If accessed directly, redirect to dashboard
    header("Location: dashboard.php");
    exit;
}
