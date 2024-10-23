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
    $document->title = $_POST['title'];
    $document->author = $_POST['author'];
    $document->year = $_POST['year'];
    $document->category = $_POST['category'];

    if ($document->update()) {
        header('Location: ../views/templates/NiceAdmin/tables-general.php');
        exit();
    } else {
        echo "Failed to update document.";
    }
} else {
    // Fetch the document details if the form is not submitted
    $document->id = isset($_POST['id']) ? $_POST['id'] : '';
    if (!empty($document->id)) {
        $document->readOne();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Document</title>
    <link rel="stylesheet" href="style.css"> 

</head>
<body>
    
    <form action="update.php" method="post">
    <h1>Update Document</h1>
        <label for="id">Document ID:</label>
        <input type="number" name="id" value="<?php echo htmlspecialchars($document->id, ENT_QUOTES); ?>" required><br>
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($document->title, ENT_QUOTES); ?>" required><br>
        <label for="author">Author:</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($document->author, ENT_QUOTES); ?>" required><br>
        <label for="year">Year:</label>
        <input type="number" name="year" value="<?php echo htmlspecialchars($document->year, ENT_QUOTES); ?>" required><br>
        <label for="category">Category:</label>
        <input type="text" name="category" value="<?php echo htmlspecialchars($document->category, ENT_QUOTES); ?>" required><br>
        <input type="submit" value="Update Document">
    </form>
</body>
</html>