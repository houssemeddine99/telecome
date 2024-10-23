<?php $title = 'Dashboard'; ?>
<?php ob_start(); ?>

<h1>Welcome to the Admin Dashboard</h1>
<p>Hello, <?= htmlspecialchars($username) ?>!</p>
<a href="/logout">Logout</a>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>