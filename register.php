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
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password) {
        $message = 'Please complete all required fields.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        if (register_user($name, $email, $password, $message)) {
            $message = 'Your account has been created. Please sign in.';
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Create your student account</h2>
            <?php if ($message): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <label>Full name</label>
                    <input type="text" name="name" placeholder="John Doe" required>
                </div>
                <div class="input-group">
                    <label>Email address</label>
                    <input type="email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="input-group">
                    <label>Confirm password</label>
                    <input type="password" name="confirm_password" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <div class="auth-footer">
                Already registered? <a href="index.php">Sign in</a>
            </div>
        </div>
        <div class="auth-hero">
            <h1>Join the campus network</h1>
            <p>Register and access personalized complaint tracking, event seats, FYP project management, and announcements delivered through the portal.</p>
            <ul>
                <li>Single sign-on for all student services</li>
                <li>Secure role-specific dashboard access</li>
                <li>Easy event booking with seat availability alerts</li>
            </ul>
        </div>
    </div>
    <script src="assets/js/app.js"></script>
</body>
</html>
