<?php
include_once './../config/database.php';
include_once './../models/rental.php';
include_once '../controller/RentalController.php';


$database = new Database();
$db = $database->getConnection();
$rentalController = new RentalController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_rental'])) {
        $user_id = $_POST['user_id'];
        $book_id = $_POST['book_id'];
        $duration = $_POST['duration'];

        $new_rental_id = $rentalController->addRental($user_id, $book_id, $duration);

        if ($new_rental_id) {
            $success_message = "New rental added successfully! Rental ID: $new_rental_id";
        } else {
            $error_message = "Failed to add new rental.";
        }
    } elseif (isset($_POST['return_rental'])) {
        $rental_id = $_POST['rental_id'];

        $is_returned = $rentalController->returnRental($rental_id);

        if ($is_returned) {
            $success_message = "Rental returned successfully!";
        } else {
            $error_message = "Failed to return rental.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Rental</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 85%;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 2.5em;
            letter-spacing: 1px;
        }

        h2 {
            font-size: 2em;
            letter-spacing: 0.5px;
        }

        .rental-form {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .rental-form input, .rental-form select {
            margin-bottom: 15px;
            padding: 12px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .rental-form input[type="submit"] {
            background-color: #FF4C60;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .rental-form input[type="submit"]:hover {
            background-color: #ff2b3d;
        }

        p {
            color: #FF4C60;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
        }

        .home-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #FF4C60;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
            margin: 30px;
        }

        .home-button:hover {
            background-color: #ff2b3d;
        }
    </style>
</head>
<body>
<a href="templates/NiceAdmin/tables-data.php" class="home-button">Return to Home</a> 
    <div class="container">
        
        <h1>Create New Rental</h1>

        
        
        <?php
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        }
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <form method="post" action="rent_document.php" class="rental-form">
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" required><br>

            <label for="book_id">Book ID:</label>
            <input type="text" id="book_id" name="book_id" required><br>

            <label for="duration">Duration (days):</label>
            <input type="number" id="duration" name="duration" required><br>

            <input type="submit" name="add_rental" value="Create Rental">
        </form>

        <h2>Return Rental</h2>
        <form method="post" action="rent_document.php" class="rental-form">
            <label for="rental_id">Rental ID:</label>
            <input type="text" id="rental_id" name="rental_id" required><br>

            <input type="submit" name="return_rental" value="Return Rental">
        </form>
        <h2>Current Rentals</h2>
<table border="1">
    <tr>
        <th>Rental ID</th>
        <th>Book ID</th>
        <th>Rental Date</th>
        <th>Return Date</th>
        <th>Status</th>
    </tr>
    <?php
    $rentals = $rentalController->getAllRentals();
    foreach ($rentals as $rental) {
        echo "<tr>";
        echo "<td>" . $rental['id'] . "</td>";
        echo "<td>" . $rental['book_id'] . "</td>";
        echo "<td>" . $rental['rental_date'] . "</td>";
        echo "<td>" . $rental['return_date'] . "</td>";
        echo "<td>" . $rental['status'] . "</td>";
        echo "</tr>";
    }
    ?>
        
    </div>
    
</body>
</html>