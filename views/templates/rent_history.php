<?php
// Configuration for the database connection
$dsn = 'mysql:host=localhost;dbname=bibliotheque';
$username = 'root';
$password = '';

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch rent history for the logged-in user
    $user_id = 1; // Assuming you have a logged-in user ID
    $stmt = $pdo->prepare("SELECT r.*, d.title FROM rentals r JOIN documents d ON r.book_id = d.id WHERE r.user_id = ?");
    $stmt->execute([$user_id]);
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display rent history
    echo "<h1>My Rent History</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Title</th><th>Rent Duration</th><th>Rent Date</th></tr>";
    foreach ($rentals as $rental) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($rental['title']) . "</td>";
       // echo "<td>" . htmlspecialchars($rental['rent_duration']) . "</td>";
        echo "<td>" . htmlspecialchars($rental['rent_date']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
