<?php
class DashboardController {
    public function index() {
        $this->checkSession();

        $username = $_SESSION['user'];
        require __DIR__ . '/../views/dashboard/index.php';
    }

    private function checkSession() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if (time() - $_SESSION['last_activity'] > 1800) {
            session_unset();
            session_destroy();
            header('Location: /login');
            exit;
        }

        $_SESSION['last_activity'] = time();
    }
}