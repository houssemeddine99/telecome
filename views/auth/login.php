<?php 
session_start(); // Start the session at the beginning of the script
$title = 'Login'; 
$error = null; // Initialize the error variable

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have some credentials to check against
    $correct_username = 'admin';
    $correct_password = 'password'; // Ideally, you'd hash and store passwords securely

    // Get the submitted username and password
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate credentials
    if ($username === $correct_username && $password === $correct_password) {
        // Set session variables
        $_SESSION['user_id'] = 1; // You might want to use a real user ID here
        $_SESSION['username'] = $username;

        // Redirect to the admin dashboard or desired page
        header('Location: ../templates/NiceAdmin/index.html');
        exit(); // Make sure to exit after the redirection
    } else {
        // If credentials are incorrect, set an error message
        $error = 'Invalid username or password';
    }
}

// Start output buffering
ob_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }
        .login-container {
            background-color: white; 
            padding: 2.5em 3em; 
            border-radius: 10px; 
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); 
            max-width: 400px; 
            width: 100%; 
            text-align: center;
        }
        h1 {
            color: #333; 
            margin-bottom: 1.5em;
            font-size: 24px;
        }
        form {
            display: flex; 
            flex-direction: column; 
            align-items: stretch;
        }
        label {
            text-align: left;
            margin-top: 1em; 
            font-weight: bold; 
            color: #555;
        }
        input {
            padding: 0.75em; 
            margin-top: 0.5em; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-size: 16px;
        }
        button {
            margin-top: 1.5em; 
            padding: 0.75em; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red; 
            margin-top: 1em;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

<?php 
// Capture the content
$content = ob_get_clean(); 

// Include the main layout
include __DIR__ . '/../layouts/main.php'; 
?>
