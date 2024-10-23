<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'admin_system');

try {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    echo "Connected successfully";
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
