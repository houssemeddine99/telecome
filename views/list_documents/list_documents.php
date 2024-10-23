<?php

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/documents.php';

$database = new Database();
$db = $database->getConnection();

$document = new Document($db);

$documents = $document->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document List</title>
    <link rel="stylesheet" href="style.css">
    <style>
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #5cb85c;
        }

        .btn-primary:hover {
            background-color: #5cb854;
        }
    </style>
</head>
<body>
    <div class="actions">
        <a href="./../create.php" class="btn btn-primary">Create Document</a>
        <a href="./../update.php" class="btn btn-primary">Update Document</a>
        <a href="./../delete.php" class="btn btn-primary">Delete Document</a>
    </div>
    
    <h1>Document List</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $doc): ?>
                <tr>
                    <td><?php echo htmlspecialchars($doc['id']); ?></td>
                    <td><?php echo htmlspecialchars($doc['title']); ?></td>
                    <td><?php echo htmlspecialchars($doc['author']); ?></td>
                    <td><?php echo htmlspecialchars($doc['year']); ?></td>
                    <td><?php echo htmlspecialchars($doc['category']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>