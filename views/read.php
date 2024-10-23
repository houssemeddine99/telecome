<?php
// Configuration for the database connection
$dsn = 'mysql:host=localhost;dbname=bibliotheque';
$username = 'your_username';
$password = 'your_password';

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query
    $stmt = $pdo->prepare("SELECT * FROM documents");
    $stmt->execute();

    // Start HTML table
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Year</th><th>Category</th><th>Actions</th></tr>";

    // Fetch and display results
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        echo "<tr>";
        echo "<td>" . htmlspecialchars($id) . "</td>";
        echo "<td>" . htmlspecialchars($title) . "</td>";
        echo "<td>" . htmlspecialchars($author) . "</td>";
        echo "<td>" . htmlspecialchars($year) . "</td>";
        echo "<td>" . htmlspecialchars($category) . "</td>";
        echo "<td>";
        echo "<a href='?action=update&id=" . htmlspecialchars($id) . "'>Edit</a> ";
        echo "<a href='?action=delete&id=" . htmlspecialchars($id) . "'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }

    // End HTML table
    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
