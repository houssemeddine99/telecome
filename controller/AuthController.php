<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Username and password are required.";
            } else {
                if ($this->userModel->authenticate($username, $password)) {
                    $_SESSION['user'] = $username;
                    $_SESSION['last_activity'] = time();
                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = "Invalid username or password";
                }
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }
}