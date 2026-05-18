<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
$user = current_user();
?>
<header class="site-header">
    <div class="brand">
        <span>Smart Campus Portal</span>
        <strong><?php echo sanitize($user['name'] ?? 'Guest'); ?></strong>
    </div>
    <nav>
        <a href="../logout.php">Logout</a>
    </nav>
</header>
