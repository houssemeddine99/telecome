<?php
// Include the necessary files
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/documents.php';

// Initialize the database and document object
$database = new Database();
$db = $database->getConnection();
$document = new Document($db);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document->id = $_POST['id'];

    if ($document->delete()) {
        header('Location: ../views/templates/NiceAdmin/tables-general.php');
        exit();
    } else {
        echo "Failed to delete document.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <form action="delete.php" method="post">
    <h1>Delete Document</h1>
        <label for="id">Document ID:</label>
        <input type="number" name="id" required><br>
        <input type="submit" value="Delete Document">
    </form>
</body>
</html>