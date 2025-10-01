<?php

$config = require __DIR__ . '/config.php';
$sessionName = $config['login']['session_name'];
unset($_SESSION[$sessionName]);
session_destroy();
header('Location: login.php');
exit;