<?php
require_once __DIR__ . '/core/Database.php';
$config = require __DIR__ . '/config/database.php';

$db = new Database($config);

$username = 'admin';
$password = 'admin123'; // Change this to a strong password
$email = 'admin@example.com';

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)");
$result = $stmt->execute([$username, $password_hash, $email]);

if ($result) {
    echo "Admin user created successfully.";
} else {
    echo "Error creating admin user.";
}