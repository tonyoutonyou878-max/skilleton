<?php
require_once 'config.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    $role = current_user_role();
    header('Location: ' . ($role === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (login_user($email, $password, $message)) {
        $role = current_user_role();
        header('Location: ' . ($role === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php'));
        exit;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="auth-wrapper">
        <div class="auth-hero">
            <h1>Welcome to the CUSIT Smart Campus Portal</h1>
            <p>Unified access for student requests, event registration, FYP coordination, and announcement management. Secure sign-in to continue.</p>
            <ul>
                <li>Role-based Admin & Student access</li>
                <li>Complaint tracking with priority and escalation workflows</li>
                <li>Event seat management and duplicate registration prevention</li>
            </ul>
        </div>
        <div class="auth-card">
            <h2>Sign in to continue</h2>
            <?php if ($message): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <label>Email address</label>
                    <input type="email" name="email" placeholder="you@example.com" required autofocus>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            <div class="auth-footer">
                New here? <a href="register.php">Create an account</a>
            </div>
        </div>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>
