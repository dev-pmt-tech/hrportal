<?php
// config.php

// Database configuration
$host = 'localhost';       // Database host
$db   = 'hr_portal';       // Database name
<<<<<<< HEAD
$user = 'transportadmin1';            // Database username
=======
$user = 'transportadmin';            // Database username
>>>>>>> 9c37376e3602a47338fc9738bfb0bc03c797cda7
$pass = 'Ominidb123$';                // Database password
$charset = 'utf8mb4';      // Charset

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, avoid displaying database errors
    exit('Database connection failed: ' . $e->getMessage());
}
