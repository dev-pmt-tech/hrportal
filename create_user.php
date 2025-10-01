<?php
require 'config.php';

// User info
$username = 'Admin'; // change as needed
$password = 'password123'; // your password
$role = 'admin'; // e.g., 'admin' or 'hr'

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $password_hash, $role]);

echo "User created successfully!";
