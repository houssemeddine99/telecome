<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Document</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
   
    <form action="./../controller/DocumentController.php?action=create" method="post">
    <h2>Add New Document</h2>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required><br>
        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required><br>
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required><br>
        <input type="submit" value="Add Document">
    </form>
</body>
</html>